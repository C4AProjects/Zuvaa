<?php
class UN_Shortcodes{
	function __construct(){
		add_shortcode('usernoise', array($this, '_usernoise_form'));
	}
	
	function _usernoise_form($attrs){
		ob_start();
		do_action('un_feedback_form', '?un_action=feedback_submit');
		return ob_get_clean();
	}
}

new UN_Shortcodes;
?>