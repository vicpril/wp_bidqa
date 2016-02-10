<?php
//---------------------------

function ProjectTheme_affiliates_myplugin_activate()
{
	global $wpdb;
	
	$ss = " CREATE TABLE `".$wpdb->prefix."project_affiliate_users` (
			`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`owner_id` INT,
			`affiliate_id` INT ,
			`datemade` BIGINT  
			
	) ENGINE = MYISAM ";	
	$wpdb->query($ss);
	
	$ss = " CREATE TABLE `".$wpdb->prefix."project_affiliate_payouts` (
			`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`pid` BIGINT,
			`uid` BIGINT ,
			`datemade` BIGINT,
			`paidon` BIGINT,
			`moneymade` VARCHAR( 255 ),
			`paid` TINYINT NOT NULL DEFAULT '0'
			  
			
	) ENGINE = MYISAM ";	
	$wpdb->query($ss);

	ProjectTheme_insert_pages('ProjectTheme_my_account_affiliates_id', 			'Affiliates', 				'[project_theme_my_account_affiliates]', 			get_option('ProjectTheme_my_account_page_id') );

}

?>