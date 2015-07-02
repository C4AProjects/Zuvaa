<?php
/**
 * Checkout login form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( is_user_logged_in()  || ! $checkout->enable_signup ) return;

$checkout_page = "";
if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {
	$checkout_page = get_permalink( wc_get_page_id( 'checkout' ) );
} else {
	$checkout_page = get_permalink( woocommerce_get_page_id( 'checkout' ) );
}

$info_message = apply_filters( 'woocommerce_checkout_login_message', __( 'Returning customer?', 'woocommerce' ) );
?>

<?php
	woocommerce_login_form(
		array(
			'message'  => '',
			'redirect' => $checkout_page,
			'hidden'   => false
		)
	);
?>