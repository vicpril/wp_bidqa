<?php

	if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl'). "/wp-login.php"); exit; }

//-----------------

	global $wpdb,$wp_rewrite,$wp_query, $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;


	function sitemile_filter_ttl($title){return __("Cancel Order",'PricerrTheme')." - ";}
	add_filter( 'wp_title', 'sitemile_filter_ttl', 10, 3 );	
	
	
	$orderid 			= $_POST['orderid'];
	$message_to_buyer 	= addslashes($_POST['message_to_buyer']);
	
	//-------------------------------
	
	$s = "select * from ".$wpdb->prefix."job_orders where id='$orderid'";
	$r = $wpdb->get_results($s);
	$row = $r[0]; 
	$pid = $row->pid;
	$post = get_post($pid);
	
	if(isset($_POST['confirm_cancellation']))
	{
		$tm = current_time('timestamp',0);
		$s = "update ".$wpdb->prefix."job_orders set message_to_buyer='$message_to_buyer', request_cancellation='1', date_request_cancellation='$tm' where id='$orderid'";
		$wpdb->query($s);
		
		//-------------------
		
		$refund_uid = $row->uid;
		
			$current_cash = PricerrTheme_get_credits($refund_uid);
			PricerrTheme_update_credits($refund_uid, $current_cash + $row->mc_gross);
			
			$reason = sprintf(__('Payment refunded for job: <a href="%s">%s</a>','PricerrTheme'), get_permalink($post->ID), $post->post_title);
			PricerrTheme_add_history_log('1', $reason, $row->mc_gross, $refund_uid);
		
		//--------------------
		
		wp_redirect(get_permalink(get_option('PricerrTheme_my_account_sales_page_id')));
		exit;	
	}
	
	
	if(isset($_POST['confirm_force_cancellation']))
	{
		$tm = current_time('timestamp',0);
		$s = "update ".$wpdb->prefix."job_orders set force_cancellation='1', closed='1', date_request_cancellation='$tm' where id='$orderid'";
		$wpdb->query($s);
		
		//-------------------
		
		$refund_uid = $row->uid;
		
			$current_cash = PricerrTheme_get_credits($refund_uid);
			PricerrTheme_update_credits($refund_uid, $current_cash + $row->mc_gross);
			
			$reason = sprintf(__('Payment refunded for job: <a href="%s">%s</a>','PricerrTheme'), get_permalink($post->ID), $post->post_title);
			PricerrTheme_add_history_log('1', $reason, $row->mc_gross, $refund_uid);
			
			//PricerrTheme_send_email_when_buyer_closes_the_job($orderid, $post->ID, $post->post_author, $refund_uid);
			PricerrTheme_send_email_when_buyer_closes_the_job($orderid, $post->ID, $refund_uid, $post->post_author);
			
		//--------------------
		
		wp_redirect(get_permalink(get_option('PricerrTheme_my_account_sales_page_id')));
		exit;	
	}
	
	
	//=====================================================
	
	
	$post = get_post($pid); 
	
	
get_header();
?>
<div id="content">

	
    		<div class="my_box3">
            <div class="padd10">
            
            	<div class="box_title"><?php echo sprintf(__("Request Order Cancellation #%s - %s",'PricerrTheme'), $orderid, PricerrTheme_wrap_the_title($post->post_title, $pid)); ?></div>
            	<div class="box_content">	
               <!-- ####### -->
                <?php
				
				
					if(isset($_POST['request_cancellation'])) //peace cancellation
					{
						$message_to_buyer 	= $_POST['message_to_buyer'];
						_e('You are about to request cancellation for this job. By using this option you are asking the buyer to cancel the order. 
						If he agrees with this, and cancels the order, the money gets refunded into his account and you will not get a bad review over it.','PricerrTheme');
						
						echo '<br/><br/>';
						_e('Your message to the buyer: ','PricerrTheme'); echo '<em>'.$message_to_buyer.'</em>';
						
						echo '<br/><br/>';
						
						_e('Click yes to confirm or your back browser button.','PricerrTheme');
						echo '<br/><br/>';
						
						echo '<form method="post">';
						echo '<input type="submit" value="'.__('Yes, Confirm Cancellation','PricerrTheme').'" name="confirm_cancellation" />';
						echo '<input type="hidden" value="'.$orderid.'" name="orderid" />';
						echo '<input type="hidden" value="'.$message_to_buyer.'" name="message_to_buyer" />';
						
						echo '</form>';
						
					}
					else
					{
						
						//$message_to_buyer 	= $_POST['message_to_buyer'];
						_e("You are about to request cancellation for this job. By using this option you are forcing cancelling this order. 
						The money gets refunded into the buyer's account, and you will get a bad review over the job.",'PricerrTheme');
						//
						//echo '<br/><br/>';
						//_e('Your message to the buyer: ','PricerrTheme'); echo '<em>'.$message_to_buyer.'</em>';
						
						echo '<br/><br/>';
						
						_e('Click yes to confirm or your back browser button.','PricerrTheme');
						echo '<br/><br/>';
						
						echo '<form method="post">';
						echo '<input type="submit" value="'.__('Yes, Confirm Cancellation','PricerrTheme').'" name="confirm_force_cancellation" />';
						echo '<input type="hidden" value="'.$orderid.'" name="orderid" />';
						
						
						echo '</form>';
						
						
					}
				
				
				
				
				
				?>
                
        
                
				<!-- ####### -->
                </div>
                
            </div>
            </div>
                
	</div>

<?php

	 


	get_footer();
	
?>
