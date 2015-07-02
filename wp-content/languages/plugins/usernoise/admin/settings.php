<?php
global $un_settings;
class UN_Settings {
	var $options;
	var $h;
	public function __construct(){
		global $hook_suffix, $un_upgrade;
		add_filter('un_notification_options', array($this, '_all_in_one_promo'));
		add_action('admin_print_styles-settings_page_usernoise', array($this, '_print_styles'));
		add_action('admin_enqueue_scripts', array($this, '_enqueue_scripts'), 9);
		$this->h = new HTML_Helpers_0_4;
		$this->options = new Plugin_Options_Framework_0_2_6(USERNOISE_MAIN,
			array(),
			array('page_title' => __('Usernoise settings', 'usernoise')));
	}

	public function _enqueue_scripts($type){
		global $submenu_file;
		if ($type == 'settings_page_usernoise'){
			wp_enqueue_script('un-chosen', usernoise_url('/vendor/chosen/chosen.jquery.js'), wp_script_is('jquery-migrate', 'registered') ? array('jquery-migrate') : array());
			wp_enqueue_script('un_settings', usernoise_url('/js/settings.js'), array('jquery'));
		}
	}

	public function _print_styles(){
		wp_enqueue_style('un-admin', usernoise_url('/css/admin.css'));
		wp_enqueue_style('un-chosen', usernoise_url('/vendor/chosen/chosen.css'));
		wp_enqueue_style('un-font-awesome', usernoise_url('/vendor/font-awesome/css/font-awesome.css'));
	}
	public function set_options(){
		$fonts = apply_filters('un_fonts', array('Helvetica Neue', 'Helvetica', 'Verdana', 'Tahoma',
			'Arial', 'Georgia', 'Palatino'));
		sort($fonts);
		$positions = array('left' => __('Left'), 'right' => __('Right'), 'top' => __('Top'),
			'bottom' => __('Bottom'));
		$general_options = array(
			array('type' => 'tab', 'title' => __('General', 'usernoise')),
			array('type' => 'checkbox', 'name' => UN_ENABLED,
				'title' => __('Enable Usernoise', 'usernoise'), 'label' => __('Enable Usernoise', 'usernoise'),
				'default' => '0'),
			array('type' => 'checkbox', 'name' => UN_PUBLISH_DIRECTLY, 'title' => __('Publish feedback directly without approval', 'usernoise'),
				'label' => __('Publish feedback directly without approval', 'usernoise'), 'default' => 0),
			array('type' => 'checkbox', 'name' => UN_SHOW_POWERED_BY,
				'title' => __('Show Powered by', 'usernoise'),
				'label' => __('Show <strong>"Powered by Usernoise"</strong> link at the modal window. Check this, please!', 'usernoise'),
				'default' => '0',
				'legend' => __('The link will only be visible at the modal window.')),
			array('type' => 'checkbox', 'name' => UN_DISABLE_ON_MOBILES,
				'title' => __('Disable on mobile devices', 'usernoise'),
				'label' => __('Disable on mobile devices', 'usernoise'),
				'default' => '1'),
				array('type' => 'checkbox', 'title' => __('Disable icons', 'usernoise'), 'name' => UN_DISABLE_ICONS,
				'default' => 0, 'legend' => __('Use this mode if Usernoise breaks your theme icons in Twitter Bootstrap-based themes', 'usernoise')),
			array('type' => 'tab', 'title' => __('Button', 'usernoise')),
			array('type' => 'select', 'name' => UN_FEEDBACK_BUTTON_POSITION,
				'title' => __('Position', 'usernoise'), 'values' => $this->h->hash2options($positions),
				'default' => 'left'),
			array('type' => 'text', 'name' => UN_FEEDBACK_BUTTON_TEXT,
				'title' => __('Text', 'usernoise'),
				'default' => _x('Feedback', 'button', 'usernoise')),
				array('type' => 'select', 'name' => UN_FEEDBACK_BUTTON_ICON, 'title' => __('Button Icon', 'usernoise'),
				'values' => $this->h->collection2options(un_get_icons(), 'icon', 'label', __('No icon', 'usernoise'), array('data-icon')),
				'legend' => __('Icon used on the button. <strong>May conflict with Twitter Bootstrap-based themes</strong>', 'usernoise'),
				'default' => ''
			),
			array('type' => 'color', 'name' => UN_FEEDBACK_BUTTON_TEXT_COLOR,
				'title' => __('Text color', 'usernoise'),
				'default' => '#FFFFFF'),
			array('type' => 'color', 'name' => UN_FEEDBACK_BUTTON_COLOR,
				'title' => __('Background color', 'usernoise'),
				'default' => '#404040'),
			array('type' => 'checkbox', 'name' => UN_FEEDBACK_BUTTON_SHOW_BORDER,
				'title' => __('Show border', 'usernoise'), 'label' => __('Show border', 'usernoise'), 'default' => 0)
			);

			$form_options = array(
			array('type' => 'tab', 'title' => __('Form', 'usernoise')),
			array('type' => 'section', 'title' => __('Form', 'usernoise')),
			array('type' => 'select', 'name' => UN_USE_FONT, 'title' => __('Font', 'usernoise'),
				'values' => $this->h->array2options($fonts),
				'default' => 'helvetica'),
			array('type' => 'text', 'name' => UN_FEEDBACK_FORM_TITLE,
				'title' => __('Form title', 'usernoise'), 'class' => 'wide',
				'default' => _x('Feedback', 'form', 'usernoise')),
			array('type' => 'textarea', 'name' => UN_FEEDBACK_FORM_TEXT,
				'title' => __('Introductional text', 'usernoise'),
				'default' => __('Please tell us what do you think, any kind of feedback is highly appreciated.', 'usernoise'),
				'legend' => __('This text will be wrapped into &lt;p&gt; tag. You can still use HTML code.'),
				'rows' => 5),
			array('type' => 'text', 'name' => UN_FEEDBACK_TEXTAREA_PLACEHOLDER, 'default' => __('Your feedback', 'usernoise'), 'title' => __('Textarea placeholder', 'usernoise')),
			array('type' => 'checkbox', 'name' => UN_FEEDBACK_FORM_SHOW_TYPE,
				'title' => __('Ask for feedback type', 'usernoise'),
				'label' => __('Ask for feedback type', 'usernoise'),
				'default' => '1'),
			array('type' => 'checkbox', 'name' => UN_FEEDBACK_FORM_SHOW_SUMMARY,
				'title' => __('Ask for a summary', 'usernoise'),
				'label' => __('Ask for a summary', 'usernoise'),
				'default' => '1'),
			array('type' => 'text', 'name' => UN_FEEDBACK_SUMMARY_PLACEHOLDER, 'default' => __('Short summary', 'usernoise'), 'title' => __('Summary field placeholder', 'usernoise')),
			array('type' => 'checkbox', 'name' => UN_FEEDBACK_FORM_SHOW_EMAIL,
				'title' => __('Ask for an email', 'usernoise'),
				'label' => __('Ask for an email', 'usernoise'),
				'default' => '1'),
			array('type' => 'text', 'name' => UN_FEEDBACK_EMAIL_PLACEHOLDER, 'default' => __('Your email (will not be published)', 'usernoise'), 'title' => __('Email field placeholder', 'usernoise')),
			array('type' => 'checkbox', 'name' => UN_FEEDBACK_FORM_SHOW_NAME,
				'title' => __('Ask for name', 'usernoise'),
				'label' => __('Ask for name', 'usernoise'),
				'default' => '1'),
			array('type' => 'text', 'name' => UN_FEEDBACK_NAME_PLACEHOLDER, 'default' => __('Your name', 'usernoise'), 'title' => __('Name field placeholder', 'usernoise')),
			array('type' => 'text', 'name' => UN_SUBMIT_FEEDBACK_BUTTON_TEXT,
				'title' => __('Submit button text', 'usernoise'),
				'default' => __('Submit feedback', 'usernoise')),
			array('type' => 'section', 'title' => __('Thank you screen', 'usernoise')),
			array('type' => 'text', 'name' => UN_THANKYOU_TITLE,
				'title' => __('Thank you screen title', 'usernoise'),
				'default' => __('Thank you', 'usernoise')),
			array('type' => 'textarea', 'name' => UN_THANKYOU_TEXT,
				'title' => __('Thank you text', 'usernoise'),
				'default' => __('Your feedback has been received.', 'usernoise'),
				'legend' => __('This text will be wrapped into &lt;p&gt; tag. You can still use HTML code.'),
				'rows' => '5'));
			$notification_options = array(
			array('type' => 'tab', 'title' => __('Notifications', 'usernoise')),
			array('type' => 'checkbox', 'name' => UN_ADMIN_NOTIFY_ON_FEEDBACK,
				'title' => __('New feedback received admin notification', 'usernoise'),
				'label' => __('Enable', 'usernoise'),
				'default' => '1',
				'legend' =>
					sprintf(__('Notification emails will be sent to: <a href="mailto:%s">%s</a>', 'usernoise'),
					apply_filters('un_admin_notification_email', get_option('admin_email')),
					apply_filters('un_admin_notification_email', get_option('admin_email'))) . " " .
					sprintf(__('(you can change it at <a href="%s">%s</a> page).', 'usernoise'),
						admin_url('options-general.php'), __('General Options'))
					)
		);
		$options = apply_filters('un_options', array_merge(
				apply_filters('un_general_options', $general_options),
				apply_filters('un_form_options', $form_options),
				apply_filters('un_notification_options', $notification_options)));
		$this->options->set_fields($options);
	}


