<?php

function ProjectTheme_payfast_main_payment_response_payment()
{
	
		global $current_user;
		get_currentuserinfo();
		
		$uid = $current_user->ID;
		
		$cust 					= $_POST['custom_str1'];
		$cust 					= explode("|",$cust);
		$pid					= $cust[0];
		$datemade 				= $cust[1];
		
		
		update_post_meta($pid, 'paid_user',"1");
		update_post_meta($pid, "paid_user_date", $datemade);
		
	
}

?>