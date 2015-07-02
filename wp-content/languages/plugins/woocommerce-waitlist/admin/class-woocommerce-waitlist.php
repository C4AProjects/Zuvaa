<?php
/**
 * Woocommerce Waitlist Admin.
 *
 * @package   Woocommerce_Waitlist_Admin
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */

/**
 *
 * @package Woocommerce_Waitlist_Admin
 * @author  Your Name <email@example.com>
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
    
	    global $wpdb;

	    $wew_DBtable_name = $wpdb->prefix . 'woocommerce_waitlist';

	    if( isset( $_POST ) && !empty( $_POST ) && isset( $_POST['wew_data_form_actions'] ) ){

	    	if( isset( $_POST['wewd_id'] ) && !empty( $_POST['wewd_id'] )  ){

	    		switch( $_POST['wew_data_form_actions'] ){
		    		case 'delete':
		    			if( is_array( $_POST['wewd_id'] ) ){

		    				$pids_array = $_POST['wewd_id'];

		    				foreach ( $pids_array as $key => $value) {

		    					$v = absint( $value );        

	        					$wpdb->query( "DELETE FROM " . $wew_DBtable_name ." WHERE id='" . $v . "'" );
		    				}
		    			}
		    		break;
		    	}
	    	}
	    }
	    elseif(isset($_GET['delete'])) {
	    
	        $_GET['delete'] = absint($_GET['delete']);        

	        $wpdb->query( "DELETE FROM " . $wew_DBtable_name ." WHERE id='" .$_GET['delete']."'" );
	    }
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

		?>
		<div class="wrap">

		  <h2><?php _e('Woocommerce Waitlist', $this->plugin_slug ) ?></h2>

		  <form method="post" action="?page=<?php echo $this->plugin_slug_data; ?>" id="wew_data_form">
		    <p>
		        <select name="wew_data_form_actions">
		            <option value="actions"><?php _e( 'Actions', $this->plugin_slug )?></option>
		            <option value="delete"><?php _e( 'Delete', $this->plugin_slug )?></option>
		      </select>
		      <input type="submit" name="wew_data_actions_changes" class="button-secondary" value="<?php _e( 'Apply', $this->plugin_slug )?>" />
		    </p>
		    <table class="widefat page fixed" cellpadding="0">
		      <thead>
		        <tr>
		        <th id="cb" class="manage-column column-cb check-column" style="" scope="col">
		          <input type="checkbox"/>
		        </th>
		          <th class="manage-column"><?php _e( 'Email', $this->plugin_slug )?></th>
		          <th class="manage-column"><?php _e( 'Product', $this->plugin_slug )?></th>
		          <th class="manage-column"><?php _e( 'Variation', $this->plugin_slug )?></th>
		          <th class="manage-column"><?php _e( 'Added', $this->plugin_slug )?></th>
		        </tr>
		      </thead>
		      <tfoot>
		        <tr>
		        <th id="cb" class="manage-column column-cb check-column" style="" scope="col">
		          <input type="checkbox"/>
		        </th>
		          <th class="manage-column"><?php _e( 'Email', $this->plugin_slug )?></th>
		          <th class="manage-column"><?php _e( 'Product', $this->plugin_slug )?></th>
		          <th class="manage-column"><?php _e( 'Variation', $this->plugin_slug )?></th>
		          <th class="manage-column"><?php _e( 'Added', $this->plugin_slug )?></th>
		        </tr>
		      </tfoot>
		      <tbody><?php

				$wewData = $this->get_wew_records();

				if( $wewData ){

					$i=0;

					foreach( $wewData as $d ) { 

						$i++;
						?>
						<tr class="<?php echo (ceil($i/2) == ($i/2)) ? "" : "alternate"; ?>">
							<th class="check-column" scope="row">
								<input type="checkbox" value="<?php echo $d->id?>" name="wewd_id[]" />
							</th>
							<td>
								<strong><?php echo $d->email; ?></strong>
								<div class="row-actions-visible">
								<span class="delete"><a href="?page=<?php echo $this->plugin_slug_data; ?>&amp;delete=<?php echo $d->id?>" onclick="return confirm('Are you sure you want to delete this record from Woocommerce Waitlist database?');"><?php _e( "Delete", $this->plugin_slug ); ?></a></span>
								</div>
							</td>
							<td><strong><a href="<?php echo get_permalink( $d->productId ); ?>" title="<?php echo get_the_title( $d->productId ); ?>" target="_blank"><?php echo get_the_title( $d->productId ); ?></a></strong></td>
							<td><?php

								if( $d->variationId == 0 ){

									echo '-';
								}
								else{

									$pplg = Woocommerce_Waitlist::get_instance();

									$variationTitle = $pplg->get_variation_titles( $d->variationId );

									if( $variationTitle ){

										echo '<strong>' . $variationTitle . '</strong>';	
									}

								}

							?></td>
							<td><?php echo $d->date; ?></td>
						</tr><?php
					}
				}
				else{
					?><tr><td colspan="4"><?php _e( 'No subscribers.', $this->plugin_slug )?></td></tr><?php
				}
				?>
				</tbody>
		    </table>
		    <p>
		        <select name="wew_data_form_actions-2">
		            <option value="actions"><?php _e( 'Actions', $this->plugin_slug )?></option>
		            <option value="delete"><?php _e( 'Delete', $this->plugin_slug )?></option>
		        </select>
		        <input type="submit" name="wew_data_actions_changes-2" class="button-secondary" value="<?php _e( 'Apply', $this->plugin_slug )?>" />
		    </p>

		  </form>
		</div><?php
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {


		if (!current_user_can( 'manage_options' )){

			// apply_filters( 'shop_manager', 'manage_options' );
			$editor = get_role('shop_manager');
			$editor->remove_cap('manage_options');

	        $this->plugin_screen_hook_suffix = add_menu_page(
											__( 'WooWaitlist', $this->plugin_slug ), 
											__( 'WooWaitlist', $this->plugin_slug ), 
											'manage_woocommerce',
											$this->plugin_slug, 
											array( $this, 'display_plugin_admin_page' ),
											plugin_dir_url( __FILE__ ) . 'images/woowaitlist-fav.png', 40
										);

	        add_submenu_page( 
				$this->plugin_slug, 
				__( 'Email Data', $this->plugin_slug ), 
				__( 'Email Data', $this->plugin_slug ), 
				'manage_woocommerce', 
				$this->plugin_slug_data, 
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
											plugin_dir_url( __FILE__ ) . 'images/woowaitlist-fav.png', 40
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