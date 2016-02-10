 <?php

	session_start();
	
	function PricerrTheme_filter_ttl($title){return __("Delete job",'PricerrTheme')." - ";}
	add_filter( 'wp_title', 'PricerrTheme_filter_ttl', 10, 3 );	
	
	if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }   
	   
	global $current_user, $wp_query;
	get_currentuserinfo;   

	$pid = $_GET['jobid'];
	$post = get_post($pid);

	$uid 	= $current_user->ID;
	$title 	= $post->post_title;
	$cid 	= $current_user->ID;
	
	if($uid != $post->post_author) { echo 'Not your post. Sorry!'; exit; }
//-----------------------------------------------------------------------------------------------

	
	
			get_header();
		
		//--------------------------------------------------
			
		$price 		= get_post_meta($pid, 'price', true);
		$ttl		= $post->post_title;
		$max_days 	= get_post_meta($pid, "max_days", true);
		$location 	= wp_get_object_terms($pid, 'job_location');
		$cat 		= wp_get_object_terms($pid, 'job_cat');
		
		
			?>
            
	
        <div id="content">
        	<div class="padd10">
       
            
            	<div class="box_title"><?php echo sprintf(__("Delete Job - %s", 'PricerrTheme'), $title); ?></div>
            	<div class="box_content">
              
              
               <?php
				
				if(isset($_POST['are_you_sure']))
				{
					wp_trash_post($pid);
					echo sprintf(__("The job has been deleted. <a href='%s'>Go back</a> to your account.", 'PricerrTheme'),PricerrTheme_my_account_link() );
				 
				}
				else
				{
				?>
                
                <form method="post" enctype="application/x-www-form-urlencoded">
                <?php _e("Are you sure you want to delete this job?", 'PricerrTheme'); ?><br/><br/>
                <input type="submit" name="are_you_sure" value="<?php _e("Confirm Deletion", 'PricerrTheme'); ?>"  />
                </form>
                  
                 <?php } ?> 

              
                
                </div>
               
        
        </div></div>
            

	<?php PricerrTheme_get_users_links(); ?>


		<?php
		get_footer();
		?>