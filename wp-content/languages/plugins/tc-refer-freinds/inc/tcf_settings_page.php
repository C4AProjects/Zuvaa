<?PHP

/*-----------------------------------------------------------------------------------*/
/*	Menu Creation
/*-----------------------------------------------------------------------------------*/

function tcraf_create_menu() {
		
	$page = add_submenu_page( 'woocommerce', __("Refer A Friend for WooCommerce", "tcraf_locale"), __("Refer A Friend", "tcraf_locale"), 'manage_options', 'tc-refer-friends/inc/tcf_settings.php', 'tcraf_settings_page' );

	// call register settings function
	add_action( 'admin_init', 'tcraf_register_settings' );
	
	// Hook style sheet loading
	add_action( 'admin_print_styles-' . $page, 'tcraf_admin_cssloader' );

}
		
/*-----------------------------------------------------------------------------------*/
/*	Add Admin CSS
/*-----------------------------------------------------------------------------------*/

// Add style sheet for plugin settings
function tcraf_settings_admin_css(){
				
	/* Register our stylesheet. */
	wp_register_style( 'tcrafSettings', TCRAF_LOCATION.'/css/tc_framework.css' );
							
} function tcraf_admin_cssloader(){
	
	// It will be called only on your plugin admin page, enqueue our stylesheet here
	wp_enqueue_style( 'tcrafSettings' );
	   
} // End admin style CSS

/*-----------------------------------------------------------------------------------*/
/*	Define Settings
/*-----------------------------------------------------------------------------------*/

global $tcraf_settings;

$tcraf_settings = array(
	// General & WooCommerce
	'enabled' 				=> 'true',
	'buyer-enabled'			=> 'true',
	'jquery-enabled'		=> 'true',
	'buyer-discount'		=> '',
	'minimum-purchase'		=> '0',
	'title'					=> 'Save On Your Purchase!',
	'message'				=> 'Enter your email right quick and we will give you a nice 20% discount off of your total! No spam, we promise.',
	'discount-string'		=> '',
	'cart-message'			=> 'Your Refer A Friend discount has been applied to your cart total. Refer your own friends to get awesome discounts sent to your email!',
	// User Discount
	'referral'				=> 'true',
	'referral-amount'		=> '5',
	'referral-type'			=> 'fixed_cart',
	'referral-title'		=> 'Your WooCommerce Referral Coupon',
	'referral-msg'			=> 'For referring a friend to our shop you have earned yourself $5 off your next purchase!',
	// Share Buttons
	'social-enabled'		=> 'true',
	'social-url'			=> get_site_url(),
	'social-message'		=> 'Share your URL on your favorite social services and start referring your friends right away!',
	'social-fb-title'		=> 'Get Hot Discounts from Shop Name',
	'social-fb-text'		=> 'Use this special link to get 20% OFF your next purchase at Shop Name!',
	'social-fb-image'		=> '',
	'social-tweet-text'		=> 'Get an awesome discount at Shop Title Here using this link!'
);

/*-----------------------------------------------------------------------------------*/
/*	Register Settings
/*-----------------------------------------------------------------------------------*/

function tcraf_register_settings(){
	global $tcraf_settings;
	$prefix = 'tc-raf';
	foreach($tcraf_settings as $setting => $value){
		// Define
		$thisSetting = $prefix.'-'.$setting;
		// Register setting
		register_setting( $prefix.'-settings-group', $thisSetting );
		// Apply default
		add_option( $thisSetting, $value );
	}
}

/*-----------------------------------------------------------------------------------*/
/*	Get Settings
/*-----------------------------------------------------------------------------------*/

function tcraf_get_settings(){
	// Get Settings	
	global $tcraf_settings;
	$prefix = 'tc-raf';
	$new_settings = array();
	foreach($tcraf_settings as $setting => $default){
		// Define
		$thisSetting = $prefix.'-'.$setting;
		$value = get_option( $thisSetting );
		if( !isset($value) ) : $value = ''; endif;
		$new_settings[$setting] = $value;
	}
	return $new_settings;
}

global $tcraf_options;
$tcraf_options = tcraf_get_settings();

