<?php
/***************************************************************************
*
*	ProjectTheme - copyright (c) - sitemile.com
*	The only project theme for wordpress on the world wide web.
*
*	Coder: Andrei Dragos Saioc
*	Email: sitemile[at]sitemile.com | andreisaioc[at]gmail.com
*	More info about the theme here: http://sitemile.com/products/wordpress-project-freelancer-theme/
*	since v1.2.5.3
*
***************************************************************************/

function ProjectTheme_my_account_affiliates_area_function()
{
	
		global $current_user, $wpdb, $wp_query;
		get_currentuserinfo();
		$uid = $current_user->ID;
		
?>
    	<div id="content" class="account-main-area">
        	
            <div class="my_box3">
            	<div class="padd10">
            
            	<div class="box_title"><?php _e("Affiliates Panel", "ProjectTheme"); ?></div>
                <div class="box_content"> 
            	
                    <div class="aff_id_thing">
                    <?php
                    
                        $aff_url = get_bloginfo('siteurl') . "/?ref_id_usr=".$uid;
                    
                    ?>
                    <?php echo sprintf(__('Your affiliate url: <b>%s</b>','ProjectTheme'), $aff_url); ?> 
                    </div>
                    
                    <div class="aff_id_thing2">
                    	<?php _e('Share this link with all your friends, when they join to the website through your link you get a percent (%) of all their money spent on our website. 
						Additionally you can share your affiliate link through social networks like facebook and twitter.','ProjectTheme'); ?>
                    </div>
                
                
                </div>
                </div>
                </div>
                
                <div class="clear10"></div>
                
                
                <div class="my_box3"> 
            	<div class="padd10"> 
            
            	<div class="box_title"><?php _e("Your Affiliate Users", "ProjectTheme"); ?></div>
                <div class="box_content">  
            	
                <?php
				
					global $wpdb;
					
					$s = "select * from ".$wpdb->prefix."project_affiliate_users where owner_id='$uid' order by id desc";
					$r = $wpdb->get_results($s);
					
					if(count($r) > 0)
					{
						?>
                        
                        <table width="100%">
                        <tr>
                        <td><strong><?php _e('Username','ProjectTheme'); ?></strong></td>
                        <td><strong><?php _e('Joined On','ProjectTheme'); ?></strong></td>
                        </tr>
                        
                        
                        
                        <?php
						
						foreach($r as $row):
							
							$usr = get_userdata($row->affiliate_id);
						
							echo '<tr>';
							echo '<td><a href="'.get_bloginfo('siteurl').'/?p_action=user_profile&post_author='.$usr->ID.'">'.$usr->user_login.'</a></td>';
							echo '<td>'.date_i18n('d-m-Y H:i:s',$row->datemade).'</td>';
							echo '</tr>';
						endforeach;
							
						?> </table> <?php
					}
					else
					{
						_e('Sorry you do not have any affiliate users right now.','ProjectTheme');	
					}
				
				?>
                
                 
                
                </div> 
                </div>
                </div>
                
                
                <div class="clear10"></div>
                
                
                <div class="my_box3"> 
            	<div class="padd10"> 
            
            	<div class="box_title"><?php _e("Your Affiliate Earnings", "ProjectTheme"); ?></div>
                <div class="box_content">  
            	
                <?php
				
					global $wpdb;
					
					$s = "select * from ".$wpdb->prefix."project_affiliate_payouts where uid='$uid' order by id desc";
					$r = $wpdb->get_results($s);
					
					if(count($r) > 0)
					{
						?>
                        
                        <table width="100%">
                        <tr>
                        <td><strong><?php _e('Project','ProjectTheme'); ?></strong></td>
                        <td><strong><?php _e('Amount','ProjectTheme'); ?></strong></td>
                        <td><strong><?php _e('DateMade','ProjectTheme'); ?></strong></td>
                        <td><strong><?php _e('Paid?','ProjectTheme'); ?></strong></td>
                        </tr>
                        
                        
                        
                        <?php
						
						foreach($r as $row):
							
							$usr = get_userdata($row->affiliate_id);
							$prj = get_post($row->pid);
							
							echo '<tr>';
							echo '<td><a href="'.get_permalink($row->pid).'">'.$prj->post_title.'</a></td>';
							echo '<td>'.projecttheme_get_show_price($row->moneymade).'</td>';
							echo '<td>'.date_i18n('d-m-Y H:i:s',$row->datemade).'</td>';
							echo '<td>'.($row->paid == 0 ? __('No','ProjectTheme') : __('Yes','ProjectTheme')).'</td>';
							echo '</tr>';
						endforeach;
							
						?> </table> <?php
					}
					else
					{
						_e('Sorry you do not have any earnings now.','ProjectTheme');	
					}
				
				?>
                
                 
                
                </div> 
                </div>
                </div>
                
                
                </div>   
<?php
		ProjectTheme_get_users_links();	
	
}


?>