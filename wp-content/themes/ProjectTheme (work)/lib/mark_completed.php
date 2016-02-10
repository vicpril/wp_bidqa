<?php
if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }
//-----------

global $wp_query, $wpdb;
$orderid = $_GET['oid'];


		$mc = PricerrTheme_mark_completed($orderid);
		
		if($mc == 1) 
		{
			wp_redirect(get_permalink(get_option('PricerrTheme_my_account_reviews_page_id')));
			exit;	
		}
	
	echo "oops.error";
	
?>