<?php

	include 'payfast_listing.php';
	include 'payfast_response.php';
	include 'payfast_project.php';
	include 'payfast_project_response.php';
	include 'payfast_deposit.php';
	include 'payfast_deposit_me.php';
	
//-----------------------------------------------------------	



add_filter('ProjectTheme_pay_for_actual_project_payment_options', 					'ProjectTheme_pay_for_actual_project_payment_payfast',0,1);
add_filter('ProjectTheme_payment_methods_tabs', 					'ProjectTheme_add_new_payfast_tab');
add_filter('ProjectTheme_payment_methods_content_divs', 			'ProjectTheme_add_new_payfast_cnt');
add_filter('ProjectTheme_payment_methods_action',					'ProjectTheme_add_new_payfast_pst');
add_filter('ProjectTheme_add_payment_options_to_post_new_project', 	'ProjectTheme_add_new_payfast_listing_btn');
add_filter('ProjectTheme_add_payment_options_to_edit_project', 		'ProjectTheme_add_new_payfast_listing_btn');
add_filter('template_redirect', 									'projectTheme_plugin_gateways_template_redirect');
add_filter('ProjectTheme_deposit_methods', 'ProjectTheme_add_payfast_deposit',0,1);


//-----------------------------------------------------------------------------------------------------------------


function ProjectTheme_pay_for_actual_project_payment_payfast($pid)
{
	$ProjectTheme_payfast_enable = get_option('ProjectTheme_payfast_enable');
	
	if($ProjectTheme_payfast_enable == "yes"):
	
		echo 'a<a href="'.get_bloginfo('siteurl').'/?p_action=payfast_project&pid='.$pid.'" class="post_bid_btn">'.__('Pay by Payfast','pt_gateways').'</a>';
		echo '<br/><br/>';	
	
	endif;
}

function projectTheme_plugin_gateways_template_redirect()
{
	global $wp_query;
	$p_action 	=  $wp_query->query_vars['p_action'];	
	
	if($p_action == "payfast_listing")
	{
		ProjectTheme_payfast_main_listing_submit_payment();
		die();	
	}
	
	if($p_action == "payfast_listing_response")
	{
		ProjectTheme_payfast_main_listing_response_payment();
		die();	
	}
	
	if($p_action == "payfast_project")
	{
		ProjectTheme_payfast_main_payment_submit_payment();
		die();	
	}
	
	if($p_action == "payfast_project_response")
	{
		ProjectTheme_payfast_main_payment_response_payment();
		die();	
	}
	
	if($p_action == "payfast_deposit_pay")
	{
		ProjectTheme_payfast_deposit_payment();
		die();	
	}
	
	if($p_action == "payfast_deposit_response")
	{
		ProjectTheme_payfast_deposit_response();
		die();	
	}
	
	
	
	
}


function ProjectTheme_add_new_payfast_listing_btn($pid)
{

	$ProjectTheme_payfast_enable = get_option('ProjectTheme_payfast_enable');

	if($ProjectTheme_payfast_enable == "yes")
	echo '<a href="'.get_bloginfo('siteurl').'/?p_action=payfast_listing&pid='.$pid.'" class="edit_project_pay_cls">'.__('Pay by Payfast','pt_gateways').'</a>';	
	
}


function ProjectTheme_add_new_payfast_pst()
{
	
	if(isset($_POST['ProjectTheme_save_p'])):
	
	$ProjectTheme_payfast_key 	= trim($_POST['ProjectTheme_payfast_key']);
	$ProjectTheme_payfast_id 	= trim($_POST['ProjectTheme_payfast_id']);
	$ProjectTheme_payfast_enable = $_POST['ProjectTheme_payfast_enable'];
	
	update_option('ProjectTheme_payfast_enable',	$ProjectTheme_payfast_enable);
	update_option('ProjectTheme_payfast_key',	$ProjectTheme_payfast_key);
	update_option('ProjectTheme_payfast_id',	$ProjectTheme_payfast_id);
	
	endif;
}


function ProjectTheme_add_new_payfast_tab()
{
	?>
    
    	<li><a href="#tabs_payfast">PayFast</a></li>
    
    <?php	
	
}


function ProjectTheme_add_new_payfast_cnt()
{
	$arr = array("yes" => __("Yes",'ProjectTheme'), "no" => __("No",'ProjectTheme'));
	
?>

<div id="tabs_payfast"  >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=payment-methods&active_tab=tabs_payfast">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','ProjectTheme'); ?></td>
                    <td><?php echo ProjectTheme_get_option_drop_down($arr, 'ProjectTheme_payfast_enable'); ?></td>
                    </tr>

                    
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('PayFast Merchant ID:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_payfast_id" value="<?php echo get_option('ProjectTheme_payfast_id'); ?>"/></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('PayFast Merchant Key:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_payfast_key" value="<?php echo get_option('ProjectTheme_payfast_key'); ?>"/></td>
                    </tr>
                    
                    
                   
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="ProjectTheme_save_p" value="<?php _e('Save Options','ProjectTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          
          </div>

<?php	
	
}





?>