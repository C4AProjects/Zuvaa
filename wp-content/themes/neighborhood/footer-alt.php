<?php
						?>
					
					<!--// CLOSE #page-wrap //-->			
					</div>
				
				<!--// CLOSE .container //-->
				</div>

			<!--// CLOSE #main-container //-->
			</div>
		
		<!--// CLOSE #container //-->
		</div>
		
		<?php
			// INCLUDED FUNCTIONALITY SETUP
			global $post, $has_portfolio, $has_blog, $has_products, $include_maps, $include_isotope, $include_carousel, $include_parallax, $has_progress_bar, $has_chart, $has_team, $sf_has_imagebanner;
				
			$sf_inc_class = "";
			
			if ($has_portfolio) {
				$sf_inc_class .= "has-portfolio ";
			}
			if ($has_blog) {
				$sf_inc_class .= "has-blog ";
			}
			if ($has_products) {
				$sf_inc_class .= "has-products ";
			}
			
			$content = $post->post_content;
			
			if (function_exists('has_shortcode')) {
				if (has_shortcode( $content, 'product_category' ) || has_shortcode( $content, 'featured_products' ) || has_shortcode( $content, 'products' ) || has_shortcode( $content, 'recent_products' ) || has_shortcode( $content, 'product' )) {
					$sf_inc_class .= "has-products ";
					$include_isotope = true;
				}
			}
			
			if ($include_maps) {
				$sf_inc_class .= "has-map ";
			}
			if ($include_carousel) {
				$sf_inc_class .= "has-carousel ";
			}
			if ($include_parallax) {
				$sf_inc_class .= "has-parallax ";
			}
			if ($has_progress_bar) {
				$sf_inc_class .= "has-progress-bar ";
			}
			if ($has_chart) {
				$sf_inc_class .= "has-chart ";
			}
			if ($has_team) {
				$sf_inc_class .= "has-team ";
			}
			if ($sf_has_imagebanner) {
				$sf_inc_class .= "has-imagebanner ";
			}
			
			$options = get_option('sf_neighborhood_options');
			
			if (isset($options['enable_product_zoom'])) {	
				$enable_product_zoom = $options['enable_product_zoom'];	
				if ($enable_product_zoom) {
					$sf_inc_class .= "has-productzoom ";
				}
			}

		?>
				
		<!--// FRAMEWORK INCLUDES //-->
		<div id="sf-included" class="<?php echo $sf_inc_class; ?>"></div>
		
		<?php $tracking = $options['google_analytics']; ?>
		<?php if ($tracking != "") { ?>
		<?php echo $tracking; ?>
		<?php } ?>
			
		<!--// WORDPRESS FOOTER HOOK //-->
		<?php wp_footer(); ?>

	
	<!--// CLOSE BODY //-->
	</body>


<!--// CLOSE HTML //-->
</html>