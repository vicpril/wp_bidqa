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

function ProjectTheme_my_account_milestones_area_function()
{
		global $current_user, $wpdb, $wp_query;
		get_currentuserinfo();
		$uid = $current_user->ID;
		
?>
    	<div id="content" class="account-main-area">
        	<?php
			
				if(ProjectTheme_is_user_business($uid) == true):
				if(isset($_GET['release_id'])):
				?>
				
                <div class="my_box3">
            	<div class="padd10">
            
            	<div class="box_title"><?php _e("Releasing Milestone Payment", "ProjectTheme"); ?></div>
                <div class="box_content"> 
                
                		<?php
						
							$release_id = intval($_GET['release_id']);
							$s = "select * from ".$wpdb->prefix."project_milestone where id='$release_id'";
							$r = $wpdb->get_results($s);
							
							if(count($r) > 0)
							{
								$row = $r[0];
								$am = projecttheme_get_show_price($row->amount);
								$prj = get_post($row->pid);
								$prj = $prj->post_title;
								
								$serv = get_userdata($row->uid);
								$serv = $serv->user_login;
								
								?>
                                
                                <form method="post">
                                <input type="hidden" value="<?php echo $_GET['release_id'] ?>" name="release_id" />
                                	
                                    <?php printf(__('Are you sure you want to release the payment of <b>%s</b> for the project <b>%s</b> to the service provider <b>%s</b> ?','ProjectTheme'), $am, $prj, $serv); ?>
                                	<br/><br/>
                                <?php
									
									$cr = projectTheme_get_credits($current_user->ID);
									if($cr < $row->amount):
								
								?>    
                                
                                	<div class="error">
                                    <?php printf(__('You do not have enough balance to pay this milestone. <a href="%s">Click here</a> to add more balance.','ProjectTheme'), ProjectTheme_get_payments_page_url('deposit')); ?>
                                    </div>
                                
                               <?php else: ?>
                                
                                    <input type="submit" name="submits1yes_me_ok_p" value="<?php _e('Yes, release','ProjectTheme') ?>" value="yes" />                                    
                                    <input type="submit" name="submits1no_me_thing_ok"  value="<?php _e('No, do not release','ProjectTheme') ?>" value="no" />
                               
                               <?php endif; ?> 
                                </form>
                                
                                <?php								
								
							}
							else echo 'my_err_00';
						
						?>
                    
                    
                </div>
                </div>
                </div>
                
                
                
                <?php
				elseif($_GET['submit_ok_p']):
					echo '<div class="saved_thing">Your milestone payment has been released.</div>';
				endif;
				
				?>
            <div class="my_box3">
            	<div class="padd10">
            
            	<div class="box_title"><?php _e("Create Milestone Payment", "ProjectTheme"); ?></div>
                <div class="box_content"> 
            	<?php
				
					if(isset($_POST['submit_milestone']))
					{
						$nok = 0; $error1 = array();
						$projectss 			= $_POST['projectss'];
						$amount_text 		= trim($_POST['amount_text']);
						$completion_date 	= strtotime($_POST['completion_date']);
						$completion_date2 	= $_POST['completion_date'];
						$tm 				= current_time('timestamp',0);
						$description 		= nl2br($_POST['description']);
						$pid = $projectss;
						
						if(empty($projectss)) { $nok = 1; $error1[] = __('You need to select a project for your payment.','ProjectTheme'); }
						if(empty($amount_text) or !is_numeric($amount_text)) { $nok = 1; $error1[] = __('Make sure you type in a payment amount for your milestone, and its numeric.','ProjectTheme'); }
						if(empty($description) ) { $nok = 1; $error1[] = __('Please provide a description for your milestone payment.','ProjectTheme'); }
						if($completion_date < $tm) { $nok = 1; $error1[] = __('The completion date must be a date in the future.','ProjectTheme'); }
						
						if($nok == 0)
						{
							/*$projectTheme_get_winner_bid 	= projectTheme_get_winner_bid($pid);
							$uid_of_winner 					= $projectTheme_get_winner_bid->uid;*/
							$uid_of_winner = $_POST['uids'];
							
							//$s1 = "select * from ".$wpdb->prefix."project_milestone where pid='$pid' AND completion_date='$completion_date' ";
							//$r1 = $wpdb->get_results($s1);
							//mysql_query($s1) or die(mysql_error());
							
							//if(count($r1) == 0){
							
								$s1 = "insert into ".$wpdb->prefix."project_milestone (owner, pid, uid, description_content, datemade, completion_date, amount) 
								values('$uid','$projectss','$uid_of_winner','$description','$tm', '$completion_date', '$amount_text')";
								$wpdb->query($s1);
								 
							
							//}
							
							echo '<div class="saved_thing">'.__('Your milestone payment has been created.','ProjectTheme').'</div>';
							 
							$amount_text = '';
							$completion_date2 = '';	
							$description = '';
						}
						else
						{
							echo '<div class="error">';
								foreach($error1 as $ee) echo '<li>'.$ee.'</li> ';
							echo '</div> <div class="clear10"></div>';	
						}
						
					}
				
				
				?>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
				<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
        		<script src="https://ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script>
        		<script src="<?php echo get_bloginfo('template_url'); ?>/js/jquery.iframe-transport.js"></script>
        		<script src="<?php echo get_bloginfo('template_url'); ?>/js/jquery.fileupload.js"></script>
        		<script src="<?php echo get_bloginfo('template_url'); ?>/js/jquery.fileupload-ui.js"></script>
        		<script src="<?php echo get_bloginfo('template_url'); ?>/js/application.js"></script>  	
                <link rel="stylesheet" media="all" type="text/css" href="<?php echo get_bloginfo('template_url'); ?>/css/ui_thing.css" />
				<script type="text/javascript" language="javascript" src="<?php echo get_bloginfo('template_url'); ?>/js/timepicker.js"></script>
        
        	<script>
 
			
			jQuery(document).ready(function() {
				 jQuery('#completion_date').datetimepicker({
				showSecond: false,
				timeFormat: 'hh:mm:ss',
				
					currentText: '<?php _e('Now','ProjectTheme'); ?>',
					closeText: '<?php _e('Done','ProjectTheme'); ?>',
					ampm: false,
					dateFormat: 'mm/dd/yy',
					timeFormat: 'hh:mm tt',
					timeSuffix: '',
					 
					timeOnlyTitle: '<?php _e('Choose Time','ProjectTheme'); ?>',
					timeText: '<?php _e('Time','ProjectTheme'); ?>',
					hourText: '<?php _e('Hour','ProjectTheme'); ?>',
					minuteText: '<?php _e('Minute','ProjectTheme'); ?>',
					secondText: '<?php _e('Second','ProjectTheme'); ?>',
					timezoneText: '<?php _e('Time Zone','ProjectTheme'); ?>'
			
			});});
 
 		</script>

 		<script>
				
				function on_proj_sel()
				{
					/*var sel_value = jQuery("#my_proj_sel").val();
					
					$.post("<?php bloginfo('siteurl'); ?>/?get_my_project_vl_thing=1", {queryString: ""+sel_value+""}, function(data){
						if(data.length >0) {
							
							//var currency = '<?php echo ProjectTheme_get_currency() ?>';
							//jQuery("#my_escrow_amount").html(currency  + data);
							//jQuery("#amount").val(data);
							jQuery("#win_providers").html(data);
							
					
							
						}
					});*/
					
			 		var sel_value = jQuery("#my_proj_sel").val();
			 		
			 		$.post("<?php bloginfo('siteurl'); ?>/?get_my_project_vl_thing=1", {queryString: ""+sel_value+""}, function(data){
			 			if(data.length >0) {
			 				
			 				//var currency = '<?php echo ProjectTheme_get_currency() ?>';
			 				/*jQuery("#my_escrow_amount").html(currency  + data);
			 				jQuery("#amount").val(data);*/
			 				jQuery("#win_providers").html(data);
			 				
			 		
			 				
			 			}
			 		});
					
				}
				
				<?php 
					
					if(!empty($_GET['poid']))
					{
						?>
						jQuery(function() {
							  on_proj_sel();
							});
						
						<?php
					}
				
				?>

				/*jQuery(document).ready(function(){
					jQuery('#make_esc_providers').submit(function(){
						jQuery("#amount").val(jQuery('#win_providers').val());
					});
				});*/
				
				
				</script>
        
        <form method="post" action="<?php echo get_permalink(get_option('ProjectTheme_my_account_milestones_id')) ?>">
                <table width="100%">
                <tr>
              		<td><?php _e('Select Project:','ProjectTheme'); ?> </td> 
                    <td><?php $xx = ProjectTheme_get_my_awarded_projects($uid); echo $xx == false ? _e('There are no projects in progress.','ProjectTheme') : $xx; ?> </td> 
               </tr>

               <tr>
                    <td><?php _e('For Provider','ProjectTheme'); ?>:</td><td id="win_providers"></td>
                </tr>
               
               
               <tr>
              		<td><?php _e('Amount:','ProjectTheme'); ?> </td> 
                    <td><input type="text" size="15" id="amount_text" name="amount_text" value="<?php echo isset($amount_text) ? $amount_text : ''; ?>" /> <?php echo projecttheme_get_currency() ?> </td> 
               </tr>
               
               
               <tr>
              		<td valign="top"><?php _e('Description:','ProjectTheme'); ?> </td> 
                    <td><textarea rows="5" cols="40" name="description" id="description"><?php echo str_replace("<br />", "", $description); ?></textarea></td> 
               </tr>
               
               <tr>
              		<td><?php _e('Completion Date:','ProjectTheme'); ?> </td> 
                    <td><input type="text" size="25" id="completion_date" name="completion_date"  value="<?php echo isset($completion_date2) ? $completion_date2 : ''; ?>" /></td> 
               </tr>
               
               
                <tr>
              		<td></td> 
                    <td><input type="submit" id="submit_milestone" value="<?php _e('Create Milestone','ProjectTheme') ?>" name="submit_milestone" /></td> 
               </tr>
               
               
               
              
              </table>
          </form>
                
                </div>
                </div>
                </div>
                
                <div class="clear10"></div>
                <?php endif; ?>
                
            <?php if(ProjectTheme_is_user_business($uid)) { ?>    
            <div class="my_box3">
            	<div class="padd10">
            
            	<div class="box_title"><?php _e("Outgoing Milestone Payments", "ProjectTheme"); ?></div>
                <div class="box_content"> 
            	
                <?php
					
					$s = "select * from ".$wpdb->prefix."project_milestone where owner='$uid' AND released='0' order by datemade desc";
					$r = $wpdb->get_results($s);
					
					if(count($r) > 0)
					{
						?>
                        <table class="bdd_bdd" width="100%">
                        	<tr>
                            	<td width="150"><?php _e('Project','ProjectTheme'); ?></td>
                                <td><?php _e('Service Provider','ProjectTheme'); ?></td>
                                <td><?php _e('Amount','ProjectTheme'); ?></td>
                                <td width="170"><?php _e('Description','ProjectTheme'); ?></td>                                
                                <td><?php _e('Due Date','ProjectTheme'); ?></td>
                                <td><?php _e('Options','ProjectTheme'); ?></td>
                            </tr>
                        
                        
                        <?php	
							foreach($r as $row):
							
							$post_p = get_post($row->pid);
							$project_title = $post_p->post_title;
							$user_of_milestone = get_userdata($row->uid);
						?>
                				<tr>
                                	<td><?php echo '<a href="'.get_permalink($row->pid).'">'.$project_title.'</a>' ?></td>
                                    <td><?php echo '<a href="'.ProjectTheme_get_user_profile_link($user_of_milestone->ID).'">'.$user_of_milestone->user_login.'</a>' ?></td>
                                    <td><?php echo projecttheme_get_show_price($row->amount) ?></td>
                                    <td><?php echo $row->description_content ?></td>
                                    <td><?php echo date_i18n('d-M-Y',$row->completion_date) ?></td>
                                	<td><a href="<?php echo projectTheme_release_milestone_link($row->id) ?>" class="green_btn block"><?php _e('Release Payment','ProjectTheme') ?></a></td>
                                </tr>
                
                		<?php endforeach; ?>
                        
                        </table>
                
                <?php } else { _e('There are no outgoing payments.','ProjectTheme'); } ?>
                
                </div>
                </div>
                </div>
                 <div class="clear10"></div>
            <? } ?>    
                
                
                
                  <div class="my_box3">
            	<div class="padd10">
            
            	<div class="box_title"><?php _e("Incoming Milestone Payments", "ProjectTheme"); ?></div>
                <div class="box_content"> 
            	
                
                
                <?php
					
					$s = "select * from ".$wpdb->prefix."project_milestone where uid='$uid' AND released='0' order by datemade desc";
					$r = $wpdb->get_results($s);
					
					if(count($r) > 0)
					{
						?>
                        <table class="bdd_bdd" width="100%">
                        	<tr>
                            	<td width="150"><?php _e('Project','ProjectTheme'); ?></td>
                                <!--<td><?php _e('Service Provider','ProjectTheme'); ?></td>-->
                                <td><?php _e('Service Owner','ProjectTheme'); ?></td>
                                <td><?php _e('Amount','ProjectTheme'); ?></td>
                                <td width="170"><?php _e('Description','ProjectTheme'); ?></td>                                
                                <td><?php _e('Due Date','ProjectTheme'); ?></td>
                                 
                            </tr>
                        
                        
                        <?php	
							foreach($r as $row):
							
							$post_p = get_post($row->pid);
							$project_title = $post_p->post_title;
							//$user_of_milestone = get_userdata($row->uid);
							$user_of_milestone = get_userdata($row->owner);
						?>
                				<tr>
                                	<td><?php echo '<a href="'.get_permalink($row->pid).'">'.$project_title.'</a>' ?></td>
                                    <td><?php echo '<a href="'.ProjectTheme_get_user_profile_link($user_of_milestone->ID).'">'.$user_of_milestone->user_login.'</a>' ?></td>
                                    <td><?php echo projecttheme_get_show_price($row->amount) ?></td>
                                    <td><?php echo $row->description_content ?></td>
                                    <td><?php echo date_i18n('d-M-Y',$row->completion_date) ?></td>
                                	 
                                </tr>
                
                		<?php endforeach; ?>
                        
                        </table>
                
                <?php } else { _e('There are no incoming payments.','ProjectTheme'); } ?>
                
                </div>
                </div>
                </div>
           
                
                </div>   
<?php
		ProjectTheme_get_users_links();

}
	
?>