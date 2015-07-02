<?php
	
	/**
	 */
	
	/* This shortcode is used for columns and text containers output
	---------------------------------------------------------- */

	class SwiftPageBuilderShortcode_spb_text_block extends SwiftPageBuilderShortcode {
	
	    public function content( $atts, $content = null ) {
	
	        $title = $pb_margin_bottom = $pb_border_bottom = $el_class = $width = $el_position = '';
	
	        extract(shortcode_atts(array(
	        	'title' => '',
	        	'icon' => '',
	        	'pb_margin_bottom' => 'no',
	        	'pb_border_bottom' => 'no',
	            'el_class' => '',
	            'el_position' => '',
	            'width' => '1/2'
	        ), $atts));
	
	        $output = '';
	
	        $el_class = $this->getExtraClass($el_class);
	        $width = spb_translateColumnWidthToSpan($width);
	
	        $el_class .= ' spb_text_column';
	        
	        if ($pb_margin_bottom == "yes") {
	        $el_class .= ' pb-margin-bottom';
	        }
	        if ($pb_border_bottom == "yes") {
	        $el_class .= ' pb-border-bottom';
	        }
	        
	        $icon_output = "";
	        
	        if ($icon) { 
	        $icon_output = '<i class="'.$icon.'"></i>';
	        }
	
	        $output .= "\n\t".'<div class="spb_content_element '.$width.$el_class.'">';
	        $output .= "\n\t\t".'<div class="spb_wrapper clearfix">';
	        if ($icon_output != "") {
	        $output .= ($title != '' ) ? "\n\t\t\t".'<h4 class="spb_heading"><span>'.$icon_output.''.$title.'</span></h4>' : '';
	        } else {
	        $output .= ($title != '' ) ? "\n\t\t\t".'<h4 class="spb_heading spb_text_heading"><span>'.$title.'</span></h4>' : '';
	        }
	        $output .= "\n\t\t\t".do_shortcode($content);
	        $output .= "\n\t\t".'</div> ' . $this->endBlockComment('.spb_wrapper');
	        $output .= "\n\t".'</div> ' . $this->endBlockComment($width);
	
	        //
	        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
	        return $output;
	    }
	}
	
	
	class SwiftPageBuilderShortcode_boxed_content extends SwiftPageBuilderShortcode {
	
	    public function content( $atts, $content = null ) {
	
	        $title = $type = $custom_styling = $custom_bg_colour = $custom_text_colour = $pb_margin_bottom = $el_class = $width = $el_position = '';
	
	        extract(shortcode_atts(array(
	        	'title' => '',
	        	'type'	=> '',
	        	'custom_bg_colour' => '',
	        	'custom_text_colour' => '',
	        	'pb_margin_bottom' => 'no',
	            'el_class' => '',
	            'el_position' => '',
	            'width' => '1/2'
	        ), $atts));
	
	        $output = '';
	
	        $el_class = $this->getExtraClass($el_class);
	        $width = spb_translateColumnWidthToSpan($width);
	
	        $el_class .= ' spb_box_text';
	        $el_class .= ' '.$type;
	        
	        if ($pb_margin_bottom == "yes") {
	        $el_class .= ' pb-margin-bottom';
	        }
	        
	        if ($custom_bg_colour != "") {
	        $custom_styling .= 'background: '.$custom_bg_colour.'!important;';
	        }
	        
	        if ($custom_text_colour != "") {
	        $custom_styling .= 'color: '.$custom_text_colour.'!important;';
	        }
	
	        $output .= "\n\t".'<div class="spb_content_element '.$width.$el_class.'">';
	        $output .= "\n\t\t".'<div class="spb_wrapper">';
	        $output .= ($title != '' ) ? "\n\t\t\t".'<h4 class="spb_heading spb_text_heading"><span>'.$title.'</span></h4>' : '';
	        $output .= "\n\t\t\t";
	        if ($custom_styling != "") {
	        $output .= '<div class="box-content-wrap" style="'.$custom_styling.'">'.do_shortcode($content).'</div>';
	        } else {
	        $output .= '<div class="box-content-wrap">'.do_shortcode($content).'</div>';
	        }
	        $output .= "\n\t\t".'</div> ' . $this->endBlockComment('.spb_wrapper');
	        $output .= "\n\t".'</div> ' . $this->endBlockComment($width);
	
	        //
	        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
	        return $output;
	    }
	}
	
	
	
	class SwiftPageBuilderShortcode_divider extends SwiftPageBuilderShortcode {
	
	    protected function content( $atts, $content = null ) {
	        $with_line = $type = $el_class = $text = '';
	        extract(shortcode_atts(array(
	            'with_line' => '',
	            'type'		=> '',
	            'full_width'		=> '',
	            'text' => '',
	            'el_class' => '',
	            'el_position' => ''
	        ), $atts));
	        
	        $width = spb_translateColumnWidthToSpan("1/1");
	        
	        $output = '';
	        if ($full_width == "yes") {
	        $output .= '<div class="spb_divider '. $type .' spb_content_element alt-bg '.$width.' '.$el_class.'">';
	        } else {
	        $output .= '<div class="spb_divider '. $type .' spb_content_element '.$width.' '.$el_class.'">';
	        }
	        if ($type == "go_to_top") {
	        $output .= '<a class="animate-top" href="#">'. $text .'</a>';
	        } else if ($type == "go_to_top_icon1") {
	        $output .= '<a class="animate-top" href="#"><i class="fa-arrow-up"></i></a>';
	        } else if ($type == "go_to_top_icon2") {
	        $output .= '<a class="animate-top" href="#">'. $text .'<i class="fa-arrow-up"></i></a>';
	        }
	        $output .= '</div>'.$this->endBlockComment('divider')."\n";
	        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
	        return $output;
	    }
	}
	
	
	class SwiftPageBuilderShortcode_blank_spacer extends SwiftPageBuilderShortcode {
	
	    protected function content( $atts, $content = null ) {
	        $height = $el_class = '';
	        extract(shortcode_atts(array(
	            'height' => '',
	            'spacer_id' => '',
	            'el_class' => '',
	        ), $atts));
	        
	        $width = spb_translateColumnWidthToSpan("1/1");
	        
	        $output = '';
	        if ($spacer_id != "") {
	        $output .= '<div id="'.$spacer_id.'" class="blank_spacer '.$width.' '.$el_class.'" style="height:'.$height.';">';
	        } else {
	        $output .= '<div class="blank_spacer '.$width.' '.$el_class.'" style="height:'.$height.';">';
	        }
	        $output .= '</div>'.$this->endBlockComment('divider')."\n";
	        return $output;
	    }
	}
	
	class SwiftPageBuilderShortcode_spb_message extends SwiftPageBuilderShortcode {
	
	    protected function content( $atts, $content = null ) {
	        $color = '';
	        extract(shortcode_atts(array(
	            'color' => 'alert-info',
	            'el_position' => ''
	        ), $atts));
	        $output = '';
	        if ($color == "alert-block") $color = "";
	        
	        $width = spb_translateColumnWidthToSpan("1/1");
	
	        $output .= '<div class="alert spb_content_element '.$width.' '.$color.'"><div class="messagebox_text">'.spb_js_remove_wpautop($content).'</div></div>'.$this->endBlockComment('alert box')."\n";
	        //$output .= '<div class="spb_messagebox message '.$color.'"><div class="messagebox_text">'.spb_js_remove_wpautop($content).'</div></div>';
	        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
	        return $output;
	    }
	}
	
	
	
	class SwiftPageBuilderShortcode_spb_toggle extends SwiftPageBuilderShortcode {
	
	    protected function content( $atts, $content = null ) {
	        $title = $el_class = $open = null;
	        extract(shortcode_atts(array(
	            'title' => __("Click to toggle", 'swift-framework-admin'),
	            'el_class' => '',
	            'open' => 'false',
	            'el_position' => '',
	            'width' => '1/1'
	        ), $atts));
	        $output = '';
	        
	        $width = spb_translateColumnWidthToSpan($width);
	
	        $el_class = $this->getExtraClass($el_class);
	        $open = ( $open == 'true' ) ? ' spb_toggle_title_active' : '';
	        $el_class .= ( $open == ' spb_toggle_title_active' ) ? ' spb_toggle_open' : '';
			$output .= '<div class="toggle-wrap '.$width.'">';
	        $output .= '<div class="spb_toggle'.$open.'">'.$title.'</div><div class="spb_toggle_content'.$el_class.'">'.spb_js_remove_wpautop($content).'</div>'.$this->endBlockComment('toggle')."\n";
	        $output .= '</div>';
			$output = $this->startRow($el_position) . $output . $this->endRow($el_position);
	        return $output;
	    }
	}

?>