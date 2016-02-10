<?php


	
	function PricerrTheme_filter_ttl($title){return __("Pay for featured job",'PricerrTheme')." - ";}
	add_filter( 'wp_title', 'PricerrTheme_filter_ttl', 10, 3 );	
	
	if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }   
	
	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;
	
	//--------------------------------
	$pid 	= $_GET['jobid'];
	$post 	= get_post($pid);
	$jbnm 	= PricerrTheme_wrap_the_title($post->post_title, $pid);
	$prc 	= get_option('PricerrTheme_new_job_feat_listing_fee');
	if(get_post_meta($pid,'featured',true) != "1") $prc = 0;
	
	$PricerrTheme_new_job_listing_fee = get_option('PricerrTheme_new_job_listing_fee');
	$prc += $PricerrTheme_new_job_listing_fee;
	
	get_header();
	
	

	$cr = PricerrTheme_get_show_price(PricerrTheme_get_credits($uid));
	
	?>
	
    
    <div id="content">
    
    <div class="box_title"><?php echo sprintf(__('Pay for job: %s','PricerrTheme'), $jbnm); ?></div>
    <div class="box_content">
    
    <?php
	
		if(isset($_GET['confirm'])):
	
			echo sprintf(__('Your payment has been received. <a href="%s">Go back</a> to your account','PricerrTheme'), get_permalink(get_option('PricerrTheme_my_account_page_id')));
			
			
			$paid = get_post_meta($pid, 'paid', true);
			
			if($paid != "1"):
			
				global $wpdb;
				$pref = $wpdb->prefix;
			
				update_post_meta($pid, 'paid', "1");
				update_post_meta($pid, 'featured', 		"1");
				
				//----------------------------
				
				$crds = PricerrTheme_get_credits($uid);
				
				PricerrTheme_update_credits($uid , ($crds - $prc));
				$reason = sprintf(__('Payment for listing featured job: %s','PricerrTheme'), '<a href="'.get_permalink($pid).'">'.$post->post_title.'</a>');
				PricerrTheme_add_history_log('0', $reason, $prc, $uid); 
				
				//----------------------------
				
				$PricerrTheme_admin_approve_job = get_option('PricerrTheme_admin_approve_job');
				
				if($PricerrTheme_admin_approve_job == "no"):
				
					wp_publish_post($pid);
				
					$mypost = array();
					$mypost['ID'] 			= $pid;
					$mypost['post_status'] 	= 'publish';
					wp_update_post($mypost);
				endif;
			
			endif;
			
	?>
    
    
    <?php else: ?>
    
    <span class="skl_pay_feat">
    <?php echo sprintf(__('You are about to pay for the listing fee using your balance. <br/>The fee is <b>%s</b>. Your current credit amount is %s.','PricerrTheme'), PricerrTheme_get_show_price($prc), $cr); ?>
    </span>
    <br/><br/>
    
    
	<?php
	
	$using_permalinks = PricerrTheme_using_permalinks();
				
	if($using_permalinks) $bklnk = get_permalink(get_option('PricerrTheme_pay_for_posting_job_page_id'))."?jobid=".$pid;
	else $bklnk = get_bloginfo('siteurl')."/?page_id=".get_option('PricerrTheme_pay_for_posting_job_page_id')."&jobid=".$pid;
	
	//------------------------------------------------------------------------------------------------------------------------------
	
	echo '<a class="payment_feat" href="'.get_bloginfo('siteurl').'/?jb_action=pay_featured_credits&confirm=yes&jobid='.$pid.'">'. __('Confirm Payment','PricerrTheme').'</a> ';
	echo '<a class="payment_feat" href="'.$bklnk.'">'. __('Go Back','PricerrTheme').'</a> ';
	
	
	
	endif;
	
	?>
     
 
    </div>
    </div>
    
    
    <div id="right-sidebar">
    	<ul class="xoxo">
        	<?php dynamic_sidebar( 'other-page-area' ); ?>
        </ul>
    </div>
    
    
    <?php get_footer(); ?>