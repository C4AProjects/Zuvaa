<?php
/**
 * My Account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<?php

global $woocommerce, $yith_wcwl;

if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {
wc_print_notices();
} else {
$woocommerce->show_messages();
}
?>

<?php sf_woo_help_bar(); ?>

<div class="my-account-left">

	<h4 class="lined-heading"><span><?php _e("My Account", "swiftframework"); ?></span></h4>
	<ul class="nav my-account-nav">
		<li class="active"><a href="#my-orders" data-toggle="tab"><?php _e("My Orders", "swiftframework"); ?></a></li>
		<?php if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) { ?>
			<?php if ( $downloads = WC()->customer->get_downloadable_products() ) { ?>
				<li><a href="#my-downloads" data-toggle="tab"><?php _e("My Downloads", "swiftframework"); ?></a></li>
			<?php } ?>
		<?php } else { ?>
			<?php if ( $downloads = $woocommerce->customer->get_downloadable_products() ) { ?>
				<li><a href="#my-downloads" data-toggle="tab"><?php _e("My Downloads", "swiftframework"); ?></a></li>
			<?php } ?>
		<?php } ?>
		<?php if ( class_exists( 'YITH_WCWL_UI' ) ) { ?>
		<li><a href="<?php echo $yith_wcwl->get_wishlist_url(); ?>"><?php _e("My Wishlist", "swiftframework"); ?></a></li>
		<?php } ?>
		<li><a href="#address-book" data-toggle="tab"><?php _e("Address Book", "swiftframework"); ?></a></li>
		<?php if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) { ?>
		<li><a href="<?php echo wc_customer_edit_account_url(); ?>"><?php _e("Change Password", "swiftframework"); ?></a></li>		
		<?php } else { ?>
		<li><a href="#change-password" data-toggle="tab"><?php _e("Change Password", "swiftframework"); ?></a></li>
		<?php } ?>
	</ul>

</div>

<?php if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) { ?>

<div class="my-account-right tab-content">
	
	<?php do_action( 'woocommerce_before_my_account' ); ?>
	
	<div class="tab-pane active" id="my-orders">
	
	<?php wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) ); ?>
	
	</div>
	
	<?php if ( $downloads = $woocommerce->customer->get_downloadable_products() ) { ?>
	
	<div class="tab-pane" id="my-downloads">
	
	<?php wc_get_template( 'myaccount/my-downloads.php' ); ?>
	
	</div>
	
	<?php } ?>
	
	<div class="tab-pane" id="address-book">
	
	<?php wc_get_template( 'myaccount/my-address.php' ); ?>
	
	</div>
	
	<?php do_action( 'woocommerce_after_my_account' ); ?>
	
</div>

<?php } else { ?>

<div class="my-account-right tab-content">
	
	<?php do_action( 'woocommerce_before_my_account' ); ?>
	
	<div class="tab-pane active" id="my-orders">
	
	<?php 
		if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
			woocommerce_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) );
		} else {
			woocommerce_get_template('myaccount/my-orders.php', array( 'recent_orders' => $recent_orders ));
		}
	?>
	
	</div>
	
	<?php if ( $downloads = $woocommerce->customer->get_downloadable_products() ) { ?>
	
	<div class="tab-pane" id="my-downloads">
	
	<?php woocommerce_get_template( 'myaccount/my-downloads.php' ); ?>
	
	</div>
	
	<?php } ?>
	
	<div class="tab-pane" id="address-book">
	
	<?php woocommerce_get_template( 'myaccount/my-address.php' ); ?>
	
	</div>
	
	<div class="tab-pane" id="change-password">
	
	<?php woocommerce_get_template( 'myaccount/form-change-password.php' ); ?>
	
	</div>		
	
	<?php do_action( 'woocommerce_after_my_account' ); ?>
	
</div>

<?php } ?>