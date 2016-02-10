<?php

add_filter('ProjectTheme_payment_methods_tabs', 					'ProjectTheme_add_new_cashenvoy_tab');
add_filter('ProjectTheme_payment_methods_content_divs', 			'ProjectTheme_add_new_cashenvoy_cnt');

function ProjectTheme_add_new_cashenvoy_tab()
{
	?>
    
    	<li><a href="#tabs_cashenvoy">Cashenvoy</a></li>
    
    <?php	
	
}


function ProjectTheme_add_new_cashenvoy_cnt()
{
	$arr = array("yes" => __("Yes",'ProjectTheme'), "no" => __("No",'ProjectTheme'));
	
?>

<div id="tabs_cashenvoy"  >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=payment-methods&active_tab=tabs_cashenvoy">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','ProjectTheme'); ?></td>
                    <td><?php echo ProjectTheme_get_option_drop_down($arr, 'ProjectTheme_cashenvoy_enable'); ?></td>
                    </tr>

                    
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Cashenvoy Merchant ID:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_cashenvoy_id" value="<?php echo get_option('ProjectTheme_cashenvoy_id'); ?>"/></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Cashenvoy Merchant Key:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_cashevnoy_key" value="<?php echo get_option('ProjectTheme_cashevnoy_key'); ?>"/></td>
                    </tr>
                    
                    
                   
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="ProjectTheme_save_cashevnoy" value="<?php _e('Save Options','ProjectTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          
          </div>

<?php	
	
}



?>