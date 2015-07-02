<?php
	
	/*
	*
	*	Swift Page Builder - Default Config Map
	*	------------------------------------------------
	*	Swift Framework
	* 	Copyright Swift Ideas 2013 - http://www.swiftideas.net
	*
	*/
	
	/* TEXT BLOCK ASSET
	================================================== */ 
	
	SPBMap::map( 'spb_text_block', array(
	    "name"		=> __("Text Block", 'swift-framework-admin'),
	    "base"		=> "spb_text_block",
	    "class"		=> "",
	    "icon"      => "spb-icon-text-block",
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
	    	    "type" => "textfield",
	    	    "heading" => __("Title icon", 'swift-framework-admin'),
	    	    "param_name" => "icon",
	    	    "value" => "",
	    	    "description" => __("Icon to the left of the title text. You can get the code from <a href='http://fortawesome.github.com/Font-Awesome/' target='_blank'>here</a>. E.g. fa-cloud", 'swift-framework-admin')
	    	),
	        array(
	            "type" => "textarea_html",
	            "holder" => "div",
	            "class" => "",
	            "heading" => __("Text", 'swift-framework-admin'),
	            "param_name" => "content",
	            "value" => "",
	            "description" => __("Enter your content.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Margin below widget", 'swift-framework-admin'),
	            "param_name" => "pb_margin_bottom",
	            "value" => array(__('No', 'swift-framework-admin') => "no", __('Yes', 'swift-framework-admin') => "yes"),
	            "description" => __("Add a bottom margin to the widget.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Border below widget", 'swift-framework-admin'),
	            "param_name" => "pb_border_bottom",
	            "value" => array(__('No', 'swift-framework-admin') => "no", __('Yes', 'swift-framework-admin') => "yes"),
	            "description" => __("Add a bottom border to the widget.", 'swift-framework-admin')
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
	
	
	/* BOXED CONTENT ASSET
	================================================== */ 
	
	SPBMap::map( 'boxed_content', array(
	    "name"		=> __("Boxed Content", 'swift-framework-admin'),
	    "base"		=> "boxed_content",
	    "class"		=> "",
	    "icon"      => "spb-icon-boxed-content",
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
	            "type" => "textarea_html",
	            "holder" => "div",
	            "class" => "",
	            "heading" => __("Text", 'swift-framework-admin'),
	            "param_name" => "content",
	            "value" => __("<p>This is a boxed content block. Click the edit button to edit this text.</p>", 'swift-framework-admin'),
	            "description" => __("Enter your content.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Box type", 'swift-framework-admin'),
	            "param_name" => "type",
	            "value" => array(__('Coloured', 'swift-framework-admin') => "coloured", __('White with stroke', 'swift-framework-admin') => "whitestroke"),
	            "description" => __("Choose the surrounding box type for this content", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "textfield",
	            "heading" => __("Custom background colour", 'swift-framework-admin'),
	            "param_name" => "custom_bg_colour",
	            "value" => "",
	            "description" => __("Provide a hex colour code here (include #). If blank, your chosen accent colour will be used.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "textfield",
	            "heading" => __("Custom text colour", 'swift-framework-admin'),
	            "param_name" => "custom_text_colour",
	            "value" => "",
	            "description" => __("Provide a hex colour code here (include #) if you want to override the default (#ffffff).", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Margin below widget", 'swift-framework-admin'),
	            "param_name" => "pb_margin_bottom",
	            "value" => array(__('No', 'swift-framework-admin') => "no", __('Yes', 'swift-framework-admin') => "yes"),
	            "description" => __("Add a bottom margin to the widget.", 'swift-framework-admin')
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
	
	
	/* DIVIDER ASSET
	================================================== */ 
	
	SPBMap::map( 'divider',  array(
	    "name"		=> __("Divider", 'swift-framework-admin'),
	    "base"		=> "divider",
	    "class"		=> "spb_divider spb_controls_top_right",
		'icon'		=> 'spb-icon-divider',
	    "controls"	=> 'edit_popup_delete',
	    "params"	=> array(
	        array(
	            "type" => "dropdown",
	            "heading" => __("Divider type", 'swift-framework-admin'),
	            "param_name" => "type",
	            "value" => array(__('Standard', 'swift-framework-admin') => "standard", __('Thin', 'swift-framework-admin') => "thin", __('Dotted', 'swift-framework-admin') => "dotted", __('Go to top (text)', 'swift-framework-admin') => "go_to_top", __('Go to top (Icon 1)', 'swift-framework-admin') => "go_to_top_icon1", __('Go to top (Icon 2)', 'swift-framework-admin') => "go_to_top_icon2"),
	            "description" => __("Select divider type.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "textfield",
	            "heading" => __("Go to top text", 'swift-framework-admin'),
	            "param_name" => "text",
	            "value" => __("Go to top", 'swift-framework-admin'),
	            "description" => __("The text for the 'Go to top (text)' divider type.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Full width", 'swift-framework-admin'),
	            "param_name" => "full_width",
	            "value" => array(__('No', 'swift-framework-admin') => "no", __('Yes', 'swift-framework-admin') => "yes"),
	            "description" => __("Select yes if you'd like the divider to be full width (only to be used with no sidebars, and with Standard/Thin/Dotted divider types).", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "textfield",
	            "heading" => __("Extra class name", 'swift-framework-admin'),
	            "param_name" => "el_class",
	            "value" => "",
	            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'swift-framework-admin')
	        )
	    ),
	    "js_callback" => array("init" => "spbTextSeparatorInitCallBack")
	) );
	
	
	/* BLANK SPACER ASSET
	================================================== */ 
	
	SPBMap::map( 'blank_spacer',  array(
	    "name"		=> __("Blank Spacer", 'swift-framework-admin'),
	    "base"		=> "blank_spacer",
	    "class"		=> "spb_blank_spacer spb_controls_top_right",
		'icon'		=> 'spb-icon-spacer',
	    "controls"	=> 'edit_popup_delete',
	    "params"	=> array(
	        array(
	            "type" => "textfield",
	            "heading" => __("Height", 'swift-framework-admin'),
	            "param_name" => "height",
	            "value" => __("30px", 'swift-framework-admin'),
	            "description" => __("The height of the spacer, in px (required).", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "textfield",
	            "heading" => __("Spacer ID", 'swift-framework-admin'),
	            "param_name" => "spacer_id",
	            "value" => "",
	            "description" => __("If you wish to add an ID to the spacer, then add it here. You can then use the id to deep link to this section of the page. NOTE: Make sure this is unique to the page!!", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "textfield",
	            "heading" => __("Extra class name", 'swift-framework-admin'),
	            "param_name" => "el_class",
	            "value" => "",
	            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'swift-framework-admin')
	        )
	    ),
	    "js_callback" => array("init" => "spbBlankSpacerInitCallBack")
	) );
	
	
	/* MESSAGE BOX ASSET
	================================================== */ 
	
	SPBMap::map( 'spb_message', array(
	    "name"		=> __("Message Box", 'swift-framework-admin'),
	    "base"		=> "spb_message",
	    "class"		=> "spb_messagebox spb_controls_top_right",
		"icon"		=> "spb-icon-message-box",
	    "wrapper_class" => "alert",
	    "controls"	=> "edit_popup_delete",
	    "params"	=> array(
	        array(
	            "type" => "dropdown",
	            "heading" => __("Message box type", 'swift-framework-admin'),
	            "param_name" => "color",
	            "value" => array(__('Informational', 'swift-framework-admin') => "alert-info", __('Warning', 'swift-framework-admin') => "alert-block", __('Success', 'swift-framework-admin') => "alert-success", __('Error', 'swift-framework-admin') => "alert-error"),
	            "description" => __("Select message type.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "textarea_html",
	            "holder" => "div",
	            "class" => "messagebox_text",
	            "heading" => __("Message text", 'swift-framework-admin'),
	            "param_name" => "content",
	            "value" => __("<p>This is a message box. Click the edit button to edit this text.</p>", 'swift-framework-admin'),
	            "description" => __("Message text.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "textfield",
	            "heading" => __("Extra class name", 'swift-framework-admin'),
	            "param_name" => "el_class",
	            "value" => "",
	            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'swift-framework-admin')
	        )
	    ),
	    "js_callback" => array("init" => "spbMessageInitCallBack")
	) );
	
	
	/* TOGGLE ASSET
	================================================== */ 
	
	SPBMap::map( 'spb_toggle', array(
	    "name"		=> __("Toggle", 'swift-framework-admin'),
	    "base"		=> "spb_toggle",
	    "class"		=> "spb_faq",
		"icon"		=> "spb-icon-toggle",
	    "params"	=> array(
	        array(
	            "type" => "textfield",
	            "holder" => "h4",
	            "class" => "toggle_title",
	            "heading" => __("Toggle title", 'swift-framework-admin'),
	            "param_name" => "title",
	            "value" => __("Toggle title", 'swift-framework-admin'),
	            "description" => __("Toggle block title.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "textarea_html",
	            "holder" => "div",
	            "class" => "toggle_content",
	            "heading" => __("Toggle content", 'swift-framework-admin'),
	            "param_name" => "content",
	            "value" => __("<p>The toggle content goes here, click the edit button to change this text.</p>", 'swift-framework-admin'),
	            "description" => __("Toggle block content.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Default state", 'swift-framework-admin'),
	            "param_name" => "open",
	            "value" => array(__("Closed", 'swift-framework-admin') => "false", __("Open", 'swift-framework-admin') => "true"),
	            "description" => __("Select this if you want toggle to be open by default.", 'swift-framework-admin')
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
	
	
	/* SINGLE IMAGE ASSET
	================================================== */ 
	
	SPBMap::map( 'spb_single_image', array(
		"name"		=> __("Single Image", 'swift-framework-admin'),
		"base"		=> "spb_single_image",
		"class"		=> "spb_single_image_widget",
		"icon"		=> "spb-icon-single-image",
	    "params"	=> array(
			array(
				"type" => "attach_image",
				"heading" => __("Image", 'swift-framework-admin'),
				"param_name" => "image",
				"value" => "",
				"description" => ""
			),
			array(
			    "type" => "dropdown",
			    "heading" => __("Image Size", 'swift-framework-admin'),
			    "param_name" => "image_size",
			    "value" => array(__("Full", 'swift-framework-admin') => "full", __("Large", 'swift-framework-admin') => "large", __("Medium", 'swift-framework-admin') => "medium", __("Thumbnail", 'swift-framework-admin') => "thumbnail"),
			    "description" => __("Select the source size for the image (NOTE: this doesn't affect it's size on the front-end, only the quality).", 'swift-framework-admin')
			),
			array(
			    "type" => "dropdown",
			    "heading" => __("Image Frame", 'swift-framework-admin'),
			    "param_name" => "frame",
			    "value" => array(__("No Frame", 'swift-framework-admin') => "noframe", __("Border Frame", 'swift-framework-admin') => "borderframe", __("Glow Frame", 'swift-framework-admin') => "glowframe", __("Shadow Frame", 'swift-framework-admin') => "shadowframe"),
			    "description" => __("Select a frame for the image.", 'swift-framework-admin')
			),
			array(
			    "type" => "dropdown",
			    "heading" => __("Full width", 'swift-framework-admin'),
			    "param_name" => "full_width",
			    "value" => array(__("No", 'swift-framework-admin') => "no", __("Yes", 'swift-framework-admin') => "yes"),
			    "description" => __("Select if you want the image to be the full width of the page. (Make sure the element width is 1/1 too).", 'swift-framework-admin')
			),
			array(
			    "type" => "dropdown",
			    "heading" => __("Enable lightbox link", 'swift-framework-admin'),
			    "param_name" => "lightbox",
			    "value" => array(__("Yes", 'swift-framework-admin') => "yes", __("No", 'swift-framework-admin') => "no"),
			    "description" => __("Select if you want the image to open in a lightbox on click", 'swift-framework-admin')
			),
			array(
			    "type" => "textfield",
			    "heading" => __("Add link to image", 'swift-framework-admin'),
			    "param_name" => "image_link",
			    "value" => "",
			    "description" => __("If you would like the image to link to a URL, then enter it here. NOTE: this will override the lightbox functionality if you have enabled it.", 'swift-framework-admin')
			),
			array(
			    "type" => "dropdown",
			    "heading" => __("Link opens in new window?", 'swift-framework-admin'),
			    "param_name" => "link_target",
			    "value" => array(__("Self", 'swift-framework-admin') => "_self", __("New Window", 'swift-framework-admin') => "_blank"),
			    "description" => __("Select if you want the link to open in a new window", 'swift-framework-admin')
			),
			array(
			    "type" => "textfield",
			    "heading" => __("Image Caption", 'swift-framework-admin'),
			    "param_name" => "caption",
			    "value" => "",
			    "description" => __("If you would like a caption to be shown below the image, add it here.", 'swift-framework-admin')
			)
	    )
	));
	
	
	/* TABS ASSET
	================================================== */ 
	
	SPBMap::map( 'spb_tabs', array(
	    "name"		=> __("Tabs", 'swift-framework-admin'),
	    "base"		=> "spb_tabs",
	    "controls"	=> "full",
	    "class"		=> "spb_tabs",
		"icon"		=> "spb-icon-tabs",
	    "params"	=> array(
	        array(
	            "type" => "textfield",
	            "heading" => __("Widget title", 'swift-framework-admin'),
	            "param_name" => "tab_asset_title",
	            "value" => "",
	            "description" => __("What text use as widget title. Leave blank if no title is needed.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "textfield",
	            "heading" => __("Extra class name", 'swift-framework-admin'),
	            "param_name" => "el_class",
	            "value" => "",
	            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'swift-framework-admin')
	        )
	    ),
	    "custom_markup" => '
		<div class="tab_controls">
			<button class="add_tab">'.__("Add New Tab", 'swift-framework-admin').'</button>
	</div>
	
		<div class="spb_tabs_holder">
			%content%
		</div>',
	    'default_content' => '
		<ul>
			<li><a href="#tab-1"><span>'.__('Tab 1', 'swift-framework-admin').'</span></a><a class="edit_tab"></a><a class="delete_tab"></a></li>
			<li><a href="#tab-2"><span>'.__('Tab 2', 'swift-framework-admin').'</span></a><a class="edit_tab"></a><a class="delete_tab"></a></li>
		</ul>
	
		<div id="tab-1" class="row-fluid spb_column_container spb_sortable_container not-column-inherit">
			[spb_text_block width="1/1"] '.__('This is a text block. Click the edit button to change this text.', 'swift-framework-admin').' [/spb_text_block]
		</div>
	
		<div id="tab-2" class="row-fluid spb_column_container spb_sortable_container not-column-inherit">
			[spb_text_block width="1/1"] '.__('This is a text block. Click the edit button to change this text.', 'swift-framework-admin').' [/spb_text_block]
		</div>',
	    "js_callback" => array("init" => "spbTabsInitCallBack", "shortcode" => "spbTabsGenerateShortcodeCallBack")
	    //"js_callback" => array("init" => "spbTabsInitCallBack", "edit" => "spbTabsEditCallBack", "save" => "spbTabsSaveCallBack", "shortcode" => "spbTabsGenerateShortcodeCallBack")
	) );
	
	
	/* TOUR ASSET
	================================================== */ 
	 
	SPBMap::map( 'spb_tour', array(
	    "name"		=> __("Tour Section", 'swift-framework-admin'),
	    "base"		=> "spb_tour",
	    "controls"	=> "full",
	    "class"		=> "spb_tour",
		"icon"		=> "spb-icon-tour",
	    "wrapper_class" => "clearfix",
	    "params"	=> array(
	        array(
	            "type" => "textfield",
	            "heading" => __("Widget title", 'swift-framework-admin'),
	            "param_name" => "title",
	            "value" => "",
	            "description" => __("What text use as widget title. Leave blank if no title is needed.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Auto rotate slides", 'swift-framework-admin'),
	            "param_name" => "interval",
	            "value" => array(0, 3, 5, 10, 15),
	            "description" => __("Auto rotate slides each X seconds. Select 0 to disable.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "textfield",
	            "heading" => __("Extra class name", 'swift-framework-admin'),
	            "param_name" => "el_class",
	            "value" => "",
	            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'swift-framework-admin')
	        )
	    ),
	    "custom_markup" => '
		<div class="tab_controls">
			<button class="add_tab">'.__("Add slide", 'swift-framework-admin').'</button>
		</div>
	
		<div class="spb_tabs_holder clearfix">
			%content%
		</div>',
	    'default_content' => '
		<ul>
			<li><a href="#tab-1"><span>'.__('Slide 1', 'swift-framework-admin').'</span></a><a class="delete_tab"></a><a class="edit_tab"></a></li>
			<li><a href="#tab-2"><span>'.__('Slide 2', 'swift-framework-admin').'</span></a><a class="delete_tab"></a><a class="edit_tab"></a></li>
		</ul>
	
		<div id="tab-1" class="row-fluid spb_column_container spb_sortable_container not-column-inherit">
			[spb_text_block width="1/1"] '.__('This is a text block. Click the edit button to change this text.', 'swift-framework-admin').' [/spb_text_block]
		</div>
	
		<div id="tab-2" class="row-fluid spb_column_container spb_sortable_container not-column-inherit">
			[spb_text_block width="1/1"] '.__('This is a text block. Click the edit button to change this text.', 'swift-framework-admin').' [/spb_text_block]
		</div>',
	    "js_callback" => array("init" => "spbTabsInitCallBack", "shortcode" => "spbTabsGenerateShortcodeCallBack")
	) );
	
	
	/* ACCORDION ASSET
	================================================== */ 
	
	SPBMap::map( 'spb_accordion', array(
	    "name"		=> __("Accordion", 'swift-framework-admin'),
	    "base"		=> "spb_accordion",
	    "controls"	=> "full",
	    "class"		=> "spb_accordion",
		"icon"		=> "spb-icon-accordion",
	    "params"	=> array(
	        array(
	            "type" => "textfield",
	            "heading" => __("Widget title", 'swift-framework-admin'),
	            "param_name" => "widget_title",
	            "value" => "",
	            "description" => __("What text use as widget title. Leave blank if no title is needed.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "textfield",
	            "heading" => __("Active Section", 'swift-framework-admin'),
	            "param_name" => "active_section",
	            "value" => "",
	            "description" => __("You can set the section that is active here by entering the number of the section here. NOTE: The first section would be 0, second would be 1, and so on. Leave blank for all sections to be closed by default.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "textfield",
	            "heading" => __("Extra class name", 'swift-framework-admin'),
	            "param_name" => "el_class",
	            "value" => "",
	            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'swift-framework-admin')
	        )
	    ),
	    "custom_markup" => '
		<div class="tab_controls">
			<button class="add_tab">'.__("Add section", 'swift-framework-admin').'</button>
		</div>
	
		<div class="spb_accordion_holder clearfix">
			%content%
		</div>',
	    'default_content' => '
		<div class="group">
			<h3><a class="title-text" href="#">'.__('Section 1', 'swift-framework-admin').'</a><a class="delete_tab"></a><a class="edit_tab"></a></h3>
			<div>
				<div class="row-fluid spb_column_container spb_sortable_container not-column-inherit">
					[spb_text_block width="1/1"] '.__('This is a text block. Click the edit button to change this text.', 'swift-framework-admin').' [/spb_text_block]
				</div>
			</div>
		</div>
		<div class="group">
			<h3><a class="title-text" href="#">'.__('Section 2', 'swift-framework-admin').'</a><a class="delete_tab"></a><a class="edit_tab"></a></h3>
			<div>
				<div class="row-fluid spb_column_container spb_sortable_container not-column-inherit">
					[spb_text_block width="1/1"] '.__('This is a text block. Click the edit button to change this text.', 'swift-framework-admin').' [/spb_text_block]
				</div>
			</div>
		</div>',
	    "js_callback" => array("init" => "spbAccordionInitCallBack", "shortcode" => "spbAccordionGenerateShortcodeCallBack")
	) );
	
	
	/* IMPACT TEXT ASSET
	================================================== */ 
	
	$colors_arr = array(__("Accent", 'swift-framework-admin') => "accent", __("Blue", 'swift-framework-admin') => "blue", __("Grey", 'swift-framework-admin') => "grey", __("Light grey", 'swift-framework-admin') => "lightgrey", __("Purple", 'swift-framework-admin') => "purple", __("Light Blue", 'swift-framework-admin') => "lightblue", __("Green", 'swift-framework-admin') => "green", __("Lime Green", 'swift-framework-admin') => "limegreen", __("Turquoise", 'swift-framework-admin') => "turquoise", __("Pink", 'swift-framework-admin') => "pink", __("Orange", 'swift-framework-admin') => "orange");
	
	$size_arr = array(__("Normal", 'swift-framework-admin') => "normal", __("Large", 'swift-framework-admin') => "large");
	
	$type_arr = array(__("Standard", 'swift-framework-admin') => "standard", __("Square with arrow", 'swift-framework-admin') => "squarearrow", __("Slightly rounded", 'swift-framework-admin') => "slightlyrounded", __("Slightly rounded with arrow", 'swift-framework-admin') => "slightlyroundedarrow", __("Rounded", 'swift-framework-admin') => "rounded", __("Rounded with arrow", 'swift-framework-admin') => "roundedarrow", __("Outer glow effect", 'swift-framework-admin') => "outerglow", __("Drop shadow effect", 'swift-framework-admin') => "dropshadow");
	
	
	$target_arr = array(__("Same window", 'swift-framework-admin') => "_self", __("New window", 'swift-framework-admin') => "_blank");
	
	SPBMap::map( 'impact_text', array(
	    "name"		=> __("Impact Text + Button", 'swift-framework-admin'),
	    "base"		=> "impact_text",
	    "class"		=> "button_grey",
		"icon"		=> "spb-icon-impact-text",
	    "controls"	=> "edit_popup_delete",
	    "params"	=> array(
	    	array(
	    	    "type" => "dropdown",
	    	    "heading" => __("Include button", 'swift-framework-admin'),
	    	    "param_name" => "include_button",
	    	    "value" => array(__("Yes", 'swift-framework-admin') => "yes", __("No", 'swift-framework-admin') => "no"),
	    	    "description" => __("Include a button in the asset.", 'swift-framework-admin')
	    	),
	    	array(
	    	    "type" => "dropdown",
	    	    "heading" => __("Button Style", 'swift-framework-admin'),
	    	    "param_name" => "button_style",
	    	    "value" => array(__("Standard", 'swift-framework-admin') => "standard", __("Arrow", 'swift-framework-admin') => "arrow"),
	    	),
	        array(
	            "type" => "textfield",
	            "heading" => __("Text on the button", 'swift-framework-admin'),
	            "param_name" => "title",
	            "value" => __("Text on the button", 'swift-framework-admin'),
	            "description" => __("Text on the button.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "textfield",
	            "heading" => __("URL (Link)", 'swift-framework-admin'),
	            "param_name" => "href",
	            "value" => "",
	            "description" => __("Button link.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Color", 'swift-framework-admin'),
	            "param_name" => "color",
	            "value" => $colors_arr,
	            "description" => __("Button color.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Size", 'swift-framework-admin'),
	            "param_name" => "size",
	            "value" => $size_arr,
	            "description" => __("Button size.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Type", 'swift-framework-admin'),
	            "param_name" => "type",
	            "value" => $type_arr
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Target", 'swift-framework-admin'),
	            "param_name" => "target",
	            "value" => $target_arr
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Button position", 'swift-framework-admin'),
	            "param_name" => "position",
	            "value" => array(__("Align right", 'swift-framework-admin') => "cta_align_right", __("Align left", 'swift-framework-admin') => "cta_align_left", __("Align bottom", 'swift-framework-admin') => "cta_align_bottom"),
	            "description" => __("Select button alignment.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "textarea_html",
	            "holder" => "div",
	            "class" => "",
	            "heading" => __("Text", 'swift-framework-admin'),
	            "param_name" => "content",
	            "value" => __("click the edit button to change this text.", 'swift-framework-admin'),
	            "description" => __("Enter your content.", 'swift-framework-admin')
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
	    ),
	    "js_callback" => array("init" => "spbCallToActionInitCallBack", "save" => "spbCallToActionSaveCallBack")
	) );
	
	
	/* VIDEO ASSET
	================================================== */ 
	
	SPBMap::map( 'spb_video', array(
	    "name"		=> __("Video Player", 'swift-framework-admin'),
	    "base"		=> "spb_video",
	    "class"		=> "",
		"icon"		=> "spb-icon-film-youtube",
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
	            "heading" => __("Video link", 'swift-framework-admin'),
	            "param_name" => "link",
	            "value" => "",
	            "description" => __('Link to the video. More about supported formats at <a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">WordPress codex page</a>.', 'swift-framework-admin')
	        ),
	        array(
	            "type" => "textfield",
	            "heading" => __("Video size", 'swift-framework-admin'),
	            "param_name" => "size",
	            "value" => "",
	            "description" => __('Enter video size in pixels. Example: 200x100 (Width x Height).', 'swift-framework-admin')
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Full width", 'swift-framework-admin'),
	            "param_name" => "full_width",
	            "value" => array(__('No', 'swift-framework-admin') => "no", __('Yes', 'swift-framework-admin') => "yes"),
	            "description" => __("Select this if you want the video to be the full width of the page container (leave the above size blank).", 'swift-framework-admin')
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
	
	
	/* GOOGLE MAPS ASSET
	================================================== */ 
	
	SPBMap::map( 'spb_gmaps',  array(
	    "name"		=> __("Google Map", 'swift-framework-admin'),
	    "base"		=> "spb_gmaps",
	    "class"		=> "",
		"icon"		=> "spb-icon-map-pin",
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
	            "heading" => __("Address", 'swift-framework-admin'),
	            "param_name" => "address",
	            "value" => "",
	            "description" => __('Enter the address that you would like to show on the map here, i.e. "Cupertino".', 'swift-framework-admin')
	        ),
	        array(
	            "type" => "textfield",
	            "heading" => __("Map height", 'swift-framework-admin'),
	            "param_name" => "size",
	            "value" => "300",
	            "description" => __('Enter map height in pixels. Example: 300).', 'swift-framework-admin')
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Map type", 'swift-framework-admin'),
	            "param_name" => "type",
	            "value" => array(__("Map", 'swift-framework-admin') => "ROADMAP", __("Satellite", 'swift-framework-admin') => "SATELLITE", __("Hybrid", 'swift-framework-admin') => "HYBRID", __("Terrain", 'swift-framework-admin') => "TERRAIN"),
	            "description" => __("Select button alignment.", 'swift-framework-admin')
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Map Zoom", 'swift-framework-admin'),
	            "param_name" => "zoom",
	            "value" => array(__("14 - Default", 'swift-framework-admin') => 14, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 15, 16, 17, 18, 19, 20)
	        ),
	        array(
	        	"type" => "attach_image",
	        	"heading" => __("Custom Map Pin", 'swift-framework-admin'),
	        	"param_name" => "pin_image",
	        	"value" => "",
	        	"description" => "Choose an image to use as the custom pin for the address on the map. Upload your custom map pin, the image size must be 150px x 75px."
	        ),
	        array(
	            "type" => "dropdown",
	            "heading" => __("Fullscreen Display", 'swift-framework-admin'),
	            "param_name" => "fullscreen",
	            "value" => array(__("No", 'swift-framework-admin') => "no", __("Yes", 'swift-framework-admin') => "yes"),
	            "description" => __("If yes, the map will be displayed from screen edge to edge.", 'swift-framework-admin')
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
	
	
	/* RAW HTML ASSET
	================================================== */ 
	
	SPBMap::map( 'spb_raw_html', array(
		"name"		=> __("Raw HTML", 'swift-framework-admin'),
		"base"		=> "spb_raw_html",
		"class"		=> "div",
		"icon"      => "spb-icon-raw-html",
		"wrapper_class" => "clearfix",
		"controls"	=> "full",
		"params"	=> array(
			array(
				"type" => "textarea_raw_html",
				"holder" => "div",
				"class" => "",
				"heading" => __("Raw HTML", 'swift-framework-admin'),
				"param_name" => "content",
				"value" => base64_encode("<p>This is a raw html block.<br/>Click the edit button to change this html.</p>"),
				"description" => __("Enter your HTML content.", 'swift-framework-admin')
			),
		)
	) );
	
	
	/* RAW JS ASSET
	================================================== */ 
	
	SPBMap::map( 'spb_raw_js', array(
		"name"		=> __("Raw JS", 'swift-framework-admin'),
		"base"		=> "spb_raw_js",
		"class"		=> "div",
		"icon"      => "spb-icon-raw-javascript",
		"wrapper_class" => "clearfix",
		"controls"	=> "full",
		"params"	=> array(
			array(
				"type" => "textarea_raw_html",
				"holder" => "div",
				"class" => "",
				"heading" => __("Raw js", 'swift-framework-admin'),
				"param_name" => "content",
				"value" => __(base64_encode("alert('Enter your js here!');"), 'swift-framework-admin'),
				"description" => __("Enter your Js.", 'swift-framework-admin')
			),
		)
	) );
	
	
	/* LAYOUT CONFIG
	================================================== */ 

	SPBMap::layout(array('id'=>'column_12', 'title'=>'1/2'));
	SPBMap::layout(array('id'=>'column_12-12', 'title'=>'1/2 + 1/2'));
	SPBMap::layout(array('id'=>'column_13', 'title'=>'1/3'));
	SPBMap::layout(array('id'=>'column_13-13-13', 'title'=>'1/3 + 1/3 + 1/3'));
	SPBMap::layout(array('id'=>'column_13-23', 'title'=>'1/3 + 2/3'));
	SPBMap::layout(array('id'=>'column_23-13', 'title'=>'2/3 + 1/3'));
	SPBMap::layout(array('id'=>'column_14', 'title'=>'1/4'));
	SPBMap::layout(array('id'=>'column_12-14-14', 'title'=>'1/2 + 1/4 + 1/4'));
	SPBMap::layout(array('id'=>'column_14-12-14', 'title'=>'1/4 + 1/2 + 1/4'));
	SPBMap::layout(array('id'=>'column_14-14-12', 'title'=>'1/4 + 1/4 + 1/2'));
	SPBMap::layout(array('id'=>'column_14-14-14-14', 'title'=>'1/4 + 1/4 + 1/4 + 1/4'));
	SPBMap::layout(array('id'=>'column_11', 'title'=>'1/1'));
	
?>