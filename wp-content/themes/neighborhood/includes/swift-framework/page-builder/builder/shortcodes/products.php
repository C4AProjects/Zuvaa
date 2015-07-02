<?php

class SwiftPageBuilderShortcode_spb_products_mini extends SwiftPageBuilderShortcode {

    protected function content($atts, $content = null) {

		    $title = $asset_type = $width = $el_class = $output = $items = $el_position = '';
		
	        extract(shortcode_atts(array(
		        'title' => '',
		        'asset_type' => 'best-sellers',
	        	'item_count' => '4',
	        	'category' => '',
	        	'el_position' => '',
	        	'width' => '1/4',
	        	'el_class' => ''
	        ), $atts));
	        
	        
			/* SIDEBAR CONFIG
			================================================== */			
			$sidebar_config = sf_get_post_meta(get_the_ID(), 'sf_sidebar_config', true);
				        
			$sidebars = '';
			if (($sidebar_config == "left-sidebar") || ($sidebar_config == "right-sidebar")) {
			$sidebars = 'one-sidebar';
			} else if ($sidebar_config == "both-sidebars") {
			$sidebars = 'both-sidebars';
			} else {
			$sidebars = 'no-sidebars';
			}
    		    		
			/* PRODUCT ITEMS
			================================================== */	
			$items = sf_mini_product_items($asset_type, $category, $item_count, $sidebars, $width);
    		
    		
    		$el_class = $this->getExtraClass($el_class);
            $width = spb_translateColumnWidthToSpan($width);
            
            $output .= "\n\t".'<div class="product_list_widget woocommerce spb_content_element '.$width.$el_class.'">';
            $output .= "\n\t\t".'<div class="spb_wrapper">';
            $output .= ($title != '' ) ? "\n\t\t\t".'<h4 class="spb_heading"><span>'.$title.'</span></h4>' : '';
            $output .= "\n\t\t\t\t".$items;
            $output .= "\n\t\t".'</div> '.$this->endBlockComment('.spb_wrapper');
            $output .= "\n\t".'</div> '.$this->endBlockComment($width);
    
            $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
            
            global $has_products;
            $has_products = true;
            
            return $output;
		
    }
}

