<?php

function ProjectTheme_payfast_main_listing_response_payment()
{
	

   	 	$c  	= $_POST['custom_str1'];
		$c 		= explode('|',$c);
		
		$pid				= $c[0];
		$uid				= $c[1];
		$datemade 			= $c[2];		
		
		//---------------------------------------------------

			global $wpdb;
			$pref = $wpdb->prefix;
		
			//--------------------------------------------
		
			update_post_meta($pid, "paid", 				"1");
			update_post_meta($pid, "paid_listing_date", current_time('timestamp',0));
			update_post_meta($pid, "closed", 			"0");
			
			//--------------------------------------------
			
			update_post_meta($pid, 'base_fee_paid', '1');
			
			$featured = get_post_meta($pid,'featured',true);	
			if($featured == "1") update_post_meta($pid, 'featured_paid', '1');
			
			$private_bids = get_post_meta($pid,'private_bids',true);	
			if($private_bids == "1") update_post_meta($pid, 'private_bids_paid', '1');
			 
			$hide_project = get_post_meta($pid,'hide_project',true);	
			if($hide_project == "1") update_post_meta($pid, 'hide_project_paid', '1');
			
			//--------------------------------------------
			do_action('ProjectTheme_moneybookers_listing_response', $pid);
			
			$projectTheme_admin_approves_each_project = get_option('projectTheme_admin_approves_each_project');
			
			if($projectTheme_admin_approves_each_project != "yes")
			{
				wp_publish_post( $pid );	
				$post_new_date = date('Y-m-d h:s',current_time('timestamp',0));  
				
				$post_info = array(  "ID" 	=> $pid,
				  "post_date" 				=> $post_new_date,
				  "post_date_gmt" 			=> $post_new_date,
				  "post_status" 			=> "publish"	);
				
				wp_update_post($post_info);
				
				ProjectTheme_send_email_posted_project_approved($pid);
				ProjectTheme_send_email_posted_project_approved_admin($pid);
			
			}
			else 
			{  
				
				ProjectTheme_send_email_posted_project_not_approved($pid);
				ProjectTheme_send_email_posted_project_not_approved_admin($pid);
					
				ProjectTheme_send_email_subscription($pid);	
				
			}
			
			//---------------------------
    
	
}

?>