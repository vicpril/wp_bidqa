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


function ProjectTheme_my_account_personal_info_function()
{
	
		global $current_user, $wpdb, $wp_query;
		get_currentuserinfo();
		$uid = $current_user->ID;
	
?>
    	<div id="content" class="account-main-area">
        	
           <?php
				
				if(isset($_POST['save-info']))
				{
					//if(file_exists('cimy_update_ExtraFields'))
					cimy_update_ExtraFields_new_me();
					
					
					if(!empty($_FILES['avatar']["tmp_name"]))
					{
						
						if(filesize( $_FILES['avatar']['tmp_name'] )>(1024*1024)){
							echo '<div class="error">'.__("Avatar was not updated. File is to big!","ProjectTheme").'</div>';	 $xxp = 1;
						}
						else{
					 
						
						//***********************************
						
						$pid = 0;
						$cid = $uid;				 
						
						require_once(ABSPATH . "wp-admin" . '/includes/file.php');	  
						$upload_overrides 	= array( 'test_form' => false );
						$uploaded_file 		= wp_handle_upload($_FILES['avatar'], $upload_overrides);
					
						$file_name_and_location = $uploaded_file['file'];
                    	$file_title_for_media_library = $_FILES['file']['name'];
						
						$arr_file_type 		= wp_check_filetype(basename($_FILES['avatar']['name']));
                    	$uploaded_file_type = $arr_file_type['type'];

		
						
						$attachment = array(
                                'post_mime_type' => $uploaded_file_type,
                                'post_title' =>  addslashes($file_title_for_media_library),
                                'post_content' => '',
                                'post_status' => 'inherit',
								'post_parent' =>  $pid,

								'post_author' => $cid,
                            );
						 require_once(ABSPATH . "wp-admin" . '/includes/image.php');
						$attach_id = wp_insert_attachment( $attachment, $file_name_and_location, $pid );
                       
                        $attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
                        wp_update_attachment_metadata($attach_id,  $attach_data);

					 
						update_user_meta($uid, 'avatar_' . 'project', $attach_id);
						
						//***********************************

						}
						
						
					}
					
					//---------------------
					
					$wpdb->query("delete from ".$wpdb->prefix."project_email_alerts where uid='$uid' ");
					
					$email_cats = $_POST['email_cats'];
					
					if(count($email_cats) > 0)
					foreach($email_cats as $em)
					{
						$wpdb->query("insert into ".$wpdb->prefix."project_email_alerts (uid,catid) values('$uid','$em') ");						
					}
					
					
					
					//-------------------
					//email_locs
					//****************************************************************************************************
					$ProjectTheme_enable_project_location = get_option('ProjectTheme_enable_project_location');
					if($ProjectTheme_enable_project_location != "no"):
					
					
						$wpdb->query("delete from ".$wpdb->prefix."project_email_alerts_locs where uid='$uid' ");
						
						$email_cats = $_POST['email_locs'];
						
						if(count($email_cats) > 0)
						foreach($email_cats as $em)
						{
							$wpdb->query("insert into ".$wpdb->prefix."project_email_alerts_locs (uid,catid) values('$uid','$em') ");						
						}
					
					endif;
					
					//****************************************************************************************************
					//-------------------
					
					$user_description = trim($_POST['user_description']);
					update_user_meta($uid, 'user_description', $user_description);
					
					
					$per_hour = trim($_POST['per_hour']);
					update_user_meta($uid, 'per_hour', $per_hour);
					
					
					$user_location = trim($_POST['project_location_cat']);
					update_user_meta($uid, 'user_location', $user_location);
					
					$user_city = trim($_POST['user_city']);
					update_user_meta($uid, 'user_city', $user_city);
					
					if(isset($_POST['paypal_email'])) {
						$paypalnum 	= trim($_POST['paypal_email']);
						if(is_email($paypalnum)){
							$my_em 			= get_user_meta($uid, 'paypal_email',true);
							$s_em 			= get_users('meta_value='.$paypalnum);
							$em_dom			= strstr($paypalnum, '@');
							$em_dom			= str_replace('@', '', $em_dom);
							if($my_em != $paypalnum && !$s_em){
								function isDomainAvailible($domain){
	               					$curlInit = curl_init($domain);
	               					curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
	               					curl_setopt($curlInit,CURLOPT_HEADER,true);
	               					curl_setopt($curlInit,CURLOPT_NOBODY,true);
	               					curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);
	               					$response = curl_exec($curlInit);
									curl_close($curlInit);
									if ($response) return true;
									return false;
	       						}
	       						if(isDomainAvailible($em_dom)){
	       							update_user_meta($uid, 'paypal_email', $paypalnum);
	       						}else{
									$xxp = 1;
									echo '<div class="error">'.__('Incorrect PayPal Email','ProjectTheme').'</div>';
	       						}
							}else{
								$xxp = 1;
								echo '<div class="error">'.__('This PayPal Email already exists','ProjectTheme').'</div>';
	       					}
						}else{
							$xxp = 1;
							echo '<div class="error">'.__('Incorrect PayPal Email','ProjectTheme').'</div>';
						}
					}else{
						update_user_meta($uid, 'paypal_email', '');
					}
					
					$personal_info = trim($_POST['payza_email']);
					update_user_meta($uid, 'payza_email', $personal_info);
					
					$personal_info = trim($_POST['moneybookers_email']);
					update_user_meta($uid, 'moneybookers_email', $personal_info);
					
					$user_url = trim($_POST['user_url']);
					update_user_meta($uid, 'user_url', $user_url);
					
					do_action('ProjectTheme_pers_info_save_action');

					if(isset($_POST['new_user_name']) && !empty($_POST['new_user_name'])) {
						$new_user_name = trim($_POST['new_user_name']);
						$new_user_name = preg_replace( '|\s+|', '', $new_user_name );
						if( ! validate_username($new_user_name) OR $new_user_name == '' OR ! preg_match('/[0-9aA-zZ]/', $new_user_name)) {
							echo '<div class="error">'.__("Incorrect username!","ProjectTheme").'</div>'; 
							$xxp = 1;
						}elseif(username_exists($new_user_name) && $new_user_name!=$current_user->user_login) {
							echo '<div class="error">'.__("This Username already exists!","ProjectTheme").'</div>'; 
							$xxp = 1;
						}elseif($new_user_name!=$current_user->user_login) {
								global $wpdb;
								$sq = "UPDATE ".$wpdb->users." SET user_login='$new_user_name' WHERE ID='$uid'" ;
								$wpdb->query($sq);
								$inc = 2;
						}
					}

					if(isset($_POST['new_user_email']) && !empty($_POST['new_user_email']))
					{
						$new_user_email = trim($_POST['new_user_email']);						
						$new_user 		= get_user_by( 'email', $new_user_email );
						$new_uid 		= $new_user->ID;
						$user 			= get_userdata($uid);

						if($new_user_email==$user->user_email){

						}
						elseif(!$new_uid){
							global $wpdb;
							$sq = "update ".$wpdb->users." set user_email='$new_user_email' where ID='$uid'" ;
							$wpdb->query($sq);
							
						}
						else{
							echo '<div class="error">'.__("This Email already exists!","ProjectTheme").'</div>'; 
							$xxp = 1;
						}
					}
					
					if(isset($_POST['password']) && !empty($_POST['password']))
					{
						$p1 = trim($_POST['password']);
						$p2 = trim($_POST['reppassword']);
						
						if(!empty($p1) && !empty($p2))
						{
						
							if($p1 == $p2)
							{
								global $wpdb;
								$newp = md5($p1);
								$sq = "update ".$wpdb->users." set user_pass='$newp' where ID='$uid'" ;
								$wpdb->query($sq);
								
								$inc = 1;
							}
							else {
								echo '<div class="error">'.__("Password was not updated. Passwords do not match!","ProjectTheme").'</div>'; 
								$xxp = 1; }
						}
						else
						{ 
							echo '<div class="error">'.__("Password was not updated. Passwords do not match!","ProjectTheme").'</div>';	 
							$xxp = 1;		
						}
					}
					 
					
					
					//---------------------------------------
						
					$arr = $_POST['custom_field_id'];
					for($i=0;$i<count($arr);$i++)
					{
						$ids 	= $arr[$i];
						$value 	= $_POST['custom_field_value_'.$ids];
						
						if(is_array($value))
						{
							delete_user_meta($uid, "custom_field_ID_".$ids);
							
							for($j=0;$j<count($value);$j++) {
								add_user_meta($uid, "custom_field_ID_".$ids, $value[$j]);
								
							}
						}
						else
						update_user_meta($uid, "custom_field_ID_".$ids, $value);
						
					}
					
					//--------------------------------------------
					if($xxp != 1)
					{
						echo '<div class="saved_thing">'.__('Info saved!','ProjectTheme');
						
						if($inc == 1)
						{
						
							echo '<br/>'.__('Your password was changed. Redirecting to login page...','ProjectTheme');
							echo '<meta http-equiv="refresh" content="2; url='.get_bloginfo('url').'/wp-login.php">';
						
						}
						elseif($inc == 2){
							echo '<br/>'.__('Your Username was changed. Redirecting to login page...','ProjectTheme');
							echo '<meta http-equiv="refresh" content="2; url='.get_bloginfo('url').'/wp-login.php">';
						}
						
						echo '</div>';
					}
				}
				$user = get_userdata($uid);
				$user_location = get_user_meta($uid, 'user_location',true);
				?>
         
       
    <script type="text/javascript">
	
	function delete_this2(id)
	{
		 jQuery.ajax({
						method: 'get',
						url : '<?php echo get_bloginfo('siteurl');?>/index.php/?_ad_delete_pid='+id,
						dataType : 'text',
						success: function (text) {   jQuery('#image_ss'+id).remove();  }
					 });
		  //alert("a");
	
	}

	
 
	
	
	</script>     
            
             <form method="post"  enctype="multipart/form-data">
             
            <div class="my_box3">
            	
             
                <div class="box_content">    
	
         <ul class="post-new3">
        <li>
        	<h2><?php echo __('Username','ProjectTheme'); ?>:</h2>
        	<p><input type="text" size="35" value="<?php echo $user->user_login; ?>" name="new_user_name" class="do_input" /></p>
        </li>

        <li>
        	<h2><?php echo __('Email','ProjectTheme'); ?>:</h2>
        	<p><input type="text" size="35" value="<?php echo $user->user_email; ?>" name="new_user_email" class="do_input" /></p>
        </li>

		<?php
			
			$opt = get_option('ProjectTheme_enable_project_location');
			if($opt != 'no'):
		
		?>
        
		 <!-- <li>
        	<h2><?php echo __('Location','ProjectTheme'); ?>:</h2>
        	<p>
            <?php	echo ProjectTheme_get_categories("project_location", $user_location , __("Select Location","ProjectTheme"), "do_input"); ?>
            </p>
        </li> -->
		
        
        <li>
        	<h2><?php echo __('Country','ProjectTheme'); ?>:</h2>
        	<p>
        		<?php
        			$args = "orderby=name&order=ASC&hide_empty=0&parent=0";
					$terms = get_terms( 'project_location', $args );
					$cur_country = get_user_meta($uid, 'user_city');
					
					echo '<select name="user_city" class="do_input"><option value="">Select Country</option>';
					if ($terms){
						foreach ($terms as $key => $term) {
							if($cur_country[0]==$term->name){
								echo '<option value="'.$term->name.'" selected>'.$term->name.'</option>';
							}
							else{
								echo '<option value="'.$term->name.'">'.$term->name.'</option>';	
							}
							
						}
					}
					echo '</select>';
        		?>
        		<!-- <input type="text" size="35" name="user_city" value="<?php echo get_user_meta($uid, 'user_city', true); ?>" class="do_input" /> -->
        	</p>
        </li>
        
		<?php endif; ?>
     
            <script>
			
			jQuery(document).ready(function(){
			tinyMCE.init({
					mode : "specific_textareas",
					theme : "modern", 
					/*statusbar: false,*/
					/*plugins : "autolink, lists, spellchecker, style, layer, table, advhr, advimage, advlink, emotions, iespell, inlinepopups, insertdatetime, preview, media, searchreplace, print, contextmenu, paste, directionality, fullscreen, noneditable, visualchars, nonbreaking, xhtmlxtras, template",*/
					editor_selector :"tinymce-enabled"
				});
			});
						
			</script>    
        <li>
        	<h2><?php echo __('Description','ProjectTheme'); ?>:</h2>
        	<p><textarea cols="40" rows="5"  name="user_description" class="tinymce-enabled do_input"><?php echo get_usermeta($uid,'user_description',true); ?></textarea></p>
        </li>
        
        <?php
		
        $opt = get_option('ProjectTheme_paypal_enable');
		if($opt == "yes"):
					
		?>
        
        <li>
        	<h2><?php echo __('PayPal Email','ProjectTheme'); ?>:</h2>
        	<p><input type="text" size="35" name="paypal_email" value="<?php echo get_user_meta($uid, 'paypal_email', true); ?>" class="do_input" /></p>
        </li>
        
        <?php
		endif;
		
        $opt = get_option('ProjectTheme_moneybookers_enable');
		if($opt == "yes"):
					
		?>
        
        <li>
        	<h2><?php echo __('Moneybookers Email','ProjectTheme'); ?>:</h2>
        	<p><input type="text" size="35" name="moneybookers_email" value="<?php echo get_user_meta($uid, 'moneybookers_email', true); ?>" class="do_input" /></p>
        </li>
        
        <?php
		endif;
		
        $opt = get_option('ProjectTheme_alertpay_enable');
		if($opt == "yes"):
					
		?>
        
         <li>
        	<h2><?php echo __('Payza Email','ProjectTheme'); ?>:</h2>
        	<p><input type="text" size="35" name="payza_email" value="<?php echo get_user_meta($uid, 'payza_email', true); ?>" class="do_input" /></p>
        </li>
       <?php endif; ?> 
        
         <li>
        	<h2><?php echo __('New Password', "ProjectTheme"); ?>:</h2>
        	<p><input type="password" value="" class="do_input" name="password" size="35" /></p>
        </li>
        
        
        <li>
        	<h2><?php echo __('Repeat Password', "ProjectTheme"); ?>:</h2>
        	<p><input type="password" value="" class="do_input" name="reppassword" size="35"  /></p>
        </li>
        
        
        <?php do_action('ProjectTheme_pers_info_fields_1'); ?>

   		  <li>
        	<h2><?php echo __('Profile Avatar','ProjectTheme'); ?>:</h2>
        	<style type="text/css">
        		.video-delete {
				    display: none;
				    color: #FF0000;
				    border: 1px solid #FF0000;
				    padding: 0 5px;
				    border-radius: 50%;
				    cursor: pointer;
				}
        		.sp-inline {
        			display: inline-block;
        		}
        	</style>
        		<p>
        			<span class="sp-inline">
        				<input type="file" name="avatar" class="new-file-style" />
        			</span>
        			<span class="video-delete" onclick="delete_choose();">X</span>
        		</p>
        		<script>
        			function delete_choose(){
       					$('.new-file-style').val('').show();
       					$('#new_name_file').remove();
       					$('.video-delete').hide();
    				}
    				$( document ).ready(function() {
    					$('.new-file-style').on("change", function(){
      						var file = $('.new-file-style')[0].files[0];
        					if(file){
        						$('.new-file-style').hide();
        						$('.video-delete').css('display','inline-block');
        						$( '<span id="new_name_file">'+file.name+'</span>' ).insertAfter( ".new-file-style" );
        					}
      					});
    				});
        		</script>
        	<p>
           		<?php _e('max file size: 1mb. Formats: jpeg, jpg, png, gif' ,'ProjectTheme'); ?><br/>
            	<img width="50" height="50" border="0" src="<?php echo ProjectTheme_get_avatar($uid,50,50); ?>" /> 
            </p>
        </li>
   
   
   <li>
   <?php
   
   if(function_exists('cimy_extract_ExtraFields'))
   cimy_extract_ExtraFields();
   
   ?>
   <?php
   if(isset($_POST['delet-pic'])) 
   {
	   if(!empty($_FILES['avatar']["tmp_name"]))
					{
						
						if(filesize( $_FILES['avatar']['tmp_name'] )>(1024*1024)){
							echo '<div class="error">'.__("Avatar was not updated. File is to big!","ProjectTheme").'</div>';	 $xxp = 1;
						}
						else{
					 
						
						//***********************************
						
						$pid = 0;
						$cid = $uid;				 
						
						require_once(ABSPATH . "wp-admin" . '/includes/file.php');	  
						$upload_overrides 	= array( 'test_form' => false );
						$uploaded_file 		= wp_handle_upload($_FILES['avatar'], $upload_overrides);
					
						$file_name_and_location = $uploaded_file['http://bidqa.com/wp-content/themes/ProjectTheme/images/noav.jpg'];
                    	$file_title_for_media_library = $_FILES['http://bidqa.com/wp-content/themes/ProjectTheme/images/noav.jpg']['http://bidqa.com/wp-content/themes/ProjectTheme/images/noav.jpg'];
						
						$arr_file_type 		= wp_check_filetype(basename($_FILES['avatar']['name']));
                    	$uploaded_file_type = $arr_file_type['type'];

		
						
						$attachment = array(
                                'post_mime_type' => $uploaded_file_type,
                                'post_title' =>  addslashes($file_title_for_media_library),
                                'post_content' => '',
                                'post_status' => 'inherit',
								'post_parent' =>  $pid,

								'post_author' => $cid,
                            );
						 require_once(ABSPATH . "wp-admin" . '/includes/image.php');
						$attach_id = wp_insert_attachment( $attachment, $file_name_and_location, $pid );
                       
                        $attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
                        wp_update_attachment_metadata($attach_id,  $attach_data);

					 
						update_user_meta($uid, 'avatar_' . 'project', $attach_id);
						
						//***********************************

						}
						
						
					}
					
   }
   ?>
   
   </li> http://bidqa.com/wp-content/themes/ProjectTheme/images/noav.jpg
        <li>
        <p><input type="submit" name="delet-pic" class="my-buttons" value="<?php _e("Delete profile pic." ,'ProjectTheme'); ?>" /></p>
       &nbsp;&nbsp;&nbsp;
        <p><input type="submit" name="save-info" class="my-buttons" value="<?php _e("Save" ,'ProjectTheme'); ?>" /></p>
        </li>
        
       </ul> 
        
               
        
           </div>
           </div>     
            
            <div class="clear10"></div>
            
            <div class="my_box3" id="other_infs_mm1">
           
            
            	<div class="box_title" id="other_infs_mm"><?php _e("Other Information",'ProjectTheme'); ?></div>
                <div class="box_content">  
                
        <ul class="post-new3">
        
        
        <?php do_action('ProjectTheme_pers_info_fields_2'); ?>
        
        <?php
		
		
		$user_tp = get_user_meta($uid,'user_tp',true);
		if(empty($user_tp)) $user_tp = 'all';
		
		if($user_tp == "all") 
			$catid = array('all','service_buyer','service_provider');
		else
			$catid = array($user_tp);
		
 		if ( current_user_can( 'manage_options' ) ) {
			$catid = array('all','service_buyer','service_provider');
		}  
		
		
		
		$k = 0;
		$arr = ProjectTheme_get_users_category_fields($catid, $uid);
		$exf = '';
		
		for($i=0;$i<count($arr);$i++)
		{
			
			        $exf .= '<li>';
					$exf .= '<h2>'.$arr[$i]['field_name'].$arr[$i]['id'].':</h2>';
					$exf .= '<p>'.$arr[$i]['value'].'</p>';
					$exf .= '</li>';
					
					$k++;
			
		}	
		
		echo $exf;
		 
		
		if(ProjectTheme_is_user_provider($uid)):
			$k++;
		?>           
                            
        <li>
        	<h2><?php echo __('Hourly Rate','ProjectTheme'); ?>:</h2>
        	<p><?php echo projectTheme_currency(); ?><input type="text" size="7" name="per_hour" value="<?php echo get_user_meta($uid, 'per_hour', true); ?>" class="do_input" /> 
             *<?php _e('your estimated hourly rate','ProjectTheme'); ?></p>
        </li>
        <script type="text/javascript">
        	$( 'input[name="per_hour"]' ).keypress(function( event ) {
        	  var kkk = event.which;     		  
        	  console.log(kkk);
        	  if(((kkk>47 && kkk<58) || (kkk>64 && kkk<91) || kkk==8 || kkk==0 || kkk==46)){    		  
        	  	return true;
        	  }
        	  else{
        	  	return false;
        	  }
        	  
        	});
        </script>
        <?php
		endif;
		
			 global $current_user;
	 get_currentuserinfo();
	 $uid = $current_user->ID;
	$cid = $uid;
		
			if(ProjectTheme_is_user_provider($uid)):
			  
		?>           
                            
        <li>
        	<h2><?php echo __('Portfolio Pictures','ProjectTheme'); ?>:</h2>
        	<p>
			
             <div class="cross_cross">



	<script type="text/javascript" src="<?php echo get_bloginfo('template_url'); ?>/js/dropzone.js"></script>     
	<link rel="stylesheet" href="<?php echo get_bloginfo('template_url'); ?>/css/dropzone.css" type="text/css" />
    
 
    
    
    <script>
 
	
	jQuery(function() {

Dropzone.autoDiscover = false; 	 
var myDropzoneOptions = {
  maxFilesize: 15,
    addRemoveLinks: true,
	acceptedFiles:'image/*',
    clickable: true,
	url: "<?php bloginfo('siteurl') ?>/?my_upload_of_project_files8=1",
};
 
var myDropzone = new Dropzone('div#myDropzoneElement2', myDropzoneOptions);

myDropzone.on("sending", function(file, xhr, formData) {
  formData.append("author", "<?php echo $current_user->ID; ?>"); // Will send the filesize along with the file as POST data.
  formData.append("ID", "<?php echo $pid; ?>"); // Will send the filesize along with the file as POST data.
});

   
    <?php

		$args = array(
		'order'          => 'ASC',
		'orderby'        => 'post_date',
		'post_type'      => 'attachment',
		'author'    => 		$current_user->ID,
		'meta_key' 			=> 'is_portfolio',
		'meta_value' 		=> '1',
 
		'numberposts'    	=> -1,
		);
		
	$attachments = get_posts($args);
	
	 
	if ($attachments) 
	{
	    foreach ($attachments as $attachment) 
		{
			$url = $attachment->guid;
			$imggg = $attachment->post_mime_type; 
			$url = wp_get_attachment_url($attachment->ID);	 
				
				?>	
						var mockFile = { name: "<?php echo $attachment->post_title ?>", size: 12345, serverId: '<?php echo $attachment->ID ?>' };
						myDropzone.options.addedfile.call(myDropzone, mockFile);
						myDropzone.options.thumbnail.call(myDropzone, mockFile, "<?php echo projectTheme_generate_thumb($attachment->ID, 100, 100) ?>");						 
				
				<?php			
	 	}
	}

	?>
 
	myDropzone.on("success", function(file, response) {
    /* Maybe display some more file information on your page */
	 file.serverId = response;
	 file.thumbnail = "<?php echo bloginfo('template_url') ?>/images/file_icon.png";
	 
	   
  });
  
  
myDropzone.on("removedfile", function(file, response) {
    /* Maybe display some more file information on your page */
	  delete_this2(file.serverId);
	 
  });  	
	
	});
	
	</script>

    

	<?php _e('Click the grey area below to add project images.','ProjectTheme') ?>
    <div class="dropzone dropzone-previews" id="myDropzoneElement2" ></div>
 
    
	</div>
            
            
            
     
            
            
            </p>
        </li>
        
        <?php
		endif;
		
		if(ProjectTheme_is_user_provider($uid)):
			$k++;
		?>
                    
                    <li>
                        <h2><?php echo __('Emails Alerts','ProjectTheme'); ?>:</h2>
                        <p>
						*<?php _e('you will get an email notification when a project is posted in the selected categories','ProjectTheme'); ?>
						<div style="border:1px solid #ccc;background:#f2f2f2; overflow:auto; width:350px; border-radius:5px; height:160px;">
                        
                        <?php
							
							global $wpdb;
							$ss = "select * from ".$wpdb->prefix."project_email_alerts where uid='$uid'";
							$rr = $wpdb->get_results($ss);
							
							$terms = get_terms( 'project_cat', 'parent=0&orderby=name&hide_empty=0' );
							
							foreach($terms as $term):
								
								$chk = (projectTheme_check_list_emails($term->term_id, $rr) == true ? "checked='checked'" : "");
								
								echo '<input type="checkbox" name="email_cats[]" '.$chk.' value="'.$term->term_id.'" /> '.$term->name."<br/>";
								
								$terms2 = get_terms( 'project_cat', 'parent='.$term->term_id.'&orderby=name&hide_empty=0' );
								foreach($terms2 as $term2):
									
								
									$chk = (projectTheme_check_list_emails($term2->term_id, $rr) == 1 ? "checked='checked'" : "");
									echo '&nbsp;&nbsp; &nbsp; <input type="checkbox" name="email_cats[]" '.$chk.' value="'.$term2->term_id.'" /> '.$term2->name."<br/>";
									
									$terms3 = get_terms( 'project_cat', 'parent='.$term2->term_id.'&orderby=name&hide_empty=0' );
									foreach($terms3 as $term3):
										
										$chk = (projectTheme_check_list_emails($term3->term_id, $rr) == 1 ? "checked='checked'" : "");
										echo '&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; <input type="checkbox" '.$chk.' name="email_cats[]" 
										value="'.$term3->term_id.'" /> '.$term3->name."<br/>";
									endforeach;
										
								endforeach;
								
							endforeach;
						
						?>
                        
                        </div>
                        <br/>
                        </p>
                    </li>
        
        <?php
		
		$ProjectTheme_enable_project_location = get_option('ProjectTheme_enable_project_location');
		if($ProjectTheme_enable_project_location != "no"):
		
		?>
        	   <li>
                        <h2>&nbsp;</h2>
                        <p>
						*<?php _e('you will get an email notification when a project is posted in the selected locations','ProjectTheme'); ?>
						<div style="border:1px solid #ccc;background:#f2f2f2; overflow:auto; width:350px; border-radius:5px; height:160px;">
                        
                        <?php
							
							global $wpdb; 
							$ss = "select * from ".$wpdb->prefix."project_email_alerts_locs where uid='$uid'";
							$rr = $wpdb->get_results($ss);
							
							$terms = get_terms( 'project_location', 'parent=0&orderby=name&hide_empty=0' );
							
							foreach($terms as $term):
								
								$chk = (projectTheme_check_list_emails($term->term_id, $rr) == true ? "checked='checked'" : "");
								
								echo '<input type="checkbox" name="email_locs[]" '.$chk.' value="'.$term->term_id.'" /> '.$term->name."<br/>";
								
								$terms2 = get_terms( 'project_location', 'parent='.$term->term_id.'&orderby=name&hide_empty=0' );
								foreach($terms2 as $term2):
									
								
									$chk = (projectTheme_check_list_emails($term2->term_id, $rr) == 1 ? "checked='checked'" : "");
									echo '&nbsp;&nbsp; &nbsp; <input type="checkbox" name="email_locs[]" '.$chk.' value="'.$term2->term_id.'" /> '.$term2->name."<br/>";
									
									$terms3 = get_terms( 'project_location', 'parent='.$term2->term_id.'&orderby=name&hide_empty=0' );
									foreach($terms3 as $term3):
										
										$chk = (projectTheme_check_list_emails($term3->term_id, $rr) == 1 ? "checked='checked'" : "");
										echo '&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp; <input type="checkbox" '.$chk.' name="email_locs[]" 
										value="'.$term3->term_id.'" /> '.$term3->name."<br/>";
									endforeach;
										
								endforeach;
								
							endforeach;
						
						?>
                        
                        </div>
                        <br/>
                        </p>
                    </li>
        
        
        <?php endif;  endif; 
		 
		if($k == 0)
		{
			echo '<style>#other_infs_mm, #bk_save_not, #other_infs_mm1 { display:none; } </style>';	
		}
		
		?>
		 
        
        			
                    <li id="bk_save_not">
        <h2>&nbsp;</h2> <input type="hidden" value="<?php echo $uid; ?>" name="user_id" />
        <p><input type="submit" class="my-buttons" name="save-info" value="<?php _e("Save" ,'ProjectTheme'); ?>" /></p>
        </li>
                    
        </ul>
                
                
              
                </div>
                </div>
                
                
             
            
            
            
            
		</form>

                
        </div> <!-- end dif content -->
        
        <?php ProjectTheme_get_users_links(); ?>
        
    
	
<?php	
} 


?>