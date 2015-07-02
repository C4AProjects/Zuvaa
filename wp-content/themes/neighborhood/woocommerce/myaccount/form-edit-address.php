<?php
/**
 * Edit address form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<?php

global $woocommerce, $current_user;

get_currentuserinfo();

$page_title = ( $load_address == 'billing' ) ? __( 'Billing Address', 'woocommerce' ) : __( 'Shipping Address', 'woocommerce' );

$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
$myaccount_page_url = "";
if ( $myaccount_page_id ) {
  $myaccount_page_url = get_permalink( $myaccount_page_id );
}

$edit_address = "";
if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) < 0 ) {
	$edit_address = get_permalink( wc_get_page_id( 'edit_address' ) );
}

if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {
	
	$page_title = ( $load_address == 'billing' ) ? __( 'Billing Address', 'woocommerce' ) : __( 'Shipping Address', 'woocommerce' );
	
	get_currentuserinfo();
	?>
	
	<?php wc_print_notices(); ?>
	
	
	<?php sf_woo_help_bar(); ?>
	
	<div class="my-account-left">
	
		<h4 class="lined-heading"><span><?php _e("My Account", "swiftframework"); ?></span></h4>
		<ul class="nav my-account-nav">
		  <li><a href="<?php echo $myaccount_page_url; ?>"><?php _e("Back to my account", "swiftframework"); ?></a></li>
		</ul>
	
	</div>
	
	<div class="my-account-right">
	
	<?php if ( ! $load_address ) : ?>
	
		<?php wc_get_template( 'myaccount/my-address.php' ); ?>
	
	<?php else : ?>
	
		<form method="post">
	
			<h3><?php echo apply_filters( 'woocommerce_my_account_edit_address_title', $page_title ); ?></h3>
	
			<?php foreach ( $address as $key => $field ) : ?>
	
				<?php woocommerce_form_field( $key, $field, ! empty( $_POST[ $key ] ) ? wc_clean( $_POST[ $key ] ) : $field['value'] ); ?>
	
			<?php endforeach; ?>
	
			<p>
				<input type="submit" class="button" name="save_address" value="<?php _e( 'Save Address', 'woocommerce' ); ?>" />
				<?php wp_nonce_field( 'woocommerce-edit_address' ); ?>
				<input type="hidden" name="action" value="edit_address" />
			</p>
	
		</form>
	
	<?php endif; ?>
	
	</div>
	
<?php } else { ?>
	
	<?php $woocommerce->show_messages(); ?>
	
	<?php sf_woo_help_bar(); ?>
	
	<div class="my-account-left">
	
		<h4 class="lined-heading"><span><?php _e("My Account", "swiftframework"); ?></span></h4>
		<ul class="nav my-account-nav">
		  <li><a href="<?php echo $myaccount_page_url; ?>"><?php _e("Back to my account", "swiftframework"); ?></a></li>
		</ul>
	
	</div>
	
	<div class="my-account-right">
	
	<?php if (!$load_address) : ?>
	
		<?php woocommerce_get_template('myaccount/my-address.php'); ?>
	
	<?php else : ?>
		
		<form action="<?php echo esc_url( add_query_arg( 'address', $load_address, get_permalink( $edit_address ) ) ); ?>" method="post" class="edit-address-form">
	
			<h3><?php if ($load_address=='billing') _e( 'Billing Address', 'woocommerce' ); else _e( 'Shipping Address', 'woocommerce' ); ?></h3>
	
			<?php
			foreach ($address as $key => $field) :
				$value = (isset($_POST[$key])) ? $_POST[$key] : get_user_meta( get_current_user_id(), $key, true );
	
				// Default values
				if (!$value && ($key=='billing_email' || $key=='shipping_email')) $value = $current_user->user_email;
				if (!$value && ($key=='billing_country' || $key=='shipping_country')) $value = $woocommerce->countries->get_base_country();
				if (!$value && ($key=='billing_state' || $key=='shipping_state')) $value = $woocommerce->countries->get_base_state();
	
				woocommerce_form_field( $key, $field, $value );
			endforeach;
			?>
	
			<p>
				<input type="submit" class="button" name="save_address" value="<?php _e( 'Save Address', 'woocommerce' ); ?>" />
				<?php $woocommerce->nonce_field('edit_address') ?>
				<input type="hidden" name="action" value="edit_address" />
			</p>
	
		</form>
		
	<?php endif; ?>
	
	</div>

<?php } ?>