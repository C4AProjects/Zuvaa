<?php
	
	/*
	*
	*	Swift Framework Main Class
	*	------------------------------------------------
	*	Swift Framework v2.0
	* 	Copyright Swift Ideas 2013 - http://www.swiftideas.net
	*
	*/
	
	
	/* SWIFT PAGE BUILDER
	================================================== */  
	include(SF_FRAMEWORK_PATH . '/page-builder/sf-page-builder.php');
	
	
	/* META BOX FRAMEWORK
	================================================== */  
	include(SF_FRAMEWORK_PATH . '/meta-box/meta-box.php');
	include(SF_FRAMEWORK_PATH . '/meta-boxes.php');
	
	
	/* THEME OPTIONS FRAMEWORK
	================================================== */  
	require_once (SF_FRAMEWORK_PATH . '/sf-options.php');
	require_once (SF_FRAMEWORK_PATH . '/sf-colour-scheme.php');
	
	
	/* CUSTOMISER OPTIONS
	================================================== */ 
	require_once (SF_FRAMEWORK_PATH . '/sf-customizer-options.php');
	
	
	/* CONTENT FUNCTIONS
	================================================== */  
	include(SF_FRAMEWORK_PATH . '/sf-content-display/sf-template-parts.php');
	include(SF_FRAMEWORK_PATH . '/sf-content-display/sf-header.php');
	include(SF_FRAMEWORK_PATH . '/sf-content-display/sf-blog.php');
	include(SF_FRAMEWORK_PATH . '/sf-content-display/sf-portfolio.php');
	include(SF_FRAMEWORK_PATH . '/sf-content-display/sf-products.php');
	include(SF_FRAMEWORK_PATH . '/sf-content-display/sf-post-formats.php');
	
	
	/* WOOCOMMERCE FILTERS/HOOKS
	================================================== */  
	include(SF_FRAMEWORK_PATH . '/sf-woocommerce.php');
	
	
	/* SHORTCODES
	================================================== */  
	include(SF_FRAMEWORK_PATH . '/shortcodes.php');
	
	
	/* CUSTOM STYLES
	================================================== */  
	include(SF_FRAMEWORK_PATH . '/sf-custom-styles.php');
	
	
	/* STYLESWITCHER
	================================================== */  
	include(SF_FRAMEWORK_PATH . '/sf-styleswitcher/sf-styleswitcher.php');
	
	
	/* THEME UPDATER FRAMEWORK
	================================================== */  
	require_once(SF_FRAMEWORK_PATH . '/wp-updates-theme.php');
	new WPUpdatesThemeUpdater_318( 'http://wp-updates.com/api/2/theme', basename(get_template_directory()));
	
?>