SPBMap::map( 'spb_products_mini', array(
    "name"		=> __("Products (Mini)", 'swift-framework-admin'),
    "base"		=> "spb_products_mini",
    "class"		=> "spb-products-mini",
    "icon"      => "spb-icon-products-mini",
    "params"	=> array(
	    array(
	        "type" => "textfield",
	        "heading" => __("Widget title", 'swift-framework-admin'),
	        "param_name" => "title",
	        "value" => "",
	        "description" => __("Heading text. Leave it empty if not needed.", 'swift-framework-admin')
	    ),
	    array(
	        "type" => "dropdown",
	        "heading" => __("Asset type", 'swift-framework-admin'),
	        "param_name" => "asset_type",
	        "value" => array(
	        	__('Best Sellers', 'swift-framework-admin') => "best-sellers",
	        	__('Latest Products', 'swift-framework-admin') => "latest-products",
	        	__('Top Rated', 'swift-framework-admin') => "top-rated",
	        	__('Sale Products', 'swift-framework-admin') => "sale-products",
	        	__('Recently Viewed', 'swift-framework-admin') => "recently-viewed",
	        	__('Featured Products', 'swift-framework-admin') => "featured-products"
	        	),
	        "description" => __("Select the order of the products you'd like to show.", 'swift-framework-admin')
	    ),
	    array(
	        "type" => "textfield",
	        "heading" => __("Product category", 'swift-framework-admin'),
	        "param_name" => "category",
	        "value" => "",
	        "description" => __("Optionally, provide the category slugs for the products you want to show (comma seperated). i.e. trainer,dress,bag.", 'swift-framework-admin')
	    ),
        array(
            "type" => "textfield",
            "class" => "",
            "heading" => __("Number of items", 'swift-framework-admin'),
            "param_name" => "item_count",
            "value" => "4",
            "description" => __("The number of products to show.", 'swift-framework-admin')
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


class SwiftPageBuilderShortcode_spb_products extends SwiftPageBuilderShortcode {

    protected function content($atts, $content = null) {

		    $title = $asset_type = $carousel = $product_size = $width = $sidebars = $el_class = $output = $items = $el_position = '';
		
	        extract(shortcode_atts(array(
		        'title' => '',
		        'asset_type' => 'best-sellers',
		        'carousel' => 'no',
		        'product_size' => 'standard',
	        	'item_count' => '8',
	        	'category' => '',
	        	'el_position' => '',
	        	'width' => '1/1',
	        	'el_class' => ''
	        ), $atts));
	        
	   	    		
			/* PRODUCT ITEMS
			================================================== */	
			$items = sf_product_items($asset_type, $category, $carousel, $product_size, $item_count, $width);
    		
    		$el_class = $this->getExtraClass($el_class);
            $width = spb_translateColumnWidthToSpan($width);
            
            $output .= "\n\t".'<div class="product_list_widget products-'.$product_size.' woocommerce spb_content_element '.$width.$el_class.'">';
            $output .= "\n\t\t".'<div class="spb_wrapper">';
            $output .= ($title != '' ) ? "\n\t\t\t".'<h4 class="spb_heading"><span>'.$title.'</span></h4>' : '';
            $output .= "\n\t\t\t\t".$items;
            $output .= "\n\t\t".'</div> '.$this->endBlockComment('.spb_wrapper');
            $output .= "\n\t".'</div> '.$this->endBlockComment($width);
    
            $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
            
            if ($carousel == "yes") {
            	global $include_carousel;
            	$include_carousel = true;
            	
            }
            global $include_isotope, $has_products;
            $include_isotope = true;
            $has_products = true;

            return $output;
		
    }
}

SPBMap::map( 'spb_products', array(
    "name"		=> __("Products", 'swift-framework-admin'),
    "base"		=> "spb_products",
    "class"		=> "spb-products",
    "icon"      => "spb-icon-products",
    "params"	=> array(
	    array(
	        "type" => "textfield",
	        "heading" => __("Widget title", 'swift-framework-admin'),
	        "param_name" => "title",
	        "value" => "",
	        "description" => __("Heading text. Leave it empty if not needed.", 'swift-framework-admin')
	    ),
	    array(
	        "type" => "dropdown",
	        "heading" => __("Asset type", 'swift-framework-admin'),
	        "param_name" => "asset_type",
	        "value" => array(
	        	__('Best Sellers', 'swift-framework-admin') => "best-sellers",
	        	__('Latest Products', 'swift-framework-admin') => "latest-products",
	        	__('Top Rated', 'swift-framework-admin') => "top-rated",
	        	__('Sale Products', 'swift-framework-admin') => "sale-products",
	        	__('Recently Viewed', 'swift-framework-admin') => "recently-viewed",
	        	__('Featured Products', 'swift-framework-admin') => "featured-products"
	        	),
	        "description" => __("Select the order of products you'd like to show.", 'swift-framework-admin')
	    ),
	    array(
	        "type" => "textfield",
	        "heading" => __("Product category", 'swift-framework-admin'),
	        "param_name" => "category",
	        "value" => "",
	        "description" => __("Optionally, provide the category slugs for the products you want to show (comma seperated). i.e. trainer,dress,bag.", 'swift-framework-admin')
	    ),
	    array(
	        "type" => "dropdown",
	        "heading" => __("Carousel", 'swift-framework-admin'),
	        "param_name" => "carousel",
	        "value" => array(
	        	__('Yes', 'swift-framework-admin') => "yes",
	        	__('No', 'swift-framework-admin') => "no",
	        	),
	        "description" => __("Select if you'd like the asset to be a carousel.", 'swift-framework-admin')
	    ),
	    array(
	        "type" => "dropdown",
	        "heading" => __("Product Size", 'swift-framework-admin'),
	        "param_name" => "product_size",
	        "value" => array(
	        	__('Standard', 'swift-framework-admin') => "standard",
	        	__('Mini', 'swift-framework-admin') => "mini",
	        	),
	        "description" => __("Select whether you would like the product size to be standard, or mini. Mini shows 6 products in a row on a page with no sidebars.", 'swift-framework-admin')
	    ),
        array(
            "type" => "textfield",
            "class" => "",
            "heading" => __("Number of items", 'swift-framework-admin'),
            "param_name" => "item_count",
            "value" => "8",
            "description" => __("The number of products to show.", 'swift-framework-admin')
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