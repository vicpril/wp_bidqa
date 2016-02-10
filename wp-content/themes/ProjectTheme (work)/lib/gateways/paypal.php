<?php

	global $wp_query;
	$pid 	= $wp_query->query_vars['jobid'];
	$action = $_GET['action'];

	global $current_user;
	get_currentuserinfo();
	$uid 	= $current_user->ID;
	$post 	= get_post($pid);
	
//--------------------------------------------------------------------------------	
	
	$busi = get_option('PricerrTheme_paypal_email');
	if(empty($busi)) { echo 'ERROR: please input your paypal address in backend'; exit; }

//--------------------------------------------------------------------------------

	include 'paypal.class.php';


	$p = new paypal_class;             // initiate an instance of the class
	$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';   // testing paypal url


	$sdb = get_option('PricerrTheme_paypal_enable_sdbx');

	if($sdb == "yes")
	$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';     // paypal url

	global $wpdb;
	$this_script = get_bloginfo('siteurl').'/?pay_for_item=paypal&jobid='.$pid;

	if(empty($action)) $action = 'process';   


	
	switch ($action) {

    

   case 'process':      // Process and order...
	
			if($current_user->ID == $post->post_author) { echo 'DEBUG_INFO: You cannot buy your own stuff.'; exit; }
			$tm = time();
			
			$nts = nl2br(strip_tags($_POST['notes_to_seller']));
			update_option("purchase_notes_".$tm.$uid, base64_encode($nts));
		
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
			
			//---------------------------------------------------
				
			  //$p->add_field('business', 'sitemile@sitemile.com');
			  $p->add_field('business', trim($busi));
			  $p->add_field('currency_code', get_option('PricerrTheme_currency'));
			  $p->add_field('return', $this_script.'&action=success');
			  $p->add_field('cancel_return', $this_script.'&action=cancel');
			  $p->add_field('notify_url', get_bloginfo('siteurl').'/?payment_response=paypal_response');
			  $p->add_field('item_name', $job_title);
			  $p->add_field('custom', $pid.'|'.$uid.'|'.$tm.$xtra_stuff);
			  $p->add_field('amount', PricerrTheme_formats_special(($price + $extr_ttl + $shipping),2));
		
			  $p->submit_paypal_post(); // submit the fields to paypal
		    
		
      break;

   case 'success':      // Order was successful...
	

	wp_redirect(get_permalink(get_option('PricerrTheme_my_account_shopping_page_id'))); //('siteurl')."/my-account/shopping/");
	
	
	break;
	

   case 'cancel':       // Order was canceled...
	
	wp_redirect(get_permalink($pid));


       break;
}
     ?>