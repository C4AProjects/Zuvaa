<?php

/* INSTALLATION HELPERS */

// Admin Bar Customisation
function flatsome_admin_bar_install() {
 global $wp_admin_bar;
 // Add a new top level menu link
if (current_user_can( 'manage_options' ) ){

  // top menu
 $wp_admin_bar->add_menu( array(
 'parent' => false,
 'id' => 'flatsome_install',
 'title' => __('Setup Flatsome'),
 ));
  
  // install required plugins
  $requiredPlugins = get_admin_url().'themes.php?page=install-required-plugins';
  $class = '';
  if(!function_exists('wc_print_notices')){ $class = 'done';}
  $wp_admin_bar->add_menu( array(
   'parent' => 'flatsome_install',
   'id' => 'theme_options2',
   'title' => __('1 - Install required plugins'),
   'href' => $requiredPlugins,
   'meta' => array('class' => $class)
 ));


  $imageSizesurl = get_admin_url().'admin.php?page=wc-settings&tab=products#set-size';
  $class = '';
  $img_size = get_option('shop_single_image_size');
  if($img_size['height'] != '300'){ $class = 'done';}
  $wp_admin_bar->add_menu( array(
   'parent' => 'flatsome_install',
   'id' => 'woocommerce_images',
   'title' => __('2 - Set image sizes'),
   'href' =>  $imageSizesurl,
   'meta' => array('class' => $class)
 ));


}
}
add_action( 'wp_before_admin_bar_render', 'flatsome_admin_bar_install' , 1 );


/* woocommerce theme options helper */
add_filter( 'woocommerce_product_settings', 'add_order_number_start_setting' );

function add_order_number_start_setting( $settings ) {

  $updated_settings = array();
  foreach ( $settings as $section ) {
    // at the bottom of the General Options section

    if ( isset( $section['id'] ) && 'image_options' == $section['id'] &&

       isset( $section['type'] ) && 'sectionend' == $section['type'] ) {

      $updated_settings[] = array(
        'id'       => 'image_size_tip',
        'type'     => 'title',
        'desc'     => '<div class="welcome-panel" style="padding:0px 10px"><a name="set-size"></a><p><strong>FLATSOME TIP:</strong> The 247x300px for Catalog images is ment for 3 column product grid with sidebar. (4 column grid without sidebar). If you for example have 3 products pr row without sidebar you need to change it to <strong>380x468px</strong> for <strong>Catalog images</strong>. You can have any height you want on the images. Remember to regenerate your images after you have change this.</p></div>'
      );
    }
    $updated_settings[] = $section;

  }

  return $updated_settings;

}