/*-----------------------------------------------------------------------------------*/
/*	Ajax save callback
/*-----------------------------------------------------------------------------------*/

add_action('wp_ajax_tcraf_tc_settings_save', 'tcraf_tc_settings_save');

function tcraf_tc_settings_save(){

	check_ajax_referer('tcraf_settings_secure', 'security');
	
	// Setup
	global $tcraf_settings;
	$prefix = 'tc-raf';

	// Loop through settings
	foreach($tcraf_settings as $setting => $value){
		
		// Define
		$thisSetting = $prefix.'-'.$setting;
					
		// Register setting
		if( isset( $_POST[$thisSetting] ) ){
			update_option( $thisSetting, $_POST[$thisSetting] );
		}
		
	} // end for each
		
}

/*-----------------------------------------------------------------------------------*/
/*	New framework settings page
/*-----------------------------------------------------------------------------------*/

function tcraf_settings_page() {
	
?>

<script>
	
jQuery(document).ready(function(){

/*-----------------------------------------------------------------------------------*/
/*	Options Pages and Tabs
/*-----------------------------------------------------------------------------------*/
	  
	jQuery('.options_pages li').click(function(){
		
		var tab_page = 'div#' + jQuery(this).attr('id');
		var old_page = 'div#' + jQuery('.options_pages li.active').attr('id');
		
		// Change button class
		jQuery('.options_pages li.active').removeClass('active');
		jQuery(this).addClass('active');
				
		// Set active tab page
		jQuery(old_page).fadeOut('slow', function(){
			
			jQuery(tab_page).fadeIn('slow');
			
		});
		
	});
	
/*-----------------------------------------------------------------------------------*/
/*	Form Submit
/*-----------------------------------------------------------------------------------*/
	
	jQuery('form#plugin-options').submit(function(){		
		
		// Update MCE
		tinyMCE.triggerSave();
			
		var data = jQuery(this).serialize();
		
		jQuery.post(ajaxurl, data, function(response){
			
			if(response == 0){
				
				// Flash success message and shadow
				var success = jQuery('#success-save');
				var bg = jQuery("#message-bg");
				success.css("position","absolute");
				bg.css({"height": jQuery(window).height()});
				bg.css({"opacity": .45});
				bg.fadeIn('slow');
				success.fadeIn('slow');
				window.setTimeout(function(){success.fadeOut(); bg.fadeOut();}, 2000);
								
			} else {
				
				//error out
				
			}
		
		});
				  
		return false;
	
	});
	
/*-----------------------------------------------------------------------------------*/
/*	Popup Center Handles
/*-----------------------------------------------------------------------------------*/
	
	// Center Function
	jQuery.fn.center = function(parent){
		this.animate({"top":( jQuery(window).height() - this.height() - 65 ) / 2+jQuery(window).scrollTop() + "px"},100);
		//this.css({"left": (((jQuery(this).parent().width() - this.outerWidth()) / 2) + jQuery(this).parent().scrollLeft() + "px")});
		this.css({"left":"250px"});
		return this;
	}
	
	// Center onLoad and Scroll
	jQuery('#success-save').center();
	jQuery(window).scroll(function(){ 
		jQuery('#success-save').center();
	});
	
/*-----------------------------------------------------------------------------------*/
/*	Image Uploaders
/*-----------------------------------------------------------------------------------*/

	// Uploading files
	var file_frame;
	
	jQuery('.tcf-image-upload').live('click', function(event){
	
		// Prevent Defaults
		event.preventDefault();
		
		// Setup Vars
		var thisField = jQuery(this);
		
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
		  file_frame.open();
		  return;
		}
		
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
		  title: jQuery( this ).data( 'uploader_title' ),
		  button: {
			text: jQuery( this ).data( 'uploader_button_text' ),
		  },
		  multiple: false  // Set to true to allow multiple files to be selected
		});
		
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
		  // We set multiple to false so only get one image from the uploader
		  attachment = file_frame.state().get('selection').first().toJSON();
		  // Set Select Box to Image URL
		  thisField.attr('value', attachment.url);
		  // Do something with attachment.id and/or attachment.url here
		});
		
		// Finally, open the modal
		file_frame.open();
	
	});
	
	jQuery('.tcf-image-remove').on('click', function(event){
		event.preventDefault();
		var thisField = jQuery(this).attr('remove-id');
		var previewImage = thisField+'-preview';
		jQuery('#'+thisField).attr('value', '');
		jQuery('#'+previewImage).fadeOut();
	});
	
