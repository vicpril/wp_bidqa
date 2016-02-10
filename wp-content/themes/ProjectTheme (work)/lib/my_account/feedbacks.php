<?php

function ProjectTheme_my_account_feedbacks_area_function()
{
	
		global $current_user, $wpdb, $wp_query;
		get_currentuserinfo();
		$uid = $current_user->ID;
		
?>
  <div id="content" class="account-main-area">
	
	 
	 <div class="my_box3">
            	
            
            	<div class="box_title"><?php _e("Reviews I need to award",'ProjectTheme'); ?></div>
                <div class="box_content">    
				
              	<?php
					
					global $wpdb;
					$query = "select * from ".$wpdb->prefix."project_ratings where fromuser='$uid' AND awarded='0'";
					$r = $wpdb->get_results($query);
					
					if(count($r) > 0)
					{
						echo '<table width="100%">';
							echo '<tr>';
								echo '<th>&nbsp;</th>';	
								echo '<th><b>'.__('Project Title','ProjectTheme').'</b></th>';
								echo '<th><b>'.__('To User','ProjectTheme').'</b></th>';									
								echo '<th><b>'.__('Aquired on','ProjectTheme').'</b></th>';								
								echo '<th><b>'.__('Price','ProjectTheme').'</b></th>';
								echo '<th><b>'.__('Options','ProjectTheme').'</b></th>';
							
							echo '</tr>';	
						
						
						foreach($r as $row)
						{
							$post 	= $row->pid;
							$post 	= get_post($post);
							$bid 	= projectTheme_get_winner_bid($row->pid);
							$user 	= get_userdata($row->touser);
							if( ! $user->ID) continue;
							$dmt2 = get_post_meta($row->pid,'closed_date',true);
							
							if(!empty($dmt2))
							$dmt = date_i18n('d-M-Y H:i:s', $dmt2);
							
							echo '<tr>';
								
								echo '<th><a href="'.get_permalink($row->pid).'"><img class="img_class" width="42" height="42" src="'.ProjectTheme_get_first_post_image($row->pid, 42, 42).'" 
                                alt="'.$post->post_title.'" /></a></th>';	
								echo '<th><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a></th>';	
								echo '<th><a href="'.ProjectTheme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></th>';							
								echo '<th>'.$dmt.'</th>';								
								echo '<th>'.projectTheme_get_show_price($bid->bid).'</th>';
								echo '<th><a href="'.get_bloginfo('siteurl').'/?p_action=rate_user&rid='.$row->id.'">'.__('Rate User','ProjectTheme').'</a></th>';
							
							echo '</tr>';
							
						}
						
						echo '</table>';
					}
					else
					{
						_e("There are no reviews to be awarded.","ProjectTheme");	
					}
				?>
                
                
           </div>
           </div>    
           
           <!-- ##### -->
           <div class="clear10"></div>

           <!-- --------------------------------- -->

           	 <div class="my_box3">
                       	
                       
                       	<div class="box_title"><?php _e("Reviews I can edit",'ProjectTheme'); ?></div>
                           <div class="box_content">    
           				
                         	<?php
           					
           					global $wpdb;
           					$query = "select * from ".$wpdb->prefix."project_ratings where fromuser='$uid' AND awarded='1'";
           					$r = $wpdb->get_results($query);
           					
           					if(count($r) > 0)
           					{
           						echo '<table width="100%" class="tb_pad">';
           							echo '<tr>';
           								echo '<th>&nbsp;</th>';	
           								echo '<th><b>'.__('Project Title','ProjectTheme').'</b></th>';
           								echo '<th><b>'.__('To User','ProjectTheme').'</b></th>';									
           								echo '<th><b>'.__('Aquired on','ProjectTheme').'</b></th>';								
           								echo '<th><b>'.__('Comment','ProjectTheme').'</b></th>';
           								echo '<th><b>'.__('Options','ProjectTheme').'</b></th>';
           							
           							echo '</tr>';	
           						
           						
           						foreach($r as $row)
           						{
           							$post 	= $row->pid;
           							$post 	= get_post($post);
           							$bid 	= projectTheme_get_winner_bid($row->pid);
           							$user 	= get_userdata($row->touser);
           							
           							$dmt2 = get_post_meta($row->pid,'closed_date',true);
           							$cur_com = urlencode($row->comment);
           							if(!empty($dmt2))
           							$dmt = date_i18n('d-M-Y H:i:s', $dmt2);
           							
           							echo '<tr>';
           								
           								echo '<th><a href="'.get_permalink($row->pid).'"><img class="img_class" width="42" height="42" src="'.ProjectTheme_get_first_post_image($row->pid, 42, 42).'" 
                                           alt="'.$post->post_title.'" /></a></th>';	
           								echo '<th><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a></th>';	
           								echo '<th><a href="'.ProjectTheme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></th>';							
           								echo '<th>'.$dmt.'</th>';								
           								echo '<th class="word_break">'.$row->comment.'</th>';
           								echo '<th><a href="'.get_bloginfo('siteurl').'/?p_action=rate_user&rid='.$row->id.'&curent_comment='.$cur_com.'">'.__('Edit','ProjectTheme').'</a></th>';
           							
           							echo '</tr>';
           							
           						}
           						
           						echo '</table>';
           					}
           					else
           					{
           						_e("There are no reviews to be awarded.","ProjectTheme");	
           					}
           				?>
                           
                           
                      </div>
                      </div>    
                      
                      <!-- ##### -->
                      <div class="clear10"></div>
           <!-- ------------------------------------- -->
           <div class="my_box3">
            
            
            	<div class="box_title"><?php _e("Reviews I am expecting ",'ProjectTheme'); ?></div>
                <div class="box_content">    
				
              	<?php
					
					global $wpdb;
					$query = "select * from ".$wpdb->prefix."project_ratings where touser='$uid' AND awarded='0'";
					$r = $wpdb->get_results($query);
					
					if(count($r) > 0)
					{
						echo '<table width="100%">';
							echo '<tr>';
								echo '<th>&nbsp;</th>';	
								echo '<th><b>'.__('Project Title','ProjectTheme').'</b></th>';								
								echo '<th><b>'.__('From User','ProjectTheme').'</b></th>';	
								echo '<th><b>'.__('Aquired on','ProjectTheme').'</b></th>';								
								echo '<th><b>'.__('Price','ProjectTheme').'</b></th>';
								//echo '<th><b>'.__('Options','ProjectTheme').'</b></th>';
							
							echo '</tr>';	
						
						
						foreach($r as $row)
						{
							$post 	= $row->pid;
							$post 	= get_post($post);
							$bid 	= projectTheme_get_winner_bid($row->pid);
							$user 	= get_userdata($row->fromuser);
							
							$dmt2 = get_post_meta($row->pid,'closed_date',true);
							
							if(!empty($dmt2))
							$dmt = date_i18n('d-M-Y H:i:s', $dmt2);
							
							echo '<tr>';
								
								echo '<th><a href="'.get_permalink($row->pid).'"><img class="img_class" width="42" height="42"  src="'.ProjectTheme_get_first_post_image($row->pid, 42, 42).'" 
                                alt="'.$post->post_title.'" /></a></th>';	
								echo '<th><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a></th>';
								echo '<th><a href="'.ProjectTheme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></th>';								
								echo '<th>'.$dmt.'</th>';								
								echo '<th>'.projectTheme_get_show_price($bid->bid).'</th>';
								//echo '<th><a href="#">Rate User</a></th>';
							
							echo '</tr>';
							
						}
						
						echo '</table>';
					}
					else
					{
						_e("There are no reviews to be awarded.","ProjectTheme");	
					}
				?>
                
                
           </div>
           </div>    
           
           <div class="clear10"></div>
           
           <div class="my_box3">
            	
            
            	<div class="box_title"><?php _e("Reviews I was awarded ",'ProjectTheme'); ?></div>
                <div class="box_content">    
				
              	<?php
					
					global $wpdb;
					$query = "select * from ".$wpdb->prefix."project_ratings where touser='$uid' AND awarded='1'";
					$r = $wpdb->get_results($query);
					
					if(count($r) > 0)
					{
						echo '<table width="100%" class="tb_pad">';
							echo '<tr>';
								echo '<th>&nbsp;</th>';	
								echo '<th><b>'.__('Project Title','ProjectTheme').'</b></th>';								
								echo '<th><b>'.__('From User','ProjectTheme').'</b></th>';	
								echo '<th><b>'.__('Aquired on','ProjectTheme').'</b></th>';								
								echo '<th><b>'.__('Price','ProjectTheme').'</b></th>';
								echo '<th><b>'.__('Rating','ProjectTheme').'</b></th>';
								
							
							echo '</tr>';	
						
						
						foreach($r as $row)
						{
							$post 	= $row->pid;
							$post 	= get_post($post);
							$bid 	= projectTheme_get_winner_bid($row->pid);
							$user 	= get_userdata($row->fromuser);
							
							$dmt2 =  $row->datemade;//get_post_meta($row->pid,'closed_date',true);
							if(empty($dmt2)) $dmt2 = current_time('timestamp',0);
							
							if(!empty($dmt2))
							$dmt = date_i18n('d-M-Y H:i:s', $dmt2);
							
							echo '<tr>';
								
								echo '<th><a href="'.get_permalink($row->pid).'"><img width="42" height="42" class="img_class" src="'.ProjectTheme_get_first_post_image($row->pid, 42, 42).'" 
                                alt="'.$post->post_title.'" /></a></th>';	
								echo '<th><a href="'.get_permalink($row->pid).'">'.$post->post_title.'</a></th>';
								echo '<th><a href="'.ProjectTheme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></th>';								
								echo '<th>'.$dmt.'</th>';								
								echo '<th>'.projectTheme_get_show_price($bid->bid).'</th>';
								echo '<th>'.floor($row->grade/2).'/5</th>';
								
							
							echo '</tr>';
							echo '<tr>';
							echo '<th></th>';
							echo '<th colspan="5" class="word_break"><b>'.__('Comment','ProjectTheme').':</b> '.$row->comment.'</th>'	;						
							echo '</tr>';
							
							echo '<tr><th colspan="6"><hr color="#eee" /></th></tr>';
							
						}
						
						echo '</table>';
					}
					else
					{
						_e("There are no reviews to be awarded.","ProjectTheme");	
					}
				?>
                
              
           </div>
           </div>    
	
	
	
	
	
		</div>
        
<?php	
		
		ProjectTheme_get_users_links(); 
		
}

?>