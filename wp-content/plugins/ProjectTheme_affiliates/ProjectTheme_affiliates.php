<?php
/*
Plugin Name: ProjectTheme Affiliates Feature
Plugin URI: http://sitemile.com/
Description: Adds an affiliate section for your Project Bidding Theme from sitemile
Author: SiteMile.com
Author URI: http://sitemile.com/
Version: 1.0
Text Domain: pt_affiliates
*/


//-------------------------------------------

include 'my_account/affiliates.php';
include 'first_run.php';


register_activation_hook( __FILE__, 'ProjectTheme_affiliates_myplugin_activate' );
add_action('the_content',							'ProjectTheme_display_my_account_affiliates');
add_action('ProjectTheme_my_account_main_menu',		'ProjectTheme_add_affiliates_user_menu');

add_action('wp_enqueue_scripts', 							'ProjectTheme_affiliates_add_theme_styles');


function projecttheme_aff_plugin_temp_redir()
{
	if(isset($_GET['ref_id_usr']))
	{
		if (!isset($_COOKIE['affiliate_newvisitor']))
			setcookie("affiliate_newvisitor", $_GET['ref_id_usr'], time()+(3600*30*24), "/", str_replace('http://www','',get_bloginfo('url')) );
        
		
		wp_redirect(get_bloginfo('siteurl'));
		die();	
	}
	
}

function ProjectTheme_affiliates_add_theme_styles()
{
	wp_register_style( 'affiliates_css_me', 	plugins_url( 'affiliates_plugin.css' , __FILE__ ), array(), '20120822', 'all' );
	wp_enqueue_style( 'affiliates_css_me' );	
}

/*************************************************************
*
*	ProjectTheme (c) sitemile.com - function
*
**************************************************************/

function ProjectTheme_display_my_account_affiliates( $content = '' ) 
{
	if ( preg_match( "/\[project_theme_my_account_affiliates\]/", $content ) ) 
	{
		ob_start();
		ProjectTheme_my_account_affiliates_area_function();
		$output = ob_get_contents();
		ob_end_clean();
		$output = str_replace( '$', '\$', $output );
		return preg_replace( "/(<p>)*\[project_theme_my_account_affiliates\](<\/p>)*/", $output, $content );
		
	} 
	else {
		return $content;
	}
}

function ProjectTheme_add_affiliates_user_menu()
{
?>	
	
    <li><a href="<?php echo get_permalink(get_option('ProjectTheme_my_account_affiliates_id')); ?>"><?php _e("Affiliates",'pt_affiliates');?></a></li>
    	
<?php	
}


add_filter('ProjectTheme_general_settings_main_details_options','ProjectTheme_general_settings_main_details_options_fnc');
add_filter('ProjectTheme_general_settings_main_details_options_save','ProjectTheme_general_settings_main_details_options_save_fnc');

function ProjectTheme_general_settings_main_details_options_save_fnc()
{
	update_option('projectTheme_aff_percent', trim($_POST['projectTheme_aff_percent']));	
}

function ProjectTheme_general_settings_main_details_options_fnc()
{
	global $menu_admin_project_theme_bull;
	
	?>
    
     <tr>
        <td valign=top width="22"><?php echo $menu_admin_project_theme_bull; ?></td>
        <td width="320">Percent for Affiliates:</td>
        <td><input type="text" name="projectTheme_aff_percent" size="5" value="<?php echo  get_option('projectTheme_aff_percent'); ?>"  /> %</td>
        </tr>
    
    <?php	
}

add_filter('ProjectTheme_admin_menu_add_item','ProjectTheme_admin_menu_add_item_fnc1a');

function ProjectTheme_admin_menu_add_item_fnc1a()
{
	$capability = 10;

	add_submenu_page('project_theme_mnu', __('Affiliates','ProjectTheme'), '<img src="'.get_bloginfo('template_url').'/images/wallet_icon.png" border="0" /> '.__('Affiliates','ProjectTheme'),$capability, 'PT_affiliates', 'projectTheme_theme_Affiliates');

}

