<?php
/*
Plugin Name: ProjectTheme Memberships 
Plugin URI: http://sitemile.com/
Description: Adds a membership/subscription feature to the Project Bidding Theme from sitemile
Author: SiteMile.com
Author URI: http://sitemile.com/
Version: 1.4
Text Domain: pt_mem
*/

add_filter('ProjectTheme_general_settings_main_details_options','ProjectTheme_general_settings_main_details_options_memss');
add_filter('ProjectTheme_general_settings_main_details_options_save','ProjectTheme_general_settings_main_details_options_save_memms');
add_filter('ProjectTheme_is_it_allowed_place_bids','ProjectTheme_is_it_allowed_place_bids_memms');
add_filter('ProjectTheme_is_it_not_allowed_place_bids_action','ProjectTheme_is_it_not_allowed_place_bids_action_meeems');
add_filter('ProjectTheme_before_payments_in_payments','ProjectTheme_before_payments_in_payments_meemss');
add_filter('template_redirect','ProjectTheme_template_redirect_meemmms');
add_filter('ProjectTheme_post_bid_ok_action', 'ProjectTheme_post_bid_ok_action_mem_fncs');
add_filter('ProjectTheme_display_bidding_panel','ProjectTheme_display_bidding_panel_mms');
add_filter('ProjectTheme_when_creating_auto_draft','ProjectTheme_when_creating_auto_draft_ff');

function ProjectTheme_when_creating_auto_draft_ff()
{
	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;	
	
	$projectTheme_monthly_nr_of_projects = get_user_meta($uid,'projectTheme_monthly_nr_of_projects',true);
	update_user_meta($uid, 'projectTheme_monthly_nr_of_projects', ($projectTheme_monthly_nr_of_projects - 1));
}

function ProjectTheme_display_bidding_panel_mms($pid)
{
	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;
	
	$projectTheme_monthly_nr_of_bids = get_user_meta($uid,'projectTheme_monthly_nr_of_bids',true);
	if($projectTheme_monthly_nr_of_bids <= 0)
	{
		$lnk = get_permalink(get_option('ProjectTheme_my_account_payments_id'));
		echo '<div class="padd10">';
		echo sprintf(__('Your membership does not have anymore bids left. You need to renew your subscription. <a href="%s">Click here</a>.','pt_mem'), $lnk);	
		echo '</div>';
	
		die();	
	}
}

function ProjectTheme_post_bid_ok_action_mem_fncs()
{
	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;
	
	$projectTheme_monthly_nr_of_bids = get_user_meta($uid,'projectTheme_monthly_nr_of_bids',true);
	update_user_meta($uid, 'projectTheme_monthly_nr_of_bids', ($projectTheme_monthly_nr_of_bids - 1));	
 
}

function ProjectTheme_mems_get_current_user_role ($uid) {
    
	$current_user 	= get_userdata($uid);
    $user_roles 	= $current_user->roles;
    $user_role 		= array_shift($user_roles);
	
    return $user_role;
} 

function ProjectTheme_template_redirect_meemmms()
{
	if(isset($_GET['p_action']) and $_GET['p_action'] == "get_new_mem")
	{
		include 'get_new_mem.php';
		die();	
	}
	
	if(isset($_GET['p_action']) and $_GET['p_action'] == "paypal_membership_mem")
	{
		include 'paypal_membership_mem.php';
		die();	
	}
	
	if(isset($_GET['p_action']) and $_GET['p_action'] == "mb_membership_mem")
	{
		include 'mb_membership_mem.php';
		die();	
	}
	
	if(isset($_GET['p_action']) and $_GET['p_action'] == "mb_deposit_response_mem")
	{
		include 'mb_deposit_response_mem.php';
		die();	
	}
	
	if(isset($_GET['p_action']) and $_GET['p_action'] == "credits_listing_mem")
	{
		include 'credits_listing_mem.php';
		die();	
	}
	
	
}

