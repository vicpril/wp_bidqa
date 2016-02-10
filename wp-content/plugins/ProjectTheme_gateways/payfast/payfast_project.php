<?php

function ProjectTheme_payfast_main_payment_submit_payment()
{
	
	global $wp_query, $wpdb, $current_user;
	$pid = $wp_query->query_vars['pid'];
	get_currentuserinfo();
	$uid = $current_user->ID;
	$post = get_post($pid);

	$business = get_option('ProjectTheme_payfast_id');
	if(empty($business)) die('ERROR. Please input your PayFast ID.');
	//-------------------------------------------------------------------------
	
		
		$bid = projectTheme_get_winner_bid($pid);	
		$my_total = $bid->bid;

	//----------------------------------------------
	$additional_payfast = 0;
	$additional_payfast = apply_filters('ProjectTheme_filter_paypal_listing_additional', $additional_paypal, $pid);

	$total = $my_total + $additional_payfast;
	
	$title_post = $post->post_title;
	$title_post = apply_filters('ProjectTheme_filter_paypal_listing_title', $title_post, $pid);
	
	//---------------------------------
	
	$tm 			= current_time('timestamp',0);
	$cancel_url 	= get_bloginfo("siteurl").'/?p_action=payfast_project_response&pid='.$pid;
	$response_url 	= get_bloginfo('siteurl').'/?p_action=payfast_project_response';
	$ccnt_url		= get_permalink(get_option('ProjectTheme_my_account_page_id'));//get_bloginfo('siteurl').'/?p_action=edit_project&paid=ok&pid=' . $pid;
	$currency 		= get_option('ProjectTheme_currency');
	
	//https://www.payfast.co.za/eng/process
		
?>


<html>
<head><title>Processing PayFast Payment...</title></head>
<body onLoad="document.frmPay.submit();">
<center><h3><?php _e('Please wait, your order is being processed...', 'pt_gateways'); ?></h3></center>

	
    <form action="https://www.payfast.co.za/eng/process" method="post" name="frmPay" id="frmPay">

        <!-- Receiver Details -->
        <input type="hidden" name="merchant_id" value="<?php echo get_option('ProjectTheme_payfast_id'); ?>">
        <input type="hidden" name="merchant_key" value="<?php echo get_option('ProjectTheme_payfast_key'); ?>">
        <input type="hidden" name="return_url" value="<?php echo $ccnt_url; ?>">
        <input type="hidden" name="cancel_url" value="<?php echo $cancel_url; ?>">
        <input type="hidden" name="notify_url" value="<?php echo $response_url; ?>">
        

        <!-- Transaction Details -->
        <input type="hidden" name="m_payment_id" value="<?php echo $pid.'_'.$tm; ?>">
        <input type="hidden" name="custom_str1" value="<?php echo $pid.'|'.$tm; ?>">
        
        
        
        <input type="hidden" name="amount" value="<?php echo $total; ?>">
        <input type="hidden" name="item_name" value="Project:">
        <input type="hidden" name="item_description" value="<?php echo $title_post; ?>">
 
        
        </form>
    
 

</body>
</html>

<?php	
	
}


?>