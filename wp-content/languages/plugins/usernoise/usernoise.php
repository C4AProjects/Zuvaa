<?php
/*
Plugin Name: Usernoise
Description: Usernoise is a modal contact / feedback form with smooth interface.
Version: 3.7.19
Author: CherryDev
*/


define('UN_VERSION', '3.7.19');

load_plugin_textdomain('usernoise', false, dirname(plugin_basename(__FILE__)) . '/languages/');

define('FEEDBACK', 'un_feedback');
define('FEEDBACK_TYPE', 'feedback_type');
define('USERNOISE', 'usernoise');
define('USERNOISE_DIR', dirname(plugin_basename(__FILE__)));
define('USERNOISE_MAIN', __FILE__);

define('UN_FEEDBACK_FORM_TITLE', 'feedback_form_title');
define('UN_USE_FONT', 'use_font');
define('UN_FEEDBACK_FORM_TEXT', 'feedback_form_text');
define('UN_FEEDBACK_BUTTON_TEXT', 'feedback_button_text');
define('UN_FEEDBACK_BUTTON_ICON', 'feedback_button_icon');
define('UN_FEEDBACK_BUTTON_COLOR', 'feedback_button_color');
define('UN_FEEDBACK_BUTTON_POSITION', 'feedback_button_position');
define('UN_FEEDBACK_BUTTON_TEXT_COLOR', 'feedback_button_text_color');
define('UN_FEEDBACK_BUTTON_SHOW_BORDER', 'feedback_button_show_border');
define('UN_DISABLE_ICONS', 'un_disable_icons');
define('UN_SUBMIT_FEEDBACK_BUTTON_TEXT', 'submit_feedback_button_text');
define('UN_FEEDBACK_TEXTAREA_PLACEHOLDER', 'feedback_textarea_placeholder');
define('UN_FEEDBACK_SUMMARY_PLACEHOLDER', 'feedback_summary_placeholder');
define('UN_FEEDBACK_EMAIL_PLACEHOLDER', 'feedback_email_placeholder');
define('UN_FEEDBACK_FORM_SHOW_SUMMARY', 'feedback_form_show_summary');
define('UN_FEEDBACK_FORM_SHOW_TYPE', 'feedback_form_show_type');
define('UN_FEEDBACK_FORM_SHOW_EMAIL', 'feedback_form_show_email');
define('UN_FEEDBACK_FORM_SHOW_NAME', 'feedback_form_show_name');
define('UN_FEEDBACK_NAME_PLACEHOLDER', 'feedback_form_name_placeholder');

define('UN_PUBLISH_DIRECTLY', 'publish_directly');
if (!defined('UN_ENABLED')){
	define('UN_ENABLED', 'enabled');
}
define('UN_SHOW_POWERED_BY', 'show_powered_by');
define('UN_ADMIN_NOTIFY_ON_FEEDBACK', 'admin_notify_on_feedback');
define('UN_THANKYOU_TITLE', 'thankyou_title');
define('UN_THANKYOU_TEXT', 'thankyou_text');
define('UN_DISABLE_ON_MOBILES', 'disable_on_mobiles');
define('UN_LOAD_IN_FOOTER', 'load_in_footer');
require(dirname(USERNOISE_MAIN) .'/vendor/plugin-options-framework/plugin-options-framework.php');
$un_h = new HTML_Helpers_0_4;
require(dirname(USERNOISE_MAIN) .'/inc/template.php');

require(dirname(USERNOISE_MAIN) . '/inc/termmeta-api.php');
if (is_admin()) require(usernoise_path('/admin/upgrade.php'));
require(usernoise_path('/admin/settings.php'));
require(usernoise_path('/inc/model.php'));
require(usernoise_path('/inc/db-upgrade.php'));
require(usernoise_path('/inc/migrations.php'));
require(usernoise_path('/admin/notifications.php'));
require(usernoise_path('/inc/form.php'));
require(usernoise_path('/inc/shortcodes.php'));
if (is_admin()){
	require(usernoise_path('/admin/admin-base.php'));
	require(usernoise_path('/admin/editor-page.php'));
	require(usernoise_path('/admin/menu.php'));
	require(usernoise_path('/admin/feedback-list.php'));
	require(usernoise_path('/admin/dashboard.php'));
	if (defined('DOING_AJAX') && un_get_option(UN_ENABLED, true)){
		require(usernoise_path('/inc/controller.php'));
	}
} else if (un_get_option(UN_ENABLED, true)){
	require(usernoise_path('/admin/admin-bar.php'));
	require(usernoise_path('/inc/integration.php'));
	require(usernoise_path('/inc/controller.php'));
}


function un_get_feedback_capabilities(){
	return array('edit_un_feedback_items', 'edit_un_feedback', 'delete_un_feedback', 
		'publish_un_feedback', 'publish_un_feedback_items', 
		'edit_others_un_feedback_items', 'edit_published_un_feedback');
}
function un_get_capable_roles(){
	return array('administrator', 'editor');
}

function un_activation_hook(){
	
}

function un_deactivation_hook(){
	delete_option('un_version');
	global $wp_roles;
	if ( ! isset( $wp_roles ) )
		$wp_roles = new WP_Roles();
	foreach(un_get_capable_roles() as $role)
		foreach(un_get_feedback_capabilities() as $cap)
			$wp_roles->remove_cap($role, $cap);
	flush_rewrite_rules();
}

register_deactivation_hook(__FILE__, 'un_deactivation_hook');
register_activation_hook(__FILE__, 'un_activation_hook');
