<?php

function shamd_wc_jal_install() {
    global $wpdb;
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    global $charset_collate;
    
    $table_name = $wpdb->prefix . 'wc_shamdooni_transactions';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		session_key varchar(100) NOT NULL,
		order_id int,
		rounded boolean DEFAULT false,
		final_amount bigint,
		round_amount bigint,
		amount bigint,
		ended boolean DEFAULT false,
        shamdooni_transaction_id varchar(100) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	dbDelta( $sql );

}
?>