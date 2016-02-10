 <?php

	session_start();
	
	function PricerrTheme_filter_ttl($title){return __("Dectivate job",'PricerrTheme')." - ";}
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
       
            
            	<div class="box_title"><?php echo sprintf(__("Deactivate Job - %s", 'PricerrTheme'), $title); ?></div>
            	<div class="box_content">
              
              
       
       <?php
				
				if(isset($_POST['are_you_sure2']))
				{
					update_post_meta($pid,	'active',	0);
					echo sprintf(__("The job has been deactivated. <a href='%s'>Go back</a> to your jobs area.", 'PricerrTheme'), get_permalink(get_option('PricerrTheme_my_account_page_id')) );
				
				}
				else
				{
				?>
                
                <form method="post" enctype="application/x-www-form-urlencoded">
                <?php _e("Are you sure you want to deactivate this job?", 'PricerrTheme'); ?><br/><br/>
                <input type="submit" name="are_you_sure2" value="<?php _e("Confirm Deactivation", 'PricerrTheme'); ?>"  />
                </form>
                  
                 <?php } ?> 

              
                
                </div>
               
        
        </div></div>
            

	<?php PricerrTheme_get_users_links(); ?>


		<?php
		get_footer();
		?>