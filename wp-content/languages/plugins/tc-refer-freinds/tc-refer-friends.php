<?php 

/*
Plugin Name: Refer A Friend for WooCommerce
Plugin Script: tc-refer-friends.php
Plugin URI: http://tyler.tc
Description: An easy way to setup a "refer a friend" or affiliate system on your WooCommerce sites.
Version: 1.0.1
Author: Tyler Colwell
Author URI: http://tyler.tc

--- THIS PLUGIN AND ALL FILES INCLUDED ARE COPYRIGHT © TYLER COLWELL 2013 --- 
- FOR FULL LICENSE INFORMATION VIEW THIS PAGE HERE: http://codecanyon.net/licenses

*/

/*-----------------------------------------------------------------------------------*/
/*	Define Anything Needed
/*-----------------------------------------------------------------------------------*/

define('TCRAF_LOCATION', WP_PLUGIN_URL . '/'.basename(dirname(__FILE__)));
define('TCRAF_PATH', plugin_dir_path(__FILE__));
define('TCRAF_RELPATH', dirname( plugin_basename( __FILE__ ) ) );
define('TCRAF_VERSION', '1.0.1');
require_once('inc/tcf_settings_page.php');
require_once('inc/tcf_bootstrap.php');
require_once('inc/tcf_dash_widget.php');
require_once('inc/stats.class.php');