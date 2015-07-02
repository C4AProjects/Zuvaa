<?php
/**
 * Plugin Name: WooCommerce Gift Card
 * Plugin URI: http://codemypain.com
 * Description: WooCommerce extension that provides the functionality of selling gift card vouchers on your store.
 * Version: 1.6
 * Author: Isaac Oyelowo
 * Author URI: http://isaacoyelowo.com
 * Requires at least: 3.5
 * Tested up to: 3.5
 */
class SB_WCGifts
{
	public function __construct()
	{
		$this->addActions();
		$this->addFilters();
		$this->addShortcodes();
	}
	public static function onActivate()
	{
	    $tps = 'You have received new gift card(s)';
		$tpl = "<html><body>\n\n"."<h1>Howdy [receiver_name],</h1><br />\n\n".
				"You have received new gift voucher(s) <strong>[coupons]</strong> to use for shopping on [blog_name]."
				."\n\n" . "Your gift voucher is redeemable at [site_url]\n\n".
				"[receiver_contents]\n\n"."</body></html>";
				
		update_option('sb_wc_gift_email_tpl', stripslashes($tpl));
		update_option('sb_wc_gift_email_tps', stripslashes($tps));
	}
	public function addActions()
	{
		if( is_admin() ):
			add_action('woocommerce_process_product_meta_simple', array($this, 'action_woocommerce_process_product_meta_simple'));
			add_action('admin_menu', array($this, 'action_admin_menu'));
		else:
			add_action('woocommerce_before_order_notes', array($this, 'action_woocommerce_before_order_notes'));
			
		endif;
		//add_action('woocommerce_payment_complete', array($this, 'actionPaymentComplete'));
		
        add_filter( 'wp_mail_content_type', array($this,'wgc_set_html_content_type') );
		add_action( 'add_meta_boxes', array($this,'wgc_metabox_function') );
        add_action('save_post',array($this, 'save_wgc_metaboox')); 
		add_action('woocommerce_order_status_completed', array($this, 'actionPaymentComplete'));
		add_action('woocommerce_order_status_processing', array($this, 'actionPaymentComplete'));
		add_action('woocommerce_checkout_update_order_meta', array($this, 'action_woocommerce_checkout_update_order_meta'), 10, 2);
		add_action('woocommerce_add_order_item_meta', array($this, 'action_woocommerce_add_order_item_meta'), 10, 2);
		//add_action('woocommerce_checkout_process' , array($this, 'action_woocommerce_checkout_process'));
		add_action('init', array($this,'wgc_localize') );
		//add_action('woocommerce_new_order' , array($this,'woocommerce_new_order'));
	}
	
	
	public function wgc_localize()  {
    // Localization
    load_plugin_textdomain('wgc', false, dirname(plugin_basename(__FILE__)). "/languages" );
    }
	
	public function wgc_set_html_content_type() {
	return 'text/html';
    }
	
	public function wgc_metabox_function(){
	add_meta_box( 'wsg', 'Gift Settings', array($this,'wgc_metabox'), 'product', 'side' ); 
	}
	
