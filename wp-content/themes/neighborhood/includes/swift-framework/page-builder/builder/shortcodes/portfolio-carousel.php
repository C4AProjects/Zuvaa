<?php

class SwiftPageBuilderShortcode_portfolio_carousel extends SwiftPageBuilderShortcode {

    protected function content($atts, $content = null) {

		    $title = $category = $item_class = $width = $el_class = $output = $filter = $items = $el_position = '';
		
	        extract(shortcode_atts(array(
		        'title' => '',
	        	"item_count"	=> '12',
	        	"category"		=> 'all',
	        	'alt_background'	=> 'none',
	        	'el_position' => '',
	        	'width' => '1/1',
	        	'el_class' => ''
	        ), $atts));
	        
	        // CATEGORY SLUG MODIFICATION
	        if ($category == "All") {$category = "all";}
	        if ($category == "all") {$category = '';}
	        $category_slug = str_replace('_', '-', $category);
		    
    		global $post, $wp_query, $carouselID;
    		
    		if ($carouselID == "") {
    		$carouselID = 1;
    		} else {
    		$carouselID++;
    		}
    		
    		$portfolio_args=array(
    			'post_type' => 'portfolio',
    			'post_status' => 'publish',
    			'portfolio-category' => $category_slug,
    			'posts_per_page' => $item_count,
    			'no_found_rows' => 1
    			);
    			    		
    		$portfolio_items = new WP_Query( $portfolio_args );
    		
    		$count = $columns = 0;
    		    		
    		$sidebar_config = sf_get_post_meta(get_the_ID(), 'sf_sidebar_config', true);
    		
    		if (is_singular('portfolio')) {
    		$sidebar_config = "no-sidebars";
    		}
    		
    		if (($sidebar_config == "left-sidebar") || ($sidebar_config == "right-sidebar")) {
    		$item_class = 'span2';
    		} else if ($sidebar_config == "both-sidebars") {
    		$item_class = 'span-bs-quarter';
    		} else {
    		$item_class = 'span3';
    		}
    		
    		if ($width == "1/4") {
    		$columns = 1;
    		} else if ($width == "1/2") {
    		$columns = 2;
    		} else if ($width == "3/4") {
    		$columns = 3;
    		} else {
    		$columns = 4;
    		}
    		    		
			$items .= '<div class="carousel-overflow"><ul id="carousel-'.$carouselID.'" class="portfolio-items carousel-items clearfix" data-columns="'.$columns.'">';
	
			while ( $portfolio_items->have_posts() ) : $portfolio_items->the_post();
								
				$item_title = get_the_title();
				
				$thumb_type = sf_get_post_meta($post->ID, 'sf_thumbnail_type', true);
				$thumb_image = rwmb_meta('sf_thumbnail_image', 'type=image&size=full');
				$thumb_video = sf_get_post_meta($post->ID, 'sf_thumbnail_video_url', true);
				$thumb_gallery = rwmb_meta( 'sf_thumbnail_gallery', 'type=image&size=thumb-image' );
				$thumb_link_type = sf_get_post_meta($post->ID, 'sf_thumbnail_link_type', true);
				$thumb_link_url = sf_get_post_meta($post->ID, 'sf_thumbnail_link_url', true);
				$thumb_lightbox_thumb = rwmb_meta( 'sf_thumbnail_image', 'type=image&size=large' );
				$thumb_lightbox_image = rwmb_meta( 'sf_thumbnail_link_image', 'type=image&size=large' );
				$thumb_lightbox_video_url = sf_get_post_meta($post->ID, 'sf_thumbnail_link_video_url', true);
				
				foreach ($thumb_image as $detail_image) {
					$thumb_img_url = $detail_image['url'];
					break;
				}
												
				if (!$thumb_image) {
					$thumb_image = get_post_thumbnail_id();
					$thumb_img_url = wp_get_attachment_url( $thumb_image, 'full' );
				}
					
				$thumb_lightbox_img_url = wp_get_attachment_url( $thumb_lightbox_image, 'full' );				
				
				$item_title = get_the_title();
				$permalink = get_permalink();
								
				if ($thumb_link_type == "link_to_url") {
					$link_config = 'href="'.$thumb_link_url.'" class="link-to-url"';
					$item_icon = "link";
				} else if ($thumb_link_type == "link_to_url_nw") {
					$link_config = 'href="'.$thumb_link_url.'" class="link-to-url" target="_blank"';
					$item_icon = "link";
				} else if ($thumb_link_type == "lightbox_thumb") {
					$link_config = 'href="'.$thumb_img_url.'" class="view"';
					$item_icon = "search";
				} else if ($thumb_link_type == "lightbox_image") {
					$lightbox_image_url = '';
					foreach ($thumb_lightbox_image as $image) {
						$lightbox_image_url = $image['full_url'];
					}
					$link_config = 'href="'.$lightbox_image_url.'" class="view"';	
					$item_icon = "search";
				} else if ($thumb_link_type == "lightbox_video") {
					$link_config = 'href="'.$thumb_lightbox_video_url.'" rel="prettyPhoto"';
					$item_icon = "facetime-video";				
				} else {
					$link_config = 'href="'.$permalink.'" class="link-to-post"';
					$item_icon = "chevron-right";
				}
				    					   	
				$items .= '<li itemscope data-id="id-'. $count .'" class="clearfix carousel-item portfolio-item '.$item_class.'">';
				
				$items .= '<figure>';
						
				// THUMBNAIL MEDIA TYPE SETUP
				
				if ($thumb_type == "video") {
					
						$video = video_embed($thumb_video, 420, 315);
					
					$items .= $video;
					
				} else if ($thumb_type == "slider") {
					
					$items .= '<div class="flexslider thumb-slider"><ul class="slides">';
								
					foreach ( $thumb_gallery as $image )
					{
						$alt = $image['alt'];
						if (!$alt) {
						$alt = $image['title'];
						}
					    $items .= "<li><a ".$link_config."><img src='{$image['url']}' width='{$image['width']}' height='{$image['height']}' alt='{$alt}' /></a></li>";
					}
																	
					$items .= '</ul></div>';
					
				} else {
				
					$image = aq_resize( $thumb_img_url, 420, 315, true, false);
					    					  					
					if($image) {
						$items .= '<a '.$link_config.'>';
						
						$items .= '<div class="overlay"><div class="thumb-info">';
						$items .= '<i class="fa-'.$item_icon.'"></i>';
						$items .= '</div></div>';
						
						$items .= '<img itemprop="image" src="'.$image[0].'" width="'.$image[1].'" height="'.$image[2].'" alt="'.$item_title.'" />';
						    						    						
						$items .= '</a>';
					}
				}
				
				$items .= '</figure>';
				
				$items .= '<div class="item-details clearfix">';
				$items .= '<h4 class="portfolio-item-title"><a href="'.$permalink.'"><span>'. $item_title .'</span><i class="fa-angle-right"></i></a></h4>';
				$items .= '</div>';

				$items .= '</li>';
				$count++;
			
			endwhile;
			
			wp_reset_query();
			
			$items .= '</ul></div>';
    		
    		$items .= '<a href="#" class="prev"><i class="fa-chevron-left"></i></a><a href="#" class="next"><i class="fa-chevron-right"></i></a>';
        	
        	$width = spb_translateColumnWidthToSpan($width);
    		$el_class = $this->getExtraClass($el_class);
                 
            if ($alt_background == "none" || $sidebar_config != "no-sidebars" || $width != "span12") {
            $output .= "\n\t".'<div class="spb_portfolio_carousel_widget spb_content_element '.$width.$el_class.'">';
            } else {
            $output .= "\n\t".'<div class="spb_portfolio_carousel_widget spb_content_element alt-bg '.$alt_background.' '.$width.$el_class.'">';            
            }
            
            $output .= "\n\t\t".'<div class="spb_wrapper carousel-wrap">';
            if ($title != '') {
            $output .= "\n\t\t\t".'<div class="heading-wrap"><h4 class="spb_heading"><span>'.$title.'</span></h4></div>';
            }
            $output .= "\n\t\t\t\t".$items;
            $output .= "\n\t\t".'</div> '.$this->endBlockComment('.spb_wrapper');
            $output .= "\n\t".'</div> '.$this->endBlockComment($width);
    
            $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
            
            global $include_carousel, $include_isotope;
            $include_carousel = true;
            $include_isotope = true;
            
            return $output;
		
    }
}