function ProjectTheme_before_payments_in_payments_meemss()
{
	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;
	
	$membership_available 	= get_user_meta($uid,'membership_available',true);
	$tm 					= current_time('timestamp',0);
	$ProjectTheme_enable_membs = get_option('ProjectTheme_enable_membs');
	
	if($ProjectTheme_enable_membs == "yes"):
	
	?>
    <div class="my_box3">
            
            
            	<div class="box_title"><?php _e("Membership/Subscription","ProjectTheme"); ?></div>
            	<div class="box_content">
                
                
                
                <?php
				
				if($membership_available > $tm )
				{
					if(ProjectTheme_is_user_provider($uid))
					{
						$projectTheme_monthly_nr_of_bids = get_user_meta($uid,'projectTheme_monthly_nr_of_bids',true);
						
						if($projectTheme_monthly_nr_of_bids <= 0)
						{
							$lnk = get_bloginfo('siteurl') . "/?p_action=get_new_mem";
							echo '<span class="balance">'.sprintf(__("Your membership has expired. Purchase from <a href='%s'>here</a>.", "ProjectTheme"), $lnk)."</span>"; 		
						}
						else
						{
							echo sprintf(__('Your membership will expire on: %s','ProjectTheme'), date_i18n('d-M-Y H:i:s',$membership_available));
							echo '<br/>';
							echo sprintf(__('Your have: %s bids left.','ProjectTheme'), $projectTheme_monthly_nr_of_bids);
						}
					}
					
					if(ProjectTheme_is_user_business($uid))
					{
						$projectTheme_monthly_nr_of_projects = get_user_meta($uid,'projectTheme_monthly_nr_of_projects',true);
						
						if($projectTheme_monthly_nr_of_projects <= 0)
						{
							$lnk = get_bloginfo('siteurl') . "/?p_action=get_new_mem";
							echo '<span class="balance">'.sprintf(__("Your membership has expired. Purchase from <a href='%s'>here</a>.", "ProjectTheme"), $lnk)."</span>"; 		
						}
						else
						{
							echo sprintf(__('Your membership will expire on: %s','ProjectTheme'), date_i18n('d-M-Y H:i:s',$membership_available));
							echo '<br/>';
							echo sprintf(__('Your have: %s projects left.','ProjectTheme'), $projectTheme_monthly_nr_of_projects);
						}
					}
					
				}
				else
				{			
					$lnk = get_bloginfo('siteurl') . "/?p_action=get_new_mem";
					echo '<span class="balance">'.sprintf(__("Your membership has expired. Purchase from <a href='%s'>here</a>.", "ProjectTheme"), $lnk)."</span>"; 
				}
				
				?> 
    
    
               
            </div>
            </div>
            
            <div class="clear10"></div> <?php endif; ?>
    
    <?php	
	
}

function ProjectTheme_is_it_not_allowed_place_bids_action_meeems()
{
	$lnk = get_permalink(get_option('ProjectTheme_my_account_payments_id'));
	echo '<div class="padd10">';
	echo sprintf(__('Your membership has expired. You need to renew your subscription. <a href="%s">Click here</a>.','pt_mem'), $lnk);	
	echo '</div>';
}


function ProjectTheme_is_it_not_allowed_place_bids_action_meeems2()
{
	$lnk = get_permalink(get_option('ProjectTheme_my_account_payments_id'));
	echo '<div class="padd10">';
	echo sprintf(__('Your membership does not have anymore bids left. You need to renew your subscription. <a href="%s">Click here</a>.','pt_mem'), $lnk);	
	echo '</div>';
}

function ProjectTheme_is_it_allowed_place_bids_memms($as)
{
	
	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;
	
	$ProjectTheme_enable_membs = get_option('ProjectTheme_enable_membs');
	
	if($ProjectTheme_enable_membs == "yes")
	{
		$trial = get_option('projectTheme_monthly_trial_period');
		if(empty($trial))
		{
			$membership_available 	= get_user_meta($uid,'membership_available',true);
			$tm 					= current_time('timestamp', 0);	
			
			if($tm > $membership_available)
			{
				add_filter('ProjectTheme_is_it_not_allowed_place_bids_action','ProjectTheme_is_it_not_allowed_place_bids_action_meeems');
				return false;
			}
		}
		else
		{
			$trial_used 	= get_user_meta($uid,'trial_used',true);
			if(empty($trial_used))
			{
				$tm = current_time('timestamp', 0);	
				update_user_meta($uid,	'trial_used',	"1");
				update_user_meta($uid, 'membership_available', ($tm + $trial*3600*24));
				
 				//------------------------
				
				$projectTheme_monthly_nr_of_bids = get_option('projectTheme_monthly_nr_of_bids');
				if(empty($projectTheme_monthly_nr_of_bids)) $projectTheme_monthly_nr_of_bids = 10;
				
				update_user_meta($uid, 'projectTheme_monthly_nr_of_bids', $projectTheme_monthly_nr_of_bids);
				
				
				return true;
			}
			else
			{
			
				$membership_available 	= get_user_meta($uid,'membership_available',true);
				$tm 					= current_time('timestamp', 0);	
				
				if($tm > $membership_available)
				{
					add_filter('ProjectTheme_is_it_not_allowed_place_bids_action','ProjectTheme_is_it_not_allowed_place_bids_action_meeems');
					return false;
				}
				else
				{
					if(ProjectTheme_is_user_business($uid))
					{
						$projectTheme_monthly_nr_of_projects = get_user_meta($uid, 'projectTheme_monthly_nr_of_projects', true);
						if($projectTheme_monthly_nr_of_projects <= -1)
						{
							add_filter('ProjectTheme_is_it_not_allowed_place_bids_action','ProjectTheme_is_it_not_allowed_place_bids_action_meeems');
							return false;	
						}	
					} 
					
					
					if(ProjectTheme_is_user_provider($uid))
					{
					
						$projectTheme_monthly_nr_of_bids = get_user_meta($uid, 'projectTheme_monthly_nr_of_bids', true);
						if($projectTheme_monthly_nr_of_bids <= 0)
						{
							add_filter('ProjectTheme_is_it_not_allowed_place_bids_action','ProjectTheme_is_it_not_allowed_place_bids_action_meeems2');
							return false;	
						}
					}
				}
			}
		}
		
	}
	
	return true;	
}

