<?php

include 'voguepay_listing.php';
include 'voguepay_deposit.php';

//---------------------

add_filter('ProjectTheme_payment_methods_tabs', 					'ProjectTheme_add_new_voguepay_tab');
add_filter('ProjectTheme_payment_methods_content_divs', 			'ProjectTheme_add_new_vogspay_cnt');
add_filter('ProjectTheme_payment_methods_action',					'ProjectTheme_add_new_vgspay_pst');
add_filter('ProjectTheme_add_payment_options_to_post_new_project', 	'ProjectTheme_add_new_vgspay_listing_btn');
add_filter('template_redirect', 									'projectTheme_plugin_gateways_template_redirect_vgspay');
add_filter('ProjectTheme_deposit_methods', 							'ProjectTheme_add_vgspay_deposit',0,1);

//-----------------------------------------------------------

function projectTheme_plugin_gateways_template_redirect_vgspay()
{
	global $wp_query;
	$p_action 	=  $wp_query->query_vars['p_action'];	
	
	if($p_action == "vogoue_listing")
	{
		ProjectTheme_voguepay_main_listing_submit_payment();
		die();	
	}
	
	if($p_action == "voguepay_deposit_pay")
	{
		ProjectTheme_vgspay_deposit_payment();
		die();	
	}
	
	if($p_action == "vgspay_deposit_response")
	{
		ProjectTheme_vgspay_deposit_response();
		die();	
	}
	
 
	
}

function ProjectTheme_add_new_vgspay_listing_btn($pid)
{

	$ProjectTheme_voguepay_enable = get_option('ProjectTheme_voguepay_enable');

	if($ProjectTheme_voguepay_enable == "yes")
	echo '<a href="'.get_bloginfo('siteurl').'/?p_action=vogoue_listing&pid='.$pid.'" class="edit_project_pay_cls">'.__('Pay by Voguepay','pt_gateways').'</a>';	
	
}


function ProjectTheme_add_new_vgspay_pst()
{
	
	if(isset($_POST['ProjectTheme_save_vgspay'])):
	
	$ProjectTheme_voguepay_enable 	= trim($_POST['ProjectTheme_voguepay_enable']);
	$ProjectTheme_voguepay_id 	= trim($_POST['ProjectTheme_voguepay_id']); 
	
	update_option('ProjectTheme_voguepay_enable',	$ProjectTheme_voguepay_enable);
	update_option('ProjectTheme_voguepay_id',	$ProjectTheme_voguepay_id); 
	
	endif;
}


function ProjectTheme_add_new_vogspay_cnt()
{
	$arr = array("yes" => __("Yes",'ProjectTheme'), "no" => __("No",'ProjectTheme'));
	
?>

<div id="tabs_Voguepay"  >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=payment-methods&active_tab=tabs_Voguepay">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','ProjectTheme'); ?></td>
                    <td><?php echo ProjectTheme_get_option_drop_down($arr, 'ProjectTheme_voguepay_enable'); ?></td>
                    </tr>

                    
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Voguepay Merchant ID:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_voguepay_id" value="<?php echo get_option('ProjectTheme_voguepay_id'); ?>"/></td>
                    </tr>
                    
          
                   
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="ProjectTheme_save_vgspay" value="<?php _e('Save Options','ProjectTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          
          </div>

<?php	
	
}



function ProjectTheme_add_new_voguepay_tab()
{
	?>
    
    	<li><a href="#tabs_Voguepay">Voguepay</a></li>
    
    <?php	
	
}


?>