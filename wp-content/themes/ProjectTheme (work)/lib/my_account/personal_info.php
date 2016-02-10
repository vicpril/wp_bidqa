<?php
/*****************************************************************************
*
*	copyright(c) - sitemile.com - PricerrTheme
*	More Info: http://sitemile.com/p/pricerr
*	Coder: Saioc Dragos Andrei
*	Email: andreisaioc@gmail.com
*
******************************************************************************/

function PricerrTheme_my_account_pers_info_area_function()
{
	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;
	
	//-------------------------------------	

		global $wpdb,$wp_rewrite,$wp_query;
		$third_page = $wp_query->query_vars['third_page'];
	
			
		?>	
       
		<div id="content">
		<!-- page content here -->	
        <div class="my_box3">
            	<div class="padd10">
        <div class="box_title3"><?php _e("Personal Info",'PricerrTheme'); ?></div>  
            
            
            
            	
                <div class="box_content">    
				<?php
				
				if(isset($_POST['save-info']))
				{
					$personal_info = strip_tags(nl2br($_POST['personal_info']), '<br />');
					update_user_meta($uid, 'personal_info', substr($personal_info,0,500));
					
					update_user_meta($uid, 'user_location', $_POST['job_location_cat']);
					
					
					
					if(isset($_POST['password']))
					{
						
						
						if(  !empty($_POST['password'])):
						$p1 = trim($_POST['password']);
						$p2 = trim($_POST['reppassword']);
						
						if($p1 == $p2)
						{
						
							global $wpdb;
							$newp = md5($p1);
							$sq = "update ".$wpdb->prefix."users set user_pass='$newp' where ID='$uid'" ;
							$wpdb->query($sq);
						} else echo '<div class="error">'.__('Password was not changed. It does not match the password confirmation.','PricerrTheme').'</div>';
						endif;
					}
					
					
					$personal_info = trim($_POST['paypal_email']);
					update_user_meta($uid, 'paypal_email', $personal_info);
					
					$user_full_name = trim($_POST['user_full_name']);
					update_user_meta($uid, 'user_full_name', $user_full_name);
					
					if(!empty($_FILES['avatar']["tmp_name"]))
					{
						$avatar = $_FILES['avatar'];
						
						$tmp_name 	= $avatar["tmp_name"];
        				$name 		= $avatar["name"];
        				
						$upldir = wp_upload_dir();
						$path = $upldir['path'];
						$url  = $upldir['url'];
						
						$name = str_replace(" ","",$name);
						
						if(getimagesize($tmp_name) > 0)
						{
						
							move_uploaded_file($tmp_name, $path."/".$name);
							update_user_meta($uid, 'avatar', $url."/".$name);
						
						}
					}
					
					
						echo '<div class="saved_thing">'.__("Information saved!","PricerrTheme").'</div>';
					
				}
				
				?>
                <form method="post" enctype="multipart/form-data">
                  <ul class="post-new3">
    
        
        <li>
        	<h2><?php echo __('Your Full Name','PricerrTheme'); ?>:</h2>
        	<p><input type="text" class="do_input" name="user_full_name" value="<?php echo get_user_meta($uid, 'user_full_name', true); ?>" size="40" /></p>
        </li>
        
        
          <li>
        	<h2><?php echo __('Your Location','PricerrTheme'); ?>:</h2>
        	<p>
            
            <?php	
			
			$locs = get_user_meta($uid, 'user_location', true);
			
			echo PricerrTheme_get_categories("job_location",  
			!isset($_POST['job_location_cat']) ?  $locs : htmlspecialchars($_POST['job_location_cat'])
			, __('Select Location','PricerrTheme'), "do_input"); ?>
            
            </p>
        </li>
        
          <li>
        	<h2><?php echo __('New Password', "PricerrTheme"); ?>:</h2>
        	<p><input type="password" value="" class="do_input" name="password" size="40" /></p>
        </li>
        
        
        <li>
        	<h2><?php echo __('Repeat Password', "PricerrTheme"); ?>:</h2>
        	<p><input type="password" value="" class="do_input" name="reppassword" size="40"  /></p>
        </li>
        
        
        
            <li>
        	<h2><?php echo __('PayPal Email','PricerrTheme'); ?>:</h2>
        	<p><input type="text" class="do_input" name="paypal_email" value="<?php echo get_user_meta($uid, 'paypal_email', true); ?>" size="40" /></p>
        </li>
        
        
        
        <li>
        	<h2><?php echo __('Profile Description','PricerrTheme'); ?>:</h2>
        	<p><textarea type="textarea" cols="30" class="do_input" rows="5" name="personal_info"><?php echo get_user_meta($uid, 'personal_info', true); ?></textarea></p>
        </li>
        
        
        <li>
        	<h2><?php echo __('Profile Avatar','PricerrTheme'); ?>:</h2>
        	<p> <input type="file" class="do_input" name="avatar" /> <br/>
           <?php _e('max file size: 2mb. Formats: jpeg, jpg, png, gif', 'PricerrTheme'); ?>
            <br/>
            <img width="50" height="50" border="0" src="<?php echo pricerrTheme_get_avatar($uid,50,50); ?>" /> 
            </p>
        </li>
        
        <li>
        <h2>&nbsp;</h2>
        <p><input type="submit" name="save-info" value="<?php _e("Save" ,'PricerrTheme'); ?>" /></p>
        </li>
        
        </ul>
                </form>
                </div>
           </div>
           </div>     
        
        
        
        </div>
        
        
        
<?php 

	PricerrTheme_get_users_links();

} ?>