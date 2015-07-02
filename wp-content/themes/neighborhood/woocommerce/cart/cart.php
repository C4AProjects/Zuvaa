<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0) {
wc_print_notices();
}

global $has_products, $include_isotope;
$has_products = true;
$include_isotope = true;
?>

<div class="container">

<?php

if (version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0) {
	
	$cart_count = sf_product_items_text(WC()->cart->cart_contents_count);
	
	do_action( 'woocommerce_before_cart' ); ?>
	
	<form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">
	
		<div class="row">
			
			<div class="span9">
			
				<h3 class="bag-summary"><?php _e('Your selection', 'swiftframework');?> <span>(<?php echo $cart_count; ?>)</span></h3>
				
				<?php do_action( 'woocommerce_before_cart_table' ); ?>
			
				<table class="shop_table cart" cellspacing="0">
					<thead>
						<tr>
							<th class="product-thumbnail"><?php _e( 'Item', 'swiftframework' ); ?></th>
							<th class="product-name"><?php _e( 'Description', 'swiftframework' ); ?></th>
							<th class="product-price"><?php _e( 'Unit Price', 'swiftframework' ); ?></th>
							<th class="product-quantity"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
							<th class="product-subtotal"><?php _e( 'Subtotal', 'swiftframework' ); ?></th>
							<th class="product-remove">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php do_action( 'woocommerce_before_cart_contents' ); ?>
				
						<?php
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
				
							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
								?>
								<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
				
									<td class="product-thumbnail">
										<?php
											$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
				
											if ( ! $_product->is_visible() )
												echo $thumbnail;
											else
												printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
										?>
									</td>
				
									<td class="product-name">
										<?php
											if ( ! $_product->is_visible() )
												echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
											else
												echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title() ), $cart_item, $cart_item_key );
				
											// Meta data
											echo WC()->cart->get_item_data( $cart_item );
				
				               				// Backorder notification
				               				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
				               					echo '<p class="backorder_notification">' . __( 'Available on backorder', 'woocommerce' ) . '</p>';
										?>
									</td>
				
									<td class="product-price">
										<?php
											echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
										?>
									</td>
				
									<td class="product-quantity">
										<?php
											if ( $_product->is_sold_individually() ) {
												$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
											} else {
												$product_quantity = woocommerce_quantity_input( array(
													'input_name'  => "cart[{$cart_item_key}][qty]",
													'input_value' => $cart_item['quantity'],
													'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
												), $_product, false );
											}
				
											echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
										?>
									</td>
				
									<td class="product-subtotal">
										<?php
											echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
										?>
									</td>
									
									<td class="product-remove">
										<?php
											echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove" title="%s">&times;</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );
										?>
									</td>
								</tr>
								<?php
							}
						}
				
						do_action( 'woocommerce_cart_contents' );
						?>
						<?php do_action( 'woocommerce_after_cart_contents' ); ?>
					</tbody>
				</table>
				
				<?php do_action( 'woocommerce_after_cart_table' ); ?>
				
				<?php if ( WC()->cart->coupons_enabled() ) { ?>
					<div class="coupon">
						
						<h4 class="lined-heading"><span><?php _e('Promotional Code', 'swiftframework'); ?></span></h4>
						
						<input name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php _e( 'Coupon code', 'woocommerce' ); ?>" /> <input type="submit" class="button" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'woocommerce' ); ?>" />
	
						<?php do_action('woocommerce_cart_coupon'); ?>
	
					</div>
				<?php } ?>
			
			</div>
			
			<div class="span3">
			
				<?php woocommerce_cart_totals(); ?>
				
				<input type="submit" class="update-cart-button button" name="update_cart" value="<?php _e( 'Update shopping bag', 'swiftframework' ); ?>" /> <input type="submit" class="checkout-button button alt" name="proceed" value="<?php _e( 'Proceed to Checkout', 'swiftframework' ); ?>" />
				
				<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
				
				<?php wp_nonce_field( 'woocommerce-cart' ); ?>
				
				<a class="continue-shopping" href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>"><?php _e('Continue shopping', 'swiftframework'); ?></a>
			
			</div>
				
		</div>
	
	</form>
	
	<div class="cart-shipping-wrap clearfix">
	
		<div class="shipping-calc">
		
			<?php woocommerce_shipping_calculator(); ?>
		
		</div>
	
	</div>
	
	<div class="cart-collaterals">
	
		<?php do_action('woocommerce_cart_collaterals'); ?>
	
	</div>
	
	<?php do_action( 'woocommerce_after_cart' ); ?>
	
