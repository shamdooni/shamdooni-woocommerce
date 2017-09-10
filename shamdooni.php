<?php
/*
Plugin Name: شمعدونی ووکامرس
Version: 1.0.0
Description: رند کننده قیمت شمعدونی برای ووکامرس
Plugin URI:
Author: Shamdooni Organization
Author URI: http://www.shamdooni.org
*/

require('includes/database.php');
register_activation_hook( __FILE__, 'jal_install' );
register_activation_hook( __FILE__, 'jal_install_data' );

require('includes/settings.php');

?>