function ProjectTheme_general_settings_main_details_options_save_memms()
{
	update_option('ProjectTheme_enable_membs', $_POST['ProjectTheme_enable_membs']);
	update_option('projectTheme_monthly_service_provider', $_POST['projectTheme_monthly_service_provider']);
	update_option('projectTheme_monthly_service_contractor', $_POST['projectTheme_monthly_service_contractor']);
	update_option('projectTheme_monthly_trial_period', $_POST['projectTheme_monthly_trial_period']);
	
	update_option('projectTheme_monthly_nr_of_bids', $_POST['projectTheme_monthly_nr_of_bids']);
	update_option('projectTheme_monthly_nr_of_projects', $_POST['projectTheme_monthly_nr_of_projects']);
	
	
	
}

function ProjectTheme_general_settings_main_details_options_memss()
{
	$arr = array("yes" => "Yes", "no" => "No");
	global $menu_admin_project_theme_bull;
	
	?>	
	
    <tr>
        <td valign=top width="22"><?php echo $menu_admin_project_theme_bull; ?></td>
        <td >Enable Memberships:</td>
        <td><?php echo ProjectTheme_get_option_drop_down($arr, 'ProjectTheme_enable_membs'); ?></td>
        </tr>
        
        <tr>
        <td valign=top width="22"><?php echo $menu_admin_project_theme_bull; ?></td>
        <td >Monthly Cost for Service Provider:</td>
        <td><input type="text" name='projectTheme_monthly_service_provider' size="4" value="<?php echo get_option('projectTheme_monthly_service_provider'); ?>" /> <?php echo projecttheme_get_currency(); ?></td>
        </tr>
        
        
        <tr>
        <td valign=top width="22"><?php echo $menu_admin_project_theme_bull; ?></td>
        <td >Number of Bids (monthly):</td>
        <td><input type="text" name='projectTheme_monthly_nr_of_bids' size="4" value="<?php echo get_option('projectTheme_monthly_nr_of_bids'); ?>" /></td>
        </tr>
        
        <tr>
        <td valign=top width="22"><?php echo $menu_admin_project_theme_bull; ?></td>
        <td >Monthly Cost for Service Contractor:</td>
        <td><input type="text" name='projectTheme_monthly_service_contractor' size="4" value="<?php echo get_option('projectTheme_monthly_service_contractor'); ?>" /> <?php echo projecttheme_get_currency(); ?></td>
        </tr>
        
        
        <tr>
        <td valign=top width="22"><?php echo $menu_admin_project_theme_bull; ?></td>
        <td >Number of Projects (monthly):</td>
        <td><input type="text" name='projectTheme_monthly_nr_of_projects' size="4" value="<?php echo get_option('projectTheme_monthly_nr_of_projects'); ?>" /></td>
        </tr>
        
        <tr>
        <td valign=top width="22"><?php echo $menu_admin_project_theme_bull; ?></td>
        <td >Trial Period:</td>
        <td><input type="text" name='projectTheme_monthly_trial_period' size="4" value="<?php echo get_option('projectTheme_monthly_trial_period'); ?>" /> days </td>
        </tr>
        
        
        <tr>
        <td valign=top width="22">&nbsp;</td>
        <td >&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
        
    <?php
}

?>