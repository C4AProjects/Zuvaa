<?php
/**
 */

class SwiftPageBuilderShortcode_spb_video extends SwiftPageBuilderShortcode {

    protected function content( $atts, $content = null ) {
        $title = $link = $size = $el_position = $full_width = $width = $el_class = '';
        extract(shortcode_atts(array(
            'title' => '',
            'link' => '',
            'size' => ( isset($content_width) ) ? $content_width : 500,
            'el_position' => '',
            'width' => '1/1',
            'full_width' => 'no',
            'el_class' => ''
        ), $atts));
        $output = '';

        if ( $link == '' ) { return null; }
        $video_h = '';
        $el_class = $this->getExtraClass($el_class);
        $width = spb_translateColumnWidthToSpan($width);
        $size = str_replace(array( 'px', ' ' ), array( '', '' ), $size);
        $size = explode("x", $size);
        $video_w = $size[0];
        if ( count($size) > 1 ) {
            $video_h = ' height="'.$size[1].'"';
        }

        global $wp_embed;
        $embed = $wp_embed->run_shortcode('[embed width="'.$video_w.'"'.$video_h.']'.$link.'[/embed]');
		
		if ($full_width == "yes") {
        $output .= "\n\t".'<div class="spb_video_widget full-width spb_content_element '.$width.$el_class.'">';
		} else {
        $output .= "\n\t".'<div class="spb_video_widget spb_content_element '.$width.$el_class.'">';
		}
		
        $output .= "\n\t\t".'<div class="spb_wrapper">';
        $output .= ($title != '' ) ? "\n\t\t\t".'<h4 class="spb_heading spb_video_heading"><span>'.$title.'</span></h4>' : '';
        $output .= $embed;
        $output .= "\n\t\t".'</div> '.$this->endBlockComment('.spb_wrapper');
        $output .= "\n\t".'</div> '.$this->endBlockComment($width);

        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
        return $output;
    }
}


class SwiftPageBuilderShortcode_spb_single_image extends SwiftPageBuilderShortcode {

    public function content( $atts, $content = null ) {

        $el_class = $width = $image_size = $frame = $lightbox = $image_link = $link_target = $caption = $el_position = $image = '';

        extract(shortcode_atts(array(
            'width' => '1/1',
            'image' => $image,
            'image_size' => '',
            'frame'	=> '',
            'lightbox' => '',
            'image_link' => '',
            'link_target' => '',
            'caption' => '',
            'el_position' => ''
        ), $atts));
		
		if ($image_size == "") { $image_size = "large"; }
		
        $output = '';
        $img = spb_getImageBySize(array( 'attach_id' => preg_replace('/[^\d]/', '', $image), 'thumb_size' => $image_size ));
        $img_url = wp_get_attachment_image_src($image, 'large');
        $el_class = $this->getExtraClass($el_class);
        $width = spb_translateColumnWidthToSpan($width);
        // $content =  !empty($image) ? '<img src="'..'" alt="">' : '';
        $content = '';
        $output .= "\n\t".'<div class="spb_content_element spb_single_image '. $frame .' '.$width.$el_class.'">';           

        $output .= "\n\t\t".'<div class="spb_wrapper">';
        if ($lightbox == "yes") {
        $output .= '<figure class="lightbox clearfix">';
        } else {
        $output .= '<figure class="clearfix">';
        }
        if ($image_link != "") {
        $output .= "\n\t\t\t".'<a class="img-link" href="'.$image_link.'" target="'.$link_target.'">';
        $output .= '<div class="overlay"><div class="thumb-info">';
        $output .= '<i class="fa-link"></i>';
        $output .= '</div></div>';
        $output .= $img['thumbnail'];
        $output .= '</a>';
        } else if ($lightbox == "yes") {
        $output .= "\n\t\t\t".'<a class="view" href="'.$img_url[0].'" rel="image-gallery">';
        $output .= '<div class="overlay"><div class="thumb-info">';
        $output .= '<i class="fa-search"></i>';
        $output .= '</div></div>';
        $output .= $img['thumbnail'];
        $output .= '</a>';
        } else { 
        $output .= "\n\t\t\t".$img['thumbnail'];
        }
        if ($caption != "") {
        $output .= '<figcaption>'.$caption.'</figcaption>';
        }
        $output .= '</figure>';
        $output .= "\n\t\t".'</div> '.$this->endBlockComment('.spb_wrapper');
        $output .= "\n\t".'</div> '.$this->endBlockComment($width);

        //
        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
        return $output;
    }

