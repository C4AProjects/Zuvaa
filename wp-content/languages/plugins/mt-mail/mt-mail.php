<?php
/**
 * Plugin Name: (mt) Mail 
 * Plugin URI: http://mediatemple.net
 * Description: Access your Media Temple web mail
 * Version: 1.1
 * Author: Media Temple, Inc.
 * Author URI: http://mediatemple.net 
 * Plugin URI: http://mediatemple.net 
 * License: GPL2
 * Copyright 2014 (mt) Media Temple, Inc. All Rights Reserved.
 */

// Make sure it's wordpress
if ( !defined( 'ABSPATH' ) ) {
    die( 'Forbidden' );
}

global $api;

global $mt_https_server;
$mt_server = 'ac.mediatemple.net';
$https_port = '443';
$mt_https_server = 'https://' . $mt_server . ':' . $https_port;

global $api_url;
$api_url = 'https://wpqs.secureserver.net/v1/';

global $plugin_update_ver;
$plugin_update_ver = '1.2';

function mtmail_library_init() {
    require_once( realpath( dirname( __FILE__ ) ) . '/classes/class.mt-update-api.php' );
    require_once( realpath( dirname( __FILE__ ) ) . '/classes/class.mt-upgrader.php' );
}
add_action( 'admin_init', 'mtmail_library_init' );

function mtmail_init_api() {
    global $api;
    global $api_url;
    global $plugin_update_ver;
    $api = new MT_Mail_Update_API( 'mt-mail', $plugin_update_ver, $api_url );
}
add_action( 'admin_init', 'mtmail_init_api' );

function mtmail_self_upgrade() {
    global $api;
    $plugin_slug = plugin_basename( __FILE__ );
    $duration = 60 * 60 * 6; // number of seconds for transient (every 6 hours)
    $mt_upgrader = new MT_Mail_Upgrader( __FILE__, $plugin_slug, $duration );
    $mt_upgrader->set_api( $api );
}
add_action( 'admin_init', 'mtmail_self_upgrade' );

function register_mt_mail_page(){
   add_menu_page( '(mt) Mail', '(mt) Mail', 'manage_options', 'mtmail_slug', 'mt_mail_page', plugins_url( 'mt-mail/images/mt-logo-16.png' ), '4.5' );
}
add_action( 'admin_menu', 'register_mt_mail_page' );

function mt_mail_page(){
    global $mt_https_server;
    $domain_name = parse_url( home_url(), PHP_URL_HOST );
    $db_name = DB_NAME;
    $url = "${mt_https_server}/rest/wpaas/get_webmail_url";
    $response = wp_remote_post( $url, array(
        'method' => 'POST',
        'timeout' => 20,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'sslverify' => false,
        'body' => array( 'domain_name' => $domain_name, 'db_name' => $db_name ),
        'cookies' => array()
    ));

    if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        echo "ERROR: Something went wrong. $error_message";
    } else {
        if ( !empty( $response ) && is_array( $response ) ) {
            if ( isset( $response['body'] ) ) {
                $body = json_decode( $response['body'] );
                if ( isset( $body->webmail_url ) ) {
                    $webmail_url = $body->webmail_url;
                } else {
                    echo '<p>ERROR: Could not determine your webmail url. Please contact MT Support <a href="http://www.mediatemple.net/help" target="_blank">http://www.mediatemple.net/help</a><br>';
                }
            } else {
                echo '<p>ERROR: Could not determine your webmail url. Please contact MT Support <a href="http://www.mediatemple.net/help" target="_blank">http://www.mediatemple.net/help</a><br>';
            }
        } else {
            echo '<p>ERROR: Could not determine your webmail url. Please contact MT Support <a href="http://www.mediatemple.net/help" target="_blank">http://www.mediatemple.net/help</a><br>';
        }
    }

    echo '<iframe src="' . esc_url($webmail_url) . '" seamless=seamless width=100% height=700 align=left></iframe>';
}
?>
