<?php
	if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }
	
	global $wp_query;
	$pid = $wp_query->query_vars['jobid'];

	function Pricerr_filter_ttl($title){ return __("Pay for your job", 'PricerrTheme')." - ".get_bloginfo('name');}
	add_filter( 'wp_title', 'Pricerr_filter_ttl', 10, 3 );

	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;

	$post 	= get_post($pid);
	$total 	= get_option('PricerrTheme_featured_fee');
	$crds 	= PricerrTheme_get_credits($uid);
	
	//---------------------------------
	
	if(isset($_GET['confirm'])):
		
		session_start();
	
		
		$datemade 	= current_time('timestamp',0);
		$nts		= '';
		$mc_gross	= $price;
		
		global $wpdb;
		$pref = $wpdb->prefix;
		
		$s1 = "select * from ".$pref."job_orders where pid='$pid' AND uid='$uid' AND date_made='$datemade'";
		$r1 = $wpdb->get_results($s1);
		
		if(count($r1) == 0)
		{
			
			$price = get_post_meta($pid, 'price', true);
			if(empty($price)) $price = get_option('PricerrTheme_job_fixed_amount');
			
			
			$extr_ttl = 0; $xtra_stuff = '';
			
			$xtra1 = 0;
			$xtra2 = 0;
			$xtra3 = 0;
			
			$xtra4 = 0;
			$xtra5 = 0;
			$xtra6 = 0;
			$xtra7 = 0;
			$xtra8 = 0;
			$xtra9 = 0;
			$xtra10 = 0;
			
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
						
						if(1 == $myitem) $xtra1 = 1;
						if(2 == $myitem) $xtra2 = 1;
						if(3 == $myitem) $xtra3 = 1;
						
						if(4 == $myitem) $xtra4 = 1;
						if(5 == $myitem) $xtra5 = 1;
						if(6 == $myitem) $xtra6 = 1;
						if(7 == $myitem) $xtra7 = 1;
						if(8 == $myitem) $xtra8 = 1;
						if(9 == $myitem) $xtra9 = 1;
						if(10 == $myitem) $xtra10 = 1;
						
						//echo $myitem." ";
											
					}
				}				
			}

			
			//----------------------------------------------------
			
			$shipping 	= get_post_meta($pid, 'shipping', 		true);						   		
			if(empty($shipping)) $shipping = 0;
			
			$ttotal = $shipping + $extr_ttl + $price;
			if($ttotal > $crds) { echo 'NO_CREDITS_LEFT'; exit; }
			
			//-----------------------------------------------------------
			
			$mc_gross = $ttotal;
				
			PricerrTheme_update_credits($uid , $crds - ( $price + $shipping + $extr_ttl  ));
			$reason = sprintf(__('Payment for job: %s','PricerrTheme'), '<a href="'.get_permalink($pid).'">'.$post->post_title.'</a>');
			PricerrTheme_add_history_log('0', $reason, ($price+ $shipping + $extr_ttl), $uid); 
				
			
			$s1 = "insert into ".$pref."job_orders (pid,uid,date_made, mc_gross, notes_to_seller, extra1, extra2, extra3, extra4, extra5, extra6, extra7, extra8, extra9, extra10) 
			values('$pid','$uid','$datemade','$mc_gross', '$nts','$xtra1','$xtra2','$xtra3','$xtra4','$xtra5','$xtra6','$xtra7','$xtra8','$xtra9','$xtra10')";
			$wpdb->query($s1);		

			//--------------
			
			$s1 = "select * from ".$pref."job_orders where pid='$pid' AND uid='$uid' AND date_made='$datemade'";
			$r1 = $wpdb->get_results($s1);
			$orderid = $r1[0]->id;
			
			//------------------------
			
			
			$g1 = "insert into ".$pref."job_chatbox (datemade, uid, oid, content) values('$datemade','0','$orderid','$ccc')";
			$wpdb->query($g1);
			
			//--------------
			
			$uid_a = get_post($pid);
			$uid_a = $uid_a->post_author;
			
			$s1 = "insert into ".$pref."job_ratings (orderid, uid, pid) values('$orderid','$uid_a','$pid')";
			$wpdb->query($s1);	
			
			$sales = get_post_meta($pid,'sales',true);
			if(empty($sales)) $sales = 1; else $sales = $sales + 1;
			
			update_post_meta($pid,'sales',$sales);
			
			//---------------
			// email to the owner of the job
			$post 	= get_post($pid);

			PricerrTheme_send_email_when_job_purchased_4_buyer($orderid, $pid, $uid, $post->post_author);
			PricerrTheme_send_email_when_job_purchased_4_seller($orderid, $pid, $post->post_author, $uid);			
			
			//---------------
			
			$instant = get_post_meta($pid,'instant',true);
			
			if($instant == "1")
			{
				$tm = current_time('timestamp',0);
				$s = "update ".$wpdb->prefix."job_orders set done_seller='1', date_finished='$tm' where id='$orderid' ";
				$wpdb->query($s);
				
				$ccc = __('Delivered','PricerrTheme');
				
				$g1 = "insert into ".$wpdb->prefix."job_chatbox (datemade, uid, oid, content) values('$tm','-1','$orderid','$ccc')";
				$wpdb->query($g1);	
				
				PricerrTheme_send_email_when_job_delivered($orderid, $pid, $uid);				
				
			}
			
			//--------------
			
			$admin_email 	= get_bloginfo('admin_email');
			$message = sprintf(__('A new job has been purchased on your site: <a href="%s">%s</a>', 'PricerrTheme'), 
			get_permalink($pid), $post->post_title);
					
			PricerrTheme_send_email($admin_email, sprintf(__('New Job Purchased on your site - %s', 'PricerrTheme'), $post->post_title), $message);	
		
		}
	
			wp_redirect(get_permalink(get_option('PricerrTheme_my_account_shopping_page_id')));
	
	endif;
	

	get_header();

?>


 <div id="content">
        	
            <div class="my_box3">
            <div class="padd10">
            
            	<div class="box_title"><?php echo __("Purchase with your balance", 'PricerrTheme'); ?></div>
            	<div class="box_content">
                
                <?php echo sprintf(__('You have %s in your balance. Click confirm now to pay using your balance.','PricerrTheme'),PricerrTheme_get_show_price($crds)); ?>
                <br/><br/>
                <?php echo '<a href="'.get_bloginfo('siteurl').'/?pay_for_item=credits&jobid='.$pid.'&confirm=1&extras='.$_GET['extras'].'">'.__('Confirm Purchase','PricerrTheme').'</a>'; ?>
                
                
                </div>
                </div>
                </div>
            
        
        </div>
         
         
         
         <div id="right-sidebar">
            <ul class="xoxo">
            <?php dynamic_sidebar( 'other-page-area' ); ?>
            </ul>
        </div>       
<?php



	get_footer();
	
?>