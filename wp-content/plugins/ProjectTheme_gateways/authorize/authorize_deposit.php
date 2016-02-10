<?php
function ProjectTheme_authorize_deposit_payment()
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
	$cancel_url 	= get_bloginfo("siteurl").'/?p_action=authorize_deposit_response';
	$response_url 	= get_bloginfo('siteurl').'/?p_action=authorize_deposit_response';
	$ccnt_url		= get_permalink(get_option('ProjectTheme_my_account_page_id'));//get_bloginfo('siteurl').'/?p_action=edit_project&paid=ok&pid=' . $pid;
	$currency 		= get_option('ProjectTheme_currency');
 
	
	
	$loginID        = get_option('ProjectTheme_authorize_id');
$transactionKey = get_option('ProjectTheme_authorize_key');
$amount         = $_POST['amount'];
$description    = "Deposit Money";
$label          = "Submit Payment"; // The is the label on the 'submit' button
$testMode       = "false";
// By default, this sample code is designed to post to our test server for
// developer accounts: https://test.authorize.net/gateway/transact.dll
// for real accounts (even in test mode), please make sure that you are
// posting to: https://secure.authorize.net/gateway/transact.dll
$url            = "https://test.authorize.net/gateway/transact.dll";
 
// If an amount or description were posted to this page, the defaults are overidden
$amount         = isset($_REQUEST["amount"]) ? $_REQUEST["amount"] : $amount;
$description    = isset($_REQUEST["description"]) ? $_REQUEST["description"] : $description;
 
// an invoice is generated using the date and time
$invoice       = date("YmdHis");
// a sequence number is randomly generated
$sequence      = rand(1, 1000);
// a timestamp is generated
$timeStamp     = time ();
 
// The following lines generate the SIM fingerprint.  PHP versions 5.1.2 and
// newer have the necessary hmac function built in.  For older versions, it
// will try to use the mhash library.
if( phpversion() >= '5.1.2' )
{    $fingerprint = hash_hmac("md5", $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount .
                              "^", $transactionKey); }
else
{ $fingerprint = bin2hex(mhash(MHASH_MD5, $loginID . "^" . $sequence . "^" . $timeStamp . "^" .
                               $amount . "^", $transactionKey)); }
 
 
echo '<body onLoad="document.frmPay.submit();">';
 
// Create the HTML form containing necessary SIM post values
echo "<FORM method='post' name='frmPay' action='$url' >";
// Additional fields can be added here as outlined in the SIM integration guide
// at: http://developer.authorize.net
echo "    <INPUT type='hidden' name='x_login' value='$loginID' />";
echo "    <INPUT type='hidden' name='x_amount' value='$amount' />";
echo "    <INPUT type='hidden' name='x_description' value='$description' />";
echo "    <INPUT type='hidden' name='x_invoice_num' value='$invoice' />";
echo "    <INPUT type='hidden' name='x_fp_sequence' value='$sequence' />";
echo "    <INPUT type='hidden' name='x_fp_timestamp' value='$timeStamp' />";
echo "    <INPUT type='hidden' name='x_fp_hash' value='$fingerprint' />";
echo "    <INPUT type='hidden' name='x_test_request' value='$testMode' />";
echo "    <INPUT type='hidden' name='x_show_form' value='PAYMENT_FORM' />";
 
echo "</FORM>";

 

	
	
}

?>