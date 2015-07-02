<?php
/**
 * Change password form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;


if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) { ?>

	<?php wc_print_notices(); ?>
	
	<?php 
		global $current_user;
	    get_currentuserinfo();
		$username = $current_user->user_login;
	?>
	
	<?php if ($username == "demo") { ?>
		<p><?php _e("It is not possible to change the password for the demo account.", "swiftframework"); ?></p>
	<?php } else { ?>
	
		<form action="<?php echo esc_url( get_permalink( wc_get_page_id( 'change_password' ) ) ); ?>" method="post">
		
			<p class="form-row form-row-first">
				<label for="password_1"><?php _e( 'New password', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="password" class="input-text" name="password_1" id="password_1" />
			</p>
			<p class="form-row form-row-last">
				<label for="password_2"><?php _e( 'Re-enter new password', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="password" class="input-text" name="password_2" id="password_2" />
			</p>
			<div class="clear"></div>
		
			<p><input type="submit" class="button" name="change_password" value="<?php _e( 'Save', 'woocommerce' ); ?>" /></p>
		
			<?php wp_nonce_field( 'woocommerce-change_password' ); ?>
			<input type="hidden" name="action" value="change_password" />
		
		</form>
	
	<?php } ?>
	
<?php } else { ?>

	<?php $woocommerce->show_messages(); ?>
	
	<h3><?php _e( 'Change password', 'swiftframework' ); ?></h3>
	
	<?php 
		global $current_user;
	    get_currentuserinfo();
		$username = $current_user->user_login;
	?>
	
	<?php if ($username == "demo") { ?>
		<p><?php _e("It is not possible to change the password for the demo account.", "swiftframework"); ?></p>
	<?php } else { ?>
		<form action="<?php echo esc_url( get_permalink(woocommerce_get_page_id('change_password')) ); ?>" method="post" class="change_password_form">
		
			<p class="form-row form-row-first">
				<label for="password_1"><?php _e( 'New password', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="password" class="input-text" name="password_1" id="password_1" />
			</p>
			<p class="form-row form-row-last">
				<label for="password_2"><?php _e( 'Re-enter new password', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="password" class="input-text" name="password_2" id="password_2" />
			</p>
			<div class="clear"></div>
		
			<p><input type="submit" class="button" name="change_password" value="<?php _e( 'Save', 'woocommerce' ); ?>" /></p>
		
			<?php $woocommerce->nonce_field('change_password')?>
			<input type="hidden" name="action" value="change_password" />
		
		</form>
	<?php } ?>

<?php } ?>