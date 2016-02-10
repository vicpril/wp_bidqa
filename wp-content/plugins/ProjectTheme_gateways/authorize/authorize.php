<?php

include 'authorize_deposit.php';
include 'authorize_listing.php';

add_filter('ProjectTheme_payment_methods_tabs', 					'ProjectTheme_add_new_authorize_tab');
add_filter('ProjectTheme_payment_methods_content_divs', 			'ProjectTheme_add_new_authorize_cnt');
add_filter('ProjectTheme_payment_methods_action',					'ProjectTheme_add_new_authorize_pst');
add_filter('ProjectTheme_add_payment_options_to_post_new_project', 	'ProjectTheme_add_new_authorize_listing_btn');
add_filter('ProjectTheme_add_payment_options_to_edit_project', 		'ProjectTheme_add_new_authorize_listing_btn');
add_filter('template_redirect',										'projectTheme_plugin_gateways_auth_template_redirect');
add_filter('ProjectTheme_deposit_methods', 							'ProjectTheme_add_authorize_deposit',0,1);
add_filter('ProjectTheme_add_payment_options_to_post_new_project', 					'ProjectTheme_pay_for_actual_project_payment_autho' );
add_filter('ProjectTheme_add_payment_options_to_edit_project', 					'ProjectTheme_pay_for_actual_project_payment_autho' );
 


function ProjectTheme_pay_for_actual_project_payment_autho($pid)
{
	$ProjectTheme_authorize_enable = get_option('ProjectTheme_authorize_enable');
	
	 if($ProjectTheme_authorize_enable == "yes"):
	
		echo '<a href="'.get_bloginfo('siteurl').'/?p_action=authorize_listing&pid='.$pid.'" class="edit_project_pay_cls">'.__('Pay by Authorize.NET','pt_gateways').'</a>';
		echo '<br/><br/>';	
	
	 endif;
}

function projectTheme_plugin_gateways_auth_template_redirect()
{
	global $wp_query;
	$p_action 	=  $wp_query->query_vars['p_action'];	
	
	 
	
	if($p_action == "authorize_listing")
	{
		ProjectTheme_authorize_main_listing_submit_payment();
		die();	
	}
	
	if($p_action == "authorize_deposit_pay")
	{
		ProjectTheme_authorize_deposit_payment();
		die();	
	}
	
}


function ProjectTheme_add_authorize_deposit($uid = '')
{
	
				
				$ProjectTheme_authorize_enable = get_option('ProjectTheme_authorize_enable');
				if($ProjectTheme_authorize_enable == "yes"):
				
				?>
                
                
                <strong><?php _e('Deposit money by Authorize.NET','pt_gateways'); ?></strong><br/><br/>
                
                <form method="post" action="<?php bloginfo('siteurl'); ?>/?p_action=authorize_deposit_pay">
                <?php _e("Amount to deposit:","pt_gateways"); ?>  <input type="text" size="10" name="amount" /> <?php echo projectTheme_currency(); ?>
                &nbsp; &nbsp; <input type="submit" name="deposit" value="<?php _e('Deposit','pt_gateways'); ?>" /></form>
    			<br/><br/>
                <?php endif; 
	
	
}


function ProjectTheme_add_new_authorize_listing_btn($pid)
{

	$ProjectTheme_authorize_enable = get_option('ProjectTheme_authorize_enable');

	if($ProjectTheme_payfast_enable == "yes")
	echo '<a href="'.get_bloginfo('siteurl').'/?p_action=authorize_listing&pid='.$pid.'" class="edit_project_pay_cls">'.__('Pay by Authorize.NET','pt_gateways').'</a>';	
	
}

function ProjectTheme_add_new_authorize_tab()
{
	?>
    
    	<li><a href="#tabs_authorize">Authorize</a></li>
    
    <?php	
	
}

function ProjectTheme_add_new_authorize_pst()
{
	
	if(isset($_POST['ProjectTheme_save_auth'])):
	
	$ProjectTheme_authorize_key 		= trim($_POST['ProjectTheme_authorize_key']);
	$ProjectTheme_authorize_id 		= trim($_POST['ProjectTheme_authorize_id']);
	$ProjectTheme_authorize_enable 	= $_POST['ProjectTheme_authorize_enable'];
	
	update_option('ProjectTheme_authorize_enable',	$ProjectTheme_authorize_enable);
	update_option('ProjectTheme_authorize_key',		$ProjectTheme_authorize_key);
	update_option('ProjectTheme_authorize_id',		$ProjectTheme_authorize_id);
	
	endif;
}

function ProjectTheme_add_new_authorize_cnt()
{
	$arr = array("yes" => __("Yes",'ProjectTheme'), "no" => __("No",'ProjectTheme'));
	
?>

<div id="tabs_authorize"  >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=payment-methods&active_tab=tabs_authorize">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','ProjectTheme'); ?></td>
                    <td><?php echo ProjectTheme_get_option_drop_down($arr, 'ProjectTheme_authorize_enable'); ?></td>
                    </tr>

                    
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Authorize Login ID:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_authorize_id" value="<?php echo get_option('ProjectTheme_authorize_id'); ?>"/></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Authorize Transaction Key:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_authorize_key" value="<?php echo get_option('ProjectTheme_authorize_key'); ?>"/></td>
                    </tr>
                    
                    
                   
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="ProjectTheme_save_auth" value="<?php _e('Save Options','ProjectTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          
          </div>

<?php	
	
}


?>