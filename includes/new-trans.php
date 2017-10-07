<?php
function shamd_wc_new_trans_shamdooni() {
    global $woocommerce;
    $session_key = $woocommerce->session->get_session_cookie()[0]; 
    shamd_wc_shamdooni_new_trans($session_key);  
}
add_action('woocommerce_before_checkout_form', 'shamd_wc_new_trans_shamdooni', 1);
?>