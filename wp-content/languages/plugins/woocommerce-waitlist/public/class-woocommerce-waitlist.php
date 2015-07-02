<?php
/**
 * Woocommerce Waitlist.
 *
 * @package   Woocommerce_Waitlist
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */

/**
 *
 * @package Woocommerce_Waitlist
 * @author  Your Name <email@example.com>
 */
class Woocommerce_Waitlist {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'woocommerce-waitlist';
	protected $plugin_slug_data = 'woocommerce-waitlist-data';
	public 	$mail_product_title = false;
	public $hc = 0;

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		
		// Plugin shortcode
		add_shortcode( 'wew_unsubscribe_waitlist', array( $this, 'wew_unsubscribe_waitlist_shortcode' ) );
		
		// Action triggered on products save
		add_action( 'save_post', array( $this, 'wew_on_product_save' ) );		
		
		// Ajax function
		add_action( 'wp_ajax_wew_save_to_db_callback', array( $this, 'wew_save_to_db_callback' ) );
		add_action( 'wp_ajax_nopriv_wew_save_to_db_callback', array( $this, 'wew_save_to_db_callback' ) );

		// Check for products that are back in stock
		add_filter( 'woocommerce_get_availability', array( $this, 'wew_check_product_availability' ), 1, 2 );
	}

	/**
	 * Check products availability that including in waitlist
	 *
	 * @since     1.0.0
	 */
	public function wew_on_product_save( $post_id ){

		if ( wp_is_post_revision( $post_id ) ){
			return;
		}

		$postType = get_post_type( $post_id );

		if( $postType == 'product' ){

			$this->checkStocks_toNotifyUsers( $post_id );
			$this->checkVariationsStocks_toNotifyUsers( $post_id );
		}
	}

	/**
	 * Send email to user that just added to waitlist
	 *
	 * @since     1.0.0
	 * 
	 * @param     string 	$user_email 	email address
	 *			  integer 	$product_id   	product id
	 *			  integer   $variation_id   variation id
	 *
	 */
	public function wew_email_onWaitlistAdd( $user_email, $product_id, $variation_id = 0 ){
		
		$unsubscribe_page_id = get_option('wew-unsubscribe-page') ? get_option('wew-unsubscribe-page') : 0 ;

		$wew_unsubscribe_page_id = false;

		if( $unsubscribe_page_id && get_page( $unsubscribe_page_id ) ){

			$unsp_data = get_page( $unsubscribe_page_id );

			if( $unsp_data->post_status == 'publish'){

				$wew_unsubscribe_page_id = absint( $unsubscribe_page_id );
			}
		}

		$this->set_mail_product_title( esc_html( get_the_title( $product_id ) ) );

		$d = '<br/>';
		$d .= __( 'Email address: ', $this->plugin_slug ) ;
		$d .= $user_email;
		$d .= '<br/>';
		$d .= __( 'Product: ', $this->plugin_slug ) ;
		$d .= '<a href="' . esc_url( get_permalink( $product_id ) ) . '">' . esc_html( get_the_title( $product_id ) ) . '</a>';
		$d .= '<br/>';

		if( $variation_id > 0 ){

			$d .= __( 'Variation: ', $this->plugin_slug ) ;
			$d .= $this->get_variation_titles( $variation_id );
			$d .= '<br/>';
		} 

		if( $wew_unsubscribe_page_id ){

			$unsubscribe_queryVars = array( 'wewmail' => $user_email, 'wewpid' => $product_id );

			if( $variation_id > 0 ){

				$unsubscribe_queryVars['wewvid'] = $variation_id;
			}

			$d .= '<a href="' . add_query_arg( $unsubscribe_queryVars , esc_url( get_permalink( $wew_unsubscribe_page_id ) ) ) . '">' . __( 'Unsubscribe from waitlist', $this->plugin_slug ) . '</a>';
		}

		$d .= '<br/><br/><a href="' . esc_url( get_permalink( $product_id ) ) . '">' . get_the_post_thumbnail( $product_id, 'medium' ) . '</a>';

		$d .= '<br/><br/>';

		$mail_receiver = $user_email;
		$mail_title = __( "Added to product's waitlist", $this->plugin_slug );
		$mail_content = $d;
		
		$this->wew_send_email( $mail_receiver, $mail_title, $mail_content );
	}

	/**
	 * Remove record from waitlist
	 *
	 * @since     1.0.0
	 * 
	 * @param     string 	$user_email 	email address
	 *			  integer 	$product_id   	product id
	 *			  integer   $variation_id   variation id
	 *
	 */
	public function remove_record_from_waitlist( $user_email, $product_id, $variation_id = 0 ){

		global $wpdb;

		$wew_DBtable_name = $wpdb->prefix . 'woocommerce_waitlist';

		if( $variation_id > 0 ){

			$sql = $wpdb->prepare( "DELETE FROM " . $wew_DBtable_name . " WHERE email = %s AND productId = %d AND variationId = %d LIMIT 1;", $user_email, $product_id, $variation_id );
		}
		else{
			
			$sql = $wpdb->prepare( "DELETE FROM " . $wew_DBtable_name . " WHERE email = %s AND productId = %d LIMIT 1;", $user_email, $product_id );
		}

		$delete = $wpdb->query( $sql );

		return $delete;
	}

	/**
	 * Unsubscribe page shortcode
	 *
	 * @since     1.0.0
	 *
	 * @return    Unsubscribe HTML content | Redirects to home page if user email and product id is not in waitlist
	 *
	 */
	public function wew_unsubscribe_waitlist_shortcode(){
		
		$url_email = false;
		$url_productId = false;

		if( get_query_var( 'wewmail' ) ){

			$url_email = urldecode( get_query_var( 'wewmail' ) );
		}
		elseif( isset( $_GET['wewmail'] ) ){

			$url_email = urldecode( $_GET['wewmail'] );
		}

		if( get_query_var( 'wewpid' ) ){
			
			$url_productId = intval( get_query_var( 'wewpid' ) );
		}
		elseif( isset( $_GET['wewpid'] ) ){

			$url_productId = intval( $_GET['wewpid'] );
		}

		$url_variationId = 0;

		if( get_query_var( 'wewvid' ) ){
			
			$url_variationId = intval( get_query_var( 'wewvid' ) );
		}
		elseif( isset( $_GET['wewvid'] ) ){

			$url_variationId = intval( $_GET['wewvid'] );
		}

		if( $url_email && is_email( $url_email ) && $url_productId ){

			$delete = $this->remove_record_from_waitlist( $url_email, $url_productId, $url_variationId );
			
			if( $delete ){
				
				$productLink .= '<a href="' . esc_url( get_permalink( $url_productId ) ) . '" target="_blank">' . esc_html( get_the_title( $url_productId ) ) . '</a>';

				$d = '<div class="wew-unsubscribe-wrapper">';
				$d .= '<p>Your email <strong>' . $url_email . '</strong> removed from notification list for product ' . $productLink;

				if( $url_variationId ){

					$d .= ' ( ' . __('variation:', $this->plugin_slug ) . ' ' . $this->get_variation_titles( $url_variationId ) . ' )';
				}

				$d .= '.</p>';
				$d .= '</div>';

				return $d;
			}
		}
		
		wp_safe_redirect( get_home_url() );
	}

	/**
	 * Save to waitlist - Ajax call
	 *
	 * @since     1.0.0
	 * 
	 * @param     string 		$user_email 	email address
	 *			  integer 		$product_id   	product id	
	 *
	 * @return    json object 	Status and messages from save action
	 */
	public function wew_save_to_db_callback(){

		$ret = array();
		$ret['error'] = false;
		$ret['message'] = "";


		if( isset( $_POST['pid'] ) && ( isset( $_POST['uemail'] ) || is_user_logged_in() ) && isset( $_POST['is_variation'] ) && isset( $_POST['variation_id'] ) ){

			global $wpdb, $current_user;

			$product_id = absint( $_POST['pid'] );

			$user_email = is_user_logged_in() ? $current_user->user_email : trim( $_POST['uemail'] );

			if( absint( $_POST['is_variation'] ) == 1 ){

				$product_variation_id = absint( $_POST['variation_id'] );
			}
			else{

				$product_variation_id = 0;
			}

			if( is_email( $user_email ) ){

				$wew_DBtable_name = $wpdb->prefix . 'woocommerce_waitlist';

				if( $product_variation_id ){

					$exists_sql = $wpdb->prepare( "SELECT * FROM " . $wew_DBtable_name . " WHERE email = %s AND productId = %d AND variationId = %d LIMIT 1;", $user_email, $product_id, $product_variation_id );
				}
				else{

					$exists_sql = $wpdb->prepare( "SELECT * FROM " . $wew_DBtable_name . " WHERE email = %s AND productId = %d LIMIT 1;", $user_email, $product_id );
				}

				$exists = $wpdb->get_row( $exists_sql );

				if( !$exists ){

					if( $product_variation_id > 0 ){

						$sql = $wpdb->prepare( "INSERT INTO " . $wew_DBtable_name . "( email, productId, variationId ) VALUES ( %s, %d, %d )", $user_email, $product_id, $product_variation_id );
					}
					else{

						$sql = $wpdb->prepare( "INSERT INTO " . $wew_DBtable_name . "( email, productId ) VALUES ( %s, %d )", $user_email, $product_id );
					}

					$exe = $wpdb->query( $sql );

					if( $exe ){

						$ret['send_email'] = self::wew_email_onWaitlistAdd( $user_email, $product_id, $product_variation_id );

						$ret['code'] = 1;

						if( $product_variation_id ){
							
							$ret['message'] = __( "Your email address has been saved <br />and you will be notified when the product's variation is back in stock.", $this->plugin_slug );
						}
						else{
							$ret['message'] = __( "Your email address has been saved <br />and you will be notified when the product is back in stock.", $this->plugin_slug );
						}
					}
					else{

						$ret['code'] = 2;
						$ret['error'] = true;

						if( $product_variation_id ){
							
							$ret['message'] = __( "An error occured on saving product's variation notification data. Try again or contact site's administrator.", $this->plugin_slug );
						}
						else{
							$ret['message'] = __( "An error occured on saving product's notification data. Try again or contact site's administrator.", $this->plugin_slug );
						}
						
					}
				}
				else{

					$ret['code'] = 3;
					$ret['error'] = true;

					if( $product_variation_id ){
							
						$ret['message'] = __( "You have already subscribed to the waitlist for this product's variation.", $this->plugin_slug );
					}
					else{

						$ret['message'] = __( "You have already subscribed to the waitlist for this product.", $this->plugin_slug );
					}
					
				}
				
			}
			else{

				$ret['code'] = 4;
				$ret['error'] = true;
				$ret['message'] = __( "Not a valid email address.", $this->plugin_slug );
			}
		}
		else{

			$ret['code'] = 5;
			$ret['error'] = true;
			$ret['message'] = __( "Not acceptable data.", $this->plugin_slug );
		}

		$ret = json_encode( $ret );

		die( $ret );
	}

	/**
	 * Save to waitlist - Ajax call
	 *
	 * @since     1.1.0
	 * 
	 * @param     integer 		$vid   	variation id
	 *
	 * @return    string 	variation title(s)
	 */
	public function get_variation_titles( $vid ){

		$r = false;

		$display_variations = array();

		$variation_meta = get_post_meta( $vid );

		foreach ( $variation_meta as $x => $y ) {
			
			if( strpos( $x, "attribute_" ) === 0 ){

				$display_variations[] = $y[0];
			}
		}

		if( !empty( $display_variations ) ){

			foreach ( $display_variations as $a => $b ) {

				$display_variations[$a] = ucfirst( str_replace('-', " ", $b ) );
			}

			$r = implode( ' - ', $display_variations );	
		}
		
		return $r;
	}

	/**
	 * Display custom "Out of stock" message - WordPress/WooCommerce Hook 
	 *
	 * @since     1.0.0
	 * 
	 * @param     array 	$availability
	 *			  object 	$_product
	 *
	 * @return    html 		"Out of stock" htmls
	 */
	public function wew_check_product_availability( $availability, $_product ){

		$plugin_slug = $this->get_plugin_slug();

		$isVariation = $_product->variation_id ? true : false;

		$_productID = $isVariation ? $_product->post->ID : $_product->id ;
		$_productTitle = get_the_title( $_productID );
		$_productPermalink = esc_url( get_permalink( $_productID ) );
		$display_outOfStock = ( !$this->productStock_quantity( $_productID ) ) ? true : false;	
		
		$display_variationOutOfStock = ( $isVariation && intval( $_product->total_stock ) == 0 && $_product->total_stock != null ) ? true : false ;

		$display_msg = "";
		$display_classes = "";
		$notify_msg = "";

		if( !$isVariation || ( $display_outOfStock && $this->get_hc() == 0 ) ){

			if( $this->get_hc() == 0 ){
				
				$oosType = "stock";

				?>
				
				<input type="hidden" id="oos-type" value="<?php echo esc_attr( $oosType ); ?>" />
				<input type="hidden" name="wew-id-for-notify" id="wew-id-for-notify" value="<?php echo $_productID; ?>" />
				<input type="hidden" name="wew-is-logged-in" id="wew-is-logged-in" value="<?php echo is_user_logged_in(); ?>" /><?php

			}

			if( $display_outOfStock ){	 	// product has NOT stock
				
				$notify_msg = get_option('wew-out-of-stock-message') ? get_option('wew-out-of-stock-message') : __('Notify me when item is back in stock', $plugin_slug );

				$display_msg = $availability['availability'];

				$display_classes = " stock out-msg " . $availability['class'] . " ";

				echo '<p class="'.$display_classes.'">'.$display_msg.'</p>';

				echo '<p class="oos-message">' . $notify_msg . '</p>';

				$display_form = '<div class="wew-notification-action_wrapper">';

				if( is_user_logged_in() ){

					$display_form .= '<button id="wew-submit-email-to-notify" class="add_to_cart_button logged button">' . __( "Subscribe to waitlist", $this->plugin_slug ) . '</button>';
				}
				else{

					$display_form .= '<input type="text" name="wew-email-to-notify" id="wew-email-to-notify" placeholder="' . __( "Insert email address", $this->plugin_slug ) . '" />';

					$display_form .= '<button id="wew-submit-email-to-notify" class="add_to_cart_button button">' . __( "Subscribe", $this->plugin_slug ) . '</button>';
				}

				$display_form .= '</div>';

				echo $display_form;

				$this->set_hc( intval( $this->get_hc() ) + 1 );

				remove_filter( 'woocommerce_get_availability', array( $this, 'wew_check_product_availability' ) );

				return;
			}
			else{ 	// product HAS stock

				$display_msg = $availability['availability'];

				$display_classes = " stock in-msg " . $availability['class'] . " ";
			}

		}
		elseif( $isVariation && !$display_outOfStock ){

			if( $this->get_hc() == 0 ){
				
				$oosType = "variation";

				?>
				
				<input type="hidden" id="oos-type" value="<?php echo esc_attr( $oosType ); ?>" />
				<input type="hidden" name="wew-id-for-notify" id="wew-id-for-notify" value="<?php echo $_productID; ?>" />
				<input type="hidden" name="wew-is-logged-in" id="wew-is-logged-in" value="<?php echo is_user_logged_in(); ?>" /><?php

			}

			if( $display_variationOutOfStock ){	 	// variation has NOT stock

				foreach ( $_product->variation_data as $a => $b ) {

					$cntv++;

					if( trim($b) != "" ){

						if( $cntv > 1 && $cntv <= count( $_product->variation_data ) ){

							$variation_title .= " - ";
						}

						$variation_title .= '<strong>' . $b . '</strong> ' ;
					}

				}

				$notify_msg = get_option('wew-variations-out-of-stock-message') ? get_option('wew-variations-out-of-stock-message') : __('Notify me when item variation is back in stock', $plugin_slug ) ;

				$display_msg = __( 'Out of stock', $plugin_slug );
				$display_classes = " stock out-msg " . $availability['class'] . " ";

				$display_msg .= '<p class="oos-message">' . $notify_msg . '</p>';
				$display_msg .= '<div class="wew-notification-action_wrapper variations"></div>';

			}
			else{ 	// variation HAS stock

				$display_msg = $availability['availability'];
				$display_classes = " stock in-msg " . $availability['class'] . " ";
			}

		}

		$availability['availability'] = $display_msg ;
		$availability['class'] = $display_classes;

		$this->set_hc( intval( $this->get_hc() ) + 1 );

		remove_filter( 'woocommerce_get_availability', array( $this, 'wew_check_product_availability' ) );

		return $availability;
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {

		return $this->plugin_slug;
	}

	/**
	 * Return waitlist data page url slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Waitlist data page url slug.
	 */
	public function get_plugin_slug_data() {
		
		return $this->plugin_slug_data;
	}

	/**
	 * Return waitlist data page url slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Waitlist data page url slug.
	 */
	public function get_mail_product_title() {

		return $this->mail_product_title;
	}

	/**
	 * Set waitlist data page url slug.
	 *
	 * @since    1.0.0
	 *
	 */

	public function set_mail_product_title( $d ) {

		$this->mail_product_title = $d;
	}

	/**
	 * Return waitlist data page url slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Waitlist data page url slug.
	 */
	public function get_hc() {

		return $this->hc;
	}

	/**
	 * Set waitlist data page url slug.
	 *
	 * @since    1.0.0
	 *
	 */
	public function set_hc( $d ) {

		$this->hc = $d;
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
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}
	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();
	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );
	}

	/**
	 * Create plugin database table
	 *
	 * @since    1.0.0
	 *
	 */
	private static function createPlugin_databaseTable(){

		global $wpdb, $charset_collate;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$wew_DBtable_name = $wpdb->prefix . 'woocommerce_waitlist';


		$sql = "show tables like '" . $wew_DBtable_name . "' ;";

		$res = $wpdb->get_results( $sql );

		if($res){

			$sql2 = "SHOW COLUMNS FROM " . $wew_DBtable_name . " LIKE 'variationId' ;";

			$res2 = $wpdb->get_results( $sql2 );

			if( !$res2 ){

				$variationColumn_sql = "ALTER TABLE " . $wew_DBtable_name . " ADD variationId BIGINT(20) NOT NULL DEFAULT 0;";

				$exe = $wpdb->query( $variationColumn_sql );
			}

			// Remove table index from previous plugin version
			$sql3 = "DROP INDEX `email_productId` ON " . $wew_DBtable_name . " ;";
			$update_exe = $wpdb->query( $sql3 );
		}
		else{

			$sql = "CREATE TABLE IF NOT EXISTS " . $wew_DBtable_name . " (
						`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
						`email` VARCHAR(100) NOT NULL,
						`productId` BIGINT(20) NOT NULL,
						`variationId`  BIGINT(20) NOT NULL DEFAULT 0,
						`date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
						PRIMARY KEY (`id`)
					)" . $charset_collate . " ; ";
			
			dbDelta( $sql );

			
		}
	}
	
	/**
	 * Return product "in stock"
	 *
	 * @since    1.0.0
	 *
	 * @param    integer 	$pid 	product id
	 *  	     integer 	$vid 	variation id
	 * @return   boolean	
	 */
	public function productStock_quantity( $pid, $vid = 0 ){
		
		$r = false;

		if( $vid > 0 ){

			$variation_meta = get_post_meta( $vid );

			if( $variation_meta && isset( $variation_meta['_stock'] ) && isset( $variation_meta['_stock'][0] ) && ( $variation_meta['_stock'][0] == null || intval( $variation_meta['_stock'][0] ) > 0 ) ){

				if( get_post_meta($pid, '_stock_status',true) == 'instock' ){

					$r = true;
				}
			}

		}
		else{

			if( get_post_meta($pid, '_manage_stock',true) == 'yes' && intval( get_post_meta($pid, '_stock',true) ) > 0 ){

				$r = true;
			}
			elseif( get_post_meta($pid, '_stock_status',true) == 'instock' ){

				$r = true;
			}

		}
		
		return $r;
	}

	/**
	 * Return all products that are in waitlist
	 *
	 * @since     1.0.0
	 *
	 * @return    array|false
	 */
	private static function getProducts_inWaitlist(){

		global $wpdb;

		$ret = false;

		$wew_DBtable_name = $wpdb->prefix . 'woocommerce_waitlist';

		$sql = "SELECT DISTINCT productId FROM " . $wew_DBtable_name . " WHERE variationId = 0 ; ";

		$results = $wpdb->get_results( $sql, ARRAY_A );

		if( $results ){

			$t = array();

			foreach( $results as $k=>$v ){

				$t[] = $v['productId'] ;
			}

			$ret = $t;
		}

		return $ret;
	}

	/**
	 * Return all products with variations that are in waitlist
	 *
	 * @since     1.0.0
	 *
	 * @return    array|false
	 */
	private static function getProducts_inwaitlist_forVariations(){

		global $wpdb;

		$ret = false;

		$wew_DBtable_name = $wpdb->prefix . 'woocommerce_waitlist';

		$sql = "SELECT DISTINCT productId, variationId FROM " . $wew_DBtable_name . " WHERE variationId != 0 ; ";

		$results = $wpdb->get_results( $sql, ARRAY_A );

		if( $results ){

			$t = array();

			foreach( $results as $k=>$v ){

				$t['product'][$k] = $v['productId'] ;
				$t['variation'][$k] = $v['variationId'] ;
			}

			$ret = $t;
		}

		return $ret;

	}

	/**
	 * Send "back in stock" email to users
	 *
	 * @since     1.0.0
	 * 
	 * @param     string 	$email 	email address
	 *			  integer 	$pid 	product id
	 *			  integer 	$vid 	variable id
	 */
	private function send_backInStock_email( $email, $pid, $vid = 0 ){

		if( is_email( $email ) ){

			$notify_available_product = get_option('wew-notify-available-product') ? get_option('wew-notify-available-product') : __("Your product %product% is back in stock, don't miss out and visit the %product page% .", $plugin_slug );

			$this->set_mail_product_title( esc_html( get_the_title( $pid ) ) );

			if( $vid > 0 ){

				$d = str_replace( '%product%', get_the_title($pid) . "( " . __( "variation:", $this->plugin_slug ) . " " . $this->get_variation_titles( $vid ) . " )", $notify_available_product );
			}
			else{

				$d = str_replace( '%product%', get_the_title($pid), $notify_available_product );
			}

			$d = str_replace( '%product page%', '<a href="' . esc_url( get_permalink($pid) ) .'">' . esc_html( get_the_title($pid) ) . '</a>', $d );

			$d = '<br/>' . $d;

			$d .= '<br/><br/><a href="' . esc_url( get_permalink( $product_id ) ) . '">' . get_the_post_thumbnail( $pid, 'medium' ) . '</a><br/><br/>';

			$mail_receiver = $email;
			
			if( $vid > 0 ){

				$mail_title = __( "Product variation is available", $this->plugin_slug );
			}
			else{

				$mail_title = __( "Product is available", $this->plugin_slug );
			}
			
			$mail_content = $d;

			if( $this->remove_record_from_waitlist( $email, $pid, $vid ) ){
				
				$this->wew_send_email( $mail_receiver, $mail_title, $mail_content );
			}
			
		}
	}

	/**
	 * Set plugin sender email - Wordpress Hook
	 *
	 * @since    1.0.0
	 *
	 * @param    integer 	$email
	 *
	 * @return   string 	email	
	 */
	public function wew_wp_mail_from( $email ){

	    $email = get_settings('woocommerce_email_from_address');
	    $email = is_email($email);
	    return $email;
	}

	/**
	 * Set plugin email sender name - Wordpress Hook
	 *
	 * @since    1.0.0
	 *
	 * @param    integer 	$email
	 *
	 * @return   string 	email	
	 */
	public function wew_wp_mail_from_name( $from ){
	    
		if( $this->get_mail_product_title() ){

			$name = get_option('wew-subscription-email-subject') . " " . $this->get_mail_product_title() ;

			$this->set_mail_product_title( false );
		}

	    $name = esc_attr($name);
	    
	    return $name;
	}

	/**
	 * Send html format email
	 *
	 * @since    1.0.0
	 *
	 * @param    string 	$receiver 	email receiver email
	 * 			 string 	$title      email subject
	 *		     string 	$content    email content in html format
	 *
	 */
	public function wew_send_email( $receiver, $title, $content ){

		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html>
                    <head>
                    <title>' . esc_html( $title ) . '</title>
                    </head>
                    <body>'
                    . $content . 
                    '</body>
                    </html>';

        add_filter( 'wp_mail_from', array( $this, 'wew_wp_mail_from' ) );
        add_filter( 'wp_mail_from_name', array( $this, 'wew_wp_mail_from_name' ) );
	    
	    add_filter( 'wp_mail_content_type', create_function( '', 'return "text/html";' ) );

	    wp_mail( $receiver, $title, $message );

	    remove_filter( 'wp_mail_from', array( $this, 'wew_wp_mail_from' ) );
        remove_filter( 'wp_mail_from_name', array( $this, 'wew_wp_mail_from_name' ) );
	}	

	/**
	 * Check if product exists in waitlist
	 *
	 * @since     1.0.0
	 * 
	 * @param     integer 	$pid 	product id
	 *			  integer 	$vid 	variable id
	 *
	 */
	public function backInStock_usersNotification( $pid, $vid = 0 ){

		global $wpdb;

		$wew_DBtable_name = $wpdb->prefix . 'woocommerce_waitlist';

		if( $vid > 0 ){

			$sql = "SELECT DISTINCT email FROM " . $wew_DBtable_name . " WHERE productId = %d AND variationId = %d ; ";
			$sql = $wpdb->prepare( $sql, $pid, $vid );
		}
		else{
			
			$sql = "SELECT DISTINCT email FROM " . $wew_DBtable_name . " WHERE productId = %d AND variationId = 0 ; ";
			$sql = $wpdb->prepare( $sql, $pid );
		}

		$results = $wpdb->get_results( $sql );

		if( $results ){

			if( $this ){

				$this_instance = $this;
			}
			else{

				$this_instance = self::get_instance();
			}

			foreach( $results as $k=>$v ){

				$this_instance->send_backInStock_email( $v->email, $pid, $vid );
			}

		}
	}

	/**
	 * Check if products stock changed and notify user
	 *
	 * @since     1.0.0
	 * 
	 * @param     integer 	$product_id		product id
	 *
	 */
	public function checkStocks_toNotifyUsers( $product_id = false ){
		
		$waitlistPorducts = self::getProducts_inwaitlist();

		if( $waitlistPorducts ){

			$args = array(
			    'posts_per_page' => -1,
			    'post_type' => 'product',
			    'orderby' => 'title',
			    'post__in' => $waitlistPorducts
			);

			if( $product_id ){

				$args['p'] = $product_id;
			}

			$the_query = new WP_Query( $args );

			if( $the_query->posts ){
				
				foreach( $the_query->posts as $product ){

					if($this){

						$pInStock = $this->productStock_quantity( $product->ID );

						if( $pInStock ){

							$this->backInStock_usersNotification( $product->ID );
						}

					}
					else{

						$pInStock = self::productStock_quantity( $product->ID );

						if( $pInStock ){

							self::backInStock_usersNotification( $product->ID );
						}

					}

				}

			}

		}
	}

	/**
	 * Check if products with variations stock changed and notify user
	 *
	 * @since     1.1.0
	 * 
	 * @param     integer 	$product_id		product id
	 *
	 */
	public function checkVariationsStocks_toNotifyUsers( $product_id = false ){

		$waitlistPorducts = self::getProducts_inwaitlist_forVariations();

		if( $waitlistPorducts ){
		
			$args = array(
			    'posts_per_page' => -1,
			    'post_type' => 'product',
			    'orderby' => 'title',
			    'post__in' => $waitlistPorducts['product']
			);

			if( $product_id ){

				$args['p'] = $product_id;
			}

			$the_query = new WP_Query( $args );

			if( $the_query->posts ){
				
				foreach( $the_query->posts as $product ){

					$product_variation_id = false;

					foreach ( $waitlistPorducts['product'] as $s => $f ) {

						if( intval( $f ) == $product->ID ){

							$product_variation_id = $waitlistPorducts['variation'][$s];
						}
					}

					if( $product_variation_id ){

						if($this){

							$pInStock = $this->productStock_quantity( $product->ID, $product_variation_id );

							if( $pInStock ){

								$this->backInStock_usersNotification( $product->ID, $product_variation_id );
							}

						}
						else{

							$pInStock = self::productStock_quantity( $product->ID, $product_variation_id );

							if( $pInStock ){

								self::backInStock_usersNotification( $product->ID, $product_variation_id );
							}

						}
					
					}

				}

			}

		}
	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {

		self::createPlugin_databaseTable();
		self::checkStocks_toNotifyUsers();
		self::checkVariationsStocks_toNotifyUsers();
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;

		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		$path = ABSPATH . 'wp-content/plugins/'.$domain.'/languages/'.$domain.'-'.$locale.'.mo';

		load_textdomain( $domain, $path );
    	load_plugin_textdomain( $domain, FALSE, dirname(plugin_basename(__FILE__)).'/languages/' );
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
		
		wp_localize_script( $this->plugin_slug . '-plugin-script', 
							'wew_ajax_object', 
							array( 
								'ajax_url' 	=> admin_url( 'admin-ajax.php' ),
								'texts'		=> array(
									'subscribe' => __( "Subscribe to waitlist", $this->plugin_slug ),
									'insert' 	=> __( "Insert email address", $this->plugin_slug ),
									'proceed' 	=> __( "Subscribe", $this->plugin_slug )
								)
							) 
			);
		

	}

}