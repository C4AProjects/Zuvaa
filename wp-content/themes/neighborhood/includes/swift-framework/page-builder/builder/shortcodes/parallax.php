<?php

class SwiftPageBuilderShortcode_spb_parallax extends SwiftPageBuilderShortcode {

    protected function content( $atts, $content = null ) {

        $title = $pin_image = $el_position = $width = $el_class = '';
        extract(shortcode_atts(array(
            'title' => '',
            'bg_image' => '',
            'bg_type' => '',
            'alt_background' => 'none',
            'el_position' => '',
            'width' => '1/1',
            'el_class' => ''
        ), $atts));
        $output = '';

        $el_class = $this->getExtraClass($el_class);
        $width = spb_translateColumnWidthToSpan($width);
		        
        $img_url = wp_get_attachment_image_src($bg_image, 'full');
		
		$output .= "\n\t".'<div class="spb_parallax_asset sf-parallax spb_content_element bg-type-'.$bg_type.' '.$width.$el_class.' alt-bg '.$alt_background.'" style="background-image: url('.$img_url[0].');">';	          
        $output .= "\n\t\t".'<div class="spb_content_wrapper">';
        $output .= ($title != '' ) ? "\n\t\t\t".'<div class="heading-wrap"><h4 class="spb_heading spb_text_heading"><span>'.$title.'</span></h4></div>' : '';
        $output .= "\n\t\t\t".do_shortcode($content);
        $output .= "\n\t\t".'</div> '.$this->endBlockComment('.spb_content_wrapper');
        $output .= "\n\t".'</div> '.$this->endBlockComment($width);
		
		$output = $this->startRow($el_position) . $output . $this->endRow($el_position);
		
//        global $include_parallax;
//        $include_parallax = true;
        
        return $output;
    }
}

SPBMap::map( 'spb_parallax',  array(
    "name"		=> __("Parallax", 'swift-framework-admin'),
    "base"		=> "spb_parallax",
    "class"		=> "",
	"icon"		=> "spb-icon-parallax",
	"wrapper_class" => "clearfix",
	"controls"	=> "full",
    "params"	=> array(
        array(
            "type" => "textfield",
            "heading" => __("Widget title", 'swift-framework-admin'),
            "param_name" => "title",
            "value" => "",
            "description" => __("Heading text. Leave it empty if not needed.", 'swift-framework-admin')
        ),
        array(
        	"type" => "attach_image",
        	"heading" => __("Background Image", 'swift-framework-admin'),
        	"param_name" => "bg_image",
        	"value" => "",
        	"description" => "Choose an image to use as the background for the parallax area."
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Background Type", 'swift-framework-admin'),
            "param_name" => "bg_type",
            "value" => array(
            			__("Cover", 'swift-framework-admin') => "cover",
            			__("Pattern", 'swift-framework-admin') => "pattern"
            			),
            "description" => __("If you're uploading an image that you want to spread across the whole asset, then choose cover. Else choose pattern for an image you want to repeat.", 'swift-framework-admin')
        ),
        array(
            "type" => "textarea_html",
            "holder" => "div",
            "class" => "",
            "heading" => __("Parallax Content", 'swift-framework-admin'),
            "param_name" => "content",
            "value" => __("<p>This is a parallax text block. Click the edit button to change this text.</p>", 'swift-framework-admin'),
            "description" => __("Enter your content.", 'swift-framework-admin')
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Show alt background", 'swift-framework-admin'),
            "param_name" => "alt_background",
            "value" => array(__("None", 'swift-framework-admin') => "none", __("Alt 1", 'swift-framework-admin') => "alt-one", __("Alt 2", 'swift-framework-admin') => "alt-two", __("Alt 3", 'swift-framework-admin') => "alt-three", __("Alt 4", 'swift-framework-admin') => "alt-four", __("Alt 5", 'swift-framework-admin') => "alt-five", __("Alt 6", 'swift-framework-admin') => "alt-six", __("Alt 7", 'swift-framework-admin') => "alt-seven", __("Alt 8", 'swift-framework-admin') => "alt-eight", __("Alt 9", 'swift-framework-admin') => "alt-nine", __("Alt 10", 'swift-framework-admin') => "alt-ten"),
            "description" => __("Show an alternative background around the asset. These can all be set in Neighborhood Options > Asset Background Options. NOTE: This will only use the text color configuration, as the background is set in this asset.", 'swift-framework-admin')
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