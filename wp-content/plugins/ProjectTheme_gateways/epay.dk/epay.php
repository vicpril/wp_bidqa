<?php

include 'epay_deposit.php';

add_filter('ProjectTheme_payment_methods_tabs', 					'ProjectTheme_add_new_epay_tab');
add_filter('ProjectTheme_payment_methods_content_divs', 			'ProjectTheme_add_new_epay_cnt');
add_filter('ProjectTheme_payment_methods_action',					'ProjectTheme_add_new_epay_pst');
add_filter('ProjectTheme_deposit_methods', 							'ProjectTheme_add_epay_deposit',0,1);
add_filter('template_redirect', 									'projectTheme_plugin_gateways_template_redirect_epay');

function projectTheme_plugin_gateways_template_redirect_epay()
{
	global $wp_query;
	$p_action 	=  $wp_query->query_vars['p_action'];	
	
	if($p_action == "epay_deposit_pay")
	{
		ProjectTheme_eps_deposit_payment_epay();
		die();	
	}
	
	if($p_action == "epay_deposit_response")
	{
		ProjectTheme_epay_deps_deposit_payment_epay();
		die();	
	}
 
 
 
	
}


function ProjectTheme_add_new_epay_pst()
{
	
	if(isset($_POST['ProjectTheme_save_epay'])):
	
	$ProjectTheme_epay_enable 	= trim($_POST['ProjectTheme_epay_enable']);
	$ProjectTheme_epay_id 	= trim($_POST['ProjectTheme_epay_id']);
 
	
	update_option('ProjectTheme_epay_enable',	$ProjectTheme_epay_enable);
	update_option('ProjectTheme_epay_id',	$ProjectTheme_epay_id);
 
	
	endif;
}


function ProjectTheme_add_new_epay_tab()
{
	?>
    
    	<li><a href="#tabs_epay">ePay.dk</a></li>
    
    <?php	
	
}



function ProjectTheme_add_new_epay_cnt()
{
	$arr = array("yes" => __("Yes",'ProjectTheme'), "no" => __("No",'ProjectTheme'));
	
?>

<div id="tabs_epay"  >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=payment-methods&active_tab=tabs_epay">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','ProjectTheme'); ?></td>
                    <td><?php echo ProjectTheme_get_option_drop_down($arr, 'ProjectTheme_epay_enable'); ?></td>
                    </tr>

                    
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('ePay Merchant Number:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_epay_id" value="<?php echo get_option('ProjectTheme_epay_id'); ?>"/></td>
                    </tr>
                  
                    
                   
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="ProjectTheme_save_epay" value="<?php _e('Save Options','ProjectTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          
          </div>

<?php	
	
}

?>