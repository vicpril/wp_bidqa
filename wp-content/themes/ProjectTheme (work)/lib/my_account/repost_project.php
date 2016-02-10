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
 
 	session_start();
	global $current_user, $wp_query;
	$pid 	=  $wp_query->query_vars['pid'];
	
	function ProjectTheme_filter_ttl($title){return __("Repost Project",'ProjectTheme')." - ";}
	add_filter( 'wp_title', 'ProjectTheme_filter_ttl', 10, 3 );	
	
	if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }   
	   
	
	get_currentuserinfo;   

	$post = get_post($pid);

	$uid 	= $current_user->ID;
	$title 	= $post->post_title;
	$cid 	= $current_user->ID;
	
	if($uid != $post->post_author) { echo 'Not your post. Sorry!'; exit; }

//-------------------------------------


$cid = $uid;
		
	
		//---autodrafting
		
			$new_pid = ProjectTheme_get_auto_draft($uid);
			$itwas_reposted = get_post_meta($new_pid, 'itwas_reposted_', true);
			
			if(empty($itwas_reposted))
			{
				
				update_post_meta($new_pid, 'itwas_reposted_', "done");
				
				
				$args = array(
				'order'          => 'ASC',
				'orderby'        => 'post_date',
				'post_type'      => 'attachment',
				'post_parent'    => $pid,
				
				'post_status'    => null,
				'numberposts'    => -1,
				);
				$attachments = get_posts($args);
				$uploads = wp_upload_dir();
				
				foreach($attachments as $att)
				{
						$img_url = wp_get_attachment_url($att->ID);
						$basedir = $uploads['basedir'].'/';
						$exp = explode('/',$img_url);
					
						
						$nr = count($exp);
						$pic = $exp[$nr-1];
						$year = $exp[$nr-3];
						$month = $exp[$nr-2];
					
						if($uploads['basedir'] == $uploads['path'])
						{
							$img_url = $basedir.'/'.$pic;
							$ba = $basedir.'/';
							$iii = $uploads['url'];
						}
						else
						{
							$img_url = $basedir.$year.'/'.$month.'/'.$pic;
							$ba = $basedir.$year.'/'.$month.'/';
							$iii = $uploads['baseurl']."/".$year."/".$month;
						}
						
						$oldPic_name = $img_url;
						
						$newpicname = 'copy_'.rand(0,999).'_'.$pic;
						$newPic_name = $uploads['path'].'/'.$newpicname;
						
						//echo $oldPic_name.'<br/>';
						//echo $newPic_name.'<br/>';
						
						copy($oldPic_name, $newPic_name);
						ProjectTheme_insert_pic_media_lib($cid, $new_pid, $uploads['url'].'/'.$newpicname, $newPic_name, $newpicname);
						//echo $newPic_name.'<br/>';
					
					
					
				}
				
				//-----------------------
			}

			// lets submit it
			
			if(isset($_POST['project_submit1']))
			{
				$project_title 			= trim(strip_tags($_POST['project_title']));
				$project_description 	= nl2br(strip_tags($_POST['project_description']));
				$project_category 		= strip_tags($_POST['project_cat_cat']);
				$project_location 		= trim($_POST['project_location_cat']);
				$project_tags 			= trim(strip_tags($_POST['project_tags']));
				
				$price 					= projectTheme_clear_sums_of_cash(trim($_POST['price']));
				$project_location_addr 	= strip_tags(trim($_POST['project_location_addr']));
				
			
				
				//-------------------------------
				$adOK = 1;
				
				if(empty($project_title)) 		{ $adOK = 0; $error['title'] 		= __('You cannot leave the project title blank!','ProjectTheme'); }
				if(empty($project_description)) { $adOK = 0; $error['description'] 	= __('You cannot leave the project description blank!','ProjectTheme'); }
			
				
				//-------------------------------
	
					$project_category2 	= $project_category;
		
					$my_post = array();
					$my_post['post_title'] 		= $project_title;
					$my_post['ID'] 				= $new_pid;
					$my_post['post_content'] 	= $project_description;	
					$my_post['post_status'] 	= 'draft';	
					wp_update_post( $my_post );
					
				//-----------------------------------------	
					
					
					$term 				= get_term( $project_category, 'project_cat' );	
					$project_category 	= $term->slug;
					wp_set_object_terms($new_pid, array($project_category),'project_cat');
			
				//-----------------------------------------
						
					
					$term 				= get_term( $project_location, 'project_location' );	
					$project_location 	= $term->slug;
					$arr_cats 					= array();
					$arr_cats[] 				= $project_location;
						
					if(!empty($_POST['subloc']))
					{
						$term = get_term( $_POST['subloc'], 'project_location' );	
						$jb_category2 = $term->slug;
						$arr_cats[] = $jb_category2;
						 
					}
					
					if(!empty($_POST['subloc2']))
					{
						$term = get_term( $_POST['subloc2'], 'project_location' );	
						$jb_category2 = $term->slug;
						$arr_cats[] = $jb_category2;
						 
					}
					
					//wp_set_object_terms($pid, $arr_cats ,'project_location');
					wp_set_object_terms($new_pid, $arr_cats/*array($project_location)*/,'project_location');	
						
				//-----------------------------------------
					  
					//$_SESSION['coupon'] = false;
					  
					wp_set_post_tags( $new_pid, $project_tags);
					  
					update_post_meta($new_pid, "Location", $project_location_addr);
					update_post_meta($new_pid, "price", $price);
					update_post_meta($new_pid, 'is_draft', "0");  
					update_post_meta($new_pid, "paid", "0");
					
					if(isset($_POST['featured'])) 
						update_post_meta($new_pid, "featured", "1");
					else
						update_post_meta($new_pid, "featured", "0");  

					# Hide project from search engines
					if(isset($_POST['hide_project'])) 
						update_post_meta($new_pid, "hide_project", "1");
					else
						update_post_meta($new_pid, "hide_project", "0");  

		
					update_post_meta($new_pid, "private_bids", strip_tags($_POST['private_bids'])); 
					update_post_meta($new_pid, "views", '0');
			
					 
					$end = $_POST['ending']; 
					 
					if(empty($end)) $ending = current_time('timestamp',0) + 30*3600*24; // ending time for auction
					else $ending = strtotime($end, current_time('timestamp',0));
				
					update_post_meta($new_pid, "closed", "0");
					update_post_meta($new_pid, "closed_date", "0");
					update_post_meta($new_pid, "ending", $ending);
					
					update_post_meta($new_pid, "price", 		ProjectTheme_get_budget_name_string_fromID($_POST['budgets'])); // set project price
					update_post_meta($new_pid, "budgets", 		$_POST['budgets']);
					 
					//------ custo fields --------------
		
					$arr = $_POST['custom_field_id'];
					for($i=0;$i<count($arr);$i++)
					{
						$ids 	= $arr[$i];
						$value 	= strip_tags($_POST['custom_field_value_'.$ids]);
						
						if(is_array($value))
						{
							for($j=0;$j<count($value);$j++)
								update_post_meta($new_pid, "custom_field_ID_".$ids, $value[$j]);
						}
						else
						update_post_meta($new_pid, "custom_field_ID_".$ids, $value);
						
					}  
					
	
				if($adOK == 1) //if everything ok, go to next step
				{		
					
					wp_redirect(get_bloginfo('siteurl').'/?p_action=relist_this_done&finalize=1&pid='.$new_pid);
					//wp_redirect(get_permalink($new_pid));
					exit;	
				}
				
			}

