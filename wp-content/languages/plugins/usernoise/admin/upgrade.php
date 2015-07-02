<?php
class UN_Upgrade {
	var $pro_url = 'http://codecanyon.net/item/usernoise-advanced-modal-feedback-debug/1420436';
	var $h;

	function __construct(){
		$this->h = new HTML_Helpers_0_4;
		if (!$this->usernoisepro_active() || !$this->usernoisepro_installed()){
				add_filter('un_options', array($this, '_pro_options_stub'));
		}
		add_action('admin_notices', array($this, 'action_admin_notices'));
		if (!$this->plugin_installed('All in One Email'))
			add_action('pof_before_page_title', array($this, '_pof_before_page_title'));
	}

	public function _pro_options_stub($options){
		$images_url = usernoise_url('/images');
		$options []= array('type' => 'tab', 'title' => __('Go Pro'), 'href' => $this->pro_url);
		return $options;
	}

	public function _pof_before_page_title($namespace){
		global $parent_file;
		if (!isset($parent_file) || $parent_file != 'options-general.php' || $_REQUEST['page'] != 'usernoise')
			return;
		$h = $this->h;
		$h->link_to($h->_img('//cdn.karevn.com/usernoise/all-in-one-email.png'),
			'http://codecanyon.net/item/all-in-one-email-for-wordpress/1290390',
			array('id' => 'all-in-one-email'));
	}

	function action_admin_notices(){
		global $parent_file;
		if (!$this->usernoisepro_active()  &&
			isset($parent_file) && $parent_file == 'edit.php?post_type=' . FEEDBACK){
		?>
		<div class="error">
			<p>
				<?php if (!$this->usernoisepro_installed()): ?>
					<?php _e(sprintf('You are using Usernoise without Usernoise Pro installed. Consider installing <a href="%s">Usernoise Pro</a> - it adds really nice features.', $this->pro_url), 'usernoise') ?>
				<?php else: ?>
					<?php if (!$this->usernoisepro_active()): ?>
						<?php _e(sprintf('Usernoise Pro is installed, but is not active. You can activate it at <a href="%s">Plugins page</a>.', admin_url('plugins.php')), 'usernoise') ?>
					<?php endif ?>
				<?php endif ?>
			</p>
		</div>
		<?php
		}
	}

	function usernoisepro_active(){
		return defined('UNPRO_VERSION');
	}

	function plugin_installed($name){
		if (!function_exists('get_plugins')){
			require_once(ABSPATH . "/wp-admin/includes/plugin.php");
		}
		foreach(get_plugins() as $path => $info){
			if ($info['Name'] == $name){
				return true;
			}
		}
		return false;
	}

	function usernoisepro_installed(){
		return $this->plugin_installed('Usernoise Pro');
	}
}

$un_upgrade = new UN_Upgrade;
?>
