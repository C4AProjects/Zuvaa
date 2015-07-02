<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

?>

<?php if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {
	
	wc_print_notices();
	
	sf_woo_help_bar();
	
	do_action( 'woocommerce_before_checkout_form', $checkout );
	
	// If checkout registration is disabled and not logged in, the user cannot checkout
	if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
		echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
		return;
	}
	
	// filter hook for include new pages inside the payment method
	$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_checkout_url() ); ?>
	
	<form name="checkout" method="post" class="checkout row" action="<?php echo esc_url( $get_checkout_url ); ?>">
	
		<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>
				
			<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
				
			<div class="span7" id="customer_details">
			
				<div>
	
					<?php do_action( 'woocommerce_checkout_billing' ); ?>
	
				</div>
	
				<div>
	
					<?php do_action( 'woocommerce_checkout_shipping' ); ?>
	
				</div>
	
			</div>
		
			<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
	
		<?php endif; ?>
		
		<div class="col-2 span5" id="review-order">
			
			<div class="review-order-wrap">
				
				<h4 id="order_review_heading"><span><?php _e( 'Your Order', 'swiftframework' ); ?></span></h4>
				
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
			
			</div>

		</div>
	
	</form>
	
	<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<?php } else {

	$woocommerce_checkout = $woocommerce->checkout();
	
	$woocommerce->show_messages();
	
	sf_woo_help_bar();
	
	do_action( 'woocommerce_before_checkout_form', $checkout );
	
	// If checkout registration is disabled and not logged in, the user cannot checkout
	if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
		if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
			echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
			return;
		}
	} else {
		if (get_option('woocommerce_enable_signup_and_login_from_checkout')=="no" && get_option('woocommerce_enable_guest_checkout')=="no" && !is_user_logged_in()) :
			echo apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce'));
			return;
		endif;
	}
	
	// filter hook for include new pages inside the payment method
	$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', $woocommerce->cart->get_checkout_url() );
	
	?>
	
	<?php if (!is_user_logged_in()) { ?>
		<p class="returning-customer"><?php _e("Returning customer?", "swiftframework"); ?> <a href="#login-form" data-toggle="modal"><?php _e("Login here", "swiftframework"); ?></a></p>
		
		<div id="login-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="email-form-modal" aria-hidden="true">
			<div class="modal-dialog">
			    <div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa-times"></i></button>
						<h3 id="login-form-modal"><?php _e("Login", "swiftframework"); ?></h3>
					</div>
					<div class="modal-body">
						<?php
							echo woocommerce_checkout_login_form(
								array(
									'message'  => '',
									'redirect' => get_permalink( woocommerce_get_page_id( 'checkout') ),
									'hidden'   => false
								)
							);
						?>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
			
	<form name="checkout" method="post" class="checkout row" action="<?php echo esc_url( $get_checkout_url ); ?>">
		
		<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>
		
			<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
				
			<div class="span7" id="customer_details">
			
				<div>
	
					<?php do_action( 'woocommerce_checkout_billing' ); ?>
	
				</div>
	
				<div>
	
					<?php do_action( 'woocommerce_checkout_shipping' ); ?>
	
				</div>
	
			</div>
		
			<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
	
		<?php endif; ?>
		
		<div class="span5" id="review-order">
			
			<div class="review-order-wrap">
				
				<h4 id="order_review_heading"><span><?php _e( 'Your Order', 'swiftframework' ); ?></span></h4>
				
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
			
			</div>

		</div>
	
	</form>
	
	<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<?php } ?>