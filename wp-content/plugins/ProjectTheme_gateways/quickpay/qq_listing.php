<?php

function ProjectTheme_qq_main_listing_submit_respo()
{
	if($_POST['qpstat'] == '000')
	{	
		$md5 = $_POST['md5check'];
		if(!empty($md5))
		{
			$tranid = $_GET['tranid'];
			$gr = get_option('LST_QUICKPAY_' . $tranid, true);
			 
			
				//******************************
			 
				$c 		= explode('|',$gr);
				
				$pid					= $c[0];
				$uid					= $c[1];
				$datemade 				= $c[2];
			 
				
				//---------------------------------------------------

				global $wpdb;
				$pref = $wpdb->prefix;
			
				//--------------------------------------------
			
				update_post_meta($pid, "paid", 				"1");
				update_post_meta($pid, "paid_listing_date", current_time('timestamp',0));
				update_post_meta($pid, "closed", 			"0");
				
				//--------------------------------------------
				
				update_post_meta($pid, 'base_fee_paid', '1');
				
				$featured = get_post_meta($pid,'featured',true);	
				if($featured == "1") update_post_meta($pid, 'featured_paid', '1');
				
				$private_bids = get_post_meta($pid,'private_bids',true);	
				if($private_bids == "1") update_post_meta($pid, 'private_bids_paid', '1');
				 
				$hide_project = get_post_meta($pid,'hide_project',true);	
				if($hide_project == "1") update_post_meta($pid, 'hide_project_paid', '1');
				
				//--------------------------------------------
				do_action('ProjectTheme_moneybookers_listing_response', $pid);
				
				$projectTheme_admin_approves_each_project = get_option('projectTheme_admin_approves_each_project');
				
				if($projectTheme_admin_approves_each_project != "yes")
				{
					wp_publish_post( $pid );	
					$post_new_date = date('Y-m-d h:s',current_time('timestamp',0));  
					
					$post_info = array(  "ID" 	=> $pid,
					  "post_date" 				=> $post_new_date,
					  "post_date_gmt" 			=> $post_new_date,
					  "post_status" 			=> "publish"	);
					
					wp_update_post($post_info);
					
					ProjectTheme_send_email_posted_project_approved($pid);
					ProjectTheme_send_email_posted_project_approved_admin($pid);
				
				}
				else 
				{  
					
					ProjectTheme_send_email_posted_project_not_approved($pid);
					ProjectTheme_send_email_posted_project_not_approved_admin($pid);
						
					ProjectTheme_send_email_subscription($pid);	
					
				}
			
			//******************************
			
		}
	}		
	
}