	public function action_admin_menu()
	{
		add_options_page(__('Woocommerce Gifts' , 'wgc'), __('Woocommerce Gifts' , 'wgc'), 'manage_options', 'gift-settings', array($this, 'gift_settings'));
	}
	public function wgc_metabox($post)
	{
	    wp_nonce_field( plugin_basename( __FILE__ ), 'wm_noncename' );
		?>
    <p>
		<label><strong><?php _e('Usage Limit' , 'wgc'); ?></strong><?php _e('(It\'s always advisable to put this at 1.)'); ?></label><br/>
		<input type="text" name="wgc_limit" value="<?php if (get_post_meta($post->ID, 'wgc_limit', true) == ""){ echo '1' ;}else{ print get_post_meta($post->ID, 'wgc_limit', true); }?>" size="100" style="padding:3px;width:150px;" />
		</p>
	<p>
		<label><strong><?php _e('Expiry Date' , 'wgc'); ?></strong><?php _e('(The date this coupon will expire, YYYY-MM-DD. Leave blank to put it at never expire)'); ?></strong></label><br/>
		<input type="text" name="wgc_expiry" value="<?php print get_post_meta($post->ID, 'wgc_expiry', true); ?>" size="100" style="padding:3px;width:150px;" />
		</p>
	<p>
		<label><strong><?php _e('Exclude Products By ID' , 'wgc'); ?></strong><?php _e('(separate each ID with comma)'); ?></label><br/>
		<input type="text" name="wgc_products_id" value="<?php print get_post_meta($post->ID, 'wgc_products_id', true); ?>" size="100" style="padding:3px;width:150px;" />
	</p>
	<?php
    }
    public function save_wgc_metaboox($post_id)
	{
		if( !isset($_POST['post_type']) || $_POST['post_type'] != 'product' || !current_user_can('edit_post') )
			return false;
		if ( ! isset( $_POST['wm_noncename'] ) || ! wp_verify_nonce( $_POST['wm_noncename'], plugin_basename( __FILE__ ) ) )
			return;
		if( isset( $_POST[ 'wgc_limit' ] ) ) 
		{
            update_post_meta( $post_id, 'wgc_limit', sanitize_text_field( $_POST[ 'wgc_limit' ] ) );
        }
		if( isset( $_POST[ 'wgc_expiry' ] ) ) 
		{
            update_post_meta( $post_id, 'wgc_expiry', sanitize_text_field( $_POST[ 'wgc_expiry' ] ) );
        }
		if( isset( $_POST[ 'wgc_products_id' ] ) ) 
		{
            update_post_meta( $post_id, 'wgc_products_id', sanitize_text_field( $_POST[ 'wgc_products_id' ] ) );
        }
	}
	
