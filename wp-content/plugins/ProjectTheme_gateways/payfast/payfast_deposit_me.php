<?php

function ProjectTheme_payfast_deposit_payment()
{
	
	global $wp_query, $wpdb, $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;


	$business = get_option('ProjectTheme_payfast_id');
	if(empty($business)) die('ERROR. Please input your PayFast ID.');
	//-------------------------------------------------------------------------
	
		
	
		$my_total = $_POST['amount'];

	//----------------------------------------------
	$additional_payfast = 0;
	$additional_payfast = apply_filters('ProjectTheme_filter_paypal_listing_additional', $additional_paypal, $pid);

	$total = $my_total + $additional_payfast;
	


	
	//---------------------------------
	
	$tm 			= current_time('timestamp',0);
	$cancel_url 	= get_bloginfo("siteurl").'/?p_action=payfast_deposit_response&uid='.$uid;
	$response_url 	= get_bloginfo('siteurl').'/?p_action=payfast_deposit_response';
	$ccnt_url		= get_permalink(get_option('ProjectTheme_my_account_page_id'));//get_bloginfo('siteurl').'/?p_action=edit_project&paid=ok&pid=' . $pid;
	$currency 		= get_option('ProjectTheme_currency');
	
	//https://www.payfast.co.za/eng/process
	//https://sandbox.payfast.co.za/eng/process
		
?>


<html>
<head><title>Processing PayFast Payment...</title></head>
<body onLoad="document.frmPay.submit();">
<center><h3><?php _e('Please wait, your order is being processed...', 'ProjectTheme'); ?></h3></center>

	
    <form action="https://www.payfast.co.za/eng/process" method="post" name="frmPay" id="frmPay">

        <!-- Receiver Details -->
        <input type="hidden" name="merchant_id" value="<?php echo get_option('ProjectTheme_payfast_id'); ?>">
        <input type="hidden" name="merchant_key" value="<?php echo get_option('ProjectTheme_payfast_key'); ?>">
        <input type="hidden" name="return_url" value="<?php echo $ccnt_url; ?>">
        <input type="hidden" name="cancel_url" value="<?php echo $cancel_url; ?>">
        <input type="hidden" name="notify_url" value="<?php echo $response_url; ?>">
        

        <!-- Transaction Details -->
        <input type="hidden" name="m_payment_id" value="<?php echo $uid.'_'.$tm; ?>">
        <input type="hidden" name="custom_str1" value="<?php echo $uid.'|'.$tm; ?>">
        
        
        
        <input type="hidden" name="amount" value="<?php echo $total; ?>">
        <input type="hidden" name="item_name" value="Deposit:"> 
        <input type="hidden" name="item_description" value="Credits">
 
        
        </form>
    
 

</body>
</html>	

<?php	
	
}

function ProjectTheme_payfast_deposit_response()
{
	
		$c  	= $_POST['custom_str1'];
		$c 		= explode('|',$c);
		
		$uid				= $c[0];
		$tm					= $c[1];
		
		//-------------------
		
			$mc_gross = $_POST['amount_net'];
			
			$cr = projectTheme_get_credits($uid);
			projectTheme_update_credits($uid,$mc_gross + $cr);
			
			update_option('ProjectTheme_deposit_'.$uid.$datemade, "1");
			$reason = __("Deposit through Payfast.","pt_gateways"); 
			projectTheme_add_history_log('1', $reason, $mc_gross, $uid);
		
			$user = get_userdata($uid);	
	
	
}


?>