function ProjectTheme_qq_main_listing_submit_payment()
{
	global $wp_query, $wpdb, $current_user;
	$pid = $wp_query->query_vars['pid'];
	get_currentuserinfo();
	$uid = $current_user->ID;
	$post = get_post($pid);

	$business = get_option('ProjectTheme_payfast_id');
	if(empty($business)) die('ERROR. Please input your Payfast ID.');
	//-------------------------------------------------------------------------
	
			$features_not_paid 	= array();		
			$payment_arr 		= array();
			$base_fee_paid 		= get_post_meta($pid, 'base_fee_paid', true);
			$base_fee 			= get_option('projectTheme_base_fee');
			
			///----custom fee check--------------
			
			$catid = ProjectTheme_get_project_primary_cat($pid);
			$ProjectTheme_get_images_cost_extra = ProjectTheme_get_images_cost_extra($pid);
			
			$custom_set = get_option('projectTheme_enable_custom_posting');
			if($custom_set == 'yes')
			{
				$base_fee = get_option('projectTheme_theme_custom_cat_'.$catid);
				if(empty($base_fee)) $base_fee = 0;		
			}
			
			//----------------------------------
			
			if($base_fee_paid != "1" && $base_fee > 0)
			{
				$new_feature_arr = array();
				$new_feature_arr[0] = __('Cost for base fee','ProjectTheme');
				$new_feature_arr[1] = $base_fee;	
				array_push($features_not_paid, $new_feature_arr);
				
				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'base_fee';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $base_fee;
				$my_small_arr['description'] 	= __('Base Fee','ProjectTheme');
				array_push($payment_arr, $my_small_arr);
				
			}
			
			//----------------------------------------------------------
			
			$my_small_arr = array();
			$my_small_arr['fee_code'] 		= 'extra_img';
			$my_small_arr['show_me'] 		= true;
			$my_small_arr['amount'] 		= $ProjectTheme_get_images_cost_extra;
			$my_small_arr['description'] 	= __('Extra Images Fee','ProjectTheme');
			array_push($payment_arr, $my_small_arr);
			
			
			//-------- Featured Project Check --------------------------
			
			
			$featured 		= get_post_meta($pid, 'featured', true);
			$featured_paid 	= get_post_meta($pid, 'featured_paid', true);
			$feat_charge 	= get_option('projectTheme_featured_fee');
			
			if($featured == "1" && $featured_paid != "1" && $feat_charge > 0)
			{
				$not_OK_to_just_publish = 1;
				
				$new_feature_arr = array();
				$new_feature_arr[0] = __('Cost to make project featured','ProjectTheme');
				$new_feature_arr[1] = $feat_charge;	
				array_push($features_not_paid, $new_feature_arr);
				
				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'feat_fee';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $feat_charge;
				$my_small_arr['description'] 	= __('Featured Fee','ProjectTheme');
				array_push($payment_arr, $my_small_arr);
			}
			
			//---------- Private Bids Check -----------------------------
			
			$private_bids 		= get_post_meta($pid, 'private_bids', true);
			$private_bids_paid 	= get_post_meta($pid, 'private_bids_paid', true);
			
			$projectTheme_sealed_bidding_fee = get_option('projectTheme_sealed_bidding_fee');
			if(!empty($projectTheme_sealed_bidding_fee))
			{
				$opt = get_post_meta($pid,'private_bids',true);
				if($opt == "0") $projectTheme_sealed_bidding_fee = 0;
			}
			
			
			if($private_bids == "1" && $private_bids_paid != "1" && $projectTheme_sealed_bidding_fee > 0)
			{
				$not_OK_to_just_publish = 1;	
				
				$new_feature_arr = array();
				$new_feature_arr[0] = __('Cost to add sealed bidding','ProjectTheme');
				$new_feature_arr[1] = $projectTheme_sealed_bidding_fee;	
				array_push($features_not_paid, $new_feature_arr);
				
				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'sealed_project';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $projectTheme_sealed_bidding_fee;
				$my_small_arr['description'] 	= __('Sealed Bidding Fee','ProjectTheme');
				array_push($payment_arr, $my_small_arr);
			}
			
			
			//---------- Hide Project Check -----------------------------
			
			$hide_project 		= get_post_meta($pid, 'hide_project', true);
			$hide_project_paid 	= get_post_meta($pid, 'hide_project_paid', true);
			
			$projectTheme_hide_project_fee = get_option('projectTheme_hide_project_fee');
			if(!empty($projectTheme_hide_project_fee))
			{
				$opt = get_post_meta($pid,'hide_project',true);
				if($opt == "0") $projectTheme_hide_project_fee = 0;
			}
			
			
			if($hide_project == "1" && $hide_project_paid != "1" && $projectTheme_hide_project_fee > 0)
			{
				$not_OK_to_just_publish = 1;
				
				$new_feature_arr = array();
				$new_feature_arr[0] = __('Cost to hide project from search engines','ProjectTheme');
				$new_feature_arr[1] = $projectTheme_hide_project_fee;	
				array_push($features_not_paid, $new_feature_arr);
				
				$my_small_arr = array();
				$my_small_arr['fee_code'] 		= 'hide_project';
				$my_small_arr['show_me'] 		= true;
				$my_small_arr['amount'] 		= $projectTheme_hide_project_fee;
				$my_small_arr['description'] 	= __('Hide Project From Search Engines Fee','ProjectTheme');
				array_push($payment_arr, $my_small_arr);	
			}
			
			//---------------------
			
			$payment_arr = apply_filters('ProjectTheme_filter_payment_array', $payment_arr, $pid);
		
						
			$my_total = 0;
			foreach($payment_arr as $payment_item):
				if($payment_item['amount'] > 0):
					$my_total += $payment_item['amount'];
				endif;
			endforeach;	


	//----------------------------------------------
	$additional_paypal = 0;
	$additional_paypal = apply_filters('ProjectTheme_filter_paypal_listing_additional', $additional_paypal, $pid);

	$total = $my_total + $additional_paypal;
	
	$title_post = $post->post_title;
	$title_post = apply_filters('ProjectTheme_filter_paypal_listing_title', $title_post, $pid);
	
	//---------------------------------
	
	$tm 			= current_time('timestamp',0);
	$cancel_url 	= get_bloginfo("siteurl").'/?p_action=qq_listing_response&pid='.$pid;
	$response_url 	= get_bloginfo('siteurl').'/?p_action=qq_listing_response';
	$ccnt_url		= get_permalink(get_option('ProjectTheme_my_account_page_id'));//get_bloginfo('siteurl').'/?p_action=edit_project&paid=ok&pid=' . $pid;
	$currency 		= get_option('ProjectTheme_currency');
	
	//https://www.payfast.co.za/eng/process
		
?>


<html>
<head><title>Processing Quickpay Payment...</title></head>
<body onLoad="document.frmPay.submit();">
<center><h3><?php _e('Please wait, your order is being processed...', 'ProjectTheme'); ?></h3></center>




     <?php
	
	$md5secret 		= get_option('ProjectTheme_qq_key');
	$merchant 		= get_option('ProjectTheme_qq_id');
 	$payurl 		= 'https://secure.quickpay.dk/form/'; 
	$amount 		= $total *100;
	$callbackurl 	= $response_url;
	$continueurl 	= $ccnt_url;
	$cancelurl 		= $ccnt_url;
	$ordernumber	= rand(0,999).time().$uid.$pid;
	$language		= 'en';
	$protocol		= '7';
	$testmode		= '0';
	$autocapture	= '0';
	$cardtypelock 	= 'creditcard';
	$msgtype 		= 'authorize';
	$callbackurl 	= get_bloginfo('siteurl').'/?p_action=qq_listing_response&tranid=' . $ordernumber;
	
	//---------------------
	
	update_option('LST_QUICKPAY_' . $ordernumber, $pid.'_'.$uid.'_'.$tm);
	
	$md5check = md5($protocol . $msgtype . $merchant . $language . $ordernumber . $amount . $currency . $continueurl . $cancelurl . $callbackurl . $autocapture . $cardtypelock . $testmode . $md5secret);
	
	echo "<form id=\"frmPay\" name=\"frmPay\" action=\"$payurl\" method=\"post\">
		<input type=\"hidden\" name=\"protocol\" value=\"$protocol\"/>
		<input type=\"hidden\" name=\"msgtype\" value=\"$msgtype\"/>
		<input type=\"hidden\" name=\"merchant\" value=\"$merchant\"/>
		<input type=\"hidden\" name=\"language\" value=\"$language\"/>
		<input type=\"hidden\" name=\"ordernumber\" value=\"$ordernumber\"/>
		<input type=\"hidden\" name=\"amount\" value=\"$amount\"/>
		<input type=\"hidden\" name=\"currency\" value=\"$currency\"/>
		<input type=\"hidden\" name=\"continueurl\" value=\"$continueurl\"/>
		<input type=\"hidden\" name=\"cancelurl\" value=\"$cancelurl\"/>
		<input type=\"hidden\" name=\"callbackurl\" value=\"$callbackurl\"/>
		<input type=\"hidden\" name=\"autocapture\" value=\"$autocapture\"/>
		<input type=\"hidden\" name=\"cardtypelock\" value=\"$cardtypelock\"/>
		<input type=\"hidden\" name=\"testmode\" value=\"$testmode\"/>
		<input type=\"hidden\" name=\"md5check\" value=\"$md5check\"/>
		
		</form>";
	
	?>
 	 

</body>
</html>


<?php 	
	
}

?>