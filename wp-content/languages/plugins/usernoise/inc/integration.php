<?php

class UN_Integration {
	
	public function __construct(){
		if (!is_admin()){
			add_action('init', array($this, '_init'));
		}
	}
	
	public function _init(){
		if (is_user_logged_in()){
			wp_enqueue_style('usernoise-adminbar', usernoise_url('/css/admin-bar.css'), null, UN_VERSION);
		}
		if (!un_get_option(UN_ENABLED)) return;
		wp_enqueue_script('usernoise', usernoise_url('/js/usernoise.js'), array('jquery'),
		 	UN_VERSION);
		wp_enqueue_script('usernoise-button', usernoise_url('/js/button.js'), array('jquery', 'usernoise'),
		 	UN_VERSION);
		wp_enqueue_style('usernoise-button', usernoise_url('/css/button.css'), null, UN_VERSION);
		wp_enqueue_style('usernoise-form', usernoise_url('/css/form.css'), null, UN_VERSION);
		if (!un_get_option(UN_DISABLE_ICONS))
			wp_enqueue_style('font-awesome', usernoise_url('/vendor/font-awesome/css/font-awesome.css'), null, UN_VERSION);
		wp_localize_script('usernoise', 'usernoiseButton', un_get_localization_array());
		add_action('wp_footer', array($this, '_wp_footer'));
	}
	
	
	public function _wp_footer(){
		?><?php require(usernoise_path('/html/thankyou.php')) ?><?php
	}
}
$un_integration = new UN_Integration;