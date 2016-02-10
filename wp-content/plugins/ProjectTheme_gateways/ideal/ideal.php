<?php
add_filter('ProjectTheme_payment_methods_tabs', 					'ProjectTheme_add_new_ideal_tab');
add_filter('ProjectTheme_payment_methods_content_divs', 			'ProjectTheme_add_new_ideal_cnt');
add_filter('ProjectTheme_payment_methods_action',					'ProjectTheme_add_new_ideal_basic_pst');
add_filter('template_redirect','PT_ideal_basic_tmpl_rdr');
add_filter('ProjectTheme_deposit_methods', 					'ProjectTheme_add_ideal_basic_deposit',0,1);

include 'ideal_deposit.php';
include 'ideal_deposit_form.php';

function PT_ideal_basic_tmpl_rdr()
{
	if($_GET['p_action'] == 'ideal_basic_deposit_pay')
	{
		$amount = trim($_POST['amount']);
		
		if($amount > 0 and is_numeric($amount))
		{
			wp_redirect(get_bloginfo('siteurl') . "/?dep_ideal_basic=" . $amount); die();	
		}
	}
	
	if(isset($_GET['dep_ideal_basic']))
	{
		PT_dep_basic_form_ideal(); die();
	}
}

function ProjectTheme_add_new_ideal_tab()
{
	?>
    
    	<li><a href="#tabs_ideal_basic">iDeal Basic</a></li>
    
    <?php	
	
}

function ProjectTheme_add_new_ideal_basic_pst()
{
	
	if(isset($_POST['ProjectTheme_save_ideal_basic'])):
	
	$ProjectTheme_ideal_basic_enable 	= trim($_POST['ProjectTheme_ideal_basic_enable']);
	$ProjectTheme_ideal_basic_id 	= trim($_POST['ProjectTheme_ideal_basic_id']); 
	
	update_option('ProjectTheme_ideal_basic_enable',	$ProjectTheme_ideal_basic_enable);
	update_option('ProjectTheme_ideal_basic_id',	$ProjectTheme_ideal_basic_id);
 
	
	endif;
}


function ProjectTheme_add_new_ideal_cnt()
{
	$arr = array("yes" => __("Yes",'ProjectTheme'), "no" => __("No",'ProjectTheme'));
	
?>

<div id="tabs_ideal_basic"  >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=payment-methods&active_tab=tabs_ideal_basic">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','ProjectTheme'); ?></td>
                    <td><?php echo ProjectTheme_get_option_drop_down($arr, 'ProjectTheme_ideal_basic_enable'); ?></td>
                    </tr>

                    
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('iDeal Basic ID:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_ideal_basic_id" value="<?php echo get_option('ProjectTheme_ideal_basic_id'); ?>"/></td>
                    </tr>
                    
                 
                   
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="ProjectTheme_save_ideal_basic" value="<?php _e('Save Options','ProjectTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          
          </div>

<?php	
	
}


?>