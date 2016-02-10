<?php
/*****************************************************************************
*
*	copyright(c) - sitemile.com - PricerrTheme
*	More Info: http://sitemile.com/p/pricerr
*	Coder: Saioc Dragos Andrei
*	Email: andreisaioc@gmail.com
*
******************************************************************************/

	
	function PricerrTheme_filter_ttl($title){return __("Edit job",'PricerrTheme')." - ";}
	add_filter( 'wp_title', 'PricerrTheme_filter_ttl', 10, 3 );	
	
	if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }   
	   
	global $current_user, $wp_query;
	get_currentuserinfo;   

	$pid = $_GET['jobid'];
	$posta = get_post($pid);

	$uid 	= $current_user->ID;
	$title 	= $posta->post_title;
	$cid 	= $current_user->ID;
	
	if($uid != $posta->post_author) { echo 'Not your post. Sorry!'; exit; }
	
	//print_r($posta);

//-------------------------------------

		if(isset($_POST['save-job']))
		{
			
			//extra job stuff
			
			$sts = get_option('PricerrTheme_get_total_extras');
			if(empty($sts)) $sts = 3;
	
			
			for($k=1;$k<=$sts;$k++)
			{
				$extra_title 	= trim($_POST['extra'.$k.'_title']);
				$extra_price 	= trim($_POST['extra'.$k.'_price']);
				$extra_content 	= trim($_POST['extra'.$k.'_content']);
				
				
				if(!empty($extra_price) && is_numeric($extra_price) && !empty($extra_content)):
				
					update_post_meta($pid, 'extra'.$k.'_title', 	$extra_title);
					update_post_meta($pid, 'extra'.$k.'_price', 	$extra_price);
					update_post_meta($pid, 'extra'.$k.'_content', 	$extra_content);
				
				else:
						
					update_post_meta($pid, 'extra'.$k.'_title', 	'');
					update_post_meta($pid, 'extra'.$k.'_price', 	'');
					update_post_meta($pid, 'extra'.$k.'_content', 	'');	
						
				endif;
			}
		
			//-----------------------------
			
			$job_title 			= trim(strip_tags(htmlspecialchars($_POST['job_title'])));
			$job_description 	= trim(nl2br(strip_tags(htmlspecialchars($_POST['job_description']))));
			$job_tags 			= trim(strip_tags(htmlspecialchars($_POST['job_tags'])));	
				
			$max_days = trim(strip_tags(htmlspecialchars($_POST['max_days'])));
			$job_cost = htmlspecialchars(trim($_POST['job_cost']));
			$instruction_box	= substr( nl2br(strip_tags(htmlspecialchars($_POST['instruction_box']))), 0 , 500);
			update_post_meta($pid, "instruction_box", $instruction_box);
			
		//-----------------------	
			
			$PricerrTheme_enable_dropdown_values 	= get_option('PricerrTheme_enable_dropdown_values');
            $PricerrTheme_enable_free_input_box 	= get_option('PricerrTheme_enable_free_input_box');
		
			if($PricerrTheme_enable_dropdown_values == "yes" || $PricerrTheme_enable_free_input_box == "yes")
			update_post_meta($pid, "price", $job_cost);
			else		
			update_post_meta($pid, "price", get_option('PricerrTheme_job_fixed_amount'));
		
		
	//---------------------------------------
	// pictures
		
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		
		$default_nr = get_option('PricerrTheme_default_nr_of_pics');
		if(empty($default_nr)) $default_nr = 5;
			
		for($j=1;$j<=	$default_nr; $j++)
		{ 
			if(!empty($_FILES['file_' . $j]['name'])):
	  
	  			$upload_overrides 	= array( 'test_form' => false );
                $uploaded_file 		= wp_handle_upload($_FILES['file_' . $j], $upload_overrides);
				
				$file_name_and_location = $uploaded_file['file'];
                $file_title_for_media_library = $_FILES['file_' . $j]['name'];
						
				$arr_file_type 		= wp_check_filetype(basename($_FILES['file_' . $j]['name']));
                $uploaded_file_type = $arr_file_type['type'];

				if($uploaded_file_type == "image/png" or $uploaded_file_type == "image/jpg" or $uploaded_file_type == "image/jpeg" or $uploaded_file_type == "image/gif" )
				{
				
					$attachment = array(
									'post_mime_type' => $uploaded_file_type,
									'post_title' => 'Uploaded image ' . addslashes($file_title_for_media_library),
									'post_content' => '',
									'post_status' => 'inherit',
									'post_parent' =>  $pid,
	
									'post_author' => $cid,
								);
							 
					$attach_id = wp_insert_attachment( $attachment, $file_name_and_location, $pid );
					$attach_data = wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
					wp_update_attachment_metadata($attach_id,  $attach_data);
				
				}
				
			endif;
		}
		
		
		//---------------------------------------

		
		if(!empty($_FILES['file_instant']['name'])):
	  
	  			$upload_overrides 	= array( 'test_form' => false );
                $uploaded_file 		= wp_handle_upload($_FILES['file_instant'], $upload_overrides);
				
				$file_name_and_location = $uploaded_file['file'];
                $file_title_for_media_library = $_FILES['file_instant']['name'];
						
				$arr_file_type 		= wp_check_filetype(basename($_FILES['file_instant']['name']));
                $uploaded_file_type = $arr_file_type['type'];
				
				if($uploaded_file_type == "application/zip" )
				{
				
					$attachment = array(
									'post_mime_type' => $uploaded_file_type,
									'post_title' => 'Uploaded ZIP ' . addslashes($file_title_for_media_library),
									'post_content' => '',
									'post_status' => 'inherit',
									'post_parent' =>  $pid,
	
									'post_author' => $cid,
								);
							 
					$attach_id 		= wp_insert_attachment( $attachment, $file_name_and_location, $pid );
					$attach_data 	= wp_generate_attachment_metadata( $attach_id, $file_name_and_location );
					wp_update_attachment_metadata($attach_id,  $attach_data);
					
					update_post_meta($pid, 'instant', "1");
					
				}
				else {
					update_post_meta($pid, 'instant', "0");	
					$error_not_zip = 1;	
				}
		else:
			
			update_post_meta($pid, 'instant', "0");
				
		endif;
		
		//---------------------------------------
		
				$args = array(
				'order'          => 'ASC',
				'orderby'        => 'post_date',
				'post_type'      => 'attachment',
				'post_parent'    => $pid,
				'post_mime_type' => 'application/zip',
				'numberposts'    => -1,
				); $i = 0;
				
				$attachments = get_posts($args);
				if(count($attachments) > 0)
				{
					update_post_meta($pid, 'instant', "1");
				}
		
		//-----------------------
			
			$my_post 					= array();
			$my_post['ID'] 				= $pid;
			$my_post['post_content'] 	= $job_description;
			$my_post['post_title'] 		= $job_title;
			
			$PricerrTheme_admin_approve_job = get_option('PricerrTheme_admin_approve_job');
			
			if($PricerrTheme_admin_approve_job == "yes")
			{
				$my_post['post_status'] 		= 'draft';
				update_post_meta($pid, 'under_review', "1");	
			}
			else
			{
				$my_post['post_status'] 		= 'publish';
				update_post_meta($pid, 'under_review', "0");	
			}
	
			wp_update_post( $my_post );
				
			$term 		= get_term( $_POST['job_cat_cat'], 'job_cat' );	
			$job_cat 	= $term->slug;
			
			wp_set_post_tags( $pid, $job_tags);
			wp_set_object_terms($pid, array($job_cat),'job_cat');

			update_post_meta($pid, "max_days", $max_days);			
			update_post_meta($pid, "has_video", "0");
			update_post_meta($pid, "shipping", trim($_POST['shipping']));
			
			for($i=1;$i<=3;$i++){
				
				$y_link = htmlspecialchars($_POST['youtube_link'.$i]);
				update_post_meta($pid, "youtube_link".$i, trim($y_link));
				update_post_meta($pid, "has_video", "1");
			
			}
			
			$job_saved = 1;
			
			/**************************************************************/
			$PricerrTheme_new_job_listing_fee 	= get_option('PricerrTheme_new_job_listing_fee'); 
			update_post_meta($pid, 'featured',	"0");	
			$featured = get_post_meta($pid, 'featured',	true);
			$paid	  = get_post_meta($pid, 'paid',		true);
			
			if((isset($_POST['featured']) or $PricerrTheme_new_job_listing_fee > 0 ) and $error_not_zip != 1)
			{
				if($paid != "1")
				{
					$my_post = array();
					$my_post['post_status'] 	= 'draft';
					$my_post['ID'] 				= $pid;		
					wp_update_post( $my_post );
					
					if(isset($_POST['featured'])) // == "1")
					update_post_meta($pid, 'featured',	"1");
					
					$using_permalinks = PricerrTheme_using_permalinks();
				
					if($using_permalinks) $rdrlnk = get_permalink(get_option('PricerrTheme_pay_for_posting_job_page_id'))."?jobid=".$pid;
					else $rdrlnk = get_bloginfo('siteurl')."/?page_id=".get_option('PricerrTheme_pay_for_posting_job_page_id')."&jobid=".$pid;
					
					wp_redirect($rdrlnk);
				}
				else
				{
					if(isset($_POST['featured']))
					update_post_meta($pid, 'featured',	"1");	
				}
			}	
			
			
		}
		
		
					
		$price 		= get_post_meta($pid, 'price', true);
		$ttl		= $posta->post_title;
		$max_days 	= get_post_meta($pid, "max_days", true);
		$location 	= wp_get_object_terms($pid, 'job_location');
		$cat 		= wp_get_object_terms($pid, 'job_cat');
		
		
		
		
		
			$posta = get_post($pid);
			get_header();
		
			?>
            
	
        <div id="content">
        
       <div class="my_box3">
            	<div class="padd10">
            
            
            	<div class="box_title"><?php echo sprintf(__("Edit Job - %s", 'PricerrTheme'), $posta->post_title); ?></div>
                
                
                
            	<div class="box_content">
              
              <?php
				
					if($job_saved == 1 and $error_not_zip != 1):
					
						echo '<div class="edit-job-ok"><div class="padd10">'.__('Your job has been saved.','PricerrTheme').'</div></div>';
					
					endif;
					
					if($error_not_zip == 1):
						
						echo '<div class="error"><div class="padd10">'.__('ERROR: You can only attach ZIP files.','PricerrTheme').'</div></div>';
						
					endif;
				
				?>
                
          <script type="text/javascript">
	
	function delete_this(id)
	{
		 $.ajax({
						method: 'get',
						url : '<?php echo get_bloginfo('siteurl');?>/index.php/?_ad_delete_pid='+id,
						dataType : 'text',
						success: function (text) {   $('#image_ss'+id).remove(); window.location.reload();  }
					 });
		  //alert("a");
	
	}
	function validate()
	{
          var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?";
          for (var i = 0; i < document.myForm.job_title.value.length; i++)
			  {
                   if (iChars.indexOf(document.myForm.job_title.value.charAt(i)) != -1) 
				   {
                      alert ("The title has special characters. \n Special Characters are not allowed");
                      return false;
                   }
             }
	
}   
	
