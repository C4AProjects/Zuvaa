<?php 
class UN_Controller {
	function __construct(){
		add_action('un_action_feedback_form_submit', array($this, 'form_submit'));
		add_action('un_feedback_form_body', array($this, 'action_un_feedback_form_body'), 100);
		add_action('wp_ajax_un_feedback_form_submit', array($this, 'form_submit'));
		add_action('wp_ajax_nopriv_un_feedback_form_submit', array($this, 'form_submit'));
		add_action('wp_ajax_nopriv_un_get_feedback_form', array($this, 'get_feedback_form'));
		add_action('wp_ajax_un_load_window', array($this, 'action_load_window'));
		add_action('wp_ajax_nopriv_un_load_window', array($this, 'action_load_window'));
	}
	
	public function action_un_feedback_form_body(){
		if (un_get_option(UN_SHOW_POWERED_BY)) require_once(usernoise_path('/html/powered-by.php'));
	}
	
	public function action_load_window(){
		global $un_h;
		$h = $un_h;
		$body_class = array();
		if (is_rtl()){
			$body_class []= 'rtl';
		}
		$body_class = implode(' ', $body_class);
		require(usernoise_path('/html/window.php'));
		exit;
	}
	
	public function form_submit(){
		global $un_model;
		if (isset($_REQUEST['title']) && $_REQUEST['title'] == un_get_option(UN_FEEDBACK_SUMMARY_PLACEHOLDER))
			$_REQUEST['title'] = '';
		if (isset($_REQUEST['description']) && $_REQUEST['description'] == un_get_option(UN_FEEDBACK_TEXTAREA_PLACEHOLDER))
			$_REQUEST['description'] = '';
		if (isset($_REQUEST['email']) && $_REQUEST['email'] == un_get_option(UN_FEEDBACK_EMAIL_PLACEHOLDER))
			$_REQUEST['email'] = '';
		if (isset($_REQUEST['name']) && $_REQUEST['name'] == un_get_option(UN_FEEDBACK_NAME_PLACEHOLDER))
			$_REQUEST['name'] = '';
		$errors = $un_model->validate_feedback_form(stripslashes_deep($_REQUEST));
		if (empty($errors)){
			$un_model->create_feedback(stripslashes_deep($_REQUEST));
			echo json_encode(array('success' => true));
		} else {
			echo json_encode(array('errors' => $errors));
		}
		exit;
	}
}

$un_controller = new UN_Controller;