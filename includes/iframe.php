<?php
function shamd_wc_add_iframe() {
    global $wc_shamduni_iframe;
    global $wc_shamduni_api;
    global $woocommerce;

    if(shamd_wc_is_shamdooni_up() && shamd_wc_is_api_key()) {
        $session_key = $woocommerce->session->get_session_cookie()[0];
        $cart_session_total = intval($woocommerce->session->get_session($session_key)["subtotal"]);
        if(!shamd_wc_is_currency_rial()) {
            $cart_session_total = $cart_session_total * 10;
        }
    
        if(shamd_wc_get_trans($session_key)) {
            $transaction = shamd_wc_get_trans($session_key);
            if($transaction[0]->{'rounded'}) {
                $iframe =  '<iframe src="' . $wc_shamduni_iframe . '?cart_total='. $cart_session_total . '&api_link=' . $wc_shamduni_api .'&session_key=' . $session_key .'&checked=true" width="485px" height="155px" style="border:none" ></iframe><br>';        
            } else {
                $iframe =  '<iframe src="' . $wc_shamduni_iframe . '?cart_total='. $cart_session_total . '&api_link=' . $wc_shamduni_api .'&session_key=' . $session_key .'&checked=false" width="485px" height="155px" style="border:none" ></iframe><br>';        
            }
            echo $iframe;
        } else {
            $iframe =  '<iframe src="' . $wc_shamduni_iframe . '?cart_total='. $cart_session_total . '&api_link=' . $wc_shamduni_api .'&session_key=' . $session_key .'&checked=false" width="485px" height="155px" style="border:none" ></iframe><br>';        
            echo  $iframe;
        }
    }
}

add_action( 'woocommerce_review_order_before_submit', 'shamd_wc_add_iframe');

?>