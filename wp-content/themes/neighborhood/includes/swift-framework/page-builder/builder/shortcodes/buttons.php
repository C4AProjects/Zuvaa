<?php
	/*
	*
	*	Swift Page Builder - Button Shortcodes
	*	------------------------------------------------
	*	Swift Framework
	* 	Copyright Swift Ideas 2013 - http://www.swiftideas.net
	*
	*/

	class SwiftPageBuilderShortcode_impact_text extends SwiftPageBuilderShortcode {
	
	    protected function content( $atts, $content = null ) {
	        $color = $type = $size = $target = $href = $border_top = $include_button = $button_style = $border_bottom = $title = $width = $position = $el_class = '';
	        extract(shortcode_atts(array(
	            'color' => 'btn',
	            'include_button' => '',
	            'button_style' => '',
	            'size' => '',
	            'target' => '',
	            'type'	=> '',
	            'href' => '',
	            'shadow'		=> 'yes',
	            'title' => __('Text on the button', 'swift-framework-admin'),
	            'position' => 'cta_align_right',
	            'alt_background'	=> 'none',
	            'width' => '1/1',
	            'el_class' => '',
	            'el_position' => '',
	        ), $atts));
	        $output = '';
	        
	        $border_class = '';
	        
	        if ($border_top == "yes") {
	        $border_class .= 'border-top ';
	        }
	        if ($border_bottom == "yes") {
	        $border_class .= 'border-bottom';
	        }
	
			$width = spb_translateColumnWidthToSpan($width);
	        $el_class = $this->getExtraClass($el_class);
	        
	        $sidebar_config = sf_get_post_meta(get_the_ID(), 'sf_sidebar_config', true);
	        
	        $sidebars = '';
	        if (($sidebar_config == "left-sidebar") || ($sidebar_config == "right-sidebar")) {
	        $sidebars = 'one-sidebar';
	        } else if ($sidebar_config == "both-sidebars") {
	        $sidebars = 'both-sidebars';
	        } else {
	        $sidebars = 'no-sidebars';
	        }
	
	        if ( $target == 'same' || $target == '_self' ) { $target = '_self'; }
	        if ( $target != '' ) { $target = $target; }
	
	        $size = ( $size != '' ) ? ' '.$size : '';
	
	        $a_class = '';
	        if ( $el_class != '' ) {
	            $tmp_class = explode(" ", $el_class);
	            if ( in_array("prettyphoto", $tmp_class) ) {
	                wp_enqueue_script( 'prettyphoto' );
	                wp_enqueue_style( 'prettyphoto' );
	                $a_class .= ' prettyphoto'; $el_class = str_ireplace("prettyphoto", "", $el_class);
	            }
	        }
	        
	        $button = '';
	        
	        if (($type == "squarearrow") || ($type == "slightlyroundedarrow") || ($type == "roundedarrow")) {
	        	$button = '<a class="spb_button sf-button'.$size.' '. $color .' '. $type .'" href="'.$href.'" target="'.$target.'"><span>' . $title . '</span><span class="arrow"></span></a>';
	        } else {
	        	$button = '<a class="spb_button sf-button'.$size.' '. $color .' '. $type .'" href="'.$href.'" target="'.$target.'"><span>' . $title . '</span></a>';
	        }
	        
	        if ($button_style == "arrow") {
	        
		        if ($position == "cta_align_left") {
		        	$button = '<a class="impact-text-arrow arrow-left" href="'.$href.'" target="'.$target.'"><i class="fa-angle-left"></i></a>';
		        } else { 
		        	$button = '<a class="impact-text-arrow arrow-right" href="'.$href.'" target="'.$target.'"><i class="fa-angle-right"></i></a>';
		        }
	        
	        }
	        
	        if ($alt_background == "none" || $sidebars != "no-sidebars") {
	        $output .= '<div class="spb_impact_text spb_content_element clearfix '.$width.' '.$position.$el_class.'">'. "\n";
	        } else {
	        $output .= '<div class="spb_impact_text spb_content_element clearfix alt-bg '.$alt_background.' '.$width.' '.$position.$el_class.'">'. "\n";
	        }
	        $output .= '<div class="impact-text-wrap clearfix">'. "\n";
	        $output .= '<div class="spb_call_text">'. spb_js_remove_wpautop($content) . '</div>'. "\n";
	        if ($include_button == "yes") {
	        $output .= $button. "\n";
	        }
	        $output .= '</div>'. "\n";
	        $output .= '</div> ' . $this->endBlockComment('.spb_impact_text') . "\n";
			
			$output = $this->startRow($el_position) . $output . $this->endRow($el_position);
			
	        return $output;
	    }
	}
?>