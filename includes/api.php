<?php
global $wc_shamdooni_address;

function shamd_wc_get_trans($session_key) {
    global $woocommerce;
    global $wpdb;
    $table_name = $wpdb->prefix . 'wc_shamdooni_transactions';
    $is_there_trans = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE session_key = '$session_key' AND ended = false " );
    if($is_there_trans) {
        return $wpdb->get_results( "SELECT * FROM $table_name WHERE session_key = '$session_key' AND ended = false" );
    } else {
        return false;
    }
}

function shamd_wc_is_currency_iranian() {
    if(get_option('woocommerce_currency') == 'IRR' ||  get_option('woocommerce_currency') == 'IRT') {
        return true;
    } else { 
        return false;
    }
}

function shamd_wc_is_currency_rial() {
    if(get_option('woocommerce_currency') == 'IRR') {
        return true;
    } else {
        return false;
    }
}

function shamd_wc_is_shamdooni_up() {
    global $wc_shamdooni_address;
    $response = wp_remote_post( $wc_shamdooni_address , 
    array(
        'method' => 'GET',
        'timeout' => 5,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'cookies' => array()
        ) 
    );
    $iranian_currency = shamd_wc_is_currency_iranian();
    if(is_array($response) && $iranian_currency) {
        return true;
    } else {
        return false;
    }
}

function shamd_wc_is_api_key() {
    global $wc_shamdooni_address;
    $options = get_option('shamduni_rounder');    
    $response = wp_remote_post( $wc_shamdooni_address . '/api/rounder/v1/checkkey' , 
        array(
            'method' => 'POST',
            'timeout' => 5,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'body' => array( 
                'key' => $options['shamduni_apikey']
            ),
            'cookies' => array()
        ) 
    );
    if($response['response']['code'] == 200) {
        return true;
    } else {
        return false;
    }
}

function shamd_wc_roundIt(WP_REST_Request $request ) {
    global $woocommerce;
    global $wpdb;
    global $wc_shamdooni_address;

    $table_name = $wpdb->prefix . 'wc_shamdooni_transactions';

    $options = get_option('shamduni_rounder');
    $session_key = $request['session_key'];
    $cart_session_total = intval($woocommerce->session->get_session($session_key)["total"]);

    if(shamd_wc_get_trans($session_key)) {
        $transaction = shamd_wc_get_trans($session_key)[0];
        $transaction_id = $transaction->shamdooni_transaction_id;
        $round = wp_remote_post( $wc_shamdooni_address . '/api/rounder/v1/' . $transaction_id , 
            array(
                'method' => 'PUT',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'body' => array( 
                    'key' => $options['shamduni_apikey'],
                    'check' => $request['check']
                ),
                'cookies' => array()
            ) 
        );
             
        $round_obj = json_decode($round['body']);
        $wpdb->update(
            $table_name,
            array(
               'final_amount' => $round_obj->{'finalAmount'},
               'round_amount' => $round_obj->{'roundAmount'},
               'amount' => $round_obj->{'amount'},
               'rounded' => $round_obj->{'rounded'}          
            ),
            array(
                'session_key' => $session_key
            )
        );
    }
}

function shamd_wc_shamdooni_new_trans($session_key) {
    global $woocommerce;
    global $wpdb;
    global $wc_shamdooni_address;
    $table_name = $wpdb->prefix . 'wc_shamdooni_transactions';  
    $transaction = shamd_wc_get_trans($session_key)[0];
    $cart_session_total = intval($woocommerce->session->get_session($session_key)["subtotal"]);    
    if(shamd_wc_get_trans($session_key)) {
        $wpdb->delete( $table_name, array( 'session_key' => $session_key, 'ended' => false ) );
    }
    
    if(shamd_wc_is_shamdooni_up() && shamd_wc_is_api_key()) {
        if(!shamd_wc_is_currency_rial()) {
            $cart_session_total = $cart_session_total * 10;
        }
        $options = get_option('shamduni_rounder');
        $response = wp_remote_post( $wc_shamdooni_address . '/api/rounder/v1' , 
            array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'body' => array( 
                    'key' => $options['shamduni_apikey'], 
                    'amount' => $cart_session_total,
                ),
                'cookies' => array()
            ) 
        );
        $result = json_decode($response['body']);
        $wpdb->insert( 
            $table_name, 
            array( 
                'time' => current_time( 'mysql' ), 
                'session_key' => $session_key, 
                'shamdooni_transaction_id' => $result->{'transId'},
                'amount' => $result->{'amount'}
            ) 
        );
    }
}


add_action( 'rest_api_init', function () {
    register_rest_route( 'shamdooni/v1', '/rounder/(?P<session_key>[\w-]+)/' , array(
        'methods' => 'POST',
        'callback' => 'shamd_wc_roundIt',
    ) );
} );

?>