<?php
	
	global $wp_query;
	$pid 	=  $wp_query->query_vars['jobid'];
	
	$pstn 			= get_option('PricerrTheme_pay_for_posting_job_page_id');
	$cancel_url 	= get_bloginfo("siteurl").'/?page_id='.$pstn.'&jobid='.$pid;
	$response_url 	= get_bloginfo('siteurl').'/?payment_response_listing=payza';
	$ccnt_url		= get_permalink(get_option('PricerrTheme_my_account_page_id'));
	
	//----------------------------------------------------------------------------------
	
	$price 			= PricerrTheme_formats(get_option('PricerrTheme_new_job_feat_listing_fee'));
	if(get_post_meta($pid,'featured',true) != "1") $price = 0;
	
	$PricerrTheme_new_job_listing_fee = get_option('PricerrTheme_new_job_listing_fee');
	$price += $PricerrTheme_new_job_listing_fee;
	
	$currency 		= get_option('PricerrTheme_currency');
	$tm 			= current_time('timestamp',0);
	$post 			= get_post($pid);
?>

<html>
<head><title>Payza</title></head>
<body onLoad="document.form_yaza.submit();">
<center><h3><?php _e('Please wait, your order is being processed...','PricerrTheme'); ?></h3></center>

<form name="form_yaza" action="https://secure.payza.com/checkout" method="post" >
        <input name="ap_purchasetype" type="hidden" value="item-goods" >
        <input name="ap_merchant" type="hidden" value="<?php echo get_option('PricerrTheme_alertpay_email'); ?>" >
        <input name="ap_itemname" type="hidden" value="<?php echo sprintf(__('Listing Fee %s','PricerrTheme'), $post->post_title); ?>" >
        <input name="ap_description" type="hidden" value="<?php echo sprintf(__('Listing Fee %s','PricerrTheme'), $post->post_title); ?>" > 
        <input name="ap_cancelurl" type="hidden" value="<?php echo $cancel_url; ?>" >
        <input name="ap_returnurl" type="hidden" value="<?php echo $ccnt_url; ?>" >
        <input name="ap_alerturl" type="hidden" value="<?php echo $response_url; ?>" >
        <input name="ap_quantity" type="hidden" value="1" >
        <input name="ap_currency" type="hidden" value="<?php echo $currency; ?>" >
        <input name="ap_itemcode" type="hidden" value="<?php echo $pid."_".$tm; ?>" >
        <input name="ap_shippingcharges" type="hidden" value="0" >
       
        <input name="apc_1" type="hidden" value="<?php echo $pid.'|'.$uid.'|'.$tm; ?>" >
        
        
        
        <input name="ap_amount" type="hidden" value="<?php echo $price; ?>" >

</form>

</body>
</html>