<?php

include 'deposit.php';
include 'listing.php';

add_filter('ProjectTheme_payment_methods_tabs', 					'ProjectTheme_add_new_ruj_tab');
add_filter('ProjectTheme_payment_methods_content_divs', 			'ProjectTheme_add_new_ruj_cnt');
add_filter('ProjectTheme_payment_methods_action',					'ProjectTheme_add_new_ruj_pst');
add_filter('template_redirect',					'projecthtme_ryuj_templ_redir');

add_filter('ProjectTheme_add_payment_options_to_post_new_project', 	'ProjectTheme_add_new_ruh_listing_btn');

function ProjectTheme_add_new_ruh_listing_btn($pid)
{

	$ProjectTheme_ruj_enable = get_option('ProjectTheme_ruj_enable');

	if($ProjectTheme_ruj_enable == "yes")
	echo '<a href="'.get_bloginfo('siteurl').'/?p_action=ruj_listing&pid='.$pid.'" class="edit_project_pay_cls">'.__('Pay by transferuj.pl','pt_gateways').'</a>';	
	
}


function projecthtme_ryuj_templ_redir()
{
	global $wp_query;
	$p_action 	=  $wp_query->query_vars['p_action'];	
	
	if($p_action == "ruj_listing")
	{
		
		ProjectTheme_ruj_listing_payment();
		die();	
	}	
	
	if($p_action == "ruj_pay")
	{
		
		ProjectTheme_ruj_deposit_payment();
		die();	
	}
	if(isset($_GET['notif_ruj_listing']))
	{
		if($_POST['tr_error'] == "none")
		{
			$tr_crc = $_POST['tr_crc'];
		 	$opt = get_option('order_listing_' . $tr_crc);
		
		
			 
		$c 		= explode('_',$opt);
		
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
	}
			//---------------------------
    		echo 'TRUE'; die();
		
	}
	
		
	if(isset($_GET['notif_ruj_deposit']))
	{
 
		if($_POST['tr_error'] == "none")
		{
			$tr_crc = $_POST['tr_crc'];
		 	$opt = get_option('order_dep_' . $tr_crc);
		 	$opt1 = get_option('order_dep_11' . $tr_crc);
 
			
			if(empty($opt1))
			{
				$cust 					= explode("_",$opt);
				$uid					= $cust[0];
				$datemade 				= $cust[1];
				
				update_option('order_dep_11' . $tr_crc,'done');
				$mc_gross = $_POST['tr_paid' ];
 
				
				$cr = projectTheme_get_credits($uid);
				projectTheme_update_credits($uid,$mc_gross + $cr);
				
				$reason = __("Deposit through Trasnferuj.pl.","ProjectTheme"); 
				projectTheme_add_history_log('1', $reason, $mc_gross, $uid);
			}
		}
		
		echo 'TRUE';
		die();
		
	}
	
}

function ProjectTheme_add_new_ruj_tab()
{
	?>
    
    	<li><a href="#tabs_ruj">transferuj.pl</a></li>
    
    <?php	
	
}


function ProjectTheme_add_new_ruj_pst()
{
	
	if(isset($_POST['ProjectTheme_save_ruj'])):
	
	$ProjectTheme_ruj_id 	= trim($_POST['ProjectTheme_ruj_id']);
	$ProjectTheme_ruj_enable = $_POST['ProjectTheme_ruj_enable'];
	$ProjectTheme_ruj_code = $_POST['ProjectTheme_ruj_code'];
	
	update_option('ProjectTheme_ruj_enable',	$ProjectTheme_ruj_enable);
	update_option('ProjectTheme_ruj_id',	$ProjectTheme_ruj_id);
	update_option('ProjectTheme_ruj_code',	$ProjectTheme_ruj_code);
	
	endif;
}


function ProjectTheme_add_new_ruj_cnt()
{
	$arr = array("yes" => __("Yes",'ProjectTheme'), "no" => __("No",'ProjectTheme'));
	
?>

<div id="tabs_ruj"  >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=payment-methods&active_tab=tabs_ruj">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','ProjectTheme'); ?></td>
                    <td><?php echo ProjectTheme_get_option_drop_down($arr, 'ProjectTheme_ruj_enable'); ?></td>
                    </tr>

                    
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Merchant ID:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_ruj_id" value="<?php echo get_option('ProjectTheme_ruj_id'); ?>"/></td>
                    </tr>
                    
                    
                      <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Security Code:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_ruj_code" value="<?php echo get_option('ProjectTheme_ruj_code'); ?>"/></td>
                    </tr>
                    
         
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="ProjectTheme_save_ruj" value="<?php _e('Save Options','ProjectTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          
          </div>

<?php	
	
}
?>