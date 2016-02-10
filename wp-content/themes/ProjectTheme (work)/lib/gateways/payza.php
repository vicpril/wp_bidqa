<?php
	
	global $wp_query;
	$pid 	=  $wp_query->query_vars['jobid'];
	global $current_user;
	get_currentuserinfo();
	
	$uid = $current_user->ID;
	
	
	$cancel_url 	= get_bloginfo("siteurl").'/?jb_action=purchase_this&jobid='.$pid;
	$response_url 	= get_bloginfo('siteurl').'/?payment_response=payza_response';
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
<head><title>Payza</title></head>
<body onLoad="document.form_yaza.submit();">
<center><h3><?php _e('Please wait, your order is being processed...','PricerrTheme'); ?></h3></center>

<form name="form_yaza" action="https://secure.payza.com/checkout" method="post" >
        <input name="ap_purchasetype" type="hidden" value="item-goods" >
        <input name="ap_merchant" type="hidden" value="<?php echo get_option('PricerrTheme_alertpay_email'); ?>" >
        <input name="ap_itemname" type="hidden" value="<?php echo $job_title; ?>" >
        <input name="ap_description" type="hidden" value="<?php echo $job_title; ?>" > 
        <input name="ap_cancelurl" type="hidden" value="<?php echo $cancel_url; ?>" >
        <input name="ap_returnurl" type="hidden" value="<?php echo $ccnt_url; ?>" >
        <input name="ap_alerturl" type="hidden" value="<?php echo $response_url; ?>" >
        <input name="ap_quantity" type="hidden" value="1" >
        <input name="ap_currency" type="hidden" value="<?php echo $currency; ?>" >
        <input name="ap_itemcode" type="hidden" value="<?php echo $pid."_".$tm; ?>" >
        <input name="ap_shippingcharges" type="hidden" value="0" >
       
        <input name="apc_1" type="hidden" value="<?php echo $pid.'|'.$uid.'|'.$tm.$xtra_stuff; ?>" >
        
        
        
        <input name="ap_amount" type="hidden" value="<?php echo ($extr_ttl + $price + $shipping); ?>" >

</form>

</body>
</html>