function projectTheme_theme_Affiliates()
{
global $menu_admin_project_theme_bull, $wpdb;
	echo '<div class="wrap">';
	echo '<div class="icon32" id="icon-options-general-withdr"><br/></div>';	
	echo '<h2 class="my_title_class_sitemile">ProjectTheme Affiliates</h2>';
	
	
	if(isset($_GET['tid']))
	{
		$tm = current_time('timestamp',0);
		$ids = $_GET['tid'];
		
		$s = "select * from ".$wpdb->prefix."project_withdraw where id='$ids'";
		$row = $wpdb->get_results($s);
		$row = $row[0];

		
		if($row->done == 0)
		{
			echo '<div class="saved_thing">Payment completed!</div>';
			$ss = "update ".$wpdb->prefix."project_withdraw set done='1', datedone='$tm' where id='$ids'";
			$wpdb->query($ss);// or die(mysql_error());
			
			
			$usr = get_userdata($row->uid);
			
			$site_name 		= get_bloginfo('name');
			$email		 	= get_bloginfo('admin_email');
			
			$subject = sprintf(__("Your withdrawal has been completed: %s",'ProjectTheme'), projectTheme_get_show_price($row->amount));
			$message = sprintf(__("Your withdrawal has been completed: %s",'ProjectTheme'), projectTheme_get_show_price($row->amount));
			
			//sitemile_send_email($usr->user_email, $subject , $message);
	
			
			$reason = sprintf(__('Withdraw to PayPal to email: %s','ProjectTheme') ,$row->payeremail);
			projectTheme_add_history_log('0', $reason, $row->amount, $usr->ID);
		}
	}
	
	?>
    
        <div id="usual2" class="usual"> 
  <ul> 
    <ul> 
            <li><a href="#tabs1"><?php _e('Affiliate Activity (not paid)','ProjectTheme'); ?></a></li> 
            <li><a href="#tabs2"><?php _e('Affiliate Activity (paid)','ProjectTheme'); ?></a></li> 
          </ul> 
  </ul> 
  <div id="tabs1">
          <?php
		  
		   $s = "select * from ".$wpdb->prefix."project_affiliate_payouts where paid='0' order by id desc";
           $r = $wpdb->get_results($s);
		  	
			if(count($r) > 0):
		  
		  ?>
          
           <table class="widefat post fixed" cellspacing="0">
            <thead>
            <tr>
            <th width="12%" ><?php _e('Username','ProjectTheme'); ?></th>
            <th><?php _e('Project','ProjectTheme'); ?></th>
            <th><?php _e('Date Made','ProjectTheme'); ?></th>
            <th ><?php _e('Amount','ProjectTheme'); ?></th>
            <th width="25%"><?php _e('Options','ProjectTheme'); ?></th>
            </tr>
            </thead>
            
            
            
            <tbody>
            <?php
            
           
            
            foreach($r as $row)
            {
                $user = get_userdata($row->uid);
				$user = get_userdata($row->uid);
                
                echo '<tr>';	
                echo '<th>'.$user->user_login.'</th>';
                echo '<th>'.$row->methods .'</th>';
				 echo '<th>'.$row->payeremail .'</th>';
                echo '<th>'.date('d-M-Y H:i:s',$row->datemade) .'</th>';
                echo '<th>'.ProjectTheme_get_show_price($row->amount) .'</th>';
                echo '<th>'.($row->done == 0 ? '<a href="'.get_bloginfo('siteurl').'/wp-admin/admin.php?page=Withdrawals&active_tab=tabs1&tid='.$row->id.'" class="awesome">'.
                __('Make Complete','ProjectTheme').'</a>' . ' | ' . '<a href="'.get_bloginfo('siteurl').'/wp-admin/admin.php?page=Withdrawals&den_id='.$row->id.'" class="awesome">'.
                __('Deny Request','ProjectTheme').'</a>' :( $row->done == 1 ? __("Completed",'ProjectTheme') : __("Rejected",'ProjectTheme') ) ).'</th>';
                echo '</tr>';
            }
            
            ?>
            </tbody>
            
            
            </table>
            <?php else: ?>
            
            <div class="padd101">
            <?php _e('There is no unpaid affiliate activity.','ProjectTheme'); ?>
            </div>
            
            <?php endif; ?>
          
          	
          </div>
          
          <div id="tabs2">	
          
          
          <?php
		  
		   $s = "select * from ".$wpdb->prefix."project_affiliate_payouts where paid='1' order by id desc";
           $r = $wpdb->get_results($s);
		  	
			if(count($r) > 0):
		  
		  ?>
          
           <table class="widefat post fixed" cellspacing="0">
            <thead>
            <tr>
            <th ><?php 	_e('Username','ProjectTheme'); ?></th>
            <th><?php 	_e('Project','ProjectTheme'); ?></th>
            <th><?php 	_e('Date Made','ProjectTheme'); ?></th>
            <th ><?php 	_e('Amount','ProjectTheme'); ?></th>
            <th><?php 	_e('Date Paid','ProjectTheme'); ?></th>
            <th><?php 	_e('Options','ProjectTheme'); ?></th>
            </tr>
            </thead>
            
            
            
            <tbody>
            <?php
            
           
            
            foreach($r as $row)
            {
                $user = get_userdata($row->uid);
                
                echo '<tr>';	
                echo '<th>'.$user->user_login.'</th>';
                echo '<th>'.$row->payeremail .'</th>';
                echo '<th>'.date('d-M-Y H:i:s',$row->datemade) .'</th>';
                echo '<th>'.ProjectTheme_get_show_price($row->amount) .'</th>';
                echo '<th>'.($row->datedone == 0 ? "Not yet" : date('d-M-Y H:i:s',$row->datedone)) .'</th>';
                echo '<th>'.($row->done == 0 ? '<a href="'.get_bloginfo('siteurl').'/wp-admin/admin.php?page=Withdrawals&active_tab=tabs1&tid='.$row->id.'" class="awesome">'.
                __('Make Complete','ProjectTheme').'</a>' . ' | ' . '<a href="'.get_bloginfo('siteurl').'/wp-admin/admin.php?page=Withdrawals&den_id='.$row->id.'" class="awesome">'.
                __('Deny Request','ProjectTheme').'</a>' :( $row->done == 1 ? __("Completed",'ProjectTheme') : __("Rejected",'ProjectTheme') ) ).'</th>';
                echo '</tr>';
            }
            
            ?>
            </tbody>
            
            
            </table>
            <?php else: ?>
            
            <div class="padd101">
            <?php _e('There is no paid affiliate activity.','ProjectTheme'); ?>
            </div>
            
            <?php endif; ?>
          
          
          </div>
          
        
        
          
          

<?php
	echo '</div>';		
	
	
}


?>