</script>        
              
               <ul class="post-new">
 
   <form method="post" enctype="multipart/form-data" onsubmit="validate()" action="<?php bloginfo('siteurl'); ?>/?jb_action=edit_job&jobid=<?php echo $pid; ?>">    
        <li>
                                <h2><?php echo __('I will', 'PricerrTheme'); ?>:</h2>
                                <p><input type="text" size="40" class="do_input" name="job_title" 
                                value="<?php echo $posta->post_title; ?>" /> <span class="large_font"><?php _e("for","PricerrTheme"); ?> 
                                <?php 
                            
							$PricerrTheme_enable_dropdown_values 	= get_option('PricerrTheme_enable_dropdown_values');
                            $PricerrTheme_enable_free_input_box 	= get_option('PricerrTheme_enable_free_input_box');
                            
                            if($PricerrTheme_enable_free_input_box == "yes")
                            {
                                
                                if(PricerrTheme_show_price_in_front() == true)
                                echo PricerrTheme_get_currency();
                                    
                                echo ' <input type="text" name="job_cost" class="do_input" value="'.$price.'" size="5" /> ';
                                
                                if(PricerrTheme_show_price_in_front() == false)
                                echo PricerrTheme_get_currency();
                                
                            }
                            elseif($PricerrTheme_enable_dropdown_values == "yes")
                            echo PricerrTheme_get_variale_cost_dropdown('do_input', $price);
                            else		
                            echo PricerrTheme_get_show_price(get_option('PricerrTheme_job_fixed_amount'));
                            
                            
                             ?></span> </p>
                            </li> 
        
        <li>
        	<h2><?php echo __('Category', 'PricerrTheme'); ?>:</h2>
        	<p><?php	echo PricerrTheme_get_categories("job_cat",  
			!isset($_POST['job_cat_cat']) ? (is_array($cat) ? $cat[0]->term_id : "") : htmlspecialchars($_POST['job_cat_cat'])
			, __('Select Category','PricerrTheme'), "do_input"); ?></p>
        </li>
        
        
         <li>
        	<h2><?php echo __('Location', 'PricerrTheme'); ?>:</h2>
        	<p><?php	echo PricerrTheme_get_categories("job_location",  
			!isset($_POST['job_location_cat']) ? (is_array($location) ? $location[0]->term_id : "") : htmlspecialchars($_POST['job_location_cat'])
			, __('Select Location','PricerrTheme'), "do_input"); ?></p>
        </li>
        
 
        <li>
        	<h2><?php echo __('Description', 'PricerrTheme'); ?>:</h2>
        <p><textarea rows="6" cols="45" class="do_input"  name="job_description"><?php 
		
		$pst = stripslashes($posta->post_content);
		
		echo empty($_POST['job_description']) ? str_replace("<br />","",$pst) : htmlspecialchars($_POST['job_description']); ?></textarea><br/>
       <?php _e('Min: 100 chars. Max: 500','PricerrTheme'); ?></p>
        </li>


	 <?php $instruction_box = get_post_meta($posta->ID, 'instruction_box', true); ?>
                            
                             <li><h2><?php echo __('Instructions to buyer', 'PricerrTheme'); ?>:</h2>
                            <p><textarea rows="6" cols="45" class="do_input"  name="instruction_box"><?php
							
							$instruction_box = stripslashes($instruction_box);
							 
                            echo empty($_POST['instruction_box']) ?  str_replace("<br />","",$instruction_box) : htmlspecialchars($_POST['instruction_box']); ?></textarea></p>
                            </li>
                            
                    
                    		<li>

