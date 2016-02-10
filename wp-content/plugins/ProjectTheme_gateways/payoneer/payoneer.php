<?php
add_filter('ProjectTheme_payment_methods_tabs', 					'ProjectTheme_add_new_payoneer_tab');
add_filter('ProjectTheme_payment_methods_content_divs', 			'ProjectTheme_add_new_payoneer_cnt');
add_filter('ProjectTheme_payment_methods_action',					'ProjectTheme_add_new_payoneer_pst');

function ProjectTheme_add_new_payoneer_tab()
{
	?>
    
    	<li><a href="#tabs_payoneerpay">Payoneer</a></li>
    
    <?php	
	
}



function ProjectTheme_add_new_payoneer_pst()
{
	
	if(isset($_POST['ProjectTheme_save_payoneer'])):
	
	$ProjectTheme_payoneer_key 	= trim($_POST['ProjectTheme_payoneer_key']);
	$ProjectTheme_payoneer_id 	= trim($_POST['ProjectTheme_payoneer_id']);
	$ProjectTheme_payoneer_enable = $_POST['ProjectTheme_payoneer_enable'];
	
	update_option('ProjectTheme_payoneer_enable',	$ProjectTheme_payoneer_enable);
	update_option('ProjectTheme_payoneer_key',		$ProjectTheme_payoneer_key);
	update_option('ProjectTheme_payoneer_id',		$ProjectTheme_payoneer_id);
	
	endif;
}

function ProjectTheme_add_new_payoneer_cnt()
{
	$arr = array("yes" => __("Yes",'ProjectTheme'), "no" => __("No",'ProjectTheme'));
	
?>

<div id="tabs_payoneerpay"  >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=payment-methods&active_tab=tabs_payoneerpay">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','ProjectTheme'); ?></td>
                    <td><?php echo ProjectTheme_get_option_drop_down($arr, 'ProjectTheme_payoneer_enable'); ?></td>
                    </tr>

                    
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Payoneer Merchant ID:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_payoneer_id" value="<?php echo get_option('ProjectTheme_payoneer_id'); ?>"/></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Secret Key:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_payoneer_key" value="<?php echo get_option('ProjectTheme_payoneer_key'); ?>"/></td>
                    </tr>
                    
                    
                   
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="ProjectTheme_save_payoneer" value="<?php _e('Save Options','ProjectTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          
          </div>

<?php	
	
}




?>