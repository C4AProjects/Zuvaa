<?php
	/*
	*
	*	WooCommerce Functions & Hooks
	*	------------------------------------------------
	*	Swift Framework
	* 	Copyright Swift Ideas 2013 - http://www.swiftideas.net
	*
	*/
	
	
	/* BASIC FILTER HOOKS
	================================================== */ 
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
	add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);
	add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);
	
	/* Remove default thumbnail output */
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	
	/* Remove default sale flash output */
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
	 
	function my_theme_wrapper_start() {
	  echo '<div class="page-content clearfix">';
	}
	 
	function my_theme_wrapper_end() {
	  echo '</div>';
	}
	
	function sf_product_items_text($count) {
		
		$product_item_text = "";
		
    	if ( $count > 1 ) {
        	$product_item_text = str_replace('%', number_format_i18n($count), __('% items', 'swiftframework'));
        } elseif ( $count == 0 ) {
        	$product_item_text = __('0 items', 'swiftframework');
        } else {
        	$product_item_text = __('1 item', 'swiftframework');
        }
        
        return $product_item_text;
	}
	
	
	/* WOOCOMMERCE CONTENT FUNCTIONS
	================================================== */ 
	
	function sf_get_product_stars() {
		
		$stars_output = "";
		
	    global $wpdb;
	    global $post;
	    $count = $wpdb->get_var("
		    SELECT COUNT(meta_value) FROM $wpdb->commentmeta
		    LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
		    WHERE meta_key = 'rating'
		    AND comment_post_ID = $post->ID
		    AND comment_approved = '1'
		    AND meta_value > 0
		");
		
		$rating = $wpdb->get_var("
		    SELECT SUM(meta_value) FROM $wpdb->commentmeta
		    LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
		    WHERE meta_key = 'rating'
		    AND comment_post_ID = $post->ID
		    AND comment_approved = '1'
		");
		
		if ( $count > 0 ) {
		
		    $average = number_format($rating / $count, 2);
		
		    $stars_output .= '<div class="starwrapper" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';
		
		    $stars_output .= '<span class="star-rating" title="'.sprintf(__('Rated %s out of 5', 'woocommerce'), $average).'"><span style="width:'.($average*16).'px"><span itemprop="ratingValue" class="rating">'.$average.'</span> </span></span>';
		
		    $stars_output .= '</div>';
		}
		
		return $stars_output;
	}
	
	function is_out_of_stock() {
	    global $post;
	    $post_id = $post->ID;
	    $stock_status = sf_get_post_meta($post_id, '_stock_status',true);
	    
	    if ($stock_status == 'outofstock') {
	    return true;
	    } else {
	    return false;
	    }
	}
	
	
	/* ADD TO CART HEADER RELOAD
	================================================== */
	if (!function_exists('sf_woo_header_add_to_cart_fragment')) {
		function sf_woo_header_add_to_cart_fragment( $fragments ) {
			global $woocommerce;
			
			ob_start();
			
			$cart_count = $woocommerce->cart->cart_contents_count;
			$cart_count_text = sf_product_items_text($cart_count);
			
			$options = get_option('sf_neighborhood_options');				
			$show_cart_count = false;
			if (isset($options['show_cart_count'])) {
				$show_cart_count = $options['show_cart_count'];
			}	
			?>	
				
			<li class="parent shopping-bag-item">
				<?php if ($show_cart_count) { ?>
				<a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping bag', 'swiftframework'); ?>"><i class="sf-cart"></i><?php echo $woocommerce->cart->get_cart_total(); ?> (<?php echo $cart_count; ?>)</a>
				<?php } else { ?>
				<a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping bag', 'swiftframework'); ?>"><i class="sf-cart"></i><?php echo $woocommerce->cart->get_cart_total(); ?></a>
				<?php }  ?>
			
				<ul class="sub-menu">     
					<li>                                      
						<div class="shopping-bag">
			 				
							<?php if ( sizeof($woocommerce->cart->cart_contents)>0 ) { ?>
					
								<div class="bag-header"><?php echo $cart_count_text; ?> <?php _e('in the shopping bag', 'swiftframework'); ?></div>
								
								<div class="bag-contents">
									
									<?php foreach ($woocommerce->cart->cart_contents as $cart_item_key => $cart_item) { ?>
								    	
								    	<?php
									        $bag_product = $cart_item['data']; 
									        $product_title = $bag_product->get_title();
								        ?>
								                                          
								        <?php if ($bag_product->exists() && $cart_item['quantity']>0) { ?>                                            
								        
								        	<div class="bag-product clearfix">   	
							            	
								            	<figure><a class="bag-product-img" href="<?php echo get_permalink($cart_item['product_id']); ?>"><?php echo $bag_product->get_image(); ?></a></figure>                   
									            
									            <div class="bag-product-details">
									           		<div class="bag-product-title">
									           			<a href="<?php echo get_permalink($cart_item['product_id']); ?>">
									           				<?php echo apply_filters('woocommerce_cart_widget_product_title', $product_title, $bag_product); ?></a>
									           		</div>
									            	<div class="bag-product-price"><?php _e("Unit Price:", "swiftframework"); ?> <?php echo woocommerce_price($bag_product->get_price()); ?></div>
									            	<div class="bag-product-quantity"><?php _e('Quantity:', 'swiftframework'); ?> <?php echo $cart_item['quantity']; ?></div>
									            </div>
									            	
									            <?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">&times;</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __('Remove this item', 'woocommerce') ), $cart_item_key ); ?>
									            
									    	</div>
								    	
								    	<?php } ?>
								    	
								    <?php } ?>
							    
							    </div>
							    
							    <div class="bag-buttons">
							    
							    	<a class="sf-roll-button bag-button" href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>"><span><?php _e('View shopping bag', 'swiftframework'); ?></span><span><?php _e('View shopping bag', 'swiftframework'); ?></span></a>
							    
							    	<a class="sf-roll-button checkout-button" href="<?php echo esc_url( $woocommerce->cart->get_checkout_url() ); ?>"><span><?php _e('Proceed to checkout', 'swiftframework'); ?></span><span><?php _e('Proceed to checkout', 'swiftframework'); ?></span></a>
							                    
								</div>
				
							<?php } else { ?>
									
								<div class="bag-header"><?php _e("0 items in the shopping bag", "swiftframework"); ?></div>
									
								<div class="bag-empty"><?php _e('Unfortunately, your shopping bag is empty.','swiftframework'); ?></div>                                   
														
								<div class="bag-buttons">
									
									<?php $shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) ); ?>
								
									<a class="sf-roll-button shop-button" href="<?php echo esc_url( $shop_page_url ); ?>"><span><?php _e('Go to the shop', 'swiftframework'); ?></span><span><?php _e('Go to the shop', 'swiftframework'); ?></span></a>
								            	                
								</div>
									
							<?php } ?>
				
							</div>
						</li>                                                                                                    
					</ul>                                                                                                          
				</li>
				
			<?php
			
			$fragments['.shopping-bag-item'] = ob_get_clean();
			
			return $fragments;
			
		}
		add_filter('add_to_cart_fragments', 'sf_woo_header_add_to_cart_fragment'); 
	}
	
	/* WISHLIST BUTTON
	================================================== */ 
	if (!function_exists('sf_wishlist_button')) {
		function sf_wishlist_button() {
		
			global $product, $yith_wcwl; 
			
			if ( class_exists( 'YITH_WCWL_UI' ) )  {
				$url = $yith_wcwl->get_wishlist_url();
				$product_type = $product->product_type;
				$exists = $yith_wcwl->is_product_in_wishlist( $product->id );
				
				$classes = get_option( 'yith_wcwl_use_button' ) == 'yes' ? 'class="add_to_wishlist single_add_to_wishlist button alt"' : 'class="add_to_wishlist"';
				
				$html  = '<div class="yith-wcwl-add-to-wishlist">'; 
				    $html .= '<div class="yith-wcwl-add-button';  // the class attribute is closed in the next row
				    
				    $html .= $exists ? ' hide" style="display:none;"' : ' show"';
				    
				    $html .= '><a href="' . htmlspecialchars($yith_wcwl->get_addtowishlist_url()) . '" data-product-id="' . $product->id . '" data-product-type="' . $product_type . '" ' . $classes . ' ><i class="fa-star"></i></a>';
				    $html .= '</div>';
				
				$html .= '<div class="yith-wcwl-wishlistaddedbrowse hide" style="display:none;"><span class="feedback">' . __( 'Product added to wishlist.', 'swiftframework' ) . '</span> <a href="' . $url . '"><i class="fa-check"></i></a></div>';
				$html .= '<div class="yith-wcwl-wishlistexistsbrowse ' . ( $exists ? 'show' : 'hide' ) . '" style="display:' . ( $exists ? 'block' : 'none' ) . '"><a href="' . $url . '"><i class="fa-check"></i></a></div>';
				$html .= '<div style="clear:both"></div><div class="yith-wcwl-wishlistaddresponse"></div>';
				
				$html .= '</div>';
				
				return $html;
				
			}
		
		}
	}
	
	
	/* SHOW PRODUCTS COUNT URL PARAMETER
	================================================== */ 
	if (isset($_GET['layout'])) {
		$page_layout = $_GET['layout'];
	}
	if( isset( $_GET['show_products'] ) ){ 
		if ($_GET['show_products'] == "all") {
	    	add_filter( 'loop_shop_per_page', create_function( '$cols', 'return -1;' ) );
	    } else {
	    	add_filter( 'loop_shop_per_page', create_function( '$cols', 'return '.$_GET['show_products'].';' ) );
	    }
	} else {
	    add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 24;' ) );
	}
	
	
	/* SINGLE PRODUCT
	================================================== */
	
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
	remove_action( 'woocommerce_product_tabs', 'woocommerce_product_description_tab', 10 );
	remove_action( 'woocommerce_product_tab_panels', 'woocommerce_product_description_panel', 10 );

	add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 35);	
	add_action( 'woocommerce_single_product_summary', 'sf_product_share', 45);
	
	if (!function_exists('sf_product_share')) {
		function sf_product_share() {
			global $post;
			$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, '' );
		?>
			<div class="product-share clearfix">
				<span><?php _e("Share", "swiftframework"); ?></span>
				<ul>
				    <li><a href="mailto:?subject=<?php the_title(); ?>&body=<?php echo strip_tags(apply_filters( 'woocommerce_short_description', $post->post_excerpt )); ?> <?php the_permalink(); ?>" class="product_share_email"><i class="fa-envelope"></i></a></li>
				    <li><a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,
				      '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="product_share_facebook"><i class="fa-facebook"></i></a></li>
				    <li><a href="https://twitter.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,
				      '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="product_share_twitter"><i class="fa-twitter"></i></a></li>   
				    <li><a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,
				      '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa-google-plus"></i></a></li>
				    <li><a href="//pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php echo $src[0]; ?>&description=<?php the_title(); ?>" onclick="javascript:window.open(this.href,
				      '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="product_share_pinterest"><i class="fa-pinterest"></i></a></li>
				</ul>  
			</div> 
		<?php }
	}
	
	if (!function_exists('sf_woo_help_bar')) {
		function sf_woo_help_bar() {
			$options = get_option('sf_neighborhood_options');
			$help_bar_text = __($options['help_bar_text'], 'swiftframework');
			$email_modal = __($options['email_modal'], 'swiftframework');
			$shipping_modal = __($options['shipping_modal'], 'swiftframework');
			$returns_modal = __($options['returns_modal'], 'swiftframework');
			$faqs_modal = __($options['faqs_modal'], 'swiftframework');
		?>
			<div class="help-bar clearfix">
				<span><?php echo do_shortcode($help_bar_text); ?></span>
				<ul>
				    <li><a href="#email-form" class="inline" data-toggle="modal"><?php _e("Email customer care", "swiftframework"); ?></a></li>
				    <li><a href="#shipping-information" class="inline" data-toggle="modal"><?php _e("Shipping information", "swiftframework"); ?></a></li>
				    <li><a href="#returns-exchange" class="inline" data-toggle="modal"><?php _e("Returns & exchange", "swiftframework"); ?></a></li>
				    <li><a href="#faqs" class="inline" data-toggle="modal"><?php _e("F.A.Q.'s", "swiftframework"); ?></a></li>
				</ul>
			</div>
			
			<div id="email-form" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="email-form-modal" aria-hidden="true">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 id="email-form-modal"><?php _e("Email customer care", "swiftframework"); ?></h3>
				</div>
				<div class="modal-body">
					
					<?php echo do_shortcode($email_modal); ?>
					
				</div>
			</div>
			
			<div id="shipping-information" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="shipping-modal" aria-hidden="true">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 id="shipping-modal"><?php _e("Shipping information", "swiftframework"); ?></h3>
				</div>
				<div class="modal-body">
					
					<?php echo do_shortcode($shipping_modal); ?>
					
				</div>
			</div>
			
			<div id="returns-exchange" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="returns-modal" aria-hidden="true">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 id="returns-modal"><?php _e("Returns & exchange", "swiftframework"); ?></h3>
				</div>
				<div class="modal-body">
					
					<?php echo do_shortcode($returns_modal); ?>
					
				</div>
			</div>
			
			<div id="faqs" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="faqs-modal" aria-hidden="true">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 id="faqs-modal"><?php _e("F.A.Q.'s", "swiftframework"); ?></h3>
				</div>
				<div class="modal-body">
					
					<?php echo do_shortcode($faqs_modal); ?>
					
				</div>
			</div>
			
		<?php }
	}	
?>