<?php

add_action('woocommerce_checkout_order_processed', 'shamd_wc_wh_pre_paymentcall');
function shamd_wc_wh_pre_paymentcall($order_id) {
    global $wpdb;
    global $woocommerce;

    if(shamd_wc_is_shamdooni_up() && shamd_wc_is_api_key()) {
        $session_key = $woocommerce->session->get_session_cookie()[0];
        
        $table_name = $wpdb->prefix . 'wc_shamdooni_transactions';
    
        $wpdb->update(
            $table_name,
            array(
               'order_id' => $order_id,
               'ended' => true
            ),
            array(
                'session_key' => $session_key,
                'ended' => false
            )
        );
    }
    
}

add_action( 'woocommerce_payment_complete', 'shamd_wc_so_payment_complete' );
function shamd_wc_so_payment_complete( $order_id ){
    global $wc_shamdooni_address;
    global $wpdb;    
    if(shamd_wc_is_shamdooni_up() && shamd_wc_is_api_key()) {
        $table_name = $wpdb->prefix . 'wc_shamdooni_transactions';
        
        $transaction = $wpdb->get_results( "SELECT * FROM $table_name WHERE order_id = $order_id" );
        $transaction_id = $transaction[0]->{'shamdooni_transaction_id'};
        
        $order_id = $transaction[0]->{'order_id'};
        $order = new WC_Order($order_id);   
        
        $user_info = json_encode(array(
            'first_name' => $order->get_billing_first_name(),
            'last_name' => $order->get_billing_last_name(),
            'email' => $order->get_billing_email()
        ));
        $round = wp_remote_post( $wc_shamdooni_address . '/api/rounder/v1/verify/' . $transaction_id , 
        array(
            'method' => 'PUT',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'cookies' => array(),
            'body' => array(
                'userinfo' => $user_info
            )
        ));
    }
}


?>