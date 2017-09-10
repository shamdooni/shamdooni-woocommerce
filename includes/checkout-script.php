<?php
function shamdooni_checkout_script() {
    if(is_checkout()) {
	    wp_enqueue_script( 'my-js', plugins_url('shamdooni/includes/checkout.js'),  array( 'jquery' ) );
    }
}

add_action( 'wp_enqueue_scripts', 'shamdooni_checkout_script' );
?>
