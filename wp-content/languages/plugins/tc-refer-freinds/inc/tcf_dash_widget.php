<?PHP

/*-----------------------------------------------------------------------------------*/
/*	Add Custom Dashboard Widget
/*-----------------------------------------------------------------------------------*/

function tcraf_dash_widget(){
	
	global $wp_meta_boxes;
	wp_add_dashboard_widget('tcraf_stats_widget', 'Refer A Friend Stats', 'tcraf_dash_widget_handler');
	
}

/*-----------------------------------------------------------------------------------*/
/*	Add CSS for Dash
/*-----------------------------------------------------------------------------------*/

function tcraf_dash_css(){
	?>
	<style type="text/css" media="screen">
		#toplevel_page_google-plus-tc-inc-gptc_settings div.wp-menu-image { background:transparent url("<?php echo GPTC_LOCATION.'/images/menu-icon.png'; ?>") no-repeat center center;}
		#toplevel_page_google-plus-tc-inc-gptc_settings:hover div.wp-menu-image,
		#toplevel_page_google-plus-tc-inc-gptc_settings.wp-has-current-submenu div.wp-menu-image
		{ background:transparent url("<?php echo GPTC_LOCATION.'/images/menu-icon-hover.png'; ?>") no-repeat center center; }
		#tcllpro_stats{
			font-family:Arial, Helvetica, sans-serif !important;
			font-size:13px !important;
			color:#333333 !important;
		}
		#tcllpro_stats li{
			background:url("<?php echo TCLLPRO_LOCATION.'/inc/mce.png'; ?>") 0px 1px no-repeat;
			line-height:22px;
			padding:0 0 15px 25px;
			border-bottom:1px solid #DFDFDF;
			margin-bottom:15px;
		}
		#tcllpro_stats li.last{
			margin-bottom:0px !important;
		}
		#tcllpro_stats li span.title{
			font-weight:bold;
		}
		#tcllpro_stats li span.time{
			color:#777777 !important;
			font-weight:bold;
		}
		
	</style>
	<?php
}

/*-----------------------------------------------------------------------------------*/
/*	Dash Widget Handler
/*-----------------------------------------------------------------------------------*/

function tcraf_dash_widget_handler(){
	
	// Start Class
	$tcraf = new TCRAFSTATS();
	$dashStats = $tcraf->dash_stats();
	$recentCoupons = $tcraf->recent_coupons();
	
	// Create Summary
	$summary = __('Your users have generated ', 'tcraf_locale').'<strong>'.$dashStats->hits_total.'</strong>'.__(' hits total from ', 'tcraf_locale').'<strong>'.$dashStats->visits_total.'</strong>'.__(' unique visitors, generating ', 'tcraf_locale').'<strong>'.$dashStats->refs_total.'</strong>'.__(' sales for your site totalling ', 'tcraf_locale').'$'.number_format($dashStats->sales_total, 2);
	
	// Output Summary
	echo '<h4>'.__('Refer A Friend Summary', 'tcraf_locale').'</h4>';
	echo '<p>'.$summary.'</p>';
	
	// Recent Referrals
	echo'
	<div class="tcraf-stats summary">
		<table class="wp-list-table widefat fixed" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" class="referrals">'.__('Recent Referrals', 'tcraf_locale').'</th>
					<th scope="col" class="referrals">'.__('Date', 'tcraf_locale').'</th>
				</tr>
			</thead>
			<tbody>
			'.$recentCoupons.'
			</tbody>
		</table>
	</div>
	';

	
}

/*-----------------------------------------------------------------------------------*/
/*	Hook Widget+CSS
/*-----------------------------------------------------------------------------------*/
add_action('wp_dashboard_setup', 'tcraf_dash_widget');
add_action('admin_head', 'tcraf_dash_css');

?>