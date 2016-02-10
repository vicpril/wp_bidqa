<?php

include 'pesapal_deposit.php';
include 'pesapal_deposit_answer.php';

add_filter('ProjectTheme_payment_methods_content_divs', 			'ProjectTheme_add_new_pesapal_cnt');
add_filter('ProjectTheme_payment_methods_tabs', 					'ProjectTheme_add_new_pesapal_tab');
add_filter('ProjectTheme_payment_methods_action',					'ProjectTheme_add_new_pesapal_pst');
add_filter('ProjectTheme_deposit_methods', 							'ProjectTheme_add_pesapal_deposit',0,1);
add_filter('template_redirect', 'ProjectTheme_pesapal_template_redir');

function ProjectTheme_pesapal_template_redir()
{
	if(($_GET['p_action'] == 'pesapal_deposit_pay'))
	{
		ProjectTheme_pesapal_deposit_show();
		die();		
	}
	
	if(isset($_GET['redirect_pesapal']))
	{
		ProjectTheme_pesapal_deposit_answer();
		die();		
	}
	
	
}

function ProjectTheme_add_new_pesapal_tab()
{
	?>
    
    	<li><a href="#tabs_pesapal">PesaPal</a></li>
    
    <?php	
	
}


function ProjectTheme_add_new_pesapal_pst()
{
	
	if(isset($_POST['ProjectTheme_save_pesapal'])):
	
		$ProjectTheme_pesapal_enable 	= trim($_POST['ProjectTheme_pesapal_enable']);
		$ProjectTheme_working_mode 		= trim($_POST['ProjectTheme_working_mode']);
		$ProjectTheme_pesapal_key 		= $_POST['ProjectTheme_pesapal_key'];
		$ProjectTheme_pesapal_secret 	= $_POST['ProjectTheme_pesapal_secret'];
		
		//---------------------
		
		update_option('ProjectTheme_pesapal_enable',	$ProjectTheme_pesapal_enable);
		update_option('ProjectTheme_working_mode',		$ProjectTheme_working_mode);
		update_option('ProjectTheme_pesapal_key',		$ProjectTheme_pesapal_key);
		update_option('ProjectTheme_pesapal_secret',	$ProjectTheme_pesapal_secret);
		
	endif;
}


function ProjectTheme_add_new_pesapal_cnt()
{
	$arr = array("yes" => __("Yes",'ProjectTheme'), "no" => __("No",'ProjectTheme'));
	$arr2 = array("live" => __("LIVE",'ProjectTheme'), "test" => __("TEST - DEMO",'ProjectTheme'));
	
?>

<div id="tabs_pesapal"  >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=payment-methods&active_tab=tabs_pesapal">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','ProjectTheme'); ?></td>
                    <td><?php echo ProjectTheme_get_option_drop_down($arr, 'ProjectTheme_pesapal_enable'); ?></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Working Mode:','ProjectTheme'); ?></td>
                    <td><?php echo ProjectTheme_get_option_drop_down($arr2, 'ProjectTheme_working_mode'); ?></td>
                    </tr>

                    
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Consumer Key:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_pesapal_key" value="<?php echo get_option('ProjectTheme_pesapal_key'); ?>"/></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Consumer Secret:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_pesapal_secret" value="<?php echo get_option('ProjectTheme_pesapal_secret'); ?>"/></td>
                    </tr>
                    
                    
                   
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="ProjectTheme_save_pesapal" value="<?php _e('Save Options','ProjectTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          
          </div>

<?php	
	
}


?>