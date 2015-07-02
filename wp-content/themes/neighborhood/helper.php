<!DOCTYPE html>

<!--// OPEN HTML //-->
<html <?php language_attributes(); ?>>

    <!--// OPEN HEAD //-->
    <head>
        <?php
        $options = get_option('sf_neighborhood_options');
        $enable_responsive = $options['enable_responsive'];
        $is_responsive = "responsive-fluid";
        if (!$enable_responsive) {
            $is_responsive = "responsive-fixed";
        }
        $header_layout = $options['header_layout'];
        $page_layout = $options['page_layout'];

        $enable_logo_fade = $options['enable_logo_fade'];
        $enable_page_shadow = $options['enable_page_shadow'];
        $enable_top_bar = $options['enable_tb'];

        $enable_mini_header = $options['enable_mini_header'];

        $enable_header_shadow = $options['enable_header_shadow'];
        $header_overlay = $options['header_overlay'];
        $enable_promo_bar = $options['enable_promo_bar'];

        $page_class = $logo_class = $ss_enable = "";

        global $catalog_mode;

        if (isset($options['enable_catalog_mode'])) {
            $enable_catalog_mode = $options['enable_catalog_mode'];
            if ($enable_catalog_mode) {
                $catalog_mode = true;
                $page_class = "catalog-mode ";
            }
        }


        if ($enable_page_shadow) {
            $page_class .= "page-shadow ";
        }

        if ($enable_header_shadow) {
            $page_class .= "header-shadow ";
        }

        if ($header_overlay) {
            $page_class .= "header-overlay ";
        }

        if ($enable_promo_bar) {
            $page_class .= "has-promo-bar ";
        }

        if ($enable_logo_fade) {
            $logo_class = "logo-fade";
        }

        if (isset($_GET['layout'])) {
            $page_layout = $_GET['layout'];
        }

        if (isset($options['ss_enable'])) {
            $ss_enable = $options['ss_enable'];
        } else {
            $ss_enable = true;
        }

        global $post;
        $extra_page_class = "";
        if ($post) {
            $extra_page_class = get_post_meta($post->ID, 'sf_extra_page_class', true);
        }
        ?>

        <!--// SITE TITLE //-->
        <title><?php wp_title('|', true, 'right'); ?></title>


        <!--// SITE META //-->
        <meta charset="<?php bloginfo('charset'); ?>" />  
        <?php if ($enable_responsive) { ?><meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1"><?php } ?>


        <!--// PINGBACK & FAVICON //-->
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
      
        <?php if (isset($options['custom_favicon'])) { ?><link rel="shortcut icon" href="<?php echo $options['custom_favicon']; ?>" /><?php } ?>

        <?php
        $custom_fonts = $google_font_one = $google_font_two = $google_font_three = $google_font_subset = $subset_output = "";

        $body_font_option = $options['body_font_option'];
        if (isset($options['google_standard_font'])) {
            $google_font_one = $options['google_standard_font'];
        }
        $headings_font_option = $options['headings_font_option'];
        if (isset($options['google_heading_font'])) {
            $google_font_two = $options['google_heading_font'];
        }
        $menu_font_option = $options['menu_font_option'];
        if (isset($options['google_menu_font'])) {
            $google_font_three = $options['google_menu_font'];
        }

        if (isset($options['google_font_subset'])) {
            $google_font_subset = $options['google_font_subset'];
            $s = 0;
            if (is_array($google_font_subset)) {
                foreach ($google_font_subset as $subset) {
                    if ($subset == "none") {
                        break;
                    }
                    if ($s > 0) {
                        $subset_output .= ',' . $subset;
                    } else {
                        $subset_output = ':' . $subset;
                    }
                    $s++;
                }
            }
        }

        if ($body_font_option == "google" && $google_font_one != "") {
            $custom_fonts .= "'" . $google_font_one . $subset_output . "', ";
        }
        if ($headings_font_option == "google" && $google_font_two != "") {
            $custom_fonts .= "'" . $google_font_two . $subset_output . "', ";
        }
        if ($menu_font_option == "google" && $google_font_three != "") {
            $custom_fonts .= "'" . $google_font_three . $subset_output . "', ";
        }

        $fontdeck_js = $options['fontdeck_js'];
        ?>
        
        <?php if (($body_font_option == "google") || ($headings_font_option == "google") || ($menu_font_option == "google")) { ?>
            <!--// GOOGLE FONT LOADER //-->
            <script>
                var html = document.getElementsByTagName('html')[0];
                html.className += '  wf-loading';
                setTimeout(function() {
                    html.className = html.className.replace(' wf-loading', '');
                }, 3000);
              
                WebFontConfig = {
                    google: { families: [<?php echo $custom_fonts; ?> 'Vidaloka'] }
                };
              
                (function() {
                    document.getElementsByTagName("html")[0].setAttribute("class","wf-loading")
                    //  NEEDED to push the wf-loading class to your head
                    document.getElementsByTagName("html")[0].setAttribute("className","wf-loading")
                    // for IE…
              
                    var wf = document.createElement('script');
                    wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
                        '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
                    wf.type = 'text/javascript';
                    wf.async = 'false';
                    var s = document.getElementsByTagName('script')[0];
                    s.parentNode.insertBefore(wf, s);
                })();
            </script>
        <?php } ?>
        <?php if (($body_font_option == "fontdeck") || ($headings_font_option == "fontdeck") || ($menu_font_option == "fontdeck")) { ?>
            <!--// FONTDECK LOADER //-->
            <?php echo $fontdeck_js; ?>
        <?php } ?>

        <!--// LEGACY HTML5 SUPPORT //-->
        <!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/excanvas.compiled.js"></script>
<![endif]-->

        <!--// WORDPRESS HEAD HOOK //-->

        <!--User added files fro popup-->
        <!--Facebook Like gallery-->
        <?php 
      // if (is_page(2729)) { 
     ?>
          <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
            <link href="<?php echo bloginfo('template_url') ?>/main.css" rel="stylesheet" type="text/css">   
            <script type="text/javascript"> 
        $(document).ready(function(){
                                   $(window).load(function(){
                                             var postidsall='';
               $(".userpro-posts").find('.userpro-post').each(function(){
                                                 postidsall+=$(this).attr('id')+',';
                                                       });
                                     $('.postslist').text(postidsall);          
                                                      });
                                            
                       });
        function submitComment(k)
        {
          var comenttext=$(k).parent('td').prev('td').find('textarea').val();
          var post_id=$(k).attr('id');
          <?php if(!is_user_logged_in()) {?>   
                       
                     alert('You must log in to Comment'); 
           return false;
                  <?php 
            }
             $current_user = wp_get_current_user(); 
           ?>
          var commentpost="<?php echo bloginfo('template_url')?>/submit_comment.php";
          var username="<?php echo $current_user->user_login; ?>";
          var useremail="<?php echo $current_user->user_email; ?>";
          var useremailip="<?php echo $_SERVER['REMOTE_ADDR']; ?>";
          var userid="<?php echo $current_user->ID; ?>";
          var formData = {'commentpost':commentpost,'post_id':post_id,'username':username,'useremail':useremail,'useremailip':useremailip,'userid':userid,'comenttext':comenttext};
           $.ajax({
                            'url':commentpost,
                            'type':'post',
                            'data':formData,
                            'success':function(res)
                 {
                  
                 }
            })
          return false;
        }              
      </script>

        <div id="photo_preview" style="display: none;">
            <div class="photo_wrp">
               <div class='showhide'>
                <img class="close" src="<?php echo bloginfo('template_url') ?>/images/close.png">

                <div class="pleft"><img class="fileUnitSpacer" src="">
                
                <div class="preview_next"><a href="#" class="slidesjs_navigation_next"><img src="<?php echo bloginfo('template_url')?>/images/next.png" alt="next"></a></div>
                 <div class="preview_prev"><a href="#" class="slidesjs_navigation_prev"><img src="<?php echo bloginfo('template_url')?>/images/prev.png" alt="prev"></a></div>
                  
                </div>
                <div class="pright" >
                    <div class="userinfo">
                        <div class="userimage">
                            <img class="postuser"  src=''/>
                        </div>
                        <div class="userwork">
                            <p class="username"><a href="#" ></a></p>
                            <p class="timeago"></p>
                        </div>

                    </div>
                    <div class='postcontent'> 

                    </div>
                    <div  class="likes">
                    </div>

               </div>
                <div style="clear:both">
                </div>
            </div>
            </div>
        </div>
        <script src="<?php echo bloginfo('template_url'); ?>/standard/js/jquery.slides.min.js"></script>

        <div class="backkground" style="position: absolute;width: 100%;background-color:#000;left:0px;height: 100%;top: 0px;opacity:.8; z-index:100; display:none;"></div>
        <div class="template_directory" style="display:none;"><?php echo bloginfo('template_url') ?>/view_post.php</div>
        <div class="template_directory_new" style="display:none;"><?php echo bloginfo('template_url') ?>/view_comment.php</div>
         <div class='postslist' style="display:none;"> </div>
        <div class="container_new" style="display:none; background-color:#933;">
            <div id="slides">
                <a href="#" class="slidesjs-previous slidesjs-navigation"><i class="icon-chevron-left icon-large"></i></a>
                <a href="#" class="slidesjs-next slidesjs-navigation"><i class="icon-chevron-right icon-large"></i></a>
            </div>
        </div> 

        <script>
            $(function(){
                $("#slides").slidesjs({
                    effect: {
                        slide: {
                            // Slide effect settings.
                            speed: 1200
                            // [number] Speed in milliseconds of the slide animation.
                        }
                    }
                });
            });
        </script>

    <?php //} ?>
    
    <!--Facebook Like gallery End-->
    <style type="text/css">
   #itro_popup
  {
    width:524px !important;
  }
  #itro_popup h3
  {
    font-weight:bold !important;
  }
  </style>
    <!--User added files fro popup End-->

    <?php wp_head(); ?>

    <!--// CLOSE HEAD //-->

    <script type="text/javascript">// <![CDATA[
        $(function () {
            //$('#myTab a:last').tab('show');
  
        })
        // ]]></script>
  <script type="text/javascript">
   $(document).ready(function(){
 
        $.ajaxSetup({cache:false});
        $(".ajax-popup-link1").click(function(){
            var post_link = $(this).attr("href");
 
            $(".res").html("content loading");
            $(".res").load(post_link);
        return false;
        });
 
    });
</script>
<?php if(is_home() || is_front_page()){ ?>
        <style>
      @media all and (max-width: 959px) {

       
      
      }
      
      @media all and (max-width: 720px) {
      
       .spb_revslider_widget{margin-top:30px;}
       #main-navigation > div{margin-left:0px;}
        ul.accepted-payment-methods{display:none;}
      #itro_popup{display:none !important;}
      #header-section #main-navigation{display:none !important;}
      }
      
      @media all and (max-width: 479px) {
      
           .full-width-text,.spb_single_image,.spb_text_column,.spb_divider.dotted{display:none !important;}
         ul.accepted-payment-methods{display:none;}
             #itro_popup{display:none !important;}
         #header-section #main-navigation{display:none !important;}
      }
    </style>
<?php } ?>
<style>
 @media all and (max-width: 959px) {

       
      
      }
      
 @media all and (max-width: 720px) {
      
       .spb_revslider_widget{margin-top:30px;}
       #main-navigation > div{margin-left:0px;}
       #megaMenu ul.megaMenu li.not_in_mobile{display:none;}
        ul.accepted-payment-methods{display:none;}
         #megaMenu{border:none;}
      }
 @media all and (max-width: 479px) {
         
         ul.accepted-payment-methods{display:none;}
         #megaMenu.megaMenuHorizontal ul.megaMenu{background: #e6eeff;}
         #megaMenu.megaResponsive ul.megaMenu>li.menu-item{border-bottom: 1px solid #b6d2ff;border-top: 1px solid #fff;}
         #megaMenu.megaResponsive ul.megaMenu>li.parent{background:url(http://www.zuvaa.com/shop/wp-content/uploads/2014/04/right_arrow.png) no-repeat 95% 50%;}
             #megaMenu{border:none;}
      }
</style>
</head>

<!--// OPEN BODY //-->
<body <?php body_class($page_class . ' ' . $is_responsive . ' ' . $extra_page_class); ?>>

    <!--// OPEN #container //-->
    <?php if ($page_layout == "fullwidth") { ?>
        <div id="container">
        <?php } else { ?>
            <div id="container" class="boxed-layout">
            <?php } ?>

            <?php
            if ($ss_enable && sf_woocommerce_activated()) {
                echo sf_super_search();
            }
            ?>

            <!--// HEADER //-->
            <div class="header-wrap">
                <?php if ($enable_top_bar) { ?>
                    <!--// TOP BAR //-->
                    <?php echo sf_top_bar(); ?>
                <?php } ?>
                <!--
                                                <div id="extra-nav">
                                                        <div class="container">
                                                                <div class="row">
                                                                        <div class="span12">
                                                                                <nav id="extra-navigation" class="clearfix">
                <?php wp_nav_menu(array('theme_location' => 'extra-menu')); ?>
                                                                                </nav>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>  
                -->

                <div id="header-section" class="<?php echo $header_layout; ?> <?php echo $logo_class; ?>">
                    <?php echo sf_header(); ?>
                </div>

                <?php if ($enable_promo_bar) { ?>
                    <!--// OPEN #promo-bar //-->
                    <div id="promo-bar">
                        <div class="container">
                            <?php echo $options['promo_bar_text']; ?>
                        </div>
                    </div>

                <?php } ?>
            </div>

            <?php if ($enable_mini_header) { ?>

                <?php echo sf_mini_header(); ?>

            <?php } ?>

            <!--// OPEN #main-container //-->
            <div id="main-container" class="clearfix">

                <?php
                if (is_page()) {
                    $show_posts_slider = get_post_meta($post->ID, 'sf_posts_slider', true);
                    $rev_slider_alias = get_post_meta($post->ID, 'sf_rev_slider_alias', true);
                    if ($show_posts_slider) {
                        sf_swift_slider();
                    } else if ($rev_slider_alias != "") {
                        ?>
                        <div class="home-slider-wrap">
                            <?php putRevSlider($rev_slider_alias); ?>
                        </div>
                        <?php
                    }
                }
                ?>

                <!--// OPEN .container //-->
                <div class="container">

                    <!--// OPEN #page-wrap //-->
                    <div id="page-wrap">