<?php } else {

	global $woocommerce;
	
	$woocommerce->show_messages();
	
	$cart_count = sf_product_items_text($woocommerce->cart->cart_contents_count);
	
	?>
	
	<?php do_action( 'woocommerce_before_cart' ); ?>
		
	<form action="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" method="post">
	
		<div class="row">
			
			<div class="span9">
			
				<h3 class="bag-summary"><?php _e('Your selection', 'swiftframework');?> <span>(<?php echo $cart_count; ?>)</span></h3>
				
				<?php do_action( 'woocommerce_before_cart_table' ); ?>
				
				<table class="shop_table cart" cellspacing="0">
					<thead>
						<tr>
							<th class="product-thumbnail"><?php _e( 'Item', 'swiftframework' ); ?></th>
							<th class="product-name"><?php _e( 'Description', 'swiftframework' ); ?></th>
							<th class="product-price"><?php _e( 'Unit Price', 'swiftframework' ); ?></th>
							<th class="product-quantity"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
							<th class="product-subtotal"><?php _e( 'Subtotal', 'swiftframework' ); ?></th>
							<th class="product-remove">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php do_action( 'woocommerce_before_cart_contents' ); ?>
				
						<?php
						if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
							foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
								$_product = $values['data'];
								if ( $_product->exists() && $values['quantity'] > 0 ) {
									?>
									<tr class = "<?php echo esc_attr( apply_filters('woocommerce_cart_table_item_class', 'cart_table_item', $values, $cart_item_key ) ); ?>">	
										<!-- The thumbnail -->
										<td class="product-thumbnail">
											<?php
												$thumbnail = apply_filters( 'woocommerce_in_cart_product_thumbnail', $_product->get_image(), $values, $cart_item_key );
				
												if ( ! $_product->is_visible() || ( ! empty( $_product->variation_id ) && ! $_product->parent_is_visible() ) )
													echo $thumbnail;
												else
													printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('woocommerce_in_cart_product_id', $values['product_id'] ) ) ), $thumbnail );
											?>
										</td>
				
										<!-- Product Name -->
										<td class="product-name">
											<?php
												if ( ! $_product->is_visible() || ( ! empty( $_product->variation_id ) && ! $_product->parent_is_visible() ) )
													echo apply_filters( 'woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key );
												else
													printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('woocommerce_in_cart_product_id', $values['product_id'] ) ) ), apply_filters('woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key ) );
				
												// Meta data
												echo $woocommerce->cart->get_item_data( $values );
				
				                   				// Backorder notification
				                   				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $values['quantity'] ) )
				                   					echo '<p class="backorder_notification">' . __( 'Available on backorder', 'woocommerce' ) . '</p>';
				                   					
				                   				if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
				                   													
													$product_price = get_option('woocommerce_tax_display_cart') == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();
														
													echo '<span class="price">'.apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $values, $cart_item_key ).'</span>';
												
												} else {
												
													$product_price = get_option('woocommerce_display_cart_prices_excluding_tax') == 'yes' || $woocommerce->customer->is_vat_exempt() ? $_product->get_price_excluding_tax() : $_product->get_price();
												
													echo apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $values, $cart_item_key );
												
												}
											?>
										</td>
				
										<!-- Product price -->
										<td class="product-price">
											<?php
												if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
													
													$product_price = get_option('woocommerce_tax_display_cart') == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();
				
													echo apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $values, $cart_item_key );
												
												} else {
												
													$product_price = get_option('woocommerce_display_cart_prices_excluding_tax') == 'yes' || $woocommerce->customer->is_vat_exempt() ? $_product->get_price_excluding_tax() : $_product->get_price();
												
													echo apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $values, $cart_item_key );
												
												}
											?>
										</td>
				
										<!-- Quantity inputs -->
										<td class="product-quantity">
											<?php
												if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
													
													if ( $_product->is_sold_individually() ) {
														$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
													} else {
					
														$step	= apply_filters( 'woocommerce_quantity_input_step', '1', $_product );
														$min 	= apply_filters( 'woocommerce_quantity_input_min', '', $_product );
														$max 	= apply_filters( 'woocommerce_quantity_input_max', $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(), $_product );
					
														$product_quantity = sprintf( '<div class="quantity"><input type="number" name="cart[%s][qty]" step="%s" min="%s" max="%s" value="%s" size="4" title="' . _x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) . '" class="input-text qty text" maxlength="12" /></div>', $cart_item_key, $step, $min, $max, esc_attr( $values['quantity'] ) );
													}
					
													echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
												
												} else {
													
													if ( $_product->is_sold_individually() ) {
														$product_quantity = '1';
													} else {
														$data_min = apply_filters( 'woocommerce_cart_item_data_min', '', $_product );
														$data_max = ( $_product->backorders_allowed() ) ? '' : $_product->get_stock_quantity();
														$data_max = apply_filters( 'woocommerce_cart_item_data_max', $data_max, $_product );
					
														$product_quantity = sprintf( '<div class="quantity"><input name="cart[%s][qty]" data-min="%s" data-max="%s" value="%s" size="4" title="Qty" class="input-text qty text" maxlength="12" /></div>', $cart_item_key, $data_min, $data_max, esc_attr( $values['quantity'] ) );
													}
					
													echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
													
												}
											?>
										</td>
				
										<!-- Product subtotal -->
										<td class="product-subtotal">
											<?php
												echo apply_filters( 'woocommerce_cart_item_subtotal', $woocommerce->cart->get_product_subtotal( $_product, $values['quantity'] ), $values, $cart_item_key );
											?>
										</td>
										
										<!-- Remove from cart link -->
										<td class="product-remove">
											<?php
												echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s"><i class="fa-times"></i></a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key );
											?>
										</td>
									</tr>
									<?php
								}
							}
						}
				
						do_action( 'woocommerce_cart_contents' );
						?>
						
						<?php do_action( 'woocommerce_after_cart_contents' ); ?>
					</tbody>
				</table>
			
				<?php do_action( 'woocommerce_after_cart_table' ); ?>
				
				<?php if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) { ?>		
					<?php if ( $woocommerce->cart->coupons_enabled() ) { ?>
					<div class="coupon">
						
						<h4 class="lined-heading"><span><?php _e('Promotional Code', 'swiftframework'); ?></span></h4>
			
						<input name="coupon_code" class="input-text" id="coupon_code" placeholder="<?php _e('Enter promotion code', 'swiftframework'); ?>" value="" /> <input type="submit" class="apply-coupon" name="apply_coupon" value="<?php _e( 'Apply', 'woocommerce' ); ?>" />
			
						<?php do_action('woocommerce_cart_coupon'); ?>
			
					</div>
					<?php } ?>
				<?php } ?>
			
			</div>
			
			<div class="span3">
			
			<?php woocommerce_cart_totals(); ?>
			
			<input type="submit" class="update-cart-button button" name="update_cart" value="<?php _e( 'Update shopping bag', 'swiftframework' ); ?>" /> <input type="submit" class="checkout-button button alt" name="proceed" value="<?php _e( 'Proceed to Checkout', 'swiftframework' ); ?>" />
			
			<?php do_action('woocommerce_proceed_to_checkout'); ?>
			
			<?php $woocommerce->nonce_field('cart') ?>
			
			<a class="continue-shopping" href="<?php echo get_permalink( woocommerce_get_page_id( 'shop' ) ); ?>"><?php _e('Continue shopping', 'swiftframework'); ?></a>
			
			</div>
			
		</div>
			
	</form>
	
	<div class="cart-shipping-wrap clearfix">
	
		<div class="shipping-calc">
		
			<?php woocommerce_shipping_calculator(); ?>
		
		</div>
	
	</div>
	
	<div class="cart-collaterals">
	
		<?php do_action('woocommerce_cart_collaterals'); ?>
	
	</div>
	
	<?php do_action( 'woocommerce_after_cart' ); ?>
	
	</div>
<?php }
?>