<?php

	/*
	*
	*	Swift Page Builder - Includes Class
	*	------------------------------------------------
	*	Swift Framework
	* 	Copyright Swift Ideas 2013 - http://www.swiftideas.net
	*
	*/
	
	/* DEFINITIONS
	================================================== */ 
	$lib_dir = $spb_settings['SPB_BUILDER_LIB'];
	$shortcodes_dir = $spb_settings['SPB_BUILDER_SHORTCODES'];
	
	
	/* INCLUDE LIB FILES
	================================================== */ 
	require_once( $lib_dir . 'abstract.php' );
	require_once( $lib_dir . 'helpers.php' );
	require_once( $lib_dir . 'mapper.php' );
	require_once( $lib_dir . 'shortcodes.php' );
	require_once( $lib_dir . 'builder.php' );
	require_once( $lib_dir . 'media_tab.php' );
	require_once( $lib_dir . 'layouts.php' );	
	
	
	/* INCLUDE SHORTCODE FILES
	================================================== */ 
	require_once( $shortcodes_dir . 'default.php' );
	require_once( $shortcodes_dir . 'column.php' );
	require_once( $shortcodes_dir . 'accordion.php' );
	require_once( $shortcodes_dir . 'tabs.php' );
	require_once( $shortcodes_dir . 'tour.php' );
	require_once( $shortcodes_dir . 'buttons.php' );
	require_once( $shortcodes_dir . 'media.php' );
	require_once( $shortcodes_dir . 'raw_content.php' );
	require_once( $shortcodes_dir . 'portfolio.php' );
	require_once( $shortcodes_dir . 'blog.php' );
	
	if ( sf_woocommerce_activated() ) {
	require_once( $shortcodes_dir . 'products.php' );
	}
	
	require_once( $shortcodes_dir . 'clients.php' );
	require_once( $shortcodes_dir . 'full-width-text.php' );
	require_once( $shortcodes_dir . 'team.php' );
	require_once( $shortcodes_dir . 'jobs.php' );
	require_once( $shortcodes_dir . 'testimonial.php' );
	require_once( $shortcodes_dir . 'testimonial-carousel.php' );
	require_once( $shortcodes_dir . 'testimonial-slider.php' );
	require_once( $shortcodes_dir . 'faqs.php' );
	require_once( $shortcodes_dir . 'revslider.php' );
	require_once( $shortcodes_dir . 'recent-posts.php' );
	require_once( $shortcodes_dir . 'parallax.php' );
	require_once( $shortcodes_dir . 'portfolio-carousel.php' );
	require_once( $shortcodes_dir . 'posts-carousel.php' );
	require_once( $shortcodes_dir . 'team-carousel.php' );
	require_once( $shortcodes_dir . 'jobs-overview.php' );
	require_once( $shortcodes_dir . 'code-snippet.php' );
	require_once( $shortcodes_dir . 'googlechart.php' );
	require_once( $shortcodes_dir . 'sitemap.php' );
	require_once( $shortcodes_dir . 'search.php' );
	require_once( $shortcodes_dir . 'latest-tweets.php' );	
	require_once( $shortcodes_dir . 'tweets-slider.php' );	
?>