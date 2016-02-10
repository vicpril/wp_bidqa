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

function ProjectTheme_my_account_private_messages_area_function()
{
		global $current_user, $wpdb, $wp_query;
		get_currentuserinfo();
		$uid = $current_user->ID;
	
	
		$myuid = $uid;
	
		
?>
    	 
       <?php 	
            global $wpdb,$wp_rewrite,$wp_query;
		$third_page = $wp_query->query_vars['pg'];
		
		if(empty($third_page)) $third_page = 'home';
		
	
		?>
        <div class="clear10"></div>
      <div id="content" class="account-main-area mess_break">
        
        <div class="my_box3"><div class="padd10">
          <div class="clear10"></div>
          
            
                <ul class="cms_cms"> 
                <li><a href="<?php echo ProjectTheme_get_priv_mess_page_url(); ?>" class="green_btn"><?php _e("Messaging Home","ProjectTheme"); ?></a></li>
               <li> <a href="<?php echo ProjectTheme_get_priv_mess_page_url('send'); ?>" class="green_btn"><?php _e("Send New Message","ProjectTheme"); ?></a></li>
                <li><a href="<?php echo ProjectTheme_get_priv_mess_page_url('inbox'); ?>" class="green_btn"><?php _e("Inbox","ProjectTheme");
				
				global $current_user;
				get_currentuserinfo();
				$rd = projectTheme_get_unread_number_messages($current_user->ID);
				if($rd > 0) echo ' ('.$rd.')';
				
				 ?></a></li>
                <li><a href="<?php echo ProjectTheme_get_priv_mess_page_url('sent-items'); ?>" class="green_btn"><?php _e("Sent Items","ProjectTheme"); ?></a></li>
             	
                </ul>
                
        </div></div>
        <div class="clear10"></div>
        <?php
		
			if($third_page == 'home') {
		
		global $current_user;
			get_currentuserinfo();
			$myuid = $current_user->ID;
		
		?>        
        
		<!-- page content here -->	
			
            
            	
            	<div class="my_box3">
            	
            	<div class="box_title"><?php _e("Latest Received Messages","ProjectTheme"); ?></div>
                <div class="box_content">  
                <?php
				global $wpdb; $uidsss = $current_user->ID;
				$s = "select * from ".$wpdb->prefix."project_pm where user='$uidsss'  AND show_to_destination='1' and approved='1'  order by id desc limit 4";
				$r = $wpdb->get_results($s);
				
				if(count($r) > 0)
				{
					echo '<table width="100%" class="privatemesg">';
					
					echo '<tr>';
						echo '<td>'.__('From User','ProjectTheme').'</td>';
						echo '<td>'.__('Subject','ProjectTheme').'</td>';						
						echo '<td>'.__('Date','ProjectTheme').'</td>';
						echo '<td>'.__('Options','ProjectTheme').'</td>';
						echo '</tr>';
					
					
					
					foreach($r as $row)
					{
						if($row->rd == 0) $cls = 'bold_stuff';
						else $cls = '';
					
						$user = get_userdata($row->initiator);
					
						echo '<tr>';
						echo '<td class="'.$cls.'"><a href="'.get_bloginfo('siteurl').'/?p_action=user_profile&post_author='.$user->ID.'">'.$user->user_login.'</a></td>';
						echo '<td class="'.$cls.'">'.substr($row->subject,0,30).'</td>';						
						//echo '<td class="'.$cls.'">'.date_i18n('d-M-Y H:i:s',$row->datemade).'</td>';
						echo '<td class="'.$cls.' conv_time">'.$row->datemade.'</td>';
						echo '<td><a href="'.ProjectTheme_get_priv_mess_page_url('read-message', $row->id).'">'.__('Read','ProjectTheme').'</a> | 
						<a href="'.ProjectTheme_get_priv_mess_page_url('delete-message', $row->id).'">'.__('Delete','ProjectTheme').'</a></td>';						
						echo '</tr>';

					
					}
					
					
					echo '</table>';
				} else _e('No messages here.','ProjectTheme');
				
				?>
      
            
                </div>
                </div>
            
            <!--#######-->
            
            <div class="clear10"></div>
            
            	<div class="my_box3">
            	
            
            	<div class="box_title"><?php _e("Latest Sent Items","ProjectTheme"); ?></div>
                <div class="box_content">  
                <?php
				global $wpdb; $uidss = $current_user->ID;
				$s = "select * from ".$wpdb->prefix."project_pm where initiator='$uidss'  AND show_to_source='1' order by id desc limit 4";
				$r = $wpdb->get_results($s);
				
				if(count($r) > 0)
				{
					echo '<table width="100%" class="privatemesg">';
					
					echo '<tr>';
						echo '<td>'.__('To User','ProjectTheme').'</td>';
						echo '<td>'.__('Subject','ProjectTheme').'</td>';						
						echo '<td>'.__('Date','ProjectTheme').'</td>';
						echo '<td>'.__('Options','ProjectTheme').'</td>';
						echo '</tr>';
					
					
					
					foreach($r as $row)
					{
						//if($row->rd == 0) $cls = 'bold_stuff';
						//else
						 $cls = '';
					
						$user = get_userdata($row->user);
					
						echo '<tr>';
						echo '<td class="'.$cls.'"><a href="'.ProjectTheme_get_user_profile_link($row->user).'">'.$user->user_login.'</a></td>';
						echo '<td class="'.$cls.'">'.substr($row->subject,0,30).'</td>';						
						//echo '<td class="'.$cls.'">'.date_i18n('d-M-Y H:i:s',$row->datemade).'</td>';
						echo '<td class="'.$cls.' conv_time">'.$row->datemade.'</td>';
						echo '<td><a href="'.ProjectTheme_get_priv_mess_page_url('read-message', $row->id).'">'.__('Read','ProjectTheme').'</a> | 
						<a id="privatedel"  href="'.ProjectTheme_get_priv_mess_page_url('delete-message', $row->id).'">'.__('Delete','ProjectTheme').'</a></td>';
						echo '</tr>';
					
					}
					
					
					echo '</table>';
				}
				else _e('No messages here.','ProjectTheme');
				?>                 
               
                </div>
                </div>
            
            
		<!-- page content here -->	
			
        <?php }
		
		
			elseif($third_page == 'inbox') {
		
			global $current_user;
			get_currentuserinfo();
			$myuid = $current_user->ID;
			//echo $myuid;
		?>        
        
		<!-- page content here -->	
			
            
            	<div class="my_box3">
           
            
            	<div class="box_title"><?php _e("Private Messages: Inbox","ProjectTheme"); ?></div>
                <div class="box_content">  
                <?php
				
				global $wpdb;
				$page_rows = 20;
				$page_rows = apply_filters('ProjectTheme_nr_of_messages_priv_pagination', $page_rows);
				
				$pagenum 	= isset($_GET['pagenum']) ? $_GET['pagenum'] : 1;
				$max 		= ' limit ' .($pagenum - 1) * $page_rows .',' .$page_rows; 
				
				$s 		= "select count(id) tots from ".$wpdb->prefix."project_pm where user='$myuid' AND show_to_destination='1' and approved='1'";
				$r 		= $wpdb->get_results($s);
				$total 	= $r[0]->tots;
				
				$last = ceil($total/$page_rows);
		 
				//-------------------------
				
				$s = "select * from ".$wpdb->prefix."project_pm where user='$myuid' AND show_to_destination='1' and approved='1' order by id desc ". $max;
				$r = $wpdb->get_results($s);
				
				 
				
				if(count($r) > 0)
				{
					?>
                    
                    <script>
					
					$(document).ready(function() {
						//set initial state.

					 $('#privatedel').click(function(){ 
              
              var ss=$(this).parent().text();
             alert(ss);
             
           });
					
						$('#select_all_stuff').change(function() {
							if($(this).is(":checked")) {
								
								$('.message_select_bx').attr("checked", true);
							}
							else
							{
								$('.message_select_bx').attr("checked", false);	
							}
						});
					});
											
					
					</script>
                    
                    <?php
					
					echo '<form method="post" action="'.ProjectTheme_get_priv_mess_page_url('delete-message','','&return=inbox').'">';
					echo '<table width="100%">';
					
					echo '<tr>';
						echo '<td><input type="checkbox" name="" id="select_all_stuff" value="1" /> '.__('Select All','ProjectTheme').' </td>';
						echo '<td>'.__('From User','ProjectTheme').'</td>';
						echo '<td>'.__('Subject','ProjectTheme').'</td>';						
						echo '<td>'.__('Date','ProjectTheme').'</td>';
						echo '<td>'.__('Options','ProjectTheme').'</td>';
						echo '</tr>';
					
					
					
					foreach($r as $row)
					{
						if($row->rd == 0) $cls = 'bold_stuff';
						else $cls = '';
					
						$user = get_userdata($row->initiator);
					
						echo '<tr>';
						echo '<td><input type="checkbox" class="message_select_bx" name="message_ids[]" value="'.$row->id.'" /></td>';
						echo '<td class="'.$cls.'"><a href="'.get_bloginfo('siteurl').'/?p_action=user_profile&post_author='.$user->ID.'">'.$user->user_login.'</a></td>';
						echo '<td class="'.$cls.'">'.substr($row->subject,0,30).'</td>';						
						//echo '<td class="'.$cls.'">'.date_i18n('d-M-Y H:i:s',$row->datemade).'</td>';
						echo '<td class="'.$cls.' conv_time">'.$row->datemade.'</td>';
						echo '<td><a href="'.ProjectTheme_get_priv_mess_page_url('read-message', $row->id).'">'.__('Read','ProjectTheme').'</a> | 
						<a href="'.ProjectTheme_get_priv_mess_page_url('delete-message', $row->id).'">'.__('Delete','ProjectTheme').'</a></td>';
						echo '</tr>';
					
					}
					
					echo '<tr><td colspan="5"><input type="submit" value="'.__('Delete Selected','ProjectTheme').'" name="delete_sel" /></td></tr>';
					echo '<tr><td colspan="5">  ';
					
						 echo ProjectTheme_get_my_pagination_main(get_bloginfo('siteurl'). "/?page_id=".get_option('ProjectTheme_my_account_private_messages_id'), 
						 $pagenum, 'pagenum', $last, '&pg=inbox'); 
					
					echo ' </td></tr>';
					
					
					
					echo '</table></form>';
				} else _e('No messages here.','ProjectTheme');
				
				?>
      
             
                </div>
                </div>
            
            
		<!-- page content here -->	
			
        <?php }
		
		elseif($third_page == 'sent-items') {
		
			global $current_user;
			get_currentuserinfo();
			$myuid = $current_user->ID;
			
			
			
			
		?>        
        		<script>
					
					$(document).ready(function() {
						//set initial state.
					 
					
						$('#select_all_stuff').change(function() {
							if($(this).is(":checked")) {
								
								$('.message_select_bx').attr("checked", true);
							}
							else
							{
								$('.message_select_bx').attr("checked", false);	
							}
						});
					});
											
					
					</script>
		<!-- page content here -->	
			
            
            	<div class="my_box3">
            
            
            	<div class="box_title"><?php _e("Private Messages: Sent Items","ProjectTheme"); ?></div>
                <div class="box_content">  
                <?php
				global $wpdb;
				
				$page_rows = 20;
				$page_rows = apply_filters('ProjectTheme_nr_of_messages_priv_pagination', $page_rows);

				$pagenum 	= isset($_GET['pagenum']) ? $_GET['pagenum'] : 1;
				$max 		= ' limit ' .($pagenum - 1) * $page_rows .',' .$page_rows; 
				
				//---------------------------------
				
				$s 		= "select count(id) tots from ".$wpdb->prefix."project_pm where initiator='$myuid' AND show_to_source='1' and approved='1'";
				$r 		= $wpdb->get_results($s);
				$total 	= $r[0]->tots;
				
				$last = ceil($total/$page_rows);
				
				//---------------------------------
				
				$s = "select * from ".$wpdb->prefix."project_pm where initiator='$myuid' AND show_to_source='1' and approved='1' order by id desc ".$max;
				$r = $wpdb->get_results($s);
				
				if(count($r) > 0)
				{
					
					echo '<form method="post" action="'.ProjectTheme_get_priv_mess_page_url('delete-message','','&return=outbox').'">';
					echo '<table width="100%">';
					
					echo '<tr>';
						echo '<td><input type="checkbox" name="" id="select_all_stuff" value="1" /> '.__('Select All','ProjectTheme').' </td>';
						echo '<td>'.__('To User','ProjectTheme').'</td>';
						echo '<td>'.__('Subject','ProjectTheme').'</td>';						
						echo '<td>'.__('Date','ProjectTheme').'</td>';
						echo '<td>'.__('Options','ProjectTheme').'</td>';
						echo '</tr>';
					
					
					
					foreach($r as $row)
					{
						//if($row->rd == 0) $cls = 'bold_stuff';
						//else 
						$cls = '';
					
						$user = get_userdata($row->user);
					
						echo '<tr>';
						echo '<td><input type="checkbox" class="message_select_bx" name="message_ids[]" value="'.$row->id.'" /></td>';
						echo '<td class="'.$cls.'"><a href="'.ProjectTheme_get_user_profile_link($row->user).'">'.$user->user_login.'</a></td>';
						echo '<td class="'.$cls.'">'.substr($row->subject,0,30).'</td>';						
						//echo '<td class="'.$cls.'">'.date_i18n('d-M-Y H:i:s',$row->datemade).'</td>';
						echo '<td class="'.$cls.' conv_time">'.$row->datemade.'</td>';
						echo '<td><a href="'.ProjectTheme_get_priv_mess_page_url('read-message', $row->id).'">'.__('Read','ProjectTheme').'</a> | 
						<a href="'.ProjectTheme_get_priv_mess_page_url('delete-message', $row->id).'">'.__('Delete','ProjectTheme').'</a></td>';
						echo '</tr>';
					
					}
					
					echo '<tr><td colspan="5"><input type="submit" value="'.__('Delete Selected','ProjectTheme').'" name="delete_sel" /></td></tr>';
					echo '<tr><td colspan="5">  ';

						echo ProjectTheme_get_my_pagination_main(get_bloginfo('siteurl'). "/?page_id=".get_option('ProjectTheme_my_account_private_messages_id'), 
						 $pagenum, 'pagenum', $last, '&pg=sent-items'); 
						
					
					echo ' </td></tr>';
					
					echo '</table></form>';
				}
				else _e('No messages here.','ProjectTheme');
				?>
      
                </div>
                </div>
        
            
            
		<!-- page content here -->	
			
        <?php }
		
		
		elseif($third_page == 'delete-message') {
		
			
			$id = $_GET['id'];
			$s = "select * from ".$wpdb->prefix."project_pm where id='$id' AND (user='$myuid' OR initiator='$myuid')";
			$r = $wpdb->get_results($s);
			$row = $r[0];
			
			global $current_user;
			get_currentuserinfo();
			$myuid = $current_user->ID;
				
			
			if($myuid == $row->initiator) $owner = true; else $owner = false;
			
			//if(!$owner)
			//$wpdb->query("update_i18n ".$wpdb->prefix."auction_pm set rd='1' where id='{$row->id}'");
			
	
		?>        
        
		<!-- page content here -->	
			
            
            	<div class="my_box3">
            	<div class="padd10">
            
            	<div class="box_title"><?php 
				
				if(isset($_POST['delete_sel']))
				{
					_e("Delete Selected Message: ","ProjectTheme"); 
					
				}
				else				
				{
					_e("Delete Message: ","ProjectTheme"); 
					echo " ".substr($row->subject,0,30);
				}
				
				 ?></div>
                <div class="box_content">  
                
                <?php
					if(isset($_POST['message_ids']))
					{
						$message_ids2 = $_POST['message_ids'];
						foreach($message_ids2 as $message_id)
						{
							$ss1 = "select * from ".$wpdb->prefix."project_pm where id='$message_id'";
							$rr1 = $wpdb->get_results($ss1);
							$rrow1 = $rr1[0];
							echo '#'.$rrow1->id." ".substr($rrow1->subject,0,30).'<br/>';	
							
						} echo '<br/>';
					}
				?>
                
                <?php //echo $row->content; ?>
      <br/> <br/>
      
      <?php if(1): //$owner == false): 
	  
	  	if(isset($_POST['delete_sel'])):
		
			$message_ids = $_POST['message_ids'];
			if(count($message_ids) == 0)
			{
				_e("No messsages selected.","ProjectTheme"); 	
			}
			else
			{
				$attash = '';
				foreach($message_ids as $message_id)
				{
					$attash .= '&message_id[]='.$message_id;	
				}
				
				?>
					
                   <a href="javascript:history.go(-1)<?php //echo ($_GET['rdr']); ?>" class="nice_link"><?php _e("Cancel",'ProjectTheme'); ?></a>
                    
                    <a href="<?php echo ProjectTheme_get_priv_mess_page_url('delete-message', '', '&confirm_message_deletion=yes&return='.urlencode($_GET['rdr'])).$attash; ?>" 
       				class="nice_link"  onclick="return privatedel()"><?php _e("Confirm Deletion",'ProjectTheme'); ?></a>
                  
                <?php
			}
		
		else:
	  
	  ?>
      
      <a href="javascript:history.go(-1)<?php //echo ($_GET['rdr']); ?>" class="nice_link"><?php _e("Cancel",'ProjectTheme'); ?></a>
      
       <a href="<?php echo ProjectTheme_get_priv_mess_page_url('delete-message', $row->id, '&confirm_message_deletion=yes&return='.urlencode($_GET['rdr'])); ?>" 
       class="nice_link"  onclick="return privatedel()"><?php _e("Confirm Deletion",'ProjectTheme'); ?></a> <?php endif; endif; ?>
                </div>
                </div>
                </div>
            
           <script type="text/javascript">
				   
					function privatedel()
					{
						var ms='Want To Delete <?php echo $row->subject ; ?>';
						//alert(ms);
						if(confirm(ms))
						{return true;}
					else {return false;}
					}
					</script> 
		<!-- page content here -->	
			
        <?php }
		
		
		elseif($third_page == 'read-message') {
		
			global $current_user, $wpdb;
			get_currentuserinfo();
			$myuid = $current_user->ID;
			
			$id = $_GET['id'];
			$s = "select * from ".$wpdb->prefix."project_pm where id='$id'  AND (user='$myuid' OR initiator='$myuid')";
			$r = $wpdb->get_results($s);
			$row = $r[0];
			
			if($myuid == $row->initiator) $owner = true; else $owner = false;
			
            // mark messags is read if user = myuid
			if($myuid == $row->user) {
                $wpdb->query("update ".$wpdb->prefix."project_pm set rd='1' where id='{$row->id}'");
            }
			
	
		?>        
        
		<!-- page content here -->	
			
            
            	<div class="my_box3">
           
            
            	<div class="box_title"><?php _e("Read Message: ","ProjectTheme"); echo " ".$row->subject ?></div>
                <div class="box_content">  
                <?php echo $row->content; ?>
      <br/> <br/>
      
      <?php
	  
	  	if(!empty($row->file_attached))
		echo sprintf(__('File Attached: %s','ProjectTheme') , '<a href="'.wp_get_attachment_url($row->file_attached).'">'.wp_get_attachment_url($row->file_attached)."</a>") ;
	  
	  ?>
      
      
      <?php if($owner == false): ?>
       <br><a href="<?php echo ProjectTheme_get_priv_mess_page_url('send', '', '&pid='.$row->pid.'&uid='.$row->initiator.'&in_reply_to='.$row->id); ?>" class="nice_link"><?php _e("Reply",'ProjectTheme'); ?></a> <?php endif; ?>
                </div>
                </div>
             
            
		<!-- page content here -->	
			
        <?php }
		 elseif($third_page == 'send') { ?>
        <?php
		
			$pid = $_GET['pid'];
			$uid = $_GET['uid'];
		
			$user = get_userdata($uid);
		
			if(!empty($pid))
			{
				$post = get_post($pid);
				$subject = "RE: ".$post->post_title;
			}
			elseif(!empty($_GET['in_reply_to']))
			{
				global $wpdb;
				$ssp = "select * from ".$wpdb->prefix."project_pm where id='".$_GET['in_reply_to']."'";
				$sspq = $wpdb->get_results($ssp);
				
				if (strpos($sspq[0]->subject ,'RE:') !== false) { $subject = $sspq[0]->subject; }
				else
				$subject = "RE: ".substr($sspq[0]->subject,0,30);//$sspq[0]->subject;
			}
		
		
			if(isset($_POST['send_a']))
			{
				
				require_once(ABSPATH . "wp-admin" . '/includes/file.php');
				require_once(ABSPATH . "wp-admin" . '/includes/image.php');
				
				
				if(!empty($_FILES['file_instant']['name'])):
	  				
					$pids = 0;
					$upload_overrides 	= array( 'test_form' => true );
					$uploaded_file 		= wp_handle_upload($_FILES['file_instant'], $upload_overrides);
					
					$file_name_and_location = $uploaded_file['file'];
					$file_title_for_media_library = $_FILES['file_instant']['name'];
							
					$arr_file_type 		= wp_check_filetype(basename($_FILES['file_instant']['name']));
					$uploaded_file_type = $arr_file_type['type'];
			
					
					if($uploaded_file_type == "application/zip" or $uploaded_file_type == "application/pdf" or $uploaded_file_type == "application/msword" or $uploaded_file_type == "application/msexcel" or 
					$uploaded_file_type == "application/doc" or $uploaded_file_type == "application/docx" or 
					$uploaded_file_type == "application/xls" or $uploaded_file_type == "application/xlsx" or $uploaded_file_type == "application/csv" or $uploaded_file_type == "application/ppt" or 
					$uploaded_file_type == "application/pptx" or $uploaded_file_type == "application/vnd.ms-excel" OR $uploaded_file_type == "application/txt" OR $uploaded_file_type == "text/plain"
					or $uploaded_file_type == "application/vnd.ms-powerpoint" or $uploaded_file_type == "application/vnd.openxmlformats-officedocument.presentationml.presentation"
					
					or $uploaded_file_type == "application/octet-stream" 
					or $uploaded_file_type == "image/png" 
					or $uploaded_file_type == "image/jpg"  or $uploaded_file_type == "image/jpeg" 
					
					  or $uploaded_file_type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" 
					  or $uploaded_file_type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"  )
					{
						
						
					
						$attachment = array(
										'post_mime_type' => $uploaded_file_type,
										'post_title' => 'Uploaded ZIP ' . addslashes($file_title_for_media_library),
										'post_content' => '',
										'post_status' => 'inherit',
										'post_parent' =>  0,
		
										'post_author' => $uid,
									);
								 
						$attach_id 		= wp_insert_attachment( $attachment, $file_name_and_location, $pids );
						$attach_data 	= wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
						wp_update_attachment_metadata($attach_id,  $attach_data);
						
						
						
						
					} else $error_mm = '1';
				
				endif;
				
				
				$subject = $_POST['subject_a'];
				$message = $_POST['message_a'];
				$uids = $_POST['to_as'];
				
				if(empty($uids))
				{
					$uids = $_GET['uid'];
					 
				}
				
//				elseif(isset($_POST['projectss'])){
//					if(!empty($_POST['projectss'])){
//						$uids = $_POST['projectss'];
//                    }
//                }
				
				
				
				if(!empty($_POST['to_as']) || !empty($_POST['projectss']))
				{
                    
					global $current_user;
					get_currentuserinfo();
                    
                    if(is_array($_POST['to_as']) || is_array($_POST['projectss'])){
                        
                        for ($index = 0; $index < count($_POST['projectss']); $index++) {
                            
                            if ($_POST['projectss'][$index] !== "") {
                                
                                $to_as = get_user_by('id', $_POST['projectss'][$index]);
                                
                            }else{
                                
                                $to_as = get_user_by('email', $_POST['to_as'][$index]);

                            }
                            
                            $uids = projectTheme_get_userid_from_username($to_as->user_login);	

                            ProjectTheme_send_priv_mess_to_person($uids, $uid,$error_mm, $subject,$message,$pid, $attach_id, $user, $post,$cant_send );
                        }
                        
                                                
                    }else{ // it isn't array
                        
                        if ($_POST['projectss'] !== '') {
                            
                            $to_as = get_user_by('id', $_POST['projectss']);
                            
                            
                        } else{
                    
                            $to_as = get_user_by('email', $_POST['to_as']);

                        }
                        
                        $uids = projectTheme_get_userid_from_username($to_as->user_login);	

                        ProjectTheme_send_priv_mess_to_person($uids, $uid, $error_mm, $subject,$message,$pid, $attach_id, $user, $post,$cant_send );
                        //if($uids == $current_user->ID) { $uids = false; $error_mm = 1; $cant_send = 1; }
                        
                        
                    }
				}
				
				
                
                
                
				
        
            
			}
			else
			{
		
		
		?>   
         <script>
		 function getdataoftextarea()
		 {
		 	 alert($(".message_content").val());
		 }
		 function validate_form()
			 {
				 
		var to = document.form5.to_as.value;
        if (to == null || to == "")
		{
        alert("Please enter email id");
		document.form5.to_as.focus();
		
        return false;
				
       }
	   var to = document.form5.to_as.value;
	   var email= /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	   var em= email.test(to);
	   if(em==false)
		{
		alert("please enter valide email id");
		document.form5.to_as.focus();
		return false;
		}
       
				 
		var subj = document.form5.subject_a.value;
        if (subj == null || subj == "")
		{
        alert("Please fill the subject field");
		document.form5.subject_a.focus();
		
        return false;
				
       }
	 // var x1 = tinymce.get('jander').getContent();
	 // alert(x1);
	var x = tinymce.get('message_a').getContent();
    if (x == null || x == "")
		{
		alert("MESSAGE FIELD CANNOT BE BLANK");
		document.form5.message_a.focus();
		
        return false;
				
       }
	    
	   
}
		 </script>    
        <div class="my_box3">
            	
            
            	<div class="box_title"><?php _e("Send Private Message to: ","ProjectTheme"); ?> <?php echo $user->user_login; ?></div>
                <div class="box_content">  
                <form method="post" enctype="multipart/form-data" name="form5" id="form5" onsubmit="return validate_form();">
                <input type="hidden" name="tm" value="<?php echo current_time('timestamp',0); ?>" />
                <table>
                <?php if(empty($uid)): 
				
				$rtt = ProjectTheme_get_my_awarded_projects2($current_user->ID, true);
				
				?>
                <tr class="send-to-raw">
                <td width="140" class="no_wrap"><?php _e("Send To (e-mail)", "ProjectTheme"); ?><font style="color:red;">*</font>&nbsp;:</td>
                <td width="200"><input size="20" name="to_as[]" class="to_as" type="text" value="" /> <?php if($rtt): _e('or','ProjectTheme'); echo " ".$rtt; endif; ?></td>
                <td><input type="button" class="add_email" value="+" onclick="add_raw()"/></td>
                </tr>
                <?php endif; ?>
                
                <tr>
                <td width="140" class="no_wrap"><?php _e("Subject", "ProjectTheme"); ?><font style="color:red;">*</font>&nbsp;:</td>
                <td><input size="50" name="subject_a" id="subject_a" type="text" value="<?php echo $subject; ?>" /></td>
                </tr>
                
                 <script>
                     
                     function add_raw(){
                         var html = jQuery('.send-to-raw:first').html();
                         var btn_remove = '<td><input type="button" class="remove_email" value="X" onclick="remove_raw(this)"/></td>'
                         html = '<tr class="send-to-raw">' + html +btn_remove  +'</tr>';
                         jQuery('.send-to-raw:last').after(html);
                     }
                     
                     function remove_raw(el){
                         jQuery(el).parent('td').parent('tr').remove();
                     }
                     
			
			jQuery(document).ready(function(){
			tinyMCE.init({
					mode : "specific_textareas",
					theme : "modern", 
					/*plugins : "autolink, lists, spellchecker, style, layer, table, advhr, advimage, advlink, emotions, iespell, inlinepopups, insertdatetime, preview, media, searchreplace, print, contextmenu, paste, 

directionality, fullscreen, noneditable, visualchars, nonbreaking, xhtmlxtras, template",*/
					editor_selector :"tinymce-enabled"
				});
			});
						
			</script>
                
                <tr>
                <td valign="top" class="no_wrap"><?php _e("Message", "ProjectTheme"); ?><font style="color:red;">*</font>&nbsp;:</td>
                <td><textarea name="message_a" class="tinymce-enabled" id="message_a"  rows="6" cols="50" ></textarea></td>
                </tr>
                
                
                <tr>
                <td valign="top" class="no_wrap"><?php _e("Attach File", "ProjectTheme"); ?>:</td>
                <td>
                	<div style="display: block;">
                		<span style="display:inline-block;"><input type="file" id="file_instant" name="file_instant" class="" multiple=""/></span>
                		<span class="del21" style="float:right;cursor:pointer;" value="clear" onclick="file_clear(); textarea_instant();">Delete</span>
                	</div>
                	<div style="display: block;">
                		<?php _e('Only PDF, TXT, ZIP, Office files and Images.','ProjectTheme'); ?>
                	</div>
                </td>
                </tr>
                
                
                 <tr>
                <td width="140">&nbsp;</td>
                <td></td>
                </tr>
                
                 <tr>
                <td width="140">&nbsp;</td>
                <td><input name="send_a" class="submit_bottom2" type="submit"  value="<?php _e("Send Message",'ProjectTheme'); ?>" /></td>
                </tr>
                
                </table>
      			</form>
                
                </div>
                </div>
             
        
        <?php } } ?>
        
             
        </div> <!-- end dif content -->
        <script type="text/javascript">
		


        	$(document).ready(function(){
        		
        		$('.conv_time').each(function(){
        			var tm = parseInt($(this).text())*1000;
        			console.log(tm);
        			var time = new Date(tm);
        			$(this).text(time.toLocaleString());
        		});
        	
});

         
        	function file_clear(){
       			$('#file_instant').val('');
    		}
			
		 
			
		  
   
   </script>
        
        <?php ProjectTheme_get_users_links(); ?>
        
    
	
<?php	
} 


function ProjectTheme_send_priv_mess_to_person($uids, $uid, $error_mm, $subject,$message,$pid, $attach_id, $user, $post,$cant_send ) {


				
				if($uids != false and $error_mm != "1"):
				
				global $current_user;
				get_currentuserinfo();
				$myuid = $current_user->ID;
				
				//echo $message;
				//*********************************************
				
				$ProjectTheme_moderate_private_messages = get_option('ProjectTheme_moderate_private_messages');
				if($ProjectTheme_moderate_private_messages == "yes") $ProjectTheme_moderate_private_messages = true;
				else $ProjectTheme_moderate_private_messages = false;
				
				//--------------------------
				
				if($ProjectTheme_moderate_private_messages == true)
				{
					$approved = '0';	
					$show_to_destination = '0';
				}
				else
				{
					$approved = '1';
					$show_to_destination = '1';	
				}
				
				//*********************************************
				
				
				global $wpdb; $wpdb->show_errors = true; $tm = $_POST['tm']; //current_time('timestamp',0);		
				
				
				$sr = "select * from ".$wpdb->prefix."project_pm where initiator='$myuid' and user='$uids' and datemade='$tm'";
				$rr = $wpdb->get_results($sr);
				
				if(count($rr) == 0)
				{
				
					if(empty($pid)) $pid = 0;
					
					$s = "insert into ".$wpdb->prefix."project_pm 
					(approved, subject, content, datemade, pid, initiator, user, file_attached, show_to_destination) 
					values('$approved','$subject','$message','$tm','$pid','$myuid','$uids', '$attach_id', '$show_to_destination')";	
						
					$wpdb->query($s); //echo $s;
					//echo $wpdb->last_error;
					
				//-----------------------
					
					$user = get_userdata($uid);
					$message = sprintf(__("You have just received a private message regarding your project: <a href='%s'>%s</a><br/>
					<a href='%s'>Click here to read the message</a>.", "ProjectTheme"),get_permalink($pid),
					$post->post_title,get_bloginfo('siteurl')."/my-account/private-messages");
					//sitemile_send_email($user->user_email, __('Private Message Received','ProjectTheme') , $message);
					
					
					if($ProjectTheme_moderate_private_messages == false) {
                        
                        ProjectTheme_send_email_on_priv_mess_received($myuid, $uids);
                    
                    }
					else
					{
						//send message to admin to moderate		
							
					}
					
				
				}
				
			//-----------------------		
				?>
                
                <div class="my_box3">
            	<div class="padd10">
                 <?php 
                 
                 $user = get_userdata($uids);
				 
				 if($ProjectTheme_moderate_private_messages == false)				 
				 	echo sprintf (__('Your message to user <b>%s</b> has been sent.','ProjectTheme'), $user->user_login);
				 else
				  	_e('Your message has been sent but the receiver will receive it only after moderation.','ProjectTheme')
				 
				  ?>
                </div>
                </div>
                
                <?php
				
				else:
				?>
					<div class="my_box3">
                    <div class="padd10">
                <?php  
                    if($error_mm == "1") { 
					
						if($cant_send == 1) echo __('You cannot send a message to yourself.','ProjectTheme');
					 	else echo sprintf(__('Wrong File format: %s','ProjectTheme'), $uploaded_file_type);
						
					}
					else _e('ERROR! wrong username provided.','ProjectTheme');
                    
                    ?>
                    </div>    
                    </div>    
                    <?php
				
				endif;
				
            }
					
            /*
             * end function
             */

?>