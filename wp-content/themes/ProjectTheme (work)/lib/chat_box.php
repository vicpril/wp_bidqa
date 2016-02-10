<?php

	session_start();
	
	function PricerrTheme_filter_ttl($title){return __("Chat Box",'PricerrTheme')." - ";}
	add_filter( 'wp_title', 'PricerrTheme_filter_ttl', 10, 3 );	
	
	if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }   
	   
	global $current_user, $wp_query;
	get_currentuserinfo;   


	$uid 	= $current_user->ID;
	$cid 	= $current_user->ID;
	
	
		global $wpdb, $current_user;
		
		get_currentuserinfo();
		$uid = $current_user->ID;
		$orderid = $_GET['oid'];
		
		$s 			= "select * from ".$wpdb->prefix."job_orders where id='$orderid'";
		$r 			= $wpdb->get_results($s);
		$row 		= $r[0]; 
		$pid		= $row->pid;
		$post_a 		= get_post($row->pid); 
		$other_uid 	= $row->uid;
		
		if(($row->uid != $uid && $post_a->post_author != $uid) || count($r) == 0) wp_redirect(get_bloginfo('siteurl'));
	

		//-----------------------------------------------------------------------------------------------
	
			get_header();
		
		//--------------------------------------------------
			
		$price 		= get_post_meta($pid, 'price', true);
		$ttl		= $post_a->post_title;
		$max_days 	= get_post_meta($pid, "max_days", true);
		$location 	= wp_get_object_terms($pid, 'job_location');
		$cat 		= wp_get_object_terms($pid, 'job_cat');
		
		
		$s = "update ".$wpdb->prefix."job_chatbox set rd_receiver='1' where oid='$orderid' AND uid!='$uid'";
		$wpdb->query($s);	
		
			?>
            
	
        <div id="content">
          <div class="my_box3">
            	<div class="padd10">
       
            
            	<div class="box_title"><?php echo sprintf(__("Conversation #%s - %s",'PricerrTheme'), $orderid, PricerrTheme_wrap_the_title($post_a->post_title, $pid)); ?></div>
            	<div class="box_content">
              
             
              
               <?php
		
		if(isset($_POST['send-message']))
		{
			$messaje 				= nl2br(trim(strip_tags(htmlspecialchars($_POST['messaje']))));
			$datemade 				= current_time('timestamp',0);
			
			global $wpdb;
			$pref = $wpdb->prefix;
			
			$ss = "select * from ".$pref."job_chatbox where content='$messaje'";
			$rr = $wpdb->get_results($ss);
			
			if(count($rr) == 0):
		
			
				//--- parse emails out of the private messages -------
				
				$PricerrTheme_filter_emails_chat_box = get_option('PricerrTheme_filter_emails_chat_box');
				if($PricerrTheme_filter_emails_chat_box == "yes") $messaje = PricerrTheme_parseEmails($messaje);
				
				//--- parse links out of the private messages  ---
				
				$PricerrTheme_filter_urls_chat_box = get_option('PricerrTheme_filter_urls_chat_box');
				if($PricerrTheme_filter_urls_chat_box == "yes") $messaje = PricerrTheme_parseHyperlinks($messaje);
				
				//----------------------
			
			
			
			
			if(!empty($messaje)):
			
			//-------------------------------------------------
			// attach file
			$new_FILES = $_FILES['file'];
			if(!empty($new_FILES['name'])):
			
			require_once(ABSPATH . "wp-admin" . '/includes/file.php');
			
			
			$arr_file_type 					= wp_check_filetype(basename($new_FILES['name']));
			
			if(PricerrTheme_allow_me_ext($arr_file_type['ext']) == true):
			
			
			$upload_overrides 				= array( 'test_form' => false );
            $uploaded_file 					= wp_handle_upload($new_FILES, $upload_overrides);			
			$file_name_and_location 		= $uploaded_file['file'];
            $file_title_for_media_library 	= $new_FILES['name'];
			$arr_file_type 					= wp_check_filetype(basename($new_FILES['name']));
            $uploaded_file_type 			= $arr_file_type['type'];
			
			$attachment = array('post_mime_type' => $uploaded_file_type,
                                'post_title' => '' . addslashes($file_title_for_media_library),
                                'post_content' => '',
                                'post_status' => 'inherit',
								'post_author' => $uid);
								
								
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			$attachment = wp_insert_attachment( $attachment, $file_name_and_location);
			
			else: $error = '<br/>'.__('Make sure your file is: zip, rar or image format.','PricerrTheme'); endif;
			
			else:
			$attachment = "";
			endif;
			//--------------------------------------------------
			$g1 = "insert into ".$pref."job_chatbox (datemade, uid, oid, content, attachment) values('$datemade','$uid','$orderid','$messaje', '$attachment')";
			$wpdb->query($g1);
			
			echo '<div class="messaje-sent"><div class="padd10">'.__('Your message has been sent.','PricerrTheme').$error.'</div></div>';
			
			//-------------------------------------------------
			
			if($uid == $post_a->post_author) $uid_to_send = $other_uid;
			else $uid_to_send = $post_a->post_author;
			
			PricerrTheme_send_email_when_new_chat($pid, $orderid, $uid, $uid_to_send);
			
			
			//-------------------------------------------------
			else:
			
				echo '<div class="error">'. __("ERROR! Your message is emtpy.","PricerrTheme"). '</div> <div class="clear10"></div>';
			
			endif;
			
			endif;
		}	
		
		?>
        
        
        <?php
		//  0 order info (like, end, start)
		// -1 marked as finished
		// -2 finished
		// -3 not finished
		
		$s = "select * from ".$wpdb->prefix."job_chatbox where oid='$orderid' order by id desc";
		$r = $wpdb->get_results($s);
		
		foreach($r as $row)
		{
			$sss1 = "select * from ".$wpdb->prefix."job_orders` where id='{$row->oid}' ";
			$rrr1 = $wpdb->get_results($sss1);
			$row1 = $rrr1[0];
			
			$post_a = get_post($row1->pid);
			
			//----------------------
			
			$icon 	= get_bloginfo('template_url').'/images/bulb.png';
			$class 	= "my-chatbox-standard";
			$message = __('The order has been sent to the seller.','PricerrTheme');
			$datine = date_i18n('d-M-Y H:i:s',$row->datemade);
			$class1 = "";
			
			if($row->uid > 0)
			{
				$icon = pricerrTheme_get_avatar($row->uid,36, 36);	
				$message = $row->content;
				$class1 = "imagine-buyer"	;
				$usr_dt = get_userdata($row->uid);
				
				$cls_m = 'buyer_username_a';
				
				if($row->uid != $post_a->post_author)
				{
					$class1 = "imagine-seller"	;	
					$cls_m = 'seller_username_a';
				}
				
				$username_k = '<div class="'.$cls_m.'">'.$usr_dt->user_login."</div>";
				
			}
			else
			{
				if($row->uid == -1) // marked as finished
				{
					$icon 	= get_bloginfo('template_url').'/images/checked.png';
					$message = __('The order has been marked as delivered by the seller.<br/>Please review your order under Reviews and Shopping section.','PricerrTheme');
				}
				
				if($row->uid == -2) // marked as finished
				{
					$icon 	= get_bloginfo('template_url').'/images/handshake.png';
					$message = __('The order has been accepted by the buyer.<br/>Please review your order under Reviews/Feedback section','PricerrTheme');
				}
				
				$username_k = '';
				
			}
			
			$PricerrTheme_filter_urls_chat_box = get_option('PricerrTheme_filter_urls_chat_box');
			
			if($PricerrTheme_filter_urls_chat_box == "yes")
			$message = PricerrTheme_url_cleaner($message);
			
			//-------------------------
			
			$PricerrTheme_filter_emails_chat_box = get_option('PricerrTheme_filter_emails_chat_box');
			
			if($PricerrTheme_filter_emails_chat_box == "yes")
			{
				$pattern = "/[^@\s]*@[^@\s]*\.[^@\s]*/";
				$replacement = "[****]";
				preg_replace($pattern, $replacement, $message);
			}
			
			//-------------------------
			
			echo '<div class="'.$class.'">';
			echo '<div class="imagine '.$class1.'"><div class="padd10"><img width="36" height="36" src="'.$icon.'" /></div>'.$username_k.'</div>';
			echo '<div class="messagine"><div class="padd10">'.$message.'';	
			echo '<br/>'.$datine;
			
			if(!empty($row->attachment)):
				echo '<br/><br/>' . __('Files attached','PricerrTheme').": ";
				$pst = get_post($row->attachment);
				echo '<a href="'.wp_get_attachment_url($row->attachment).'">'.$pst->post_title.'</a>';
				
			endif;
			
			echo '</div></div>';
					
			echo '</div>';
			
			
		}
		
		?>   
       <div class="my-chatbox-standard"><div class="padd10">  
        <form method="post" enctype="multipart/form-data">
        <table width="100%">
        <tr>
        <td width="80" valign="top"><?php _e('Message','PricerrTheme'); ?>:</td>
        <td><textarea cols="60" name="messaje" rows="2"></textarea></td>
        </tr>
        
        <tr>
        <td valign="top"><?php _e('Attach File','PricerrTheme'); ?>:</td>
        <td><input type="file" name="file" /></td>
        </tr>
        
        <?php do_action('PricerrTheme_add_stuff_to_chat_box_form'); ?>
        
        <tr>
        <td valign="top"></td>
        <td><input type="submit" name="send-message" value="<?php _e('Send','PricerrTheme'); ?>" /></td>
        </tr>
        
        </table>
        </form>
        </div>
        </div>
              
              
                
                </div>
               
        </div>
        </div></div>
            

	<?php PricerrTheme_get_users_links(); ?>


		<?php
		get_footer();
		?>