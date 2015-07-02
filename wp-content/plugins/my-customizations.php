<?php
/**
 * Plugin Name: My Customizations
 * Plugin URI: 
 * Description: All my site cusomizations
 * Version: 1.0
 * Author: My Name
 * Author URI: 
 * License: GPL2
 */

add_action( 'woocommerce_thankyou', 'my_custom_tracking' );
function my_custom_tracking( $order_id ) {
    // Lets grab the order
    $order = new WC_Order( $order_id );
?>
    <!-- Start Tracking code -->
<!-- Google Code for Zuvaa2 Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 980843289;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "dATYCJ-mmAoQmfbZ0wM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/980843289/?label=dATYCJ-mmAoQmfbZ0wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
    <!-- End Tracking code -->
<!-- Start Tracking code -->
<!-- Facebook Conversion Code for Zuvaa2 -->
<script>(function() {
  var _fbq = window._fbq || (window._fbq = []);
  if (!_fbq.loaded) {
    var fbds = document.createElement('script');
    fbds.async = true;
    fbds.src = '//connect.facebook.net/en_US/fbds.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(fbds, s);
    _fbq.loaded = true;
  }
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', '6017080473376', {'value':'0.00','currency':'USD'}]);
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6017080473376&amp;cd[value]=0.00&amp;cd[currency]=USD&amp;noscript=1" /></noscript>
<!-- End Tracking code -->
<?php
}