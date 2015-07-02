<?php
/**
 * Woocommerce Waitlist Admin.
 *
 * @package   Woocommerce_Waitlist_Admin
 * @author    WPCream <info@wpcream.com>
 * @license   GPL-2.0+
 * @link      http://wpcream.com
 * @copyright 2014 Makis Mourelatos
 */

class Woocommerce_Waitlist_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		$plugin = Woocommerce_Waitlist::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		$this->plugin_slug_data = $plugin->get_plugin_slug_data();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), Woocommerce_Waitlist::VERSION );
		}
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), Woocommerce_Waitlist::VERSION );
		}
	}

	/**
	 * Waitlist records admin actions
	 *
	 * @since    1.0.0
	 *
	 */
	public function wew_data_actions(){
	}

	/**
	 * Init/display Waitlist data page
	 *
	 * @since    1.0.0
	 *
	 * @return   html
	 */

	public function woocommerce_Waitlist_data_options(){

	    $this->wew_data_actions();

	    if ( empty($_GET['edit']) ) {

	        $this->wew_manage_data();
	    }
	}

	/**
	 * Get Waitlist records from database
	 *
	 * @since    1.0.0
	 *
	 * @return   html
	 */

	public function get_wew_records(){

		global $wpdb;

		$wew_DBtable_name = $wpdb->prefix . 'woocommerce_waitlist';

		$ret = $wpdb->get_results("SELECT * FROM ".$wew_DBtable_name." ORDER BY date DESC");

		return $ret;
	}

	/**
	 * Display admin Waitlist data
	 *
	 * @since    1.0.0
	 *
	 * @return   html
	 */

	public function wew_manage_data(){

		global $plugin_slug_data;

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		if (!current_user_can( 'manage_options' )){

	        $this->plugin_screen_hook_suffix = add_menu_page(
											__( 'WooWaitlist', $this->plugin_slug ), 
											__( 'WooWaitlist', $this->plugin_slug ), 
											'manage_woocommerce',
											$this->plugin_slug, 
											array( $this, 'display_plugin_admin_page' ), 
											plugin_dir_url( __FILE__ ) . 'images/poll_red.png', 40
										);

	        add_submenu_page( 
				$this->plugin_slug, 
				__( 'Email Data', $this->plugin_slug ), 
				__( 'Email Data', $this->plugin_slug ), 
				'manage_woocommerce', $this->plugin_slug_data, 
				array( $this, 'woocommerce_Waitlist_data_options' )
				);
	    }
	    else{

	    	$this->plugin_screen_hook_suffix = add_menu_page(
											__( 'WooWaitlist', $this->plugin_slug ), 
											__( 'WooWaitlist', $this->plugin_slug ), 
											'manage_options',
											$this->plugin_slug, 
											array( $this, 'display_plugin_admin_page' ), 
											plugin_dir_url( __FILE__ ) . 'images/poll_red.png', 40
										);

	    	add_submenu_page( 
				$this->plugin_slug, 
				__( 'Email Data', $this->plugin_slug ), 
				__( 'Email Data', $this->plugin_slug ), 
				'manage_options', $this->plugin_slug_data, 
				array( $this, 'woocommerce_Waitlist_data_options' )
				);
	    }
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);
	}

}