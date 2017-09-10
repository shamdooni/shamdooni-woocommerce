<?php
function shamduni_rounder_menu() {
    add_options_page(
        'شمعدونی ووکامرس',
        'شمعدونی ووکامرس',
        'manage_options',
        'shamduni-rounder',
        'shamduni_rounder_options_page'
    );
}
add_action('admin_menu', 'shamduni_rounder_menu');

function shamduni_rounder_options_page() {
    global $plugin_url;
    global $options;
    global $woocommerce;
    if (!current_user_can('manage_options')) {
        wp_die('شما دسترسی لازم برای تنظیم شمعدونی ندارید. ببخشید :(');
    }
    if (isset($_POST['shamduni_form_submitted'])) {
        $hidden_field = esc_html($_POST['shamduni_form_submitted']);
        if($hidden_field == 'Y') {
            $shamduni_apikey = esc_html($_POST['shamduni_apikey']);
            $options['shamduni_apikey'] = $shamduni_apikey;
            update_option('shamduni_rounder', $options);
        }
    }
    $options = get_option('shamduni_rounder');
    if ($options != '') {
        $shamduni_apikey = $options['shamduni_apikey'];
    }
    require('options-page-wrapper.php');
}

function shamdooni_rounder_backend_styles() {
    wp_enqueue_style( 'shamdooni_rounder_backend_css', plugins_url('shamdooni-rounder/includes/backend.css'));
}

add_action('admin_head', 'shamdooni_rounder_backend_styles');

?>