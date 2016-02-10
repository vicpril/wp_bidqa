<?php
				
if($_POST['status'] > -1)
{
		
		$c  	= $_POST['field1'];
		$c 		= explode('|',$c);
		
		$pid				= $c[0];
		$uid				= $c[1];
		$datemade 			= $c[2];		
		
		//---------------------------------------------------

			global $wpdb;
			$pref = $wpdb->prefix;
		
			update_post_meta($pid, 'paid', "1");
			update_post_meta($pid, 'featured', "1");
			

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
}
	
?>