<?php

	$job_tags = '';
	$t = wp_get_post_tags($posta->ID);
	
	foreach($t as $tag)
	{
		$job_tags .= $tag->name.',';
	}
?>
		<li>
        	<h2><?php echo __('Tags', 'PricerrTheme'); ?>:</h2>
        <p><input type="text" size="50" class="do_input"  name="job_tags" value="<?php echo $job_tags; ?>" /> </p>
        </li>
        
        
        <?php
							
								$PricerrTheme_enable_shipping = get_option('PricerrTheme_enable_shipping');
								if($PricerrTheme_enable_shipping == "yes"):
								
							?>
                            
                            <li>
                                <h2><?php echo __('Requires shipping?', 'PricerrTheme'); ?>:</h2>
                            <p>
                            <?php if(PricerrTheme_show_price_in_front())
                                echo PricerrTheme_get_currency(); ?>
                            <input type="text" size="5" class="do_input"  name="shipping" value="<?php echo (empty($shipping) ? get_post_meta($pid,'shipping',true) : $shipping ); ?>" />
                            <?php if(!PricerrTheme_show_price_in_front())
                                echo PricerrTheme_get_currency(); ?> </p>
                            </li>
                            
                            <?php endif; ?>
        <?php
		
			$instant = get_post_meta($pid,'instant',true);
			if($instant == 1): echo '<input type="hidden" value="1" name="max_days" />'; else:
		?>
        <li>
        	<h2><?php echo __('Max Days do Deliver', 'PricerrTheme'); ?>:</h2>
        <p><input type="text" size="10" class="do_input"  name="max_days" value="<?php echo $max_days; ?>" /> </p>
        </li>
        
       <?php endif; ?> 
        
           <?php
							
								$pricerrtheme_enable_instant_deli = get_option('pricerrtheme_enable_instant_deli');
								if($pricerrtheme_enable_instant_deli != "no"):
							
							?>
                            
          
             <li>
                                <h2><?php echo __('Instant Delivery File', 'PricerrTheme'); ?>:</h2>
                            <p>
                             <?php
		  
										$args = array(
										'order'          => 'ASC',
										'orderby'        => 'post_date',
										'post_type'      => 'attachment',
										'post_parent'    => $pid,
										'post_mime_type' => 'application/zip',
										'numberposts'    => -1,
										); $i = 0;
										
										$attachments = get_posts($args);
                            			
										if(count($attachments) == 0):
							
							?>
                            
                            <input type="file" class="do_input" name="file_instant" /> (<?php _e('Only ZIP Files','PricerrTheme'); ?>)
                            
                            <?php else: 
							
								
									if ($attachments) {
											foreach ($attachments as $attachment) {
											$url = wp_get_attachment_url($attachment->ID);
											
												echo '<p class="div_div2"  id="image_ss'.$attachment->ID.'">'.$attachment->post_title.'
												<a href="javascript: void(0)" onclick="delete_this(\''.$attachment->ID.'\')"><img border="0" src="'.get_bloginfo('template_url').'/images/delete_icon.png" /></a>
												</p>';
										  
										}
										}
								
							
							 endif; ?>
                            
                             </p>
                            </li>
                            
        <?php endif; ?>
        
        
                     <li>
                            <h2><?php echo __('Images', 'PricerrTheme'); ?>:</h2>
                            <p>
          <?php
		  
		  		$args = array(
				'order'          => 'ASC',
				'orderby'        => 'post_date',
				'post_type'      => 'attachment',
				'post_parent'    => $pid,
				'post_mime_type' => 'image',
				'numberposts'    => -1,
				); $i = 0;
				
				$attachments = get_posts($args);
				
				$default_nr = get_option('PricerrTheme_default_nr_of_pics');
		  		if(empty($default_nr)) $default_nr = 5;
				
				$actual_nr = count($attachments);
				$dis = $default_nr - $actual_nr;
		  
		  		for($i=1;$i<=$dis;$i++):
				?>                   
        		
                	<input type="file" class="do_input" name="file_<?php echo $i; ?>" />
				
				<?php	endfor; ?>
	
    <style type="text/css">
	.div_div
	{
		margin-left:5px; float:left; 
		width:110px;margin-top:10px;
	}
	
	</style>
    
                         
                           
                    
                          </p>
                            </li>
                           
                           <li>
                           
                            <div id="thumbnails" style="overflow:hidden;">
    
    <?php

	


	if ($attachments) {
	    foreach ($attachments as $attachment) {
		$url = wp_get_attachment_url($attachment->ID);
		
			echo '<div class="div_div"  id="image_ss'.$attachment->ID.'"><img width="70" class="image_class" height="70" src="' .
			PricerrTheme_generate_thumb($url, 70, 70). '" />
			<a href="javascript: void(0)" onclick="delete_this(\''.$attachment->ID.'\')"><img border="0" src="'.get_bloginfo('template_url').'/images/delete_icon.png" /></a>
			</div>';
	  
	}
	}


	?>
    
    </div>
    </li>
    
    
       
        <li>
        	<h2><?php echo __('Youtube Video Link #1','PricerrTheme'); ?>:</h2>
        <p><input type="text" size="50" name="youtube_link1" class="do_input" 
        	value="<?php echo get_post_meta($pid, 'youtube_link1', true); ?>" /></p>
        </li>
        
   		
         <li>
        	<h2><?php echo __('Youtube Video Link #2','PricerrTheme'); ?>:</h2>
        <p><input type="text" size="50" name="youtube_link2" class="do_input" 
        	value="<?php echo get_post_meta($pid, 'youtube_link2', true); ?>" /></p>
        </li>
        
        
         <li>
        	<h2><?php echo __('Youtube Video Link #3','PricerrTheme'); ?>:</h2>
        <p><input type="text" size="50" name="youtube_link3" class="do_input" 
        	value="<?php echo get_post_meta($pid, 'youtube_link3', true); ?>" /></p>
        </li>
        
    
       
          
        <?php $featured = get_post_meta($pid, 'featured', true); ?>
    <li>
        <h2>
       <?php _e("Feature job?",'PricerrTheme'); ?>:</h2>
        <p><input type="checkbox" class="do_input" name="featured" value="1" <?php if($featured == "1") echo 'checked="checked"'; ?> /> 
        
        <?php 
                            $PricerrTheme_new_job_feat_listing_fee = get_option('PricerrTheme_new_job_feat_listing_fee');
                            $PricerrTheme_new_job_feat_listing_fee = PricerrTheme_get_show_price($PricerrTheme_new_job_feat_listing_fee);
                            
                            echo sprintf( __("By clicking this checkbox you mark your job as featured. Extra fee of %s is applied.", 'PricerrTheme'), $PricerrTheme_new_job_feat_listing_fee); ?>
        
         </p>
        </li>
     
        <?php
							
			$PricerrTheme_enable_extra = get_option('PricerrTheme_enable_extra');
			if($PricerrTheme_enable_extra != "no"):
							
							?>
        
        <li class="xtra_stuff"><div class="padd10">
        <table width="100%">
        <?php
		
			$sts = get_option('PricerrTheme_get_total_extras');
			if(empty($sts)) $sts = 3;
								
			for($i=1;$i<=$sts;$i++):
			
		
		?>
            <tr><td valign="top">
            <?php _e('For an extra','PricerrTheme'); ?> 
            
			<?php if(PricerrTheme_show_price_in_front())  echo PricerrTheme_get_currency(); ?>
            <input type="text" size="3" name="extra<?php echo $i; ?>_price" value="<?php echo get_post_meta($pid, 'extra'.$i.'_price', true); ?>" />
			<?php if(!PricerrTheme_show_price_in_front())  echo PricerrTheme_get_currency(); ?>
            
            &nbsp; &nbsp; <?php _e('I will:','PricerrTheme'); ?> </td>
            <td>  <textarea name="extra<?php echo $i; ?>_content" cols="40" rows="2"><?php echo get_post_meta($pid, 'extra'.$i.'_content', true); ?></textarea></td></tr>
    
        <?php endfor; ?>
        
        </table>
        
      	</div>
        </li>
        <?php endif; ?>
        
        <li>
        <h2>&nbsp;</h2>
        <p><input type="submit" name="save-job" value="<?php _e("Save Job", 'PricerrTheme'); ?> >>" /></p>
        </li>
    
    </form>
    </ul>

              
              
                
                </div>
               
        
        </div></div></div>
            

	<?php PricerrTheme_get_users_links(); ?>


		<?php
		get_footer();
		?>