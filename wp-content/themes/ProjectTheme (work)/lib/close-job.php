<?php
//-----------
	
	if(!is_user_logged_in()) { wp_redirect(PricerrTheme_login_url()); exit; }

//-----------

	global $current_user;
	get_currentuserinfo;
	
	global $wp_query, $wpdb;
	$orderid = $wp_query->query_vars['orderid'];

	$s 		= "select distinct * from ".$wpdb->prefix."job_orders where id='$orderid'";
	$r 		= $wpdb->get_results($s);
	$row 	= $r[0];
	$post 	= get_post($row->pid);
	
	if($row->uid == $current_user->ID)
	{
		$tm = time();
		$s = "update ".$wpdb->prefix."job_orders set done_buyer='1', closed='1' where id='$orderid' ";
		$wpdb->query($s);
		
		//------------------
		
		$raw_amount = $row->mc_gross;
			
		$current_cash = PricerrTheme_get_credits($current_user->ID);
		PricerrTheme_update_credits($current_user->ID, $current_cash + $raw_amount);
			
		$reason = sprintf(__('Amount refunded for cancelled job: <a href="%s">%s</a>','PricerrTheme'), get_permalink($post->ID), $post->post_title);
		PricerrTheme_add_history_log('1', $reason, $raw_amount, $current_user->ID);
		
		//------------------
		
		$using_perm = PricerrTheme_using_permalinks();
	
		if($using_perm)	$shp_pg_lnk = get_permalink(get_option('PricerrTheme_my_account_shopping_page_id')). "/?";
		else $shp_pg_lnk = get_bloginfo('siteurl'). "/?page_id=". get_option('PricerrTheme_my_account_shopping_page_id'). "&";	
		
		
		PricerrTheme_send_email_when_buyer_closes_the_job($orderid, $row->pid, $post->post_author, $current_user->ID);
		
		wp_redirect($shp_pg_lnk . "pg=cancelled");
		exit;
	}
	
	
	echo "oops.error";
	
?>