SPBMap::map( 'portfolio_carousel', array(
    "name"		=> __("Portfolio Carousel", 'swift-framework-admin'),
    "base"		=> "portfolio_carousel",
    "class"		=> "spb_portfolio_carousel spb_carousel",
    "icon"      => "spb-icon-portfolio-carousel",
    "params"	=> array(
	    array(
	        "type" => "textfield",
	        "heading" => __("Widget title", 'swift-framework-admin'),
	        "param_name" => "title",
	        "value" => "",
	        "description" => __("Heading text. Leave it empty if not needed.", 'swift-framework-admin')
	    ),
        array(
            "type" => "textfield",
            "class" => "",
            "heading" => __("Number of items", 'swift-framework-admin'),
            "param_name" => "item_count",
            "value" => "12",
            "description" => __("The number of portfolio items to show in the carousel.", 'swift-framework-admin')
        ),
        array(
            "type" => "select-multiple",
            "heading" => __("Portfolio category", 'swift-framework-admin'),
            "param_name" => "category",
            "value" => get_category_list('portfolio-category'),
            "description" => __("Choose the category for the portfolio items.", 'swift-framework-admin')
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Show alt background", 'swift-framework-admin'),
            "param_name" => "alt_background",
            "value" => array(__("None", 'swift-framework-admin') => "none", __("Alt 1", 'swift-framework-admin') => "alt-one", __("Alt 2", 'swift-framework-admin') => "alt-two", __("Alt 3", 'swift-framework-admin') => "alt-three", __("Alt 4", 'swift-framework-admin') => "alt-four", __("Alt 5", 'swift-framework-admin') => "alt-five", __("Alt 6", 'swift-framework-admin') => "alt-six", __("Alt 7", 'swift-framework-admin') => "alt-seven", __("Alt 8", 'swift-framework-admin') => "alt-eight", __("Alt 9", 'swift-framework-admin') => "alt-nine", __("Alt 10", 'swift-framework-admin') => "alt-ten"),
            "description" => __("Show an alternative background around the asset. These can all be set in Neighborhood Options > Asset Background Options. NOTE: This is only available on a page with the no sidebar setup.", 'swift-framework-admin')
        ),
        array(
            "type" => "altbg_preview",
            "heading" => __("Alt Background Preview", 'swift-framework-admin'),
            "param_name" => "altbg_preview",
            "value" => "",
            "description" => __("", 'swift-framework-admin')
        ),
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", 'swift-framework-admin'),
            "param_name" => "el_class",
            "value" => "",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'swift-framework-admin')
        )
    )
) );

?>