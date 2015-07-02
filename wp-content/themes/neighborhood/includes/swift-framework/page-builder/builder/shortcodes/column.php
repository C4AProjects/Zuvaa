<?php

	/*
	*
	*	Swift Page Builder - Column Shortcode Class
	*	------------------------------------------------
	*	Swift Framework
	* 	Copyright Swift Ideas 2013 - http://www.swiftideas.net
	*
	*/
	
	class SwiftPageBuilderShortcode_spb_column extends SwiftPageBuilderShortcode {
	
	    public function content( $atts, $content = null ) {
	
	        $el_class = $width = $el_position = '';
	
	        extract(shortcode_atts(array(
	            'el_class' => '',
	            'el_position' => '',
	            'width' => '1/2'
	        ), $atts));
	
	        $output = '';
	
	        $el_class = $this->getExtraClass($el_class);
	        $width = spb_translateColumnWidthToSpan($width);
	
	        if ( $this->shortcode == 'spb_column' ) {
	            $el_class .= ' column_container';
	        }
	        else if ( $this->shortcode == 'spb_text_block' ) {
	            $el_class .= ' spb_text_column';
	        }
	
	        $output .= "\n\t".'<div class="spb_content_element '.$width.$el_class.'">';
	        $output .= "\n\t\t".'<div class="spb_wrapper">';
	        $output .= "\n\t\t\t". spb_js_remove_wpautop($content);
	        $output .= "\n\t\t".'</div> '.$this->endBlockComment('.spb_wrapper');
	        $output .= "\n\t".'</div> '.$this->endBlockComment($width);
	
	        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
	        return $output;
	    }
	
	    public function contentAdmin($atts, $content = null) {
	        $width = '';
	        extract(shortcode_atts(array(
	            'width' => 'column_12'
	        ), $atts));
	
	        $output = '';
	
	        $column_controls = $this->getColumnControls('size_delete');
	
	        if ( $width == 'column_14' || $width == '1/4' ) {
	            $width = array('span3');
	        }
	        else if ( $width == 'column_14-14-14-14' ) {
	            $width = array('span3', 'span3', 'span3', 'span3');
	        }
	        else if ( $width == 'column_14-12-14' ) {
	            $width = array('span3', 'span6', 'span3');
	        }
	        else if ( $width == 'column_12-14-14' ) {
	            $width = array('span6', 'span3', 'span3');
	        }
	        else if ( $width == 'column_14-14-12' ) {
	            $width = array('span3', 'span3', 'span6');
	        }
	        else if ( $width == 'column_13' || $width == '1/3' ) {
	            $width = array('span4');
	        }
	        else if ( $width == 'column_13-23' ) {
	            $width = array('span4', 'span8');
	        }
	        else if ( $width == 'column_23-13' ) {
	            $width = array('span8', 'span4');
	        }
	        else if ( $width == 'column_13-13-13' ) {
	            $width = array('span4', 'span4', 'span4');
	        }
	
	        else if ( $width == 'column_12' || $width == '1/2' ) {
	            $width = array('span6');
	        }
	        else if ( $width == 'column_12-12' ) {
	            $width = array('span6', 'span6');
	        }
	
	        else if ( $width == 'column_23' || $width == '2/3' ) {
	            $width = array('span8');
	        }
	        else if ( $width == 'column_34' || $width == '3/4' ) {
	            $width = array('span9');
	        }
	        else {
	            $width = array('span12');
	        }
	
	
	        for ( $i=0; $i < count($width); $i++ ) {
	            $output .= '<div class="spb_column spb_sortable spb_droppable '.$width[$i].' not-column-inherit">';
	            $output .= '<input type="hidden" class="spb_sc_base" name="" value="spb_column" />';
	            $output .= str_replace("%column_size%", spb_translateColumnWidthToFractional($width[$i]), $column_controls);
	            $output .= '<div class="spb_element_wrapper">';
	            $output .= '<div class="row-fluid spb_column_container spb_sortable_container not-column-inherit">';
	            $output .= do_shortcode( shortcode_unautop($content) );
	            $output .= SwiftPageBuilder::getInstance()->getLayout()->getContainerHelper();
	            $output .= '</div>';
	            $output .= '</div>';
	            $output .= '</div>';
	        }
	
	        return $output;
	    }
	}

?>