/*-----------------------------------------------------------------------------------*/
/*	Finished
/*-----------------------------------------------------------------------------------*/
	
});

</script>

<div class="wrap">

    <div id="icon-tc-raf" class="icon32"><br/></div>
    <h2 class="tc-heading"><?PHP _e('Refer A Friend for WooCommerce', 'tcraf_locale') ?> <span id="version">V<?PHP echo TCRAF_VERSION; ?></span> <a href="<?PHP echo TCRAF_LOCATION; ?>/documentation" target="_blank">&raquo; <?PHP _e('View Plugin Documentation', 'tcraf_locale') ?></a></h2>

</div>

<div id="message-bg"></div>
<div id="success-save"></div>

<div id="tc_framework_wrap">

    <div id="content_wrap">
    
    	<form id="plugin-options" name="plugin-options" action="/">
        <?php settings_fields( 'tc-raf-settings-group' ); ?>
        <input type="hidden" name="action" value="tcraf_tc_settings_save" />
        <input type="hidden" name="security" value="<?php echo wp_create_nonce('tcraf_settings_secure'); ?>" />
        <!-- Checkbox Fall Backs -->
        <input type="hidden" name="tc-raf-jquery-enabled" id="tc-raf-jquery-enabled" value="false" />
        <input type="hidden" name="tc-raf-social-enabled" id="tc-raf-social-enabled" value="false" />
        <input type="hidden" name="tc-raf-double-optin" id="tc-raf-double-optin" value="false" />
        <input type="hidden" name="tc-raf-first-name" id="tc-raf-first-name" value="false" />
        <input type="hidden" name="tc-raf-last-name" id="tc-raf-last-name" value="false" />
        <input type="hidden" name="tc-raf-discount-string" id="tc-raf-discount-string" value="<?PHP echo get_option('tc-raf-discount-string'); ?>" />
        
        	<div id="sub_header" class="info">
            
                <input type="submit" name="settingsBtn" id="settingsBtn" class="button-framework save-options" value="<?php _e('Save All Changes', 'tcraf_locale') ?>" />
                <span><?PHP _e('Options Page', 'tcraf_locale') ?></span>
                
            </div>
            
            <div id="content">
            
            	<div id="options_content">
                
                	<ul class="options_pages">
                    	<li id="general_options" class="active"><a href="#"><?php _e('General Settings', 'tcraf_locale') ?></a><span></span></li>
                    	<li id="referral_options"><a href="#"><?php _e('Referral Settings', 'tcraf_locale') ?></a><span></span></li>
                    	<li id="social_options"><a href="#"><?php _e('Social Button Settings', 'tcraf_locale') ?></a><span></span></li>
                    </ul>
                    
                    <div id="general_options" class="options_page">
                    
                    	<?PHP if( tcraf_check_pages() == 'false' ){ ?>

                    	<div class="option">
                        	<h3><?php _e('No "Refer A Friend" Page Found!', 'tcraf_locale') ?></h3>
                            <div class="section">
                                <div class="description full">
									<?php _e('Currently you do not have a Refer A Friend page setup for your users. Without one they cannot view their link IDs or see their stats and coupons.', 'tcraf_locale') ?>
                                    <p><?php _e('Create a page with your choice of title and enter [tcraf-refer-page] for the page content. You can direct your users to this page to start referring sales.', 'tcraf_locale') ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <?PHP } // end if ?>  

                    	<div class="option">
                        	<h3><?php _e('Enable Refer A Friend', 'tcraf_locale') ?></h3>
                            <div class="section">
                            	<div class="element">
                                    <select name="tc-raf-enabled" id="tc-raf-enabled" class="textfield">
                                        <option value="true" <?PHP if(get_option('tc-raf-enabled') == 'true'){echo 'selected="selected"';} ?>><?php _e('Enabled', 'tcraf_locale') ?></option>
                                        <option value="false" <?PHP if(get_option('tc-raf-enabled') == 'false'){echo 'selected="selected"';} ?>><?php _e('Disabled', 'tcraf_locale') ?></option>
                                    </select>                                    
                                </div>
                                <div class="description"><?php _e('Disable / Enable Refer A Friend quickly and easily.', 'tcraf_locale') ?></div>
                            </div>
                        </div>  
                                                                                                
                    	<div class="option">
                        	<h3><?php _e('Buyer Discount Setup', 'tcraf_locale') ?></h3>
                            <div class="section">
                            	<div class="element">
                                    <select name="tc-raf-buyer-enabled" id="tc-raf-buyer-enabled" class="textfield">
                                        <option value="true" <?PHP if(get_option('tc-raf-buyer-enabled') == 'true'){echo 'selected="selected"';} ?>><?php _e('Enabled', 'tcraf_locale') ?></option>
                                        <option value="false" <?PHP if(get_option('tc-raf-buyer-enabled') == 'false'){echo 'selected="selected"';} ?>><?php _e('Disabled', 'tcraf_locale') ?></option>
                                    </select>                                    
                                    <p><?PHP _e('Coupon Code', 'tcraf_locale'); ?></p>
                                    <?PHP
                                    global $wpdb;
                                    $discount_codes = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = 'shop_coupon' ORDER BY id DESC");
                                    $count = count($discount_codes);
                                    if( $count > 0 ){
                                        echo'<select class="textfield" name="tc-raf-buyer-discount" type="text" id="tc-raf-buyer-discount">';
                                        foreach( $discount_codes as $discount ){
                                            $selected = '';
                                            if(get_option('tc-raf-buyer-discount') == $discount->post_title){$selected = "selected=selected";}
                                            echo '<option value="'.$discount->post_title.'" '.$selected.'>'.$discount->post_title.'</option>';
                                            
                                        }
                                        echo '</select>';
                                    } else {
                                        echo '<p>'._e('You have no WooCommerce Coupon Codes! Create one!', 'tcraf_locale').'</p>';
                                    }
                                    ?>								
                                </div>
                                <div class="description"><?php _e('Select the WooCommerce coupon you want to apply to the shopping cart when a user makes a purchase after being referred.', 'tcraf_locale') ?>.</div>
                            </div>
                        </div>
                        
                        
                    	<div class="option">
                        	<h3><?php _e('Cart / Success Message', 'tcraf_locale') ?></h3>
                            <div class="editor-description"><?php _e('Choose the message that will be displayed on the cart / checkout pages when a buyer has been given the "Refer a Friend" discount.', 'tcraf_locale') ?>.</div><br />
                            <div class="section">
                            	<div class="editor-element">
                                    <?PHP wp_editor( stripslashes(get_option('tc-raf-cart-message')), 'tc-raf-cart-message-pro', array( 'textarea_name' => 'tc-raf-cart-message', 'media_buttons' => true, 'tinymce' => array( 'theme_advanced_buttons1' => 'formatselect,forecolor,|,bold,italic,underline,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,|,spellchecker,wp_fullscreen,wp_adv' ) ) ); ?>
                                </div>
                            </div>
                        </div>
                                                
                    </div>
                    
                    
                    <div id="referral_options" class="options_page hide">
                    
                    	<div class="option">
                        	<h3><?PHP _e('Enable Referral Coupon', 'tcraf_locale'); ?></h3>
                            <div class="section">
                            	<div class="element"><select name="tc-raf-referral" id="tc-raf-referral" class="textfield">
                <option value="true" <?PHP if(get_option('tc-raf-referral') == 'true'){echo 'selected="selected"';} ?>><?php _e('Enabled', 'tcraf_locale') ?></option>
                <option value="false" <?PHP if(get_option('tc-raf-referral') == 'false'){echo 'selected="selected"';} ?>><?php _e('Disabled', 'tcraf_locale') ?></option>
                				</select></div>
                                <div class="description"><?php _e('Enable an optional one time use coupon that will be sent to the user who refers a friend.', 'tcraf_locale') ?></div>
                            </div>
                        </div>
                                                
                    	<div class="option">
                        	<h3><?PHP _e('Minimum Purchase Amount', 'tcraf_locale'); ?></h3>
                            <div class="section">
                            	<div class="element"><input class="textfield" name="tc-raf-minimum-purchase" type="text" id="tc-raf-minimum-purchase" value="<?php echo get_option('tc-raf-minimum-purchase'); ?>" /></div>
                                <div class="description"><?PHP _e('Enter a minimum purchase amount for referrals. If this cart total is not met the referral coupon will not be credited to the referring user. Enter 0 to disable minimum cart requirement.', 'tcraf_locale'); ?></div>
                            </div>
                        </div>

                    	<div class="option">
                        	<h3><?PHP _e('Referral Coupon Type', 'tcraf_locale'); ?></h3>
                            <div class="section">
                            	<div class="element">
                                <select name="tc-raf-referral-type" id="tc-raf-referral-type" class="textfield">
                <option value="percent" <?PHP if(get_option('tc-raf-referral-type') == 'percent'){echo 'selected="selected"';} ?>><?php _e('Percentage', 'tcraf_locale') ?></option>
                <option value="fixed_cart" <?PHP if(get_option('tc-raf-referral-type') == 'fixed_cart'){echo 'selected="selected"';} ?>><?php _e('Flat Rate', 'tcraf_locale') ?></option>
                				</select>
                                </div>
                                <div class="description"><?php _e('Choose the type of coupon you want to use.', 'tcraf_locale') ?></div>
                            </div>
                        </div>
                        
                    	<div class="option">
                        	<h3><?PHP _e('Coupon Amount', 'tcraf_locale'); ?></h3>
                            <div class="section">
                            	<div class="element"><input class="textfield" name="tc-raf-referral-amount" type="text" id="tc-raf-referral-amount" value="<?php echo get_option('tc-raf-referral-amount'); ?>" /></div>
                                <div class="description"><?PHP _e('Enter the amount of the discount.', 'tcraf_locale'); ?></div>
                            </div>
                        </div>
                        
                    	<div class="option">
                        	<h3><?PHP _e('Coupon Email Subject', 'tcraf_locale'); ?></h3>
                            <div class="section">
                            	<div class="element"><input class="textfield" name="tc-raf-referral-title" type="text" id="tc-raf-referral-title" value="<?php echo get_option('tc-raf-referral-title'); ?>" /></div>
                                <div class="description"><?PHP _e('Enter the subject of the email that will be sent notifiying the user of their coupon code.', 'tcraf_locale'); ?></div>
                            </div>
                        </div>
                        
                    	<div class="option">
                        	<h3><?php _e('Coupon Email Message', 'tcraf_locale') ?></h3>
                            <div class="editor-description"><?php _e('This is the message that will be sent in the email to the user notifying them of their referral coupon code. The coupon code will be inserted under this message.', 'tcraf_locale') ?>.</div><br />
                            <div class="section">
                            	<div class="editor-element">
                                    <?PHP wp_editor( stripslashes(get_option('tc-raf-referral-msg')), 'tc-raf-referral-msg-pro', array( 'textarea_name' => 'tc-raf-referral-msg', 'media_buttons' => true, 'tinymce' => array( 'theme_advanced_buttons1' => 'formatselect,forecolor,|,bold,italic,underline,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,|,spellchecker,wp_fullscreen,wp_adv' ) ) ); ?>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    
                    <div id="social_options" class="options_page hide">
                    
                    	<div class="option">
                        	<h3><?php _e('Enable Social Buttons', 'tcraf_locale') ?></h3>
                            <div class="section">
                            	<div class="element">
                                    <select name="tc-raf-social-enabled" id="tc-raf-social-enabled" class="textfield">
                                        <option value="true" <?PHP if(get_option('tc-raf-social-enabled') == 'true'){echo 'selected="selected"';} ?>><?php _e('Enabled', 'tcraf_locale') ?></option>
                                        <option value="false" <?PHP if(get_option('tc-raf-social-enabled') == 'false'){echo 'selected="selected"';} ?>><?php _e('Disabled', 'tcraf_locale') ?></option>
                                    </select>
                                    <label><?PHP _e('Enter URL To Share', 'tcraf_locale'); ?></label>   
                                    <input class="textfield" name="tc-raf-social-url" type="text" id="tc-raf-social-url" value="<?php echo get_option('tc-raf-social-url'); ?>" />
								</div>
                                <div class="description"><?php _e('Here you can enable optional buttons that allow affiliates / referring users a way to share referral links right from their stats page. The URL you enter will be affixed with the users affiliate ID and shared on the various networks.', 'tcraf_locale') ?></div>
                            </div>
                        </div>  

                    	<div class="option">
                        	<h3><?php _e('Facebook Setup', 'tcraf_locale') ?></h3>
                            <div class="section">
                            	<div class="element">
                                    <label><?PHP _e('Facebook Share Title', 'tcraf_locale'); ?></label>   
                                    <input class="textfield" name="tc-raf-social-fb-title" type="text" id="tc-raf-social-fb-title" value="<?php echo get_option('tc-raf-social-fb-title'); ?>" />
                                    <label><?PHP _e('Facebook Share Text', 'tcraf_locale'); ?></label>   
                                    <input class="textfield" name="tc-raf-social-fb-text" type="text" id="tc-raf-social-fb-text" value="<?php echo get_option('tc-raf-social-fb-text'); ?>" />
                                    
                                    <p><?PHP _e('Upload Custom Image', 'tcraf_locale'); ?> - <a href="#" remove-id="tc-raf-social-fb-image" class="tcf-image-remove"><?PHP _e('Remove Image', 'tcraf_locale'); ?></a></p>
                                    <?PHP // display image to user if set :)
									if( get_option('tc-raf-social-fb-image') != '' ){
										echo'<img id="tc-raf-social-fb-image-preview" src="'.get_option('tc-raf-social-fb-image').'" style="max-width:333px;">';
									}
									?>
                                    <input name="tc-raf-social-fb-image" id="tc-raf-social-fb-image" class="textfield tcf-image-upload" type="text" value="<?PHP echo get_option('tc-raf-social-fb-image'); ?>" />
								</div>
                                <div class="description"><?php _e('Here you can set a custom title and message for the Share snippet that will be posted on Facebook. You can also select a custom thumbnail image to set in the Share snippet. Around 100x100 pixels should do the trick.', 'tcraf_locale') ?></div>
                            </div>
                        </div>  

                    	<div class="option">
                        	<h3><?php _e('Twitter Setup', 'tcraf_locale') ?></h3>
                            <div class="section">
                            	<div class="element">
                                    <label><?PHP _e('Default Tweet Text', 'tcraf_locale'); ?></label>   
                                    <input class="textfield" name="tc-raf-social-tweet-text" type="text" id="tc-raf-social-tweet-text" value="<?php echo get_option('tc-raf-social-tweet-text'); ?>" />
								</div>
                                <div class="description"><?php _e('Here you can tweak the Tweet button by setting a custom message that gets preloaded when an affiliate clicks the Twitter button.', 'tcraf_locale') ?></div>
                            </div>
                        </div>  
                        
                    	<div class="option">
                        	<h3><?php _e('Social Buttons Message', 'tcraf_locale') ?></h3>
                            <div class="editor-description"><?php _e('Here you can customize the message that is displayed inside the social button sharing widget displayed in the affiliates\' stats page.', 'tcraf_locale') ?>.</div><br />
                            <div class="section">
                            	<div class="editor-element">
                                    <?PHP wp_editor( stripslashes(get_option('tc-raf-social-message')), 'tc-raf-social-message-pro', array( 'textarea_name' => 'tc-raf-social-message', 'media_buttons' => true, 'tinymce' => array( 'theme_advanced_buttons1' => 'formatselect,forecolor,|,bold,italic,underline,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,|,spellchecker,wp_fullscreen,wp_adv' ) ) ); ?>
                                </div>
                            </div>
                        </div>
                    
                    </div>
                                        
            		<br class="clear" />
                    
            </div>
            
            <div class="info bottom">
            
                <input type="submit" name="settingsBtn" id="settingsBtn" class="button-framework save-options" value="<?php _e('Save All Changes', 'tcraf_locale') ?>" />
            
            </div>
            
        </form>
        
    </div>

</div>

<?php } ?>