//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

	get_header();
	
	
	$post 		= get_post($pid);
	$location 	= wp_get_object_terms($pid, 'project_location');
	$cat 		= wp_get_object_terms($pid, 'project_cat');	
?>

                 <div class="page_heading_me">
                        <div class="page_heading_me_inner">
                            <div class="mm_inn"> <?php _e("Repost Project", "ProjectTheme"); ?> </div>
                  	            
                                        
                        </div>
                    
                    </div> 
<!-- ########## -->

<div id="main_wrapper">
		<div id="main" class="wrapper"><div class="padd10">
 


<div id="content" class="account-main-area">
        	
            <div class="my_box3">
            	<div class="padd10">
            
             
                <div class="box_content"> 
            	
                
               
      
      <?php
	
	$ProjectTheme_enable_images_in_projects = get_option('ProjectTheme_enable_images_in_projects');
				if($ProjectTheme_enable_images_in_projects == "yes"):
				
				?>
  
              
                <?php endif; //print_r($error); ?>
                
                <form method="post">  
    <ul class="post-new3">
        <li>
        	<h2><?php echo __('Your project title', 'ProjectTheme'); ?>:</h2>
        	<p><input type="text" size="50" class="do_input" name="project_title" 
            value="<?php echo (empty($_POST['project_title']) ? 
			($post->post_title == "draft project" ? "" : $post->post_title) : $_POST['project_title']); ?>" /></p>
        </li>
        
        <li>
        	<h2><?php echo __('Category', 'ProjectTheme'); ?>:</h2>
        	<p><?php	echo ProjectTheme_get_categories("project_cat",  
			!isset($_POST['project_cat_cat']) ? (is_array($cat) ? $cat[0]->term_id : "") : $_POST['project_cat_cat']
			, __('Select','ProjectTheme'), "do_input"); ?></p>
        </li>
        
	
    	        <?php   

	$cid = $current_user->ID;
	$cwd = str_replace('wp-admin','',getcwd());
	$cwd .= 'wp-content/uploads';

	//echo get_template_directory();

