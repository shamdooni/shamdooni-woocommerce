<?php
function shamd_wc_woocommerce_custom_surcharge() {
    global $woocommerce;
    global $wpdb;
    $table_name = $wpdb->prefix . 'wc_shamdooni_transactions';
    $session_key = $woocommerce->session->get_session_cookie()[0];
    $is_there_trans = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE session_key = '$session_key' AND rounded = 1 AND ended = false" );

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;
    
    if($is_there_trans) {
        $transaction = shamd_wc_get_trans($session_key)[0];
        $round_amount = $transaction->{'round_amount'};
        if(!shamd_wc_is_currency_rial()) {
            $round_amount = $round_amount / 10;
        }
        $woocommerce->cart->add_fee( 'شمعدونی', $round_amount, true, '' );
    }
} 
add_action( 'woocommerce_cart_calculate_fees','shamd_wc_woocommerce_custom_surcharge' );
?>