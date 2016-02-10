<?php
/*****************************************************************************
*
*	copyright(c) - sitemile.com - PricerrTheme
*	More Info: http://sitemile.com/p/pricerr
*	Coder: Saioc Dragos Andrei
*	Email: andreisaioc@gmail.com
*
******************************************************************************/

function PricerrTheme_my_account_reviews_area_function()
{
	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;
	
	//-------------------------------------	

    
    	
		global $wpdb,$wp_rewrite,$wp_query;
		$third_page = $wp_query->query_vars['third_page'];
	
		$third_page = $_GET['pg'];
		if(empty($_GET['pg'])) $third_page = 'home';	
			
		?>	
       
		<div id="content">
        	
        <div class="my_box3">
            	<div class="padd10">
        
        
		<!-- page content here -->	
		<div class="box_title3"><?php _e("My Ratings",'PricerrTheme'); ?></div>     	
		 <div class="clear10"></div>
	
        
        <div class="clear10"></div>
			<?php
			
			$using_perm = PricerrTheme_using_permalinks();
	
			if($using_perm)	$rev_pg_lnk = get_permalink(get_option('PricerrTheme_my_account_reviews_page_id')). "/?";
			else $rev_pg_lnk = get_bloginfo('siteurl'). "/?page_id=". get_option('PricerrTheme_my_account_reviews_page_id'). "&";	
			
			
			?>
            <ul id="shopping_menu">
            	<li><a <?php  echo ($third_page == "home" ? 'class="actiove"' : ""); ?> href="<?php echo $rev_pg_lnk; ?>"><?php _e("Ratings to Award","PricerrTheme"); ?></a></li>
            	<li><a <?php  echo ($third_page == "waiting" ? 'class="actiove"' : ""); ?> href="<?php echo $rev_pg_lnk; ?>pg=waiting"><?php _e("Pending Ratings","PricerrTheme"); ?></a></li>
            	<li><a <?php  echo ($third_page == "my_rev" ? 'class="actiove"' : ""); ?> href="<?php echo $rev_pg_lnk; ?>pg=my_rev"><?php _e("My Ratings","PricerrTheme"); ?></a></li>
            	
            </ul>
            

			<div class="clear10"></div>
			
            <?php
			
				if($third_page == "home"):
			
			?>

                <div class="box_content">    
				<script>
				
					 $(document).ready(function() {
  
					$('.dd-submit-rating').click(function() {
					
					var id = $(this).attr('rel');  
					var uprating = $("#rating_me-" + id + " :selected").val();
					var reason = $("#reason-" + id).val();
					if(reason.length == 0) { alert("<?php _e('Please input a description for your rating','PricerrTheme'); ?>"); return false; }
					
					$.ajax({
						   type: "POST",
						   url: "<?php echo get_bloginfo('siteurl'); ?>/",
						   data: "rate_me=1&ids="+id+"&uprating="+uprating+"&reason="+reason,
						   success: function(msg){
							   
							$("#post-" + id).hide('slow');
							
						   }
						 });
					
					return false;
					});
					
					//-------------------------
					 
				  
				  
				 });
								
				
				</script>
              	<?php
					
					global $wpdb;
					$query = "select distinct *, ratings.id ratid from ".$wpdb->prefix."job_ratings ratings, ".$wpdb->prefix."job_orders orders where 
					 ratings.awarded='0' AND orders.id=ratings.orderid AND orders.uid='$uid'";
					$r = $wpdb->get_results($query);
					
					if(count($r) > 0)
					{

						
						foreach($r as $row)
						{
							$post = $row->pid;
							$post = get_post($post);
							$user = get_userdata($row->touser);
							
							?>
                            
                            <div class="post" id="post-<?php echo $row->ratid; ?>">
                                <div class="padd10_only">
                                <div class="image_holder3">
                                <a href="<?php the_permalink(); ?>"><img width="65" height="50" 
                                src="<?php echo PricerrTheme_get_first_post_image($row->pid,65,50); ?>" /></a>
                                </div>
                            
                            <div  class="title_holder3" >
                            <h2><a href="<?php echo get_permalink($row->pid); ?>"><?php echo PricerrTheme_wrap_the_title($post->post_title,$row->pid); ?></a></h2>
                            
                            <div class="c111">Rate:</div>
                            <div class="c111"><textarea id="reason-<?php echo $row->ratid; ?>" rows="2" cols="35"></textarea></div>
                            
                            <div class="c111"> <select name="rating_me" id="rating_me-<?php echo $row->ratid; ?>">
                            
                            <option value="5">5</option>
                            <option value="4">4</option>
                            <option value="3">3</option>
                            <option value="2">2</option>
                            <option value="1">1</option>
                            </select>
                            
                            </div>
                            <div class="c111 ck999">
                            <a href="#" rel="<?php echo $row->ratid; ?>" class="dd-submit-rating"><?php _e('Submit Rating Now','PricerrTheme') ?></a>
                            
                            </div>
                            
                            
                            </div> 
                            
                         
                            
                            </div>
                            </div>
                        
                            
                            <?php
							
						}
					
					}
					else
					{
						_e("There are no reviews to be awarded.","PricerrTheme");	
					}
				?>
                
          
           </div>    
           
           <?php elseif($third_page == "waiting"): ?>
                <div class="box_content">    
				
              	<?php
					
					global $wpdb;
					$query = "select distinct * from ".$wpdb->prefix."job_ratings ratings, ".$wpdb->prefix."job_orders orders, 
					".$wpdb->prefix."posts posts where posts.ID=orders.pid AND 
					 ratings.awarded='0' AND orders.id=ratings.orderid AND posts.post_author='$uid'";
					$r = $wpdb->get_results($query);
					
					if(count($r) > 0)
					{

						
						foreach($r as $row)
						{
							$post = $row->pid;
							$post = get_post($post);
							$user = get_userdata($row->uid);
							
							?>
                            
                            <div class="post" id="post-<?php echo $row->ratid; ?>">
                                <div class="padd10_only">
                                <div class="image_holder3">
                                <a href="<?php the_permalink(); ?>"><img width="65" height="50" 
                                src="<?php echo PricerrTheme_get_first_post_image($row->pid,65,50); ?>" /></a>
                                </div>
                            
                            <div  class="title_holder3" >
                            <h2><a href="<?php echo get_permalink($row->pid); ?>"><?php echo PricerrTheme_wrap_the_title($post->post_title,$row->pid); ?></a></h2> 
                            <?php echo sprintf(__('Waiting from: %s','PricerrTheme'), $user->user_login ); ?>
                            
                            
                            
                            
                            </div> 
                            
                         
                            
                            </div>
                            </div>
                        
                            
                            <?php
							
						}
					
					}
					else
					{
						_e("You have no pending reviews.","PricerrTheme");	
					}
				?>
                
                
                </div>
  
  
  				<?php elseif($third_page == "my_rev"): ?>
  
                <div class="box_content">    
				
              	<?php
					
					global $wpdb;
					$query = "select distinct *, ratings.id ratid from ".$wpdb->prefix."job_ratings ratings, ".$wpdb->prefix."job_orders orders, 
					".$wpdb->prefix."posts posts where posts.ID=orders.pid AND 
					 ratings.awarded='1' AND orders.id=ratings.orderid AND posts.post_author='$uid'";
					$r = $wpdb->get_results($query);
					
					if(count($r) > 0)
					{

						
						foreach($r as $row)
						{
							$post = $row->pid;
							$post = get_post($post);
							$user = get_userdata($row->touser);
							
							?>
                            
                            <div class="post" id="post-<?php echo $row->ratid; ?>">
                                <div class="padd10_only">
                                <div class="image_holder3">
                                <a href="<?php the_permalink(); ?>"><img width="65" height="50" 
                                src="<?php echo PricerrTheme_get_first_post_image($row->pid,65,50); ?>" /></a>
                                </div>
                            
                            <div  class="title_holder3" >
                            <h2><a href="<?php echo get_permalink($row->pid); ?>"><?php echo PricerrTheme_wrap_the_title($post->post_title, $row->pid); ?></a></h2>
                            
                            <div class="c111"><b><?php _e("Rated","PricerrTheme"); ?>: </b></div>
                            
                            <div class="c111"><?php
							
							if($row->grade == 0) echo __('Negative','PricerrTheme');
							if($row->grade == 1) echo __('Positive','PricerrTheme');
							
							?>
                            </div>
                            <div class="clear10"></div>
                            <div class="c111"><b><?php _e("Description","PricerrTheme"); ?>: </b></div>
                            
                            <div class="c111"><?php echo stripslashes($row->reason); ?>
                            </div>
                            
                            
                            </div> 
                            
                         
                            
                            </div>
                            </div>
                        
                            
                            <?php
							
						}
					
					}
					else
					{
						_e("You have no reviews.","PricerrTheme");	
					}
				?>
                
                
                </div>
          <?php endif; ?>
		<!-- page content here -->	
		</div></div></div>		
    
    
    
    
    
    <?php
	
	PricerrTheme_get_users_links();
	
}


?>