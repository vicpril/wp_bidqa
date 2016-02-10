<?php
	
	global $wp_query;
	$pid 	=  $wp_query->query_vars['jobid'];
	global $current_user;
	get_currentuserinfo();
	
	$uid = $current_user->ID;
	
	
	$cancel_url 	= get_bloginfo("siteurl").'/?jb_action=purchase_this&jobid='.$pid;
	$response_url 	= get_bloginfo('siteurl').'/?payment_response=moneybookers_response';
	$ccnt_url		= get_permalink(get_option('PricerrTheme_my_account_shopping_page_id'));
	
	//----------------------------------------------------------------------------------

	$currency 		= get_option('PricerrTheme_currency');
	$tm 			= current_time('timestamp',0);
	$post 			= get_post($pid);
	
	$price = get_post_meta($pid, 'price', true);
	if(empty($price)) $price = get_option('PricerrTheme_job_fixed_amount');
	
	$job_title = get_post_meta($pid, 'job_title', true);
	if(empty($job_title)) $job_title = $post->post_title;

	
	//---------------------------------------------------
			
			$extr_ttl = 0; $xtra_stuff = '';
				
			$extras = $_GET['extras'];
			$extras = explode("|", $extras);
				
			if(count($extras))
			{
				foreach($extras as $myitem)
				{
					if(!empty($myitem))
					{
						$extra_price 	= get_post_meta($pid, 'extra'.$myitem.'_price', 		true);
						$extr_ttl += $extra_price;
						$xtra_stuff .= '|'. $myitem;					
					}
				}				
			}
			
			
			
				$shipping 	= get_post_meta($pid, 'shipping', 		true);						   		
				if(empty($shipping)) $shipping = 0;
			
	//----------------------------------------------------
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
<input type="hidden" name="field1" value="<?php echo $pid.'|'.$uid.'|'.$tm.$xtra_stuff; ?>">

<input type="hidden" name="amount" value="<?php echo PricerrTheme_formats(($price + $extr_ttl + $shipping),2); ?>">
<input type="hidden" name="currency" value="<?php echo $currency ?>">

<input type="hidden" name="detail1_description" value="Product: ">
<input type="hidden" name="detail1_text" value="<?php echo $job_title; ?>">

<input type="hidden" name="return_url" value="<?php echo $ccnt_url; ?>">


</form>

</body>
</html>