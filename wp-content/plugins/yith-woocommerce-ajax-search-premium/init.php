<?php
/**
* Plugin Name: YITH WooCommerce Ajax Search Premium
* Plugin URI: http://yithemes.com/themes/plugins/yith-woocommerce-ajax-search/
* Description: YITH WooCommerce Ajax Search Premium allows your users to search products in real time.
* Version: 1.3.2
* Author: Yithemes
* Author URI: http://yithemes.com/
* Text Domain: yit
* Domain Path: /languages/
*
* @author Yithemes
* @package YITH WooCommerce Ajax Search Premium
* @version 1.2.6
 */

if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if ( !function_exists( 'yit_deactive_free_version' ) ) {
    require_once 'plugin-fw/yit-deactive-plugin.php';
}
yit_deactive_free_version( 'YITH_WCAS_FREE_INIT', plugin_basename( __FILE__ ) );

if ( defined( 'YITH_WCAS_VERSION' ) ){
    return;
}else{
    define( 'YITH_WCAS_VERSION', '1.3.2' );
}

if ( ! defined( 'YITH_WCAS_PREMIUM' ) ) {
    define( 'YITH_WCAS_PREMIUM', '1' );
}

function yith_ajax_search_premium_constructor() {

    if ( !function_exists( 'WC' ) ) {
        function yith_wcas_premium_install_woocommerce_admin_notice() {
            ?>
            <div class="error">
                <p><?php _e( 'YITH WooCommerce Ajax Search Premium is enabled but not effective. It requires Woocommerce in order to work.', 'ywcm' ); ?></p>
            </div>
        <?php
        }

        add_action( 'admin_notices', 'yith_wcas_premium_install_woocommerce_admin_notice' );
        return;
    }

    load_plugin_textdomain( 'yit', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );

    if ( ! defined( 'YITH_WCAS' ) ) {
        define( 'YITH_WCAS', true );
    }

    if ( ! defined( 'YITH_WCAS_FILE' ) ) {
        define( 'YITH_WCAS_FILE', __FILE__ );
    }

    if ( ! defined( 'YITH_WCAS_URL' ) ) {
        define( 'YITH_WCAS_URL', plugin_dir_url( __FILE__ ) );
    }

    if ( ! defined( 'YITH_WCAS_DIR' ) ) {
        define( 'YITH_WCAS_DIR', plugin_dir_path( __FILE__ )  );
    }

    if ( ! defined( 'YITH_WCAS_TEMPLATE_PATH' ) ) {
        define( 'YITH_WCAS_TEMPLATE_PATH', YITH_WCAS_DIR . 'templates' );
    }

    if ( ! defined( 'YITH_WCAS_ASSETS_URL' ) ) {
        define( 'YITH_WCAS_ASSETS_URL', YITH_WCAS_URL . 'assets' );
    }

    if ( ! defined( 'YITH_WCAS_ASSETS_IMAGES_URL' ) ) {
        define( 'YITH_WCAS_ASSETS_IMAGES_URL', YITH_WCAS_ASSETS_URL . '/images/' );
    }

    if ( ! defined( 'YITH_WCAS_INIT' ) ) {
        define( 'YITH_WCAS_INIT', plugin_basename( __FILE__ ) );
    }

    if ( ! defined( 'YITH_WCAS_SLUG' ) ) {
        define( 'YITH_WCAS_SLUG', 'yith-woocommerce-ajax-search' );
    }

    if ( ! defined( 'YITH_WCAS_SECRET_KEY' ) ) {
        define( 'YITH_WCAS_SECRET_KEY', 'SyKDKcXuRIOqRW6Aag5z' );
    }

    // Load required classes and functions
    require_once('functions.yith-wcas.php');
    require_once('class.yith-wcas-admin.php');
    require_once('class.yith-wcas-frontend.php');
    require_once('widgets/class.yith-wcas-ajax-search.php');
    require_once('class.yith-wcas.php');

    // Let's start the game!
    global $yith_wcas;
    $yith_wcas = new YITH_WCAS();
}
add_action( 'plugins_loaded', 'yith_ajax_search_premium_constructor' );