	public function gift_settings()
	{
		if( isset($_POST['tpl']) )
		{
			update_option('sb_wc_gift_email_tpl', stripslashes($_POST['tpl']));
		}
		if( isset($_POST['tps']) )
		{
		    update_option('sb_wc_gift_email_tps', stripslashes($_POST['tps']));
		}
		$email_tpl = get_option('sb_wc_gift_email_tpl');
		$email_tps = get_option('sb_wc_gift_email_tps');
		
		?>
		<div class="wrap">
			<h2><?php _e('Gift Email Settings' , 'wgc'); ?></h2>
			<form action="" method="post">
			<p>
			<label>
				<strong><?php _e('Email subject' , 'wgc'); ?></label></strong><br />
				<input type="text" name="tps" style="width:300px;" value="<?php print $email_tps; ?>" />
				</p>
					<strong><label><?php _e('Email template' , 'wgc'); ?></label></strong><br/>
					<textarea rows="" cols="" style="width:50%;height:200px;" name="tpl"><?php print $email_tpl; ?></textarea>
				</p>
				<button type="submit" class="button-primary"><?php _e('Save' , 'wgc'); ?></button>
			</form>
		</div>
		<?php 
		}
// style --	.woocommerce ul.products li.first, .woocommerce-page ul.products li.first {clear:none !important}
	public function action_woocommerce_add_order_item_meta($item_id, $values)
	{
		global $woocommerce,$post_id,$order_id ,$wpdb ,$user_id,$user;
	   $this->orderID = $order_id;
        $customer_orders = get_posts(array(
            'numberposts' => '1',
            'meta_key' => '_customer_user',
            'meta_value' => get_current_user_id(),
            'post_type' => 'shop_order',
            'post_status' => 'publish'
        ));

        foreach ($customer_orders as $customer_order) {
            $order = new WC_Order();

            $order->populate($customer_order);
            

            $status = get_term_by('slug', $order->status, 'shop_order_status');
            $item_count = $order->get_item_count();
			var_dump($order);
			var_dump($order_id);

		}
		//self::log($usage);
		//self::log(get_post_meta($order_id, '_billing_email', 1));
		//self::log($the_order);
		if( get_post_meta($values['product_id'], '_gift', 1) != 'yes' )
			return;
		$amount = get_post_meta($values['product_id'], '_sale_price', 1);
		if (empty($amount) ){
		     $amount = get_post_meta($values['product_id'], '_regular_price', 1);
			 }else{
			 $amount = get_post_meta($values['product_id'], '_sale_price', 1);
		}
			 self::log(get_post_meta($item_id));
		$usage = get_post_meta($values['product_id'], 'wgc_limit', 1);
		$expiry = get_post_meta($values['product_id'], 'wgc_expiry', 1);
		$exclude = get_post_meta($values['product_id'], 'wgc_products_id', 1);
		$coupon_codes = $coupon_ids = '';
		for($i = 0; $i < $values['quantity']; $i++)
		{
			$number = strtoupper(substr(md5($values['product_id'].$item_id.$amount.time()), rand(0, 10), 5));
			$coupon_code = 'wcg-'.$number.'-'.$amount;
			$coupon_ids .= $this->createCoupon($coupon_code, $amount, $usage, $expiry, $exclude) . '|';
			$coupon_codes .= $coupon_code . ',';
		}
		$coupon_codes = substr($coupon_codes, 0, -1);
		woocommerce_add_order_item_meta($item_id, '_coupon_ids', $coupon_ids);
		woocommerce_add_order_item_meta($item_id, '_coupon_codes', $coupon_codes);
		//self::log('values');
		//self::log($values);
	}
	public function action_woocommerce_process_product_meta_simple($product_id)
	{
		$is_gift = isset( $_POST['_gift'] ) ? 'yes' : 'no';
		update_post_meta($product_id, '_gift', $is_gift);
		update_post_meta($product_id, '_visibility', ($is_gift == 'yes') ? 'hidden' : 'visible');
	}
	public function action_woocommerce_checkout_update_order_meta($order_id)
	{
		update_post_meta($order_id, '_gift_receiver_name', trim(@$_POST['gift_receipt_name']));
		update_post_meta($order_id, '_gift_receiver_email', trim(@$_POST['gift_receipt_email']));
		update_post_meta($order_id, '_gift_receiver_msg', trim(@$_POST['gift_receipt_msg']));
	}
	public function action_woocommerce_before_order_notes($checkout)
	{
		global $woocommerce;
		//print_r($woocommerce->cart->get_cart());
		$exists_gift = false;
		foreach($woocommerce->cart->get_cart() as $cart_item_key => $p)
		{
			$gift = get_post_meta($p['product_id'], '_gift', 1);
			if( $gift == 'yes' )
			{
				$exists_gift = true;
				break;
			}
		}
		if( !$exists_gift )
			return false;
		?>
		<div style="clear:both;"></div>
		<h3><?php _e('I\'m sending this Gift Card to someone') ;?></h3>
		<p class="form-row form-row-wide">
			<label><?php _e('Recipient\'s name' , 'wgc'); ?></label>
			<input type="text" class="input-text" name="gift_receipt_name" style="width:200px;" />
		</p>
		<p class="form-row form-row-wide">
			<label><?php _e('Recipient\'s email' , 'wgc'); ?></label>
			<input type="text" class="input-text" name="gift_receipt_email" style="width:200px;" />
		</p>
		<p class="form-row form-row-wide">
		    <label><?php _e('Message to Recipient' , 'wgc'); ?></label>
		    <textarea style="width:200px;" name="gift_receipt_msg"></textarea>
		</p>
		<?php 
	}

	public function actionPaymentComplete($order_id)
	{
		//SB_WCGifts::log('payment completed');
		//self::log('order id');
		//self::log($order_id);
		$order = new WC_Order($order_id);
		$rname =  get_post_meta($order_id, '_gift_receiver_name', 1);
		$remail = get_post_meta($order_id, '_gift_receiver_email', 1);
		$rmsg = get_post_meta($order_id, '_gift_receiver_msg', 1);
		$email_tpl = get_option('sb_wc_gift_email_tpl');
		$subject = get_option('sb_wc_gift_email_tps');
		//self::log($email_tpl);
		$customer = new WP_User(get_post_meta($order_id, '_customer_user', 1));
		$to = empty($remail) ? $order->billing_email : $remail;
		$message = str_replace('[receiver_name]', empty($rname) ? sprintf("%s %s", $order->billing_first_name, $order->billing_last_name) : $rname, $email_tpl);
		$coupons = '';
		//self::log(get_post_meta($order_id));
		foreach($order->get_items() as $item_id => $item)
		{
			//self::log($order);
			//check if order item is a product gift
			$is_gift = get_post_meta($item['product_id'], '_gift', 1);
			//self::log("is_gift: $is_gift");
			if( $is_gift != 'yes' ) continue;
			
			$coupons .= sprintf(" %s\n", $item['coupon_codes']);
		
		if( empty($coupons) )
		{
			//self::log('coupons empty');
			return false;
			
		}
		$blogname = get_bloginfo();
		$siteurl = '<a href="'.site_url().'">'.site_url().'</a>' ;
		$date = date("Y-m-d" ) ;
		$message = str_replace('[coupons]', $coupons, $message);
		$message = str_replace('[blog_name]', $blogname, $message);
		$message = str_replace('[site_url]', $siteurl, $message);
		$message = str_replace('[date]', $date, $message);
		$message = str_replace('[total]', $item['line_total'], $message);
		$message = str_replace('[quantity]', $item['qty'], $message);
		$message = str_replace('[quantity]', $item['qty'], $message);
	    }
		if (!empty ($rname) )
	    {
		$contents = '<br />Additional message from sender:<br />'. $rmsg.'<br /><br />You\'ve received the Gift Voucher with kind heart from ' . $order->billing_first_name. ' ' . $order->billing_last_name ;
		}
		$message = str_replace('[receiver_contents]', $contents, $message);
		if( $is_gift == 'yes' )
		{
		//self::log($message);
		//SB_WCGifts::log('sending email to ' . $to);
		wp_mail($to,$subject, $message, array("From: $blogname <no-reply@nodomain.com>"));
	    }
	}
	public function addFilters()
	{
		add_filter('product_type_options', array($this, 'filter_product_type_options'));
	}
	public function addShortcodes()
	{
		add_shortcode('coupon_page', array($this, 'shortcode_coupon_page'));
	}
	public function filter_product_type_options($types)
	{
		$types['gift'] = array('id' => '_gift', 
								'wrapper_class' => 'show_if_simple',
								'label' => __( 'Gift', 'wgc'),
								'description' => __( 'Gift products.', 'wgc'));
		return $types;
	}
	public function shortcode_coupon_page($atts)
	{
		require_once dirname(__FILE__) . '/frontend/gifts-listing.php';
	}
	public function createCoupon($coupon_code, $amount, $usage, $expiry, $exclude)
	{
		//$coupon_code = 'UNIQUECODE'; // Code
		//$amount = '10'; // Amount
		$discount_type = 'fixed_cart'; // Type: fixed_cart, percent, fixed_product, percent_product
		$coupon = array(
				'post_title' => $coupon_code,
				'post_content' => '',
				'post_status' => 'publish',
				'post_author' => 1,
				'post_type' => 'shop_coupon'
		);
		$new_coupon_id = wp_insert_post( $coupon );
		// Add meta
		update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
		update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
		update_post_meta( $new_coupon_id, 'individual_use', 'no' );
		update_post_meta( $new_coupon_id, 'product_ids', '' );
		update_post_meta( $new_coupon_id, 'exclude_product_ids', $exclude );
		update_post_meta( $new_coupon_id, 'usage_limit', $usage );
		update_post_meta( $new_coupon_id, 'expiry_date', $expiry );
		update_post_meta( $new_coupon_id, 'apply_before_tax', 'no' );//fix for coupon codes not applying to shipping fee
		update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
		
		return $new_coupon_id;
	}
	public static function log($str)
	{
		$log_file = dirname(__FILE__) . '/log.txt';
		$fh = file_exists($log_file) ? fopen($log_file, 'a+') : fopen($log_file, 'w+');
		fwrite($fh, print_r($str, 1)."\n");
		fclose($fh);
	}
}
global $sb_instances;
if( !is_array($sb_instances) )
	$sb_instances = array();
register_activation_hook(__FILE__, array('SB_WCGifts', 'onActivate'));
$sb_instances['sb_wc_gifts'] = new SB_WCGifts();