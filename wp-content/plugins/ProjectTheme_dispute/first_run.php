<?php

function ProjectTheme_dispute_myplugin_activate()
{
	global $wpdb;
	
	$ss = " CREATE TABLE `".$wpdb->prefix."project_disputes` (
			`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`initiator` INT,
			`pid` BIGINT ,
			`datemade` BIGINT ,
			`solution` TINYINT NOT NULL DEFAULT '0',
			`winner` TINYINT NOT NULL DEFAULT '0',
			`closedon` BIGINT,
			`comment` TEXT NOT NULL ,
			`defendant` INT,
			
	) ENGINE = MYISAM ";	
	$wpdb->query($ss);

	//---------------------------

	ProjectTheme_insert_pages('ProjectTheme_my_account_disputes_id', 			'Disputes', 				'[project_theme_my_account_disputes]', 			get_option('ProjectTheme_my_account_page_id') );

}

?>