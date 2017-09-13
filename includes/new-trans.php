<?php
function new_trans_shamdooni() {
    global $woocommerce;
    /*foreach ($woocommerce->cart->get_fees() as $fee) {
        if($fee->{'name'} == 'شمعدونی') {
            echo $fee->{'name'};
        } 
    }*/
    $session_key = $woocommerce->session->get_session_cookie()[0];    
    shamdooni_new_trans($session_key);  
}
add_action('woocommerce_before_checkout_form', 'new_trans_shamdooni', 1);
?>