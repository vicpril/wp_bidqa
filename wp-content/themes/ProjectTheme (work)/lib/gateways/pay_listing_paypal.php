<?php

	session_start();
	$busi = get_option('PricerrTheme_paypal_email');
	if(empty($busi)) { echo 'ERROR: please input your paypal address in backend'; exit; }

//--------------------------------------------------------------------------------

	global $current_user;
	get_currentuserinfo();
	$uid 	= $current_user->ID;
	$post 	= get_post($pid);

	$pid 	= $_GET['jobid'];
	$action = $_GET['action'];

	include 'paypal.class.php';

	//if($current_user->ID == $post->post_author) { echo 'DEBUG_INFO: You cannot buy your own stuff.'; exit; }




	$p = new paypal_class;             // initiate an instance of the class
	$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';   // testing paypal url


	$sdb = get_option('PricerrTheme_paypal_enable_sdbx');

	if($sdb == "yes")
	$p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';     // paypal url

	global $wpdb;
	$this_script = get_bloginfo('siteurl').'/?jb_action=pay_featured&method=paypal&jobid='.$pid;

	if(empty($action)) $action = 'process';   

	global $current_user;
	get_currentuserinfo();
	
	$uid = $current_user->ID;
	$post = get_post($pid);
	
	switch ($action) {

    

   case 'process':      // Process and order...

			$prc = get_option('PricerrTheme_new_job_feat_listing_fee');
			$price = $prc;
			
			if(get_post_meta($pid,'featured',true) != "1") $price = 0;
			
			$PricerrTheme_new_job_listing_fee = get_option('PricerrTheme_new_job_listing_fee');
			$price += $PricerrTheme_new_job_listing_fee;
				
			$job_title = get_post_meta($pid, 'job_title', true);
			if(empty($job_title)) $job_title = $post->post_title;
				
			//---------------------------------------------------
				
			  //$p->add_field('business', 'sitemile@sitemile.com');
			  $p->add_field('business', trim($busi));
			  $p->add_field('currency_code', get_option('PricerrTheme_currency'));
			  $p->add_field('return', $this_script.'&action=success');
			  $p->add_field('cancel_return', $this_script.'&action=cancel');
			  $p->add_field('notify_url', get_bloginfo('siteurl').'/?payment_response_listing=paypal');
			  $p->add_field('item_name', sprintf(__("Listing Fee: %s",'PricerrTheme'),$job_title));
			  $p->add_field('custom', $pid.'|'.$uid.'|'.$tm);
			  $p->add_field('amount', PricerrTheme_formats_special($price,2));
		
			  $p->submit_paypal_post(); // submit the fields to paypal
		    
		
      break;

   case 'success':      // Order was successful...
	

	wp_redirect(get_permalink(get_option('PricerrTheme_my_account_page_id')));
	
	
	break;
	

   case 'cancel':       // Order was canceled...
	
	$pstn = get_option('PricerrTheme_pay_for_posting_job_page_id');
	wp_redirect(get_bloginfo("siteurl").'/?page_id='.$pstn.'&jobid='.$pid);


       break;
}
     ?>