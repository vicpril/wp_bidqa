<?php


if(isset($_POST['custom']))
{
		
		$cust 				= $_POST['custom'];
		$cust 				= explode("|",$cust);
		
		$pid				= $cust[0];
		$uid				= $cust[1];
		$datemade 			= $cust[2];		
		
		//---------------------------------------------------

		$payment_status = $_POST['payment_status'];
		
		if($payment_status == "Completed"):
			

			global $wpdb;
			$pref = $wpdb->prefix;
		
			update_post_meta($pid, 'paid', "1");
			update_post_meta($pid, 'featured', 		"1");
			
			//----------------------------
			
			$PricerrTheme_admin_approve_job = get_option('PricerrTheme_admin_approve_job');
			
			if($PricerrTheme_admin_approve_job == "no"):
			
				wp_publish_post($pid);
			
				$mypost = array();
				$mypost['ID'] 			= $pid;
				$mypost['post_status'] 	= 'publish';
				wp_update_post($mypost);
			endif;
			
			//---------------------------
			
		endif;	
}
	
?>