    public function singleParamHtmlHolder($param, $value) {
        $output = '';
        // Compatibility fixes
        $old_names = array('yellow_message', 'blue_message', 'green_message', 'button_green', 'button_grey', 'button_yellow', 'button_blue', 'button_red', 'button_orange');
        $new_names = array('alert-block', 'alert-info', 'alert-success', 'btn-success', 'btn', 'btn-info', 'btn-primary', 'btn-danger', 'btn-warning');
        $value = str_ireplace($old_names, $new_names, $value);
        //$value = __($value, 'swift-framework-admin');
        //
        $param_name = isset($param['param_name']) ? $param['param_name'] : '';
        $type = isset($param['type']) ? $param['type'] : '';
        $class = isset($param['class']) ? $param['class'] : '';

        if ( isset($param['holder']) == false || $param['holder'] == 'hidden' ) {
            $output .= '<input type="hidden" class="spb_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="'.$value.'" />';
            if(($param['type'])=='attach_image') {
                $img = spb_getImageBySize(array( 'attach_id' => (int)preg_replace('/[^\d]/', '', $value), 'thumb_size' => 'thumbnail' ));
                $output .= ( $img ? $img['thumbnail'] : '<img width="150" height="150" src="' . SwiftPageBuilder::getInstance()->assetURL('img/blank_f7.gif') . '" class="attachment-thumbnail" alt="" title="" />') . '<a href="#" class="column_edit_trigger' . ( $img && !empty($img['p_img_large'][0]) ? ' image-exists' : '' ) . '"><i class="spb-icon-single-image"></i>' . __( 'No image yet! Click here to select it now.', 'swift-framework-admin' ) . '</a>';
            }
        }
        else {
            $output .= '<'.$param['holder'].' class="spb_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">'.$value.'</'.$param['holder'].'>';
        }
        return $output;
    }
}

class SwiftPageBuilderShortcode_spb_gmaps extends SwiftPageBuilderShortcode {

    protected function content( $atts, $content = null ) {

        $title = $address = $size = $zoom = $pin_image = $type = $el_position = $width = $el_class = '';
        extract(shortcode_atts(array(
            'title' => '',
            'address' => '',
            'size' => 200,
            'zoom' => 14,
            'pin_image' => '',
            'type' => 'm',
            'fullscreen' => 'no',
            'el_position' => '',
            'width' => '1/1',
            'el_class' => ''
        ), $atts));
        $output = '';

        if ( $address == '' ) { return null; }

        $el_class = $this->getExtraClass($el_class);
        $width = spb_translateColumnWidthToSpan($width);
		
        $size = str_replace(array( 'px', ' ' ), array( '', '' ), $size);
                
        $img_url = wp_get_attachment_image_src($pin_image, 'full');
		
		if ($fullscreen == "yes" && $width == "span12") {
		$output .= "\n\t".'<div class="spb_gmaps_widget fullscreen-map spb_content_element '.$width.$el_class.'">';	          
        } else {
        $output .= "\n\t".'<div class="spb_gmaps_widget spb_content_element '.$width.$el_class.'">';	          
        }
        $output .= "\n\t\t".'<div class="spb_wrapper">';
        $output .= ($title != '' ) ? "\n\t\t\t".'<h4 class="spb_heading"><span>'.$title.'</span></h4>' : '';
        $output .= '<div class="spb_map_wrapper"><div class="map-canvas" style="width:100%;height:'.$size.'px;" data-address="'.$address.'" data-zoom="'.$zoom.'" data-maptype="'.$type.'" data-pinimage="'.$img_url[0].'"></div></div>';
        $output .= "\n\t\t".'</div> '.$this->endBlockComment('.spb_wrapper');
        $output .= "\n\t".'</div> '.$this->endBlockComment($width);
		if ($fullscreen != "yes") {
        $output = $this->startRow($el_position) . $output . $this->endRow($el_position);
        }
        global $include_maps;
        $include_maps = true;
        
        return $output;
    }
}