<?php
/**
 * Represents the view for the administration dashboard.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */

$plugin_slug = 'woocommerce-waitlist';


if( /*!current_user_can( "manage_options" ) && */isset( $_POST ) && !empty( $_POST ) ){

	// print_r( $_POST );

	$oos_message = trim( wp_unslash( $_POST['wew-out-of-stock-message'] ) );
	$voos_message = trim( wp_unslash( $_POST['wew-variations-out-of-stock-message'] ) );
	$notifyAvail = trim( wp_unslash( $_POST['wew-notify-available-product'] ) );
	$remove_onUninstall = isset( $_POST['wew-remove-waitlist-on-uninstall'] ) ? wp_unslash( $_POST['wew-remove-waitlist-on-uninstall']) : "";
	$unsubscribe_page = intval( $_POST['wew-unsubscribe-page'] );
	$subMail_title = trim( wp_unslash( $_POST['wew-subscription-email-subject'] ) );

	update_option( 'wew-out-of-stock-message', $oos_message );
	update_option( 'wew-variations-out-of-stock-message', $voos_message );
	update_option( 'wew-notify-available-product', $notifyAvail );
	update_option( 'wew-remove-waitlist-on-uninstall', $remove_onUninstall );
	update_option( 'wew-unsubscribe-page', $unsubscribe_page );
	update_option( 'wew-subscription-email-subject', $subMail_title );
}

function get_pages_array(){
    
    $r = array();

    $args = array(
        'sort_order' => 'DESC',
        'orderby' => 'date',
        'hierarchical' => 1,
        'child_of' => 0,
        'parent' => -1,
        'offset' => 0,
        'post_type' => 'page',
        'post_status' => 'publish'
    ); 

    $pages = get_pages($args);    

    if( $pages && !empty( $pages ) ){

    	$r = $pages;
    }

    return $r;
}

?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<?php

	if( !current_user_can( "manage_options" ) ){
		?>
		<form method="post" action="?page=<?php echo $plugin_slug; ?>">
		<?php
	}
	else{
		?>
		<form method="post" action="options.php">
		<?php
	}

	?>	

		<?php wp_nonce_field('update-options'); ?>

		<table class="form-table">

			<?php
						
			$out_of_stock_message = get_option('wew-out-of-stock-message') ? get_option('wew-out-of-stock-message') : __('Notify me when item is back in stock', $plugin_slug );
			$out_variation_of_stock_message = get_option('wew-variations-out-of-stock-message') ? get_option('wew-variations-out-of-stock-message') : __('Notify me when item variation is back in stock', $plugin_slug );
			$notify_available_product = get_option('wew-notify-available-product') ? get_option('wew-notify-available-product') : __("Your product %product% is back in stock, don't miss out and visit the %product page% .", $plugin_slug );
			$wew_enable_shop_manager_settings = get_option('wew-enable-shop-manager-settings') && get_option('wew-enable-shop-manager-settings') == "on" ? 'checked="checked"' : "";
			$wew_remove_data_on_uninstall = get_option('wew-remove-waitlist-on-uninstall') && get_option('wew-remove-waitlist-on-uninstall') == "on" ? 'checked="checked"' : "";
			$wew_unsubscribe_page = get_option('wew-unsubscribe-page') ? get_option('wew-unsubscribe-page') : 0 ;

			$name = __( 'Site title', $plugin_slug ) . ' ' . __('waitlist', $plugin_slug ) . ' ' . __( 'for', $plugin_slug );

			$subscription_email_subject = get_option('wew-subscription-email-subject') ? get_option('wew-subscription-email-subject') : $name ;
			?>

			<tr valign="top">
				<td>
					<label><?php _e('"Out of stock" notification', $plugin_slug ); ?></label>
					<br/>
					<textarea name="wew-out-of-stock-message" id="wew-out-of-stock-message"><?php echo $out_of_stock_message; ?></textarea>
				</td>
			</tr>

			<tr valign="top">
				<td>
					<label><?php _e('Variations "out of stock" notification', $plugin_slug ); ?></label>
					<br/>
					<textarea name="wew-variations-out-of-stock-message" id="wew-variations-out-of-stock-message, "><?php echo $out_variation_of_stock_message; ?></textarea>
				</td>
			</tr>

			<tr valign="top">
				<td>
					<label><?php _e('"Back in stock" email content', $plugin_slug ); ?></label>
					<br/>
					<textarea name="wew-notify-available-product" id="wew-notify-available-product"><?php echo $notify_available_product; ?></textarea>
				</td>
			</tr>

			

			<tr valign="top">
				<td>
					<label><?php _e('Subscription email subject [ <i>following by product title</i> ]', $plugin_slug ); ?></label>
					<br/>
					<textarea name="wew-subscription-email-subject" id="wew-subscription-email-subject"><?php echo $subscription_email_subject; ?></textarea>

				</td>
			</tr>



		</table>

		<p><?php echo __( 'You can use following shortcodes:', $plugin_slug ); ?></p>
		<ul>
			<li><strong>%product%</strong> : <?php _e("Display product title", $plugin_slug ); ?></li>
			<li><strong>%product page%</strong> : <?php _e("Display linked product title", $plugin_slug ); ?></li>
		</ul>
		
		<br/>

		<hr>
		
		<?php

		$sites_allPages = get_pages_array();

		?>
		
		<p><strong><?php _e( 'Select unsubscribe page', $plugin_slug ); ?></strong></p>

		<select name="wew-unsubscribe-page">
			<option value="0" <?php echo $wew_unsubscribe_page == 0 ? 'selected="selected"' : ""; ?>><?php _e( "-- Select page --", $plugin_slug ); ?></option>
			
			<?php
			foreach( $sites_allPages as $spk => $spv ){

				$selected_val = "";

				if( $wew_unsubscribe_page == $spv->ID ){

					$selected_val = ' selected="selected" ';
				}

				echo '<option value="' . $spv->ID . '" ' . $selected_val . '>' . $spv->post_title . '</option>';
			}
			?>
		</select>

		<br/>

		<br/>

		<?php

		$values_to_save = "wew-out-of-stock-message, wew-variations-out-of-stock-message, wew-notify-available-product, wew-subscription-email-subject, wew-remove-waitlist-on-uninstall, wew-unsubscribe-page";

		if( current_user_can( "manage_options" ) ){

			$values_to_save .= ", wew-enable-shop-manager-settings";

			?>

			<hr>

			<br/>

			<label for="wew-enable-shop-manager-settings"><?php _e( 'Enable settings for "Shop manager" user role.', $plugin_slug ); ?></label>
			<input type="checkbox" id="wew-enable-shop-manager-settings" name="wew-enable-shop-manager-settings" <?php echo $wew_enable_shop_manager_settings; ?> />

			<br/>
			
			<br/>

			<?php
		}
		
		?>

		<hr>

		<br/>
		
		<label for="wew-remove-waitlist-on-uninstall"><?php _e( "Remove waitlist data on plugin uninstall", $plugin_slug ); ?></label>
		<input type="checkbox" id="wew-remove-waitlist-on-uninstall" name="wew-remove-waitlist-on-uninstall" <?php echo $wew_remove_data_on_uninstall; ?> />

		<input type="hidden" name="action" value="update" />

		<input type="hidden" name="page_options" value="<?php echo $values_to_save; ?>" />

		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes', $plugin_slug) ?>" />
		</p>

	</form>

</div>