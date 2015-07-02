<?PHP

/*-----------------------------------------------------------------------------------*/
/*	Analytics Class
/*-----------------------------------------------------------------------------------*/

class TCRAFSTATS {
	
/*-----------------------------------------------------------------------------------*/
/*	Stats for Affiliate Shortcode Page
/*-----------------------------------------------------------------------------------*/

	function aff_stats($aff_id){

		global $wpdb;
		$tc_table = $wpdb->prefix."tc_refer_friends";
		
		// Get Visits Total
		$total_v = $wpdb->get_results("SELECT * FROM $tc_table WHERE aff_id = '$aff_id' GROUP BY ip");
		$this->visits_total = $wpdb->num_rows;
		
		// Get Hits Total
		$total_h = $wpdb->get_results("SELECT * FROM $tc_table WHERE aff_id = '$aff_id'");
		$this->hits_total = $wpdb->num_rows;
		
		// Get Referrals And Ratio
		$total_refs = $wpdb->get_results("SELECT * FROM $tc_table WHERE aff_id = '$aff_id' AND status = 'complete'");
		if( $wpdb->num_rows <= 0 ){
			$this->refs_total = 0;
			$this->ratio = 'N / A';
		} else {
			$this->refs_total = $wpdb->num_rows;
			$this->ratio = number_format( ($this->refs_total / $this->hits_total), 4 ) . ' %';
		}

		// Get cart Min not met
		$total_min = $wpdb->get_results("SELECT * FROM $tc_table WHERE aff_id = '$aff_id' AND status = 'cartmin'");
		$this->min_total = $wpdb->num_rows;
				
		// Return Object
		return $this;		
														
	} // end add_stats
	
/*-----------------------------------------------------------------------------------*/
/*	Get Coupons for Affiliate Shortcode Page
/*-----------------------------------------------------------------------------------*/

	function aff_coupons($aff_id){
		
		// Setup
		global $wpdb;
		$tc_table = $wpdb->prefix."tc_refer_friends";
		$output = '';
		
		// Get Rows
		$rows = $wpdb->get_results("SELECT * FROM $tc_table WHERE aff_id = '$aff_id' AND status = 'complete' AND coupon_id != ''");
		
		// For Each Row
		foreach($rows as $result){
						
			// Check Coupon
			$post_data = get_post($result->coupon_id, OBJECT);
			$post_meta = get_post_meta($result->coupon_id);
			$order_data = get_post($result->order_id, OBJECT);			
			
			// If Not Used, Show It!
			if( !isset( $post_meta['usage_count'][0] ) ){
						
				// Create Description
				if( $post_meta['discount_type'][0] == 'fixed_cart' ){
					$discount = '$'.$post_meta['coupon_amount'][0];
				} else if( $post_meta['discount_type'][0] == 'percent' ){
					$discount = $post_meta['coupon_amount'][0].'%';
				}
				$thisString = $discount.' '.__('Off Cart Total for Referring Sale', 'tcraf_locale');
				
				// Add User to Tracking If Found
				$user_data = get_userdata($order_data->post_author);
				if( $user_data->data->display_name ){
					$thisString.= ' From '.$user_data->data->display_name;
				}
							
				// Create Row
				$output.='
						<tr>
							<td class="details">'.$thisString.'</td>
							<td class="code">'.$post_data->post_title.'</td>
						</tr>
				';
				
			} // end usage check
						
		} // end for each
		
		return $output;
		
	} // endd aff_coupons
	
/*-----------------------------------------------------------------------------------*/
/*	Dash widget stats
/*-----------------------------------------------------------------------------------*/

	function dash_stats(){

		global $wpdb;
		$tc_table = $wpdb->prefix."tc_refer_friends";
		
		// Get Visits Total
		$total_v = $wpdb->get_results("SELECT * FROM $tc_table GROUP BY ip");
		$this->visits_total = $wpdb->num_rows;
		
		// Get Hits Total
		$total_h = $wpdb->get_results("SELECT * FROM $tc_table WHERE aff_id != '' AND ip != ''");
		$this->hits_total = $wpdb->num_rows;
		
		// Get Referrals And Ratio
		$total_refs = $wpdb->get_results("SELECT * FROM $tc_table WHERE status = 'complete' OR status = 'cartmin'");
		if( $wpdb->num_rows <= 0 ){
			$this->refs_total = 0;
		} else {
			$this->refs_total = $wpdb->num_rows;
		}
		
		// Get Sales Total
		$rows = $wpdb->get_results("SELECT * FROM $tc_table WHERE status = 'complete' AND coupon_id != ''");
		$sales = '0.00';
		
		// For Each Row
		foreach($rows as $result){
						
			// Check Coupon
			$order_data = get_post_meta($result->order_id);
			$sales = $sales + $order_data['_order_total'][0];
			
		} // end for each
		$this->sales_total = $sales;

		// Return Object
		return $this;		
														
	} // end dash_stats
	
/*-----------------------------------------------------------------------------------*/
/*	Get recent referals for dash widget
/*-----------------------------------------------------------------------------------*/

	function recent_coupons(){
		
		// Setup
		global $wpdb;
		$tc_table = $wpdb->prefix."tc_refer_friends";
		$output = '';
		
		// Get Rows
		$rows = $wpdb->get_results("SELECT * FROM $tc_table WHERE aff_id != '' AND order_id != ''");
		
		// Zero checl
		if( count( $rows ) < 1 ){
			
			$output.='
				<tr>
					<td class="referrals">'.__('No recent referrals to show!', 'tcraf_locale').'</td>
					<td class="date"></td>
				</tr>
			';
		
		} else {
		
			// For Each Row
			foreach($rows as $result){
							
				// Coupon Setup
				$aff_data = get_userdata($result->aff_id);
				$order_data = get_post($result->order_id, OBJECT);
				$order_meta = get_post_meta($result->order_id);
				$saleTotal = '$' . number_format( $order_meta['_order_total'][0], 0 );
				$added = date( "m / d / y  - g:i A", strtotime($result->added) );
		
				// Get User From Order and Continue
				$user_data = get_userdata($order_data->post_author);
				if( $user_data->data->display_name ){
					$string = '<strong>'.$aff_data->data->display_name.'</strong>'.__(' referred ', 'tcraf_locale').'<strong>'.$user_data->data->display_name.'</strong>'.__(' for a ', 'tcraf_locale').'<strong>'.$saleTotal.'</strong>'.__(' sale!', 'tcraf_locale');
				} else {
					$string = '<strong>'.$aff_data->data->display_name.'</strong>'.__(' referred a ', 'tcraf_locale').'<strong>'.$saleTotal.'</strong>'.__(' sale!', 'tcraf_locale');
				}
							
				// Create Row
				$output.='
					<tr>
						<td class="referrals">'.$string.'</td>
						<td class="date">'.$added.'</td>
					</tr>
				';
							
			} // end for each
			
		} // end row check
			
		return $output;
		
	} // endd recent_coupons
	
/*-----------------------------------------------------------------------------------*/
/*	End Class
/*-----------------------------------------------------------------------------------*/
	
} // end class

?>