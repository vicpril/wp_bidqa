<?php
if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }
//-----------

global $current_user;
get_currentuserinfo();

global $wp_query, $wpdb;
$orderid = $_GET['oid'];


	$s = "select distinct * from ".$wpdb->prefix."job_orders where id='$orderid'";
	$r = $wpdb->get_results($s);
	$row = $r[0];
	$post = get_post($row->pid);
	$uid_to_send = $row->uid;
	
	if($post->post_author == $current_user->ID) 
	{
		$tm = current_time('timestamp',0);
		$s = "UPDATE ".$wpdb->prefix."job_orders set done_seller='1', date_finished='$tm' where id='$orderid' ";
		$wpdb->query($s);
		
		$g1 = "INSERT into ".$wpdb->prefix."job_chatbox (datemade, uid, oid, content) values('$tm','-1','$orderid','$ccc')";
		$wpdb->query($g1);
		
		
		//-------------------------------------------------
			/*
			$user_daata 		= get_userdata($uid_to_send);
			$message_subject 	= sprintf(__('Job marked delivered by seller: %s','PricerrTheme'), $post->post_title);
			$message_content 	= sprintf(__('The job <b>%s</b> was marked as delivered by the seller.<br/>
			To view status, and check your messages go to the <a href="%s">conversation page</a>, then check your shopping <a href="%s">activity</a>, 
			and mark the job completed.','PricerrTheme'), 
			$post->post_title, get_bloginfo('siteurl').'/my-account/chat-box/?oid='.$orderid, get_bloginfo('siteurl').'/my-account/shopping/?pg=pending');
			
		*/
			
		PricerrTheme_send_email_when_job_delivered($orderid, $row->pid, $uid_to_send);
			
		//-------------------------------------------------
		
		wp_redirect(get_permalink(get_option('PricerrTheme_my_account_sales_page_id'))."?pg=delivered");
		exit;
	}
	
	
	echo "oops.error";
	
?>