?>

 
    
    <li>
        	<h3><?php _e('Attach Images','ProjectTheme'); ?></h3>
        </li>
        
         <li>
        <div class="cross_cross">

 
    
 
    
    
    <script>
 
	
	jQuery(function() {

Dropzone.autoDiscover = false; 	 
var myDropzoneOptions = {
  maxFilesize: 15,
    addRemoveLinks: true,
	acceptedFiles:'image/*',
    clickable: true,
	url: "<?php bloginfo('siteurl') ?>/?my_upload_of_project_files2=1",
};
 
var myDropzone = new Dropzone('div#myDropzoneElement2', myDropzoneOptions);

myDropzone.on("sending", function(file, xhr, formData) {
  formData.append("author", "<?php echo $cid; ?>"); // Will send the filesize along with the file as POST data.
  formData.append("ID", "<?php echo $pid; ?>"); // Will send the filesize along with the file as POST data.
});

   
    <?php

		$args = array(
	'order'          => 'ASC',
	'orderby'        => 'menu_order',
	'post_type'      => 'attachment',
	'post_parent'    => $pid,
	'post_status'    => null,
	'post_mime_type' => 'image',
	'numberposts'    => -1,
	);
	$attachments = get_posts($args);
	
	if($pid > 0)
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

    

	<?php _e('Click the grey area below to add project images. Other files are not accepted. Use the form below.','ProjectTheme') ?>
    <div class="dropzone dropzone-previews" id="myDropzoneElement2" ></div>
 
    
	</div>
        </li>
   		
        <li>
        	<h3><?php _e('Attach Files','ProjectTheme'); ?></h3>
        </li>
        
        
        <li>
        <div class="cross_cross">



	<script type="text/javascript" src="<?php echo get_bloginfo('template_url'); ?>/js/dropzone.js"></script>     
	<link rel="stylesheet" href="<?php echo get_bloginfo('template_url'); ?>/css/dropzone.css" type="text/css" />
    
 
    
    
    <script>
 
	
	jQuery(function() {

Dropzone.autoDiscover = false; 	 
var myDropzoneOptions = {
  maxFilesize: 15,
    addRemoveLinks: true,
	acceptedFiles:'.zip,.pdf,.rar,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.psd,.ai,.rtf,.txt',
    clickable: true,
	url: "<?php bloginfo('siteurl') ?>/?my_upload_of_project_files=1",
};
 
var myDropzone = new Dropzone('div#myDropzoneElement', myDropzoneOptions);

myDropzone.on("sending", function(file, xhr, formData) {
  formData.append("author", "<?php echo $cid; ?>"); // Will send the filesize along with the file as POST data.
  formData.append("ID", "<?php echo $pid; ?>"); // Will send the filesize along with the file as POST data.
});

   
    <?php

		$args = array(
	'order'          => 'ASC',
	'orderby'        => 'menu_order',
	'post_type'      => 'attachment',
	'post_parent'    => $pid,
	'post_status'    => null,
	'numberposts'    => -1,
	);
	$attachments = get_posts($args);
	
	if($pid > 0)
	if ($attachments) {
	    foreach ($attachments as $attachment) {
		$url = $attachment->guid;
		$imggg = $attachment->post_mime_type; 
		
		if('image/png' != $imggg && 'image/jpeg' != $imggg)
		{
		$url = wp_get_attachment_url($attachment->ID);
 
			
			?>

					var mockFile = { name: "<?php echo $attachment->post_title ?>", size: 12345, serverId: '<?php echo $attachment->ID ?>' };
					myDropzone.options.addedfile.call(myDropzone, mockFile);
					myDropzone.options.thumbnail.call(myDropzone, mockFile, "<?php echo bloginfo('template_url') ?>/images/file_icon.png");
					 
			
			<?php
			
	  
	}
	}}


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

	<?php _e('Click the grey area below to add project files. Images are not accepted.','ProjectTheme') ?>
    <div class="dropzone dropzone-previews" id="myDropzoneElement" ></div>
 
    
	</div>
        </li>
        
        
        <li>
        	<h2><?php echo __('Price', 'ProjectTheme'); ?>:</h2>
        <p>
        
          <?php
	  
	  $sel = get_post_meta($pid, 'budgets', true);
	  echo ProjecTheme_get_budgets_dropdown($sel, 'do_input');
	  
	  ?>
        
        </p>
        </li>
        
        
        

        
        
        
                 <li>
        <h2>
        
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script>

        
        
        <link rel="stylesheet" media="all" type="text/css" href="<?php echo get_bloginfo('template_url'); ?>/css/ui-thing.css" />
		<script type="text/javascript" language="javascript" src="<?php echo get_bloginfo('template_url'); ?>/js/jquery-ui-timepicker-addon.js"></script>
 
       <?php _e("Project Ending On",'ProjectTheme'); ?>:</h2>
        <p><input type="text" name="ending" id="ending" class="do_input"  /></p>
        </li>
        
 <script>

$(document).ready(function() {
	 $('#ending').datetimepicker({
	showSecond: true,
	timeFormat: 'hh:mm:ss'
});});
 
 </script>
        
        
        <li>
        	<h2><?php echo __('Location:', 'ProjectTheme'); ?></h2>
        <!-- <p><?php	echo ProjectTheme_get_categories("project_location", 
		empty($_POST['project_location_cat']) ? (is_array($location) ? $location[0]->term_id : "") : $_POST['project_location_cat'], __('Select','ProjectTheme'), 
		"do_input"); ?></p> -->
		        <p class="strom_100"> 
		        
		  
		        
		        <?php	 
					 
					 	echo projectTheme_get_categories_clck("project_location",  
		                                !isset($_POST['project_location_cat']) ? (is_array($location) ? $location[0]->term_id : "") : htmlspecialchars($_POST['project_location_cat'])
		                                , __('Select Location','ProjectTheme'), "do_input_new", 'onchange="display_subcat2(this.value)"' );
										
										
										echo '<br/><span id="sub_locs">';
					 
						
													if(!empty($location[1]->term_id))
													{
														$args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$location[0]->term_id;
														$sub_terms2 = get_terms( 'project_location', $args2 );	
														
														$ret = '<select class="do_input_new" name="subloc">';
														$ret .= '<option value="">'.__('Select SubLocation','ProjectTheme'). '</option>';
														$selected1 = $location[1]->term_id;
														
														foreach ( $sub_terms2 as $sub_term2 )
														{
															$sub_id2 = $sub_term2->term_id; 
															$ret .= '<option '.($selected1 == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">'.$sub_term2->name.'</option>';
														
														}
														$ret .= "</select>";
														echo $ret;	
														
														
													}
													
												echo '</span>';		
												
												
												echo '<br/><span id="sub_locs2">';
					 
						
													if(!empty($location[2]->term_id))
													{
														$args2 = "orderby=name&order=ASC&hide_empty=0&parent=".$location[1]->term_id;
														$sub_terms2 = get_terms( 'project_location', $args2 );	
														
														$ret = '<select class="do_input_new" name="subloc2">';
														$ret .= '<option value="">'.__('Select SubLocation','ProjectTheme'). '</option>';
														$selected1 = $location[2]->term_id;
														
														foreach ( $sub_terms2 as $sub_term2 )
														{
															$sub_id2 = $sub_term2->term_id; 
															$ret .= '<option '.($selected1 == $sub_id2 ? "selected='selected'" : " " ).' value="'.$sub_id2.'">'.$sub_term2->name.'</option>';
														
														}
														$ret .= "</select>";
														echo $ret;	
														
														
													}
													
												echo '</span>';			
					
					 ?>
		        
		        
		        
		        
		        
		        
		       <?php echo '<div><h2 class="other_locs">Don\'t see your country? Contact us!</h2><div style="display:none;">'.do_shortcode('[contact-form-7 id="146" title="Contact form 1"]').'</div></div>'; ?> 
		        </p>
		        
		        </li>
		        <script type="text/javascript">
		        	$(document).ready(function(){
		        		$('.other_locs').click(function(){
		        			$(this).next().toggle();
		        		});
		        	});
		        </script>
		        <script>
			
									function display_subcat(vals)
									{
										jQuery.post("<?php bloginfo('siteurl'); ?>/?get_subcats_for_me=1", {queryString: ""+vals+""}, function(data){
											if(data.length >0) {
												 
												jQuery('#sub_cats').html(data);
												 
											}
										});
										
									}
									
									
									function display_subcat2(vals)
									{
										jQuery.post("<?php bloginfo('siteurl'); ?>/?get_locscats_for_me=1", {queryString: ""+vals+""}, function(data){
											if(data.length >0) {
												 
												jQuery('#sub_locs').html(data);
												jQuery('#sub_locs2').html("&nbsp;");
												 
											}
											else
											{
												jQuery('#sub_locs').html("&nbsp;");
												jQuery('#sub_locs2').html("&nbsp;");	
											}
										});
										
									}
									
									function display_subcat3(vals)
									{
										jQuery.post("<?php bloginfo('siteurl'); ?>/?get_locscats_for_me2=1", {queryString: ""+vals+""}, function(data){
											if(data.length >0) {
												 
												jQuery('#sub_locs2').html(data);
												 
											}
										});
										
									}
									
									</script>
        </li>
        
        
 
        
        
        
        <li>
        	<h2><?php echo __('Address:','ProjectTheme'); ?></h2>
        <p><input type="text" size="50" class="do_input"  name="project_location_addr" value="<?php echo !isset($_POST['project_location_addr']) ? 
		get_post_meta($pid, 'Location', true) : $_POST['project_location_addr']; ?>" /> </p>
        </li>
        
        
        <li>
        	<h2><?php echo __('Description', 'ProjectTheme'); ?>:</h2>
        <p><textarea rows="6" cols="40" class="do_input description_edit"  name="project_description"><?php 
		echo empty($_POST['project_description']) ? trim($post->post_content) : $_POST['project_description']; ?></textarea></p>
        </li>


		<li>
        	<h2><?php echo __('Tags', 'ProjectTheme'); ?>:</h2>
        <p><input type="text" size="50" class="do_input"  name="project_tags" value="<?php echo $project_tags; ?>" /> </p>
        </li>
        
        <?php
		
        $ProjectTheme_enable_featured_option = get_option('ProjectTheme_enable_featured_option');						   
		if($ProjectTheme_enable_featured_option != "no"):
						   
		  ?>
        
        
        <li>
        <h2><?php _e("Feature project?",'ProjectTheme'); ?>:</h2>
        <p><input type="checkbox" class="do_input" name="featured" <?php
		$featured = get_post_meta($pid, 'featured', true);
		if(isset($_POST['featured'])) echo 'checked="checked"';
		else
		{
			if($featured == "1") echo 'checked="checked"';		
		}
		 ?> value="1" /> 
        <?php _e("By clicking this checkbox you mark your project as featured. Extra fee is applied.", 'ProjectTheme'); ?></p>
        </li>
       
          <?php
					endif;
					
						   
					 	$ProjectTheme_enable_sealed_option = get_option('ProjectTheme_enable_sealed_option');						   
					   	if($ProjectTheme_enable_sealed_option != "no"):
						   
						   ?>
        <li>
        <h2><?php _e("Sealed bidding?",'ProjectTheme'); ?>:</h2>
        <p><input type="checkbox" class="do_input" name="private_bids" <?php
		$private_bids = get_post_meta($pid, 'private_bids', true);
		if(isset($_POST['private_bids'])) echo 'checked="checked"';
		else
		{
			if($private_bids == "1") echo 'checked="checked"';		
		}
		 ?> value="1" /> 
        <?php _e("By clicking this checkbox you mark your seal the bidding. Extra fee is applied.", 'ProjectTheme'); ?></p>
        </li>
        
        <?php endif; ?>
        
        
        <?php
						   
						   	$ProjectTheme_enable_hide_option = get_option('ProjectTheme_enable_hide_option');						   
						   	if($ProjectTheme_enable_hide_option != "no"):
						   
						   ?>  
        
        <li>
        <h2><?php _e("Hide project from search engines?",'ProjectTheme'); ?>:</h2>
        <p><input type="checkbox" class="do_input" name="hide_project" <?php
		$hide_project = get_post_meta($pid, 'hide_project', true);
		if(isset($_POST['hide_project'])) echo 'checked="checked"';
		else
		{
			if($hide_project == "1") echo 'checked="checked"';		
		}
		 ?> value="1" /> 
        <?php _e("By clicking this checkbox you hide the project from search engines. Extra fee is applied.", 'ProjectTheme'); ?></p>
        </li>
        
        
		
		<?php endif; ?>
        
        
       <!--         
        <li>
        <h2><?php _e("Coupon", "ProjectTheme"); ?>:</h2>
        <p><input type="text" class="do_input" name="coupon" size="30" />
        <?php if($ok_ad == 0 && isset($_POST['auction_submit1'])) _e('The coupon code you used is wrong.','ProjectTheme'); ?></p>
        </li>
        
-->
        
        <?php /*-------  custom fields  -------- */ ?>
        <?php
		
		
		$arr = ProjectTheme_get_project_category_fields($catid, $pid);
		
		for($i=0;$i<count($arr);$i++)
		{
			        echo '<li>';
					echo '<h2>'.$arr[$i]['field_name'].$arr[$i]['id'].':</h2>';
					echo '<p>'.$arr[$i]['value'].'</p>';
					echo '</li>';
		
		}	
		
		
		?>        
       
     
        
        <li>
        <h2>&nbsp;</h2>
        <p><input type="submit" name="project_submit1" value="<?php _e("Publish Project", 'ProjectTheme'); ?> >>" /></p>
        </li>
    
    
    </ul>
    </form>
                
                
                </div>
                </div>
                </div>
                </div>
                
	<?php ProjectTheme_get_users_links(); ?>

</div></div></div>
<?php get_footer(); ?>