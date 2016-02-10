<?php
	
	global $wp_query;
	$pid 	=  $wp_query->query_vars['jobid'];
	
	$pstn 			= get_option('PricerrTheme_pay_for_posting_job_page_id');
	$cancel_url 	= get_bloginfo("siteurl").'/?page_id='.$pstn.'&jobid='.$pid;
	$response_url 	= get_bloginfo('siteurl').'/?payment_response_listing=moneybookers';
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
<head><title>MoneyBookers</title></head>
<body onLoad="document.form_mb.submit();">
<center><h3><?php _e('Please wait, your order is being processed...','PricerrTheme'); ?></h3></center>

<form name="form_mb" action="https://www.moneybookers.com/app/payment.pl">
<input type="hidden" name="pay_to_email" value="<?php echo get_option('PricerrTheme_moneybookers_email'); ?>">
<input type="hidden" name="payment_methods" value="ACC,OBT,GIR,DID,SFT,ENT,EBT,SO2,IDL,PLI,NPY,EPY">

<input type="hidden" name="recipient_description" value="<?php bloginfo('name'); ?>">

<input type="hidden" name="cancel_url" value="<?php echo $cancel_url; ?>">
<input type="hidden" name="status_url" value="<?php echo $response_url; ?>">

<input type="hidden" name="language" value="EN">

<input type="hidden" name="merchant_fields" value="field1">
<input type="hidden" name="field1" value="<?php echo $pid.'|'.$uid.'|'.$tm; ?>">

<input type="hidden" name="amount" value="<?php echo $price; ?>">
<input type="hidden" name="currency" value="<?php echo $currency ?>">

<input type="hidden" name="detail1_description" value="Product: ">
<input type="hidden" name="detail1_text" value="<?php echo sprintf(__('Listing Fee %s','PricerrTheme'), $post->post_title); ?>">

<input type="hidden" name="return_url" value="<?php echo $ccnt_url; ?>">


</form>

</body>
</html>