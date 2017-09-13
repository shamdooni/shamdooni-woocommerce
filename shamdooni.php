<?php
/*
Plugin Name: شمعدونی ووکامرس
Version: 1.0.0
Description: رند کننده قیمت شمعدونی برای ووکامرس
Plugin URI:
Author: Shamdooni Organization
Author URI: http://www.shamdooni.org
*/

######################################################
//
// variable dec
//
$plugin_url = WP_PLUGIN_URL . '/shamdooni-rounder';
$options = array();
$wc_shamdooni_address = 'http://localhost:8080';
$wc_shamduni_iframe = $wc_shamdooni_address.'/iframe';
$wc_shamduni_api = get_site_url() . '/wp-json/shamdooni/v1/rounder';


######################################################
require('includes/database.php');
register_activation_hook( __FILE__, 'jal_install' );
register_activation_hook( __FILE__, 'jal_install_data' );

require('includes/settings.php');
require('includes/api.php');
require('includes/new-trans.php');
require('includes/checkout-script.php');
require('includes/iframe.php');
?>