<?php

include 'qq_listing.php';
include 'qq_deposit.php';

add_filter('ProjectTheme_payment_methods_tabs', 					'ProjectTheme_add_new_quickpay_tab');
add_filter('ProjectTheme_payment_methods_content_divs', 			'ProjectTheme_add_new_qq_cnt');
add_filter('ProjectTheme_payment_methods_action',					'ProjectTheme_add_new_quickpay_pst');
add_filter('ProjectTheme_add_payment_options_to_post_new_project', 	'ProjectTheme_add_new_qq_listing_btn');
add_filter('template_redirect', 									'projectTheme_plugin_qq_gateways_template_redirect');
add_filter('ProjectTheme_deposit_methods', 'ProjectTheme_add_qq_deposit',0,1);

function projectTheme_plugin_qq_gateways_template_redirect()
{
	global $wp_query;
	$p_action 	=  $wp_query->query_vars['p_action'];	
	
	if($p_action == "qq_listing")
	{
		ProjectTheme_qq_main_listing_submit_payment();
		die();	
	}
	
	if($p_action == "qq_listing_response")
	{
		ProjectTheme_qq_main_listing_submit_respo();
		die();	
	}
	
	if($p_action == "qq_deposit_pay")
	{
		ProjectTheme_qq_deposit_payment();
		die();	
	}
	
	
	if($p_action == "qq_deposit_response")
	{
		ProjectTheme_qq_deposit_resp();
		die();	
	}
	
	
	
}

function ProjectTheme_add_new_qq_listing_btn($pid)
{

	$ProjectTheme_qq_enable = get_option('ProjectTheme_qq_enable');

	if($ProjectTheme_qq_enable == "ProjectTheme_qq_enable")
	echo '<a href="'.get_bloginfo('siteurl').'/?p_action=qq_listing&pid='.$pid.'" class="edit_project_pay_cls">'.__('Pay by Quickpay','pt_gateways').'</a>';	
	
}

function ProjectTheme_add_new_qq_cnt()
{
	$arr = array("yes" => __("Yes",'ProjectTheme'), "no" => __("No",'ProjectTheme'));
	
?>

<div id="tabs_quickpay"  >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=payment-methods&active_tab=tabs_quickpay">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','ProjectTheme'); ?></td>
                    <td><?php echo ProjectTheme_get_option_drop_down($arr, 'ProjectTheme_qq_enable'); ?></td>
                    </tr>

                    
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Merchant ID:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_qq_id" value="<?php echo get_option('ProjectTheme_qq_id'); ?>"/></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('MD5 Secret Key:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_qq_key" value="<?php echo get_option('ProjectTheme_qq_key'); ?>"/></td>
                    </tr>
                    
                    
                   
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="ProjectTheme_saveqq" value="<?php _e('Save Options','ProjectTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          
          </div>

<?php	
	
}


function ProjectTheme_add_new_quickpay_pst()
{
	
	if(isset($_POST['ProjectTheme_saveqq'])):
	
	$ProjectTheme_qq_key 	= trim($_POST['ProjectTheme_qq_key']);
	$ProjectTheme_qq_id 	= trim($_POST['ProjectTheme_qq_id']);
	$ProjectTheme_qq_enable = $_POST['ProjectTheme_qq_enable'];
	
	update_option('ProjectTheme_qq_enable',	$ProjectTheme_qq_enable);
	update_option('ProjectTheme_qq_key',	$ProjectTheme_qq_key);
	update_option('ProjectTheme_qq_id',	$ProjectTheme_qq_id);
	
	endif;
}


function ProjectTheme_add_new_quickpay_tab()
{
	?>
    
    	<li><a href="#tabs_quickpay">Quickpay</a></li>
    
    <?php	
	
}
?>