	public function _all_in_one_promo($options){
		$options []= array('type' => 'custom',
			'title' => __('Notifications do not work right?', 'usernoise'),
			'html' => __("Check out <a href='http://codecanyon.net/item/all-in-one-email-for-wordpress/1290390'>All in One Email plugin</a>. It adds email options missing in WordPress natively.", 'usernoise'));
		return $options;
	}

}

$un_settings = new UN_Settings;
$un_settings->set_options();

function un_get_option($name, $default = null){
	global $un_settings;
	return trim($un_settings->options->get_option($name)) ?
		$un_settings->options->get_option($name) : $default;
}

function un_set_option($name, $value){
	global $un_settings;
	return $un_settings->options->set_option($name, $value);
}

function un_get_localization_array(){
	global $un_settings;
	return apply_filters('un_localization_array', array(
		'text' => (un_get_option(UN_FEEDBACK_BUTTON_ICON) && !un_get_option(UN_DISABLE_ICONS) ? ("<i class='" . un_get_option(UN_FEEDBACK_BUTTON_ICON) . "'></i>")  : '') . un_get_option(UN_FEEDBACK_BUTTON_TEXT, __('Feedback', 'usernoise')),
		'style' => sprintf("background-color: %s; color: %s",
				un_get_option(UN_FEEDBACK_BUTTON_COLOR), un_get_option(UN_FEEDBACK_BUTTON_TEXT_COLOR)),
		'class' => implode(' ', un_button_class()),
		'windowUrl' => un_ajax_url('load_window'),
		'showButton' => apply_filters('un_show_button', true),
		'disableOnMobiles' => un_get_option(UN_DISABLE_ON_MOBILES)
		));
}
