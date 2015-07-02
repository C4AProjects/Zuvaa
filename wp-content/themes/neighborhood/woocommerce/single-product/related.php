<?php
/**
 * Related Products
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop, $sf_carouselID;

$related = $product->get_related(12);

if ( sizeof( $related ) == 0 ) return;

$args = apply_filters('woocommerce_related_products_args', array(
	'post_type'				=> 'product',
	'ignore_sticky_posts'	=> 1,
	'no_found_rows' 		=> 1,
	'posts_per_page' 		=> 12,
	'orderby' 				=> $orderby,
	'post__in' 				=> $related,
	'post__not_in'			=> array($product->id)
) );

$products = new WP_Query( $args );

//$woocommerce_loop['columns'] = $columns;
$woocommerce_loop['columns'] = 4;

if ($sf_carouselID == "") {
$sf_carouselID = 1;
} else {
$sf_carouselID++;
}

if ( $products->have_posts() ) : ?>

	<div class="related products product-carousel" data-columns="<?php echo $woocommerce_loop['columns']; ?>">

		<h4 class="lined-heading"><span><?php _e( 'Related Products', 'woocommerce' ); ?></span></h4>
			
		<div class="carousel-overflow">
		
		<?php woocommerce_product_loop_start(); ?>

			<?php while ( $products->have_posts() ) : $products->the_post(); ?>
								
				<?php woocommerce_get_template_part( 'content', 'product' ); ?>
				
			<?php endwhile; // end of the loop. ?>
				
		<?php woocommerce_product_loop_end(); ?>
		
		</div>
		
		<a href="#" class="prev"><i class="fa-chevron-left"></i></a><a href="#" class="next"><i class="fa-chevron-right"></i></a>
		
	</div>

<?php endif;

global $include_carousel, $include_isotope;
$include_carousel = true;
$include_isotope = true;

wp_reset_postdata();
