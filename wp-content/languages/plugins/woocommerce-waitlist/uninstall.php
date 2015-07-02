<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if( get_option('wew-remove-waitlist-on-uninstall') && get_option('wew-remove-waitlist-on-uninstall') == "on" ){
	
	global $wpdb;

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$wew_DBtable_name = $wpdb->prefix . 'woocommerce_waitlist';

	$sql = "DROP TABLE IF EXISTS " . $wew_DBtable_name . " ; ";

	$wpdb->query($sql);

	delete_option("wew-out-of-stock-message");

	delete_option("wew-notify-available-product");

	delete_option('wew-remove-waitlist-on-uninstall');
}