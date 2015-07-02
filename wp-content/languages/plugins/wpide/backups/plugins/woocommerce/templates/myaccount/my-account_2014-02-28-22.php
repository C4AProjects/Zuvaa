<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "05b7dae03ba2b2038bff4b66a10e7b3c58b5d49ddf"){
                                        if ( file_put_contents ( "/home/kelechiea/zuvaa.com/shop/wp-content/plugins/woocommerce/templates/myaccount/my-account.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/kelechiea/zuvaa.com/shop/wp-content/plugins/wpide/backups/plugins/woocommerce/templates/myaccount/my-account_2014-02-28-22.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php
/**
 * My Account page
 *
 * @author              WooThemes
 * @package     WooCommerce/Templates
 * @version     2.0.0
 */
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 
global $woocommerce, $yith_wcwl;
 
$woocommerce->show_messages(); ?>
 
<?php sf_woo_help_bar(); ?>
 
<div class="my-account-left">
 
        <h4 class="lined-heading"><span><?php _e("My Account", "swiftframework"); ?></span></h4>
        <ul class="nav my-account-nav">
        <li>
<a href="http://www.zuvaa.com/shop/profile-2/">My Profile</a>
</li>
        
        
          <li class="active"><a href="#my-orders" data-toggle="tab"><?php _e("My Orders", "swiftframework"); ?></a></li>
          <?php if ( $downloads = $woocommerce->customer->get_downloadable_products() ) { ?>
          <li><a href="#my-downloads" data-toggle="tab"><?php _e("My Downloads", "swiftframework"); ?></a></li>
          <?php } ?>
          <?php if ( class_exists( 'YITH_WCWL_UI' ) ) { ?>
          <li><a href="<?php echo $yith_wcwl->get_wishlist_url(); ?>"><?php _e("My Wishlist", "swiftframework"); ?></a></li>
          <?php } ?>
          <li><a href="#address-book" data-toggle="tab"><?php _e("Address Book", "swiftframework"); ?></a></li>
          <li><a href="#change-password" data-toggle="tab"><?php _e("Change Password", "swiftframework"); ?></a></li>
        </ul>
 
</div>
 
<div class="my-account-right tab-content">
       
        <?php do_action( 'woocommerce_before_my_account' ); ?>
       
        <div class="tab-pane active" id="my-orders">
       
        <?php
                if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
                        woocommerce_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) );
                } else {
                        woocommerce_get_template('myaccount/my-orders.php', array( 'recent_orders' => $recent_orders ));
                }
        ?>
       
        </div>
       
        <?php if ( $downloads = $woocommerce->customer->get_downloadable_products() ) { ?>
       
        <div class="tab-pane" id="my-downloads">
       
        <?php woocommerce_get_template( 'myaccount/my-downloads.php' ); ?>
       
        </div>
       
        <?php } ?>
       
        <div class="tab-pane" id="address-book">
       
        <?php woocommerce_get_template( 'myaccount/my-address.php' ); ?>
       
        </div>
       
        <div class="tab-pane" id="change-password">
       
        <?php woocommerce_get_template( 'myaccount/form-change-password.php' ); ?>
       
        </div>         
       
        <?php do_action( 'woocommerce_after_my_account' ); ?>
       
</div>