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
	
	function projectTheme_colorbox_stuff()
	{	
	
		echo '<link media="screen" rel="stylesheet" href="'.get_bloginfo('template_url').'/css/colorbox.css" />';
		/*echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>'; */
		echo '<script src="'.get_bloginfo('template_url').'/js/jquery.colorbox.js"></script>';
		
		$get_bidding_panel = 'get_bidding_panel';
		$get_bidding_panel = apply_filters('ProjectTheme_get_bidding_panel_string', $get_bidding_panel) ;
		
?>
		
		<script>
		
		var $ = jQuery;
		
			jQuery(document).ready(function(){				
				
				
				jQuery("a[rel='image_gal1']").colorbox();
				jQuery("a[rel='image_gal2']").colorbox();
				
				
 
				
				jQuery('.get_files').click( function () {
					
					var myRel = jQuery(this).attr('rel');
					myRel = myRel.split("_");
					
					$(this).fancybox({href: "<?php bloginfo('siteurl'); ?>/?get_files_panel=" + myRel[0] +"&uid=" + myRel[1]});
					/*jQuery.colorbox({href: "<?php bloginfo('siteurl'); ?>/?get_files_panel=" + myRel[0] +"&uid=" + myRel[1] });*/
					return false;
				});
				
				
				jQuery("#report-this-link").click( function() {
					
					if(jQuery("#report-this").css('display') == 'none') {
						jQuery("#report-this").show('slow');
						$('html, body').animate({
						        scrollTop: ($("#report-this").offset().top-200)
						    }, 2000); 
					}
					else {
						jQuery("#report-this").hide('slow');
					}
					
					return false;
				});
				
				
				jQuery("#contact_seller-link").click( function() {
					
					if(jQuery("#contact-seller").css('display') == 'none')					
					jQuery("#contact-seller").show('slow');
					else
					jQuery("#contact-seller").hide('slow');
					
					return false;
				});
				
		});
		</script>

<?php
	}
	
	add_action('wp_head','projectTheme_colorbox_stuff');	
	//=============================
	
	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;
	global $wpdb;


/*****************************************************
*
*
******************************************************/	
	
	

if($_POST['cansel_proj_id'] && $_POST['cansel_user_id']){
		$bid_posted = 0;	
			$pid=$_POST['cansel_proj_id'];
			$uid=$_POST['cansel_user_id'];

			$query 	= "DELETE FROM ".$wpdb->prefix."project_bids where pid='$pid' AND uid='$uid'";
					$a = $wpdb->query($query);						
					$bid_posted = 2;	
			wp_redirect(get_permalink(get_the_ID()) . "/?bid_posted=".$bid_posted);			
		}	
	
	if(isset($_POST['bid_now_reverse']))
	{
		if(is_user_logged_in()):

		

		if(isset($_POST['control_id']))
		{
			$pid 		= base64_decode($_POST['control_id']);	
			$post 		= get_post($pid);
			$bid 		= trim($_POST['bid']);	
			$des 		= trim(strip_tags($_POST['description2']));	
			$post 		= get_post($pid);
		
			$tm 		= current_time('timestamp',0);
			$days_done	= trim($_POST['days_done']);
			
			//---------------------
			
			
	
			$projectTheme_enable_custom_bidding = get_option('projectTheme_enable_custom_bidding');
			if($projectTheme_enable_custom_bidding == "yes")
			{
				
				$ProjectTheme_get_project_primary_cat = ProjectTheme_get_project_primary_cat($pid);	
				$projectTheme_theme_bidding_cat_ = get_option('projectTheme_theme_bidding_cat_' . $ProjectTheme_get_project_primary_cat);
				
				if($projectTheme_theme_bidding_cat_ > 0)
				{
					$ProjectTheme_get_credits = ProjectTheme_get_credits($uid);
					$do_not_show = 0;
					$prc = $projectTheme_theme_bidding_cat_;
					
					if(	$ProjectTheme_get_credits < $projectTheme_theme_bidding_cat_) { $do_not_show = 1;	
						$prc = $projectTheme_theme_bidding_cat_;
						
					}
					
					
				}
				
			}
			
			
			//---------------------
			
			$closed = get_post_meta($pid,'closed',true);
			if($closed == "1") { echo 'DEBUG.Project Closed'; exit; }
			
			//---------------------
			
			if(empty($days_done) || !is_numeric($days_done))
			{
				$days_done = 3;	
			}
			
			$query = "select * from ".$wpdb->prefix."project_bids where uid='$uid' AND pid='$pid'";
			$r = $wpdb->get_results($query);
			
			$other_error_to_pace_bid = false;			
			$other_error_to_pace_bid = apply_filters('ProjectTheme_other_error_to_pace_bid', $other_error_to_pace_bid, $pid);
			
			if($other_error_to_pace_bid == true):
				
				$bid_posted = 0;
				$errors = apply_filters('ProjectTheme_post_bid_errors_array', $errors, $pid);
			
			else:
			
				
				if(!is_numeric($bid)):
				
					$bid_posted = 0;
					$errors['numeric_bid_tp'] = __("Your bid must be numeric type. Eg: 9.99",'ProjectTheme');
				
				elseif($uid == $post->post_author):
					
					$bid_posted = 0;
					$errors['not_yours'] = __("Your cannot bid your own projects.",'ProjectTheme');
				
				elseif(count($r) > 0):
					
					$row 	= $r[0];
					$id 	= $row->id;
		
					
					$query 	= "update ".$wpdb->prefix."project_bids set bid='$bid', days_done='$days_done', 
					description='$des',date_made='$tm',uid='$uid' where id='$id'";
					$wpdb->query($query);
					$bid_posted = 1;
					
					 
				else:
			
					$query = "insert into ".$wpdb->prefix."project_bids (days_done,bid,description, uid, pid, date_made) 
					values('$days_done','$bid','$des','$uid','$pid','$tm')";
					$wpdb->query($query);
					$bid_posted = 1;
					
					//**********
					
					if($do_not_show == 0)
					{
						if($prc > 0)
						{
							$pst = get_post($pid); 
							$cr = projectTheme_get_credits($uid);
							projectTheme_update_credits($uid, $cr - $prc);
							
							$reason = sprintf(__('Payment for bidding on project: <a href="%s">%s</a>','ProjectTheme'), get_permalink($pid), $pst->post_title);
							projectTheme_add_history_log('0', $reason, $prc, $uid);	
						}
					}
			
					
					//**********
					add_post_meta($pid,'bid',$uid);
					
					
					do_action('ProjectTheme_post_bid_ok_action');
					

					
				endif; // endif has bid already

			endif;
		}
		
		
	
	
		if($bid_posted):
			
			ProjectTheme_send_email_when_bid_project_owner($pid, $uid, $bid);
			ProjectTheme_send_email_when_bid_project_bidder($pid, $uid, $bid);
			
			//---------------------
			
			$prm = ProjectTheme_using_permalinks();
			if($prm == true)			
			wp_redirect(get_permalink(get_the_ID()) . "/?bid_posted=".$bid_posted); 
			else
			{
				wp_redirect(get_permalink(get_the_ID()) . "&bid_posted=".$bid_posted); 	
			}
			
			exit;
			
		
		endif; //endif bid posted
	
	else:
	
		$pid 		= base64_decode($_POST['control_id']);	
		wp_redirect(get_bloginfo('siteurl')."/wp-login.php");
		$_SESSION['redirect_me_back'] = get_permalink($pid);	
		exit;
		
	endif;
	}
	

	//=============================
	//function Project_change_main_class() { echo "<style> #main { background:url('".get_bloginfo('template_url')."/images/bg1.png')  } </style>"; }
	//add_filter('wp_head', 'Project_change_main_class');
	 
	
	get_header();
	global $post;
?>

     
     


<?php
	$hide_project_p = get_post_meta($post->ID, 'hide_project', true);
	
	if(($hide_project_p == "1" or $hide_project_p == "yes") && !is_user_logged_in()):
	?>
    
              <div class="page_heading_me_project">
                        <div class="page_heading_me_inner">
                        <div class="main-pg-title">
                            <div class="mm_inn"><?php echo $post->post_title; ?>     </div>
                  	         
                             </div>   
                                 <?php  projectTheme_get_the_search_box() ?>         
                        </div>
                    
                    </div>
             
	<div id="main_wrapper">
		<div id="main" class="wrapper"> 
    
    <div class="my_box3">
            <div class="padd10">
            
            	<div class="box_title"><?php echo sprintf(__("Project \"%s\" is marked hidden.",'ProjectTheme'), $post->post_title); ?></div>
                <div class="box_content">
                <?php echo sprintf(__('The project "%s" was marked as hidden. <a href="%s">Please login</a> to see project details.','ProjectTheme') , $post->post_title, get_bloginfo('siteurl')."/wp-login.php"); ?>
                </div>
    </div>
    </div>   
    
    
    
    </div></div></div>
    
    </div></div>
    
    <?php
	
	get_footer();
	exit;
	endif;

	?>







 
	
	
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

  <?php


	$location   		= get_post_meta(get_the_ID(), "Location", true);
	$ending     		= get_post_meta(get_the_ID(), "ending", true);
	$featured     		= get_post_meta(get_the_ID(), "featured", true);
	$private_bids     	= get_post_meta(get_the_ID(), "private_bids", true);
	
	//---- increase views
	
	$views    	= get_post_meta(get_the_ID(), "views", true);
	$views 		= $views + 1;
	update_post_meta(get_the_ID(), "views", $views);

	

?>	   
                <div class="page_heading_me_project">
                        <div class="page_heading_me_inner page_heading_me_inner_project">
                        <div class="main-pg-title">
                            <div class="mm_inn mm_inn21"><?php echo urldecode(the_title('','',false)) ?> <br>
                <?php
				
				if($featured == "1")
				echo '<span class="featured_thing_project">'.__('Featured Project','ProjectTheme').'</span>';
				
				if($private_bids == "1" or $private_bids == "yes")
				echo '<span class="private_thing_project">'.__('Sealed Bidding','ProjectTheme').'</span>';
				
				?>
                
                </div>      
                  	    
                        
                        <?php 

		if(function_exists('bcn_display'))
		{
		    echo '<div class="my_box3_breadcrumb breadcrumb-wrap">';	
		    echo urldecode(bcn_display(true));
			echo '</div>';
		}

?>
                                
                                        
                        </div>
                    
                   		<?php  projectTheme_get_the_search_box('proj-form') ?>            
                    
    </div>
</div>
           

<div id="main_wrapper">
		<div id="main" class="wrapper"><div class="padd10">

<?php
	
	$ProjectTheme_adv_code_project_page_above_content = get_option('ProjectTheme_adv_code_project_page_above_content');
	if(!empty($ProjectTheme_adv_code_project_page_above_content))
	{
		echo '<div class="padd10 full_width" style="padding-top:0">'.$ProjectTheme_adv_code_project_page_above_content.'</div> <div class="clear10"></div>';
		
	}


?>

<div id="content">

 
<?php
$rep_form_show = 'none';
	if(isset($_POST['report_this']) and is_user_logged_in())
	{	
		
		
		/*if(isset($_SESSION['reported-soon']))
		{
			$rp = $_SESSION['reported-soon'];
			if($rp < current_time('timestamp',0)) { $_SESSION['reported-soon'] = current_time('timestamp',0) + 60; $rep_ok = 1; }
			else { 
				$rep_ok = 0; 
				$error_mess = 'Slow down buddy! You reported this before.';
			}
		}
		else
		{
			$_SESSION['reported-soon'] = current_time('timestamp',0) + 60; $rep_ok = 1;	
		}*/

		if($_POST['reason_report']){
			$rep_ok = 1;			
		}
		else{
			$rep_ok = 0;
			$rep_form_show = 'block';
			$error_mess = 'You cannot leave Reason for reporting blank!';
		}
		
		if($rep_ok == 1)
		{
		$pid_rep = $_POST['pid_rep'];
		$reason_report = nl2br($_POST['reason_report']);
		
		//---- send email to admin
		$subject = __("Report offensive project")." : ".get_the_title();
		
		$message = __("This project has been reported as offensive");
		$message .= ' : <a href="' . get_permalink(get_the_ID()) . '">' . get_the_title() . '</a>'; 
		$message .= " <br/>Message: ".strip_tags($_POST['reason_report']); 
        $seller_id = get_current_user_id();
        $seller = get_user_by('id', $seller_id);
        $message .= ' <br/>Seller: <b>' . $seller->user_login . '</b> ' .ProjectTheme_get_user_profile_link($seller->ID);
		
		$recipients = get_bloginfo('admin_email');
		
		ProjectTheme_send_email($recipients, $subject, $message);
		
		//------------------------
        
        //---- send report to project owner
        
//        $post = get_post(get_the_ID());
//        $owner_id = $post->post_author;
//        $recipients = get_user_by('id', $owner_id )->user_email;
//		
//		ProjectTheme_send_email($recipients, $subject, $message);
        
        //---------------------------------
        
		?>
        <div class="my_box3">
            <div class="padd10">
        		<div class="box_content">
                
                	<?php _e('Thank you! Your report has been submitted.','ProjectTheme'); ?>
                
       			</div>
        	</div>
        </div>
        
        <div class="clear10"></div>
		
		<?php
		}
		else
		{
		?>	
		
        
        <div class="my_box3">
            <div class="padd10">
        		<div class="box_content" style="color:red;"><b>
                
                	<?php echo $error_mess;//_e('Slow down buddy! You reported this before.','ProjectTheme'); ?>
                </b>
       			</div>
        	</div>
        </div>
        
        <div class="clear10"></div>	
			
		<?php	
		}
	}

?>

<div id="report-this" style="display:<?php echo $rep_form_show; ?>">
<div class="my_box3">
            <div class="padd10">
            
            	<div class="box_title"><?php echo __("Report this project",'ProjectTheme'); ?></div>
                <div class="box_content">
                <?php
				
				if(!is_user_logged_in()):
				
				?>
                
                <?php echo sprintf(__('You need to be <a href="%s">logged</a> in to use this feature.','ProjectTheme'), get_bloginfo('siteurl')."/wp-login.php" ); ?>
                <?php else: ?>
                
                
					<form method="post"><input type="hidden" value="<?php the_ID(); ?>" name="pid_rep" />
                    <ul class="post-new3">

        
        <li>
        	<h2><?php echo __('Reason for reporting','ProjectTheme'); ?>:</h2>
        <p><textarea rows="4" cols="40" class="do_input"  name="reason_report"></textarea></p>
        </li>
        
        
     
        
        <li>
        <h2>&nbsp;</h2>
        <p><input type="submit" name="report_this" value="<?php _e('Submit Report','ProjectTheme'); ?>" /></p>
        </li>
    
    
    </ul>
    </form> <?php endif; ?>
                    
                    
				</div>
			</div>
			</div>
            
            <div class="clear10"></div>

</div>


<!-- ######### -->

<?php

	if(isset($_POST['contact_seller']))
	{
		
		if(isset($_SESSION['contact_soon']))
		{
			$rp = $_SESSION['contact_soon'];
			if($rp < current_time('timestamp',0)) { $_SESSION['contact_soon'] = current_time('timestamp',0) + 60; $rep_ok = 1; }
			else { $rep_ok = 0; }
		}
		else
		{
			$_SESSION['contact_soon'] = current_time('timestamp',0) + 60; $rep_ok = 1;	
		}
		
		if($rep_ok == 1)
		{
		
		$subject = $_POST['subject'];
		$email = $_POST['email'];
		$message = nl2br($_POST['message']);
		
		//---- send email to admin

		
		$p = get_post(get_the_ID());
		$a = $p->post_author;
		$a = get_userdata($a);
		
		ProjectTheme_send_email($a->user_email, $subject, $message."<br/>From Email: ".$email);
		
		//------------------------
		?>
        <div class="my_box3">
            <div class="padd10">
        		<div class="box_content">
                
                	<?php _e('Thank you! Your message has been sent.','ProjectTheme'); ?>
                
       			</div>
        	</div>
        </div>
        
        <div class="clear10"></div>
		
		<?php
		}
		else
		{
		?>	
			
            <div class="my_box3">
            <div class="padd10">
        		<div class="box_content">
                
                	<?php _e('Slow down buddy!.','ProjectTheme'); ?>
                
       			</div>
        	</div>
        </div>
        
        <div class="clear10"></div>
			
            
           <?php
		}
	}

?>





 			<div class="project-signle-content-main">
 
			 
				
                <?php
				
				$closed 	 = get_post_meta(get_the_ID(),'closed',true);
				
				?>
                
				<div class="project-page-details-holder">
                <?php 
				if($closed == "0") :
				if($bid_posted == "0"): ?> 
		
                        <div class="bid_panel_err">
                        <div class="padd10">
                        <?php _e("Your bid has not been posted. Please correct the errors and try again.",'ProjectTheme');
                                echo '<br/>';
                                foreach($errors as $err)
                                echo $err.'<br/>';
                         ?>
                        </div>
                        </div>
                
                <?php endif; ?>
                
                
                <?php if($_GET['bid_posted'] == 1): ?>
		
                        <div class="bid_panel_ok">
                        <div class="padd10">
                        <?php _e("Your bid has been posted.",'ProjectTheme');
                                
                         ?>
                        </div>
                        </div>
                <?php elseif($_GET['bid_posted'] == 2): ?>
                		<div class="bid_panel_ok">
                        <div class="padd10">
                        <?php _e("Your bid has been canceld.",'ProjectTheme');
                                
                         ?>
                        </div>
                        </div>
                
                <?php endif; ?>

               
               
               	<div class="bid_panel_front">
                <div class="padd10">
                
                <div class="small_buttons_div_left">
                	<ul class="project-details">
							<li>
                            	<img src="<?php bloginfo('template_url') ?>/images/wallet_icon2.png" width="18" height="18" alt="budget" />
								<h3><?php echo __("Project Budget",'ProjectTheme'); ?>:</h3>
								<p><?php echo '<a href="'.get_permalink(get_option("ProjectTheme_advanced_search_page_id")).'?budgets='.get_post_meta(get_the_ID(), 'budgets', true).'">'.ProjectTheme_get_budget_name_string_fromID(get_post_meta(get_the_ID(), 'budgets', true)).'</a>'; ?></p>
								
							</li>
                            
                            
                            <li>
                            	<img src="<?php bloginfo('template_url') ?>/images/coins_icon.png" width="18" height="18" alt="coins" />
								<h3><?php echo __("Average Bid",'ProjectTheme'); ?>:</h3>
								<p><?php echo ProjectTheme_average_bid(get_the_ID()); ?></p>
							</li>
                            
                            
                         
                            
            			<?php
		
						$ProjectTheme_enable_project_location = get_option('ProjectTheme_enable_project_location');
						if($ProjectTheme_enable_project_location == "yes"):
		
						?>   
                            <li>
								<img src="<?php echo get_bloginfo('template_url'); ?>/images/loc_icon.png" width="18" height="18" alt="location" /> 
								<h3><?php echo __("Location",'ProjectTheme'); ?>:</h3>
								<p><?php echo get_the_term_list( get_the_ID(), 'project_location', '', ', ', '' ); ?></p>
							</li>
                          
                        <?php endif; ?>  
                          
                             
                             <li>
								<img src="<?php echo get_bloginfo('template_url'); ?>/images/cate_icon.png" width="18" height="18" alt="category" /> 
								<h3><?php echo __("Category",'ProjectTheme'); ?>:</h3>
								<p><?php echo get_the_term_list( get_the_ID(), 'project_cat', '', ', ', '' ); ?></p>
							</li>
                            
                            <li>
								<img src="<?php echo get_bloginfo('template_url'); ?>/images/loc_icon.png" width="18" height="18" alt="location" /> 
								<h3><?php echo __("Skills",'ProjectTheme'); ?>:</h3>
								<p><?php echo get_the_term_list( get_the_ID(), 'project_skill', '', ', ', '' ); ?></p>
							</li>

          
							<li>
								<img src="<?php echo get_bloginfo('template_url'); ?>/images/cal_icon.png" width="18" height="18" alt="calendar" /> 
								<h3><?php echo __("Posted on",'ProjectTheme'); ?>:</h3>
								<p><?php the_time("jS F Y g:i A"); ?></p>
							</li>
                          
                          <li>
								<img src="<?php echo get_bloginfo('template_url'); ?>/images/clock_icon.png" width="18" height="18" alt="clock" /> 
								<h3><?php echo __("Time Left",'ProjectTheme'); ?>:</h3>
								<p><?php echo ($closed == "0" ? ProjectTheme_prepare_seconds_to_words($ending - current_time('timestamp',0)) 
								: __("Expired/Closed",'ProjectTheme')); ?></p>
							</li>
                            
                          
                          <li>
                            	<img src="<?php bloginfo('template_url') ?>/images/prop_icon.png" width="18" height="18" alt="proposals" />
								<h3><?php echo __("Proposals",'ProjectTheme'); ?>:</h3>
								<p><?php echo projectTheme_number_of_bid(get_the_ID()); ?></p>
							</li>
                            
                	</ul>
                    
                    <div class="clear10"></div>
                    
                    </div>
                    <!-- ########### -->
                    
                    <div class="small_buttons_div">
                    	<ul id="these-special-buttons">
                    	<?php
                    		$post = get_post(get_the_ID());
								if($current_user->ID != $post->post_author){ ?>
									<li><a href="<?php echo ProjectTheme_get_priv_mess_page_url('send', '', '&uid='.$post->post_author.'&pid='.get_the_ID()); ?>" class="project-owner-contact"><?php _e('Contact Project Owner','ProjectTheme') ?></a></li>
								<?php
								}
									?>								
                        	
                            <li><a href="#" class="message_brd_cls project-owner-contact" rel="<?php the_ID() ?>"><?php _e('Project Message Board','ProjectTheme') ?></a></li>
                        </ul>
                    </div>
                    <script type="text/javascript">
                    $(window).load(function(){                      	
                    	$('#fancybox-wrap').off();	
                    });
                    </script>
                  
                </div>
                </div>
               
                
                <?php  else: 
				// project closed
				?>
                
                <div class="bid_panel">
                <div class="padd10">
                
                	<?php
					
					$pid 	= get_the_ID();
					$winner = get_post_meta(get_the_ID(), 'winner', true);
					
					if(!empty($winner))
					{
						
						global $wpdb;
						$q = "select bid from ".$wpdb->prefix."project_bids where pid='$pid' and winner='1'";
						$r = $wpdb->get_results($q);
						$r = $r[0];
						
						_e("Project closed for price: ",'ProjectTheme');
						echo ProjectTheme_get_show_price($r->bid);
						
					}
					
					?>
                
                </div>
                </div>
                
                <?php endif; ?>
               
						
												
					</div>
				
				 
			</div>
			
			
			<div class="clear10"></div>
			
			<!-- ####################### -->
			
			<div class="my_box3">
           
            
            	<div class="box_title"><?php echo __("Project Description",'ProjectTheme'); ?></div>
                <div class="box_content" id="project-single-page-content">
					<?php the_content(); 
					
					do_action('ProjectTheme_after_description_in_single_proj_page');
					
					 ?>
				</div>

				<?php
            		$project_othe_skills = get_post_meta(get_the_ID(), 'project_othe_skills', true);
					if ($project_othe_skills):
				?>

				<div class="box_title"><?php echo __("Othe Skills",'ProjectTheme'); ?></div>
                <div class="box_content" id="project-single-page-content">
					<?php echo $project_othe_skills; ?>
				</div>

				<?php endif; ?>

			</div>
		
			
			<div class="clear10"></div>
							
            <!-- ####################### -->
            <div class="my_box3">
			<?php 
			
			$private_bids = get_post_meta(get_the_ID(), 'private_bids', true);
			
			?>
			 
            
            
            	<div class="box_title"><?php echo __("Proposals",'ProjectTheme'); ?> <?php
				
				if($private_bids == 'yes' or $private_bids == '1' or $private_bids == 1) _e('[project has private proposals]','ProjectTheme');
				
				 ?></div>
				 <div class="box_content" id="project-single-page-content">
                 
				<?php
				global $wpdb;
				$ProjectTheme_enable_project_files = get_option('ProjectTheme_enable_project_files');
				$winner = get_post_meta(get_the_ID(), 'winner', true);
				$ending = get_post_meta(get_the_ID(), "ending", true);
				$closed = get_post_meta(get_the_ID(),'closed',true);
				$post 	= get_post(get_the_ID());
				$pid 	= get_the_ID();
				$bids 	= "select * from ".$wpdb->prefix."project_bids where pid='$pid' order by id DESC";
				$res  	= $wpdb->get_results($bids);
			
				if($post->post_author == $uid) $owner = 1; else $owner = 0;
				
				if(count($res) > 0)
				{
					
					if($private_bids == 'yes' or $private_bids == '1' or $private_bids == 1)
					{
						if ($owner == 1) $show_stuff = 1;
						else if(projectTheme_current_user_has_bid($uid, $res)) $show_stuff = 1;
						else $show_stuff = 0;
					}
					else $show_stuff = 1;
					
					//------------
					
					if($show_stuff == 1):
						//if($owner == 1 && $closed == "0")
							//echo '<div class="myrow"><a id="users" class="btn-show" href="">Choose all selected as Winners</a></div>'; // location changes in js script
						echo '<div id="my_bids" width="100%">';
					endif;
					
					//-------------
					$users = array();
					foreach($res as $row) {
						
						if ($owner == 1) $show_this_around = 1;
						else
						{
							if($private_bids == 'yes' or $private_bids == '1' or $private_bids == 1)
							{
								if($uid == $row->uid) $show_this_around = 1;
								else $show_this_around = 0;
							}
							else
							$show_this_around = 1;
							
						}
						 
						if($show_this_around == 1):
						
						$user = get_userdata($row->uid);
						echo '<div class="myrow">';
						echo '<div><i class="bid-person"></i> <a href="'.ProjectTheme_get_user_profile_link($user->ID).'">'.$user->user_login.'</a></div>';
						echo '<div><i class="bid-money"></i>  '.ProjectTheme_get_show_price($row->bid).'</div>';
						echo '<div><i class="bid-clock"></i> '.date_i18n("d-M-Y H:i:s", $row->date_made).'</div>';
						echo '<div><i class="bid-days"></i> '. sprintf(__("%s days" ,"ProjectTheme"), $row->days_done) .'</div>';
						//if($owner == 1 && $closed == "0")
							//echo '<div><input name="bids" data-pid="' . get_the_ID() . '" data-bid="' . $row->id . '" type="checkbox"></div>';
						if ($owner == 1 ) {
							
							$nr = 7;
							if($row->winner != 1 && $ending > current_time('timestamp',0) && $closed == 0)
								echo '<div><i class="bid-select"></i>  <a href="'.get_bloginfo('siteurl').'/?p_action=choose_winner&pid='.get_the_ID().'&bid='.$row->id.'">'.__('Select as Winner','ProjectTheme').'</a></div>';						
							
							if($ProjectTheme_enable_project_files != "no")
							{
								if(projecttheme_see_if_project_files_bid(get_the_ID(), $row->uid) == true)
								{
								echo '<div> <i class="bid-days"></i> ';								
								echo '<a href="#" class="get_files" rel="'.get_the_ID().'_'.$row->uid.'">'.__('See Bid Files','ProjectTheme').'</a> ';							
								echo '</div>';
								}
							
							}
							echo '<div><i class="bid-env"></i> <a href="'.ProjectTheme_get_priv_mess_page_url('send', '', '&uid='.$row->uid.'&pid='.get_the_ID()).'">'.__('Send Message','ProjectTheme').'</a></div>';
							if($row->winner != 1)
							echo '<div><a href="'.get_bloginfo('siteurl').'/?p_action=remove_provider&uid='.$row->uid.'&pid='.get_the_ID().'"><span class="delete-no-image" alt="Delete" title="Delete provider">x</span></a></div>';
						}
						else $nr = 4;
						
						//if($closed == "1") { 
						
							if($row->winner == 1) {echo '<div><i class="bid-flag"></i> '.__('Project Winner','ProjectTheme').'</div>'; $winners[]=$row->uid;  }
						//}
						
						 
						
						 
						echo '<div class="my_td_with_border">'.$row->description.'</div>';
						echo '</div>';
						endif;
					}
					if($show_stuff == 1):
						echo ' </div> ';
					endif;
					//echo ' </div> ';
				}
				else _e("No proposals placed yet.",'ProjectTheme');
				// echo '<div style="display:none"><div id="data">';
				// foreach($users as $user)
				// 	echo '<span>Login '.$user["login"].'</span><input type="checkbox" name="users" value="'.$user["id"].'"><br>';
				// echo '<a id="fewWinner" href="http://projects.for-test1ng.tk/?p_action=few_winners&amp;pid=41&amp;bid=8">Select Winners</a>';
				// echo '</div></div>';
				?>	
					</div>
				</div>
		
			
				
			 
			
			
            <?php
			
				$ProjectTheme_enable_images_in_projects = get_option('ProjectTheme_enable_images_in_projects');
				$ProjectTheme_enable_images_in_projects = apply_filters('ProjectTheme_enable_images_in_projects_hk', $ProjectTheme_enable_images_in_projects);
			 	
				if($ProjectTheme_enable_images_in_projects == "yes"):
			
			?>
			<div class="clear10"></div>
			
			<!-- ####################### -->
			
			<div class="my_box3">
           
            
            	<div class="box_title"><?php echo __("Image Gallery",'ProjectTheme'); ?></div>
                <div class="box_content">
				<?php
				
				$arr = ProjectTheme_get_post_images(get_the_ID());
				$xx_w = 600;
				$projectTheme_width_of_project_images = get_option('projectTheme_width_of_project_images');
				
				if(!empty($projectTheme_width_of_project_images)) $xx_w = $projectTheme_width_of_project_images;
				if(!is_numeric($xx_w)) $xx_w = 600;
				
				if($arr)
				{
					
				
				echo '<ul class="image-gallery">';
				foreach($arr as $image)
				{
					echo '<li><a href="'.ProjectTheme_generate_thumb($image, 900,$xx_w).'" rel="image_gal2"><img src="'.ProjectTheme_generate_thumb($image, 100,80).'" width="100" class="img_class" /></a></li>';
				}
				echo '</ul>';
				
				}
				else { echo __('No images.','ProjectTheme') ;}
				
				?>
				
				
				</div>
			</div>
			<?php endif; ?>
			
			<div class="clear10"></div>
			
			<!-- ####################### -->
			<?php
		
			$ProjectTheme_enable_project_location = get_option('ProjectTheme_enable_project_location');
			if($ProjectTheme_enable_project_location == "yes"):
		
		?>
        
			<div class="my_box3">
            
            
            	<div class="box_title"><?php echo __("Map Location",'ProjectTheme'); ?></div>
                <div class="box_content">
	
				<div id="map" style="width: 635px; height: 300px;border:2px solid #ccc;float:left"></div>
				
                <script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script> 
            
            <script type="text/javascript"
            src="<?php echo get_bloginfo('template_url'); ?>/js/mk.js"></script> 
                                                <script type="text/javascript"> 
   



	  var geocoder;
  var map;
  function initialize() {
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(-34.397, 150.644);
    var myOptions = {
      zoom: 13,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map"), myOptions);
  }

  function codeAddress(address) {
    
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        var marker = new MarkerWithLabel({
            
            position: results[0].geometry.location,
			map: map,
       labelContent: address,
       labelAnchor: new google.maps.Point(22, 0),
       labelClass: "labels", // the CSS class for the label
       labelStyle: {opacity: 1.0}

        });
      } else {
        //alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }

initialize();

codeAddress("<?php 

	global $post;
	$pid = $post->ID;

	$terms = wp_get_post_terms($pid,'project_location');
	foreach($terms as $term)
	{
		echo $term->name." ";
	}

	$location = get_post_meta($pid, "Location", true);	
	echo $location;
	
 ?>");

    </script> 
				
			
			</div>
			</div> <?php endif; ?>
			
			<!-- ####################### -->
			
<?php endwhile; // end of the loop. ?>


<div class="clear10"></div>

<div class="my_box3">
    <div class="box_title"><?php echo __("Project Tags",'ProjectTheme'); ?></div>
    <div class="box_content">
    <?php
    	$posttags = get_the_tags();
    	if(!$posttags){
    		echo "No tags";
    	}
    	else{
    		foreach ($posttags as $key => $tag) {
    			echo '<a href="'.get_tag_link($tag->term_id).'">'.$tag->name.'</a> ';
    		}
    	}
    ?>
    </div>
</div>

</div>




<?php


	echo '<div id="right-sidebar" class="page-sidebar">';
	echo '<ul class="xoxo">';
	
	//---------------------
	// build the exclude list
	//---------------------
	// build the exclude list
		$exclude = array();
		
	$args = array(
	'order'          => 'ASC',
	'post_type'      => 'attachment',
	'post_mime_type' => 'image',
	'post_parent'    => $pid,
	'numberposts'    => -1,
	'post_status'    => null,
	);
	
	$attachments = get_posts($args);
 
	foreach($attachments as $att) $exclude[] = $att->ID;
	
	//-0------------------
	
	
	
	$args = array(
	'order'          => 'ASC',
	'post_type'      => 'attachment',
	'meta_key' => 'is_bidding_file',
	'meta_value' => '1',
	'post_parent'    => $pid,
	'numberposts'    => -1,
	'post_status'    => null,
	);
	
	$attachments = get_posts($args);
 
	foreach($attachments as $att) $exclude[] = $att->ID;
	
	//------------------
	
	$args = array(
	'order'          => 'ASC',
	'post_type'      => 'attachment',
	'post_parent'    => $pid,
	'exclude'    => $exclude, 
	'numberposts'    => -1,
	'post_status'    => null,
	);
	$attachments = get_posts($args);

	// start providers table
	if($owner == 1 && $closed == "0"){
		global $wpdb;
		$providersResult = $wpdb->get_results('select * FROM wp_users inner JOIN wp_postmeta on wp_users.ID = wp_postmeta.meta_value where wp_postmeta.meta_key="winner" and wp_postmeta.post_id='.$pid);//$wpdb->get_results("select users_id from project_group where project_id=".$pid);
		//var_dump($providersResult);
		if($providersResult){
			//$providersId = unserialize($providersResult[0]->users_id);
			echo "<li>";	
			echo "<h3>Current QA Engineers</h3>";
			$time_spent = get_post_meta($pid, 'time_spent', true);
			$time_spent = unserialize($time_spent);
			foreach($providersResult as $user_info ){
				//$user_info = get_userdata($id);
				if($time_spent[$user_info->ID]['count_time']){
					$curent_time_spent = $time_spent[$user_info->ID]['count_time'];					
				}
				else{$curent_time_spent = 0;}

				$hours = floor($curent_time_spent/3600);                 	
                 	$minutes = floor(($curent_time_spent/3600 - $hours)*60);                 	
                 	if($minutes<10){$minutes='0'.$minutes;}
					$seconds = round(((($curent_time_spent/3600 - $hours)*60)-$minutes)*60);
					if($seconds<10){$seconds='0'.$seconds;}
                 	$str_time = $hours.':'.$minutes.':'.$seconds;
				
				echo "<p><span>" . $user_info->user_login ."(".$str_time.")</span><span><a href='".get_bloginfo('siteurl')."/?p_action=remove_provider&pid=".$pid."&uid=".$user_info->ID."'><i class='close-awsome provider-list'></i></a></span><span class='provider-list'><a href=\"".get_permalink(get_option('ProjectTheme_my_account_disputes_id'))."\">Dispute</a></span></p>";
				echo "<hr>";
			}
			echo "</li>";
		} 
	}
	?>
<?php
		global $current_user;
		get_currentuserinfo();
		$uid = $current_user->ID;	
		$bids = "select uid from ".$wpdb->prefix."project_bids where pid='$pid' order by id DESC";
		$res  = $wpdb->get_results($bids);															
		foreach ($res as $key => $value) {
			$res2[]=$value->uid;
		}
		
		if(!$res2){ $res2=array(); }
		if(!$winners){ $winners=array(); }
		//if($closed == "0" && ProjectTheme_is_user_provider($uid) == true && !in_array($uid,$winners)):

		if(!ProjectTheme_is_user_business($uid) && $closed == "0" && ProjectTheme_is_user_provider($uid) == true && !in_array($uid,$winners)){
?>
    <li class="widget-container widget_text" id="ad-other-details">
		<h3 class="widget-title"><?php _e("Apply for this Project",'ProjectTheme'); ?></h3>
		<p>
        <?php _e('You can use the button below to apply and submit a proposal for this project.','ProjectTheme') ?>
        </p>
        
        
       <p id='proposal_btn_p'>

         <?php
								
								/*global $current_user;
								get_currentuserinfo();
								$uid = $current_user->ID;																
								if(!$winners){$winners=array();}
								if($closed == "0" && ProjectTheme_is_user_provider($uid) == true && !in_array($uid,$winners)):*/
							
							if(in_array($uid, $res2)){
							?>                            
								<a href="#" class="post_bid_btn_new" id='submit-proposal-id' rel="<?php the_ID(); ?>_bid"><?php _e('Update a Proposal','ProjectTheme'); ?></a>
								<p></p>
								<form method="POST">
								<input hidden name="cansel_proj_id" value="<?php the_ID(); ?>">
								<input hidden name="cansel_user_id" value="<?php echo $uid; ?>">
								<a href="#" class="post_bid_btn_new" id='cansel-proposal-id'><?php _e('Cancel a Proposal','ProjectTheme'); ?></a>
								</form>
								<script type="text/javascript">
								$(document).ready(function(){
									$('#cansel-proposal-id').click(function(event){
										event.preventDefault();
										$(this).parent().submit();
									});
								});
								</script>
							 
                          	<?php //endif;
                          	}else{ ?>
								<a href="#" class="post_bid_btn_new" id='submit-proposal-id' rel="<?php the_ID(); ?>_bid"><?php _e('Submit a Proposal','ProjectTheme'); ?></a>							
							<?php
                          	}
                          	 ?>
                          	
       
       </p> 
    </li>
    <?php } ?>
    
    
    
    	<li class="widget-container widget_text" id="ad-other-details">
		<h3 class="widget-title"><?php _e("Project Posted By",'ProjectTheme'); ?></h3>
		
        <div class="avatar-op-wrap">
        	
            <div class="avatar-op-inner"><img width="60" height="60" border="0" class="project-single-avatar" src="<?php echo ProjectTheme_get_avatar($post->post_author, 60, 60); ?>" /> </div>
        	<div class="avatar-op-list">
            		<ul>
                    	<li><a class="avatar-posted-by-username" href="<?php bloginfo('siteurl'); ?>/?p_action=user_profile&post_author=<?php echo $post->post_author; ?>">
                    	<?php 
                    	$autor_info = get_userdata($post->post_author);
                    	echo $autor_info->user_login; 
                    	?>
                    	</a></li>
                    	<li><?php echo ProjectTheme_project_get_star_rating2($post->post_author); ?></li>
                        <li><a href="<?php echo ProjectTheme_get_user_feedback_link($post->post_author); ?>"><?php _e('View User Feedback','ProjectTheme'); ?></a></li>
                    </ul>
            </div>
            
        </div>
        
        <p>
        <ul class="other-dets other-dets2">
				 
                <?php
					
					$has_created 	= projectTheme_get_total_number_of_created_Projects($post->post_author);
					$has_closed 	= projectTheme_get_total_number_of_closed_Projects($post->post_author);
					$has_rated 		= projectTheme_get_total_number_of_rated_Projects($post->post_author);
				
				?>
                
                 
                 
                <li>
					<h3><?php _e("Has created:",'ProjectTheme');?></h3>
					<p><?php echo sprintf(__("%s project(s)",'ProjectTheme'), $has_created); ?></p> 
				</li>
                
                
                <li>
					<h3><?php _e("Has closed:",'ProjectTheme');?></h3>
					<p><?php echo sprintf(__("%s project(s)",'ProjectTheme'), $has_closed); ?></p> 
				</li>
            
            
            	<li>
					<h3><?php _e("Has rated:",'ProjectTheme');?></h3>
					<p><?php echo sprintf(__("%s provider(s)",'ProjectTheme'), $has_rated); ?></p> 
				</li>
            
            
            	<br/><br/>
               <a href="<?php bloginfo('siteurl'); ?>/?p_action=user_profile&post_author=<?php echo $post->post_author; ?>"><?php _e('See More Projects by this user','ProjectTheme'); ?></a><br/>
               
                		
			</ul>
   		</p>
   </li>
       
       <?php
						   
						   	$ProjectTheme_enable_project_files = get_option('ProjectTheme_enable_project_files');						   
						   	if($ProjectTheme_enable_project_files != "no"):
						   
						   ?>
       
     	<li class="widget-container widget_text" id="ad-other-details">
		<h3 class="widget-title"><?php _e("Project Files",'ProjectTheme'); ?></h3>
		<p>
        
        <ul class="other-dets other-dets2">
				<?php
				
				if(count($attachments) == 0) echo __('No project files.','ProjectTheme');
				
				foreach($attachments as $at)
				{
					 
			
					 
				?>
                
                <li> <a href="<?php echo wp_get_attachment_url($at->ID); ?>" download><?php echo $at->post_title; ?></a>
				</li> 
			<?php }   ?>		
			</ul>
   		</p>
   </li>
  <?php endif; ?>  
    
    
	<li class="widget-container widget_text" id="ad-other-details">
		<h3 class="widget-title"><?php _e("Other Options",'ProjectTheme'); ?></h3>
		<p>
        
        <div class="add-this">
						<!-- AddThis Button BEGIN -->
							<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
							<a class="addthis_button_preferred_1"></a>
							<a class="addthis_button_preferred_2"></a>
							<a class="addthis_button_preferred_3"></a>
							<a class="addthis_button_preferred_4"></a>
							<a class="addthis_button_compact"></a>
							<a class="addthis_counter addthis_bubble_style"></a>
							</div>
							<script type="text/javascript" src="//s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4df68b4a2795dcd9"></script>
							<!-- AddThis Button END -->
						</div>	
        
   		</p>
   </li>
       
    
	<li class="widget-container widget_text" id="ad-other-details">
		<h3 class="widget-title"><?php _e("Other Details",'ProjectTheme'); ?></h3>
		<p>
			<ul class="other-dets other-dets2">
	 
		 
				<?php
		
			$ProjectTheme_enable_project_location = get_option('ProjectTheme_enable_project_location');
			if($ProjectTheme_enable_project_location == "yes"):
		
		?>
				<li>
					<img src="<?php echo get_bloginfo('template_url'); ?>/images/location.png" width="15" height="15" /> 
					<h3><?php _e("Address",'ProjectTheme');?>:</h3>
					<p><?php echo $location; ?></p> 
				</li>
		
        
        <?php endif; ?>
        
        		
                <?php
				
				$rt = get_option('projectTheme_show_project_views');
				
				if($rt != 'no'):
				?>
				
				<li>
					<img src="<?php echo get_bloginfo('template_url'); ?>/images/viewed.png" width="15" height="15" /> 
					<h3><?php _e("Viewed",'ProjectTheme');?>:</h3>
					<p><?php echo $views; ?> <?php _e("times",'ProjectTheme');?></p> 
				</li>
				<?php endif; ?>
				
                
                <?php
				
				$my_arrms = true;
				$my_arrms = apply_filters('ProjectTheme_show_fields_in_sidebar', $my_arrms);
				
				if($my_arrms == true): 
				
				$arrms = ProjectTheme_get_project_fields_values(get_the_ID());
				
			 
				
				if(count($arrms) > 0) 
					for($i=0;$i<count($arrms);$i++)
					{
				
				?>
                <li>
					<h3><?php echo $arrms[$i]['field_name'];?>:</h3>
               	 	<p><?php echo $arrms[$i]['field_value'];?></p>
                </li>
				<?php } endif; ?>
				
                
				
			</ul>
			<?php
				
				if(ProjectTheme_is_owner_of_post())
				{
					
				?>
				
			<a href="<?php echo get_bloginfo('siteurl'); ?>/?p_action=edit_project&pid=<?php the_ID(); ?>" class="nice_link"><?php _e("Edit",'ProjectTheme'); ?></a> 
			<a href="<?php echo get_bloginfo('siteurl'); ?>/?p_action=repost_project&pid=<?php the_ID(); ?>" class="nice_link"><?php _e("Repost",'ProjectTheme'); ?></a> 
		<!--	<a href="<?php echo get_bloginfo('siteurl'); ?>/?p_action=delete_project&pid=<?php the_ID(); ?>" class="nice_link"><?php _e("Delete",'ProjectTheme'); ?></a> -->
			
			<?php } else {?>
			
			<a href="#" id="report-this-link" class="nice_link"><?php _e("Report",'ProjectTheme'); ?></a>
            <a href="<?php
            $post = get_post(get_the_ID());
			
			
			echo ProjectTheme_get_priv_mess_page_url('send', '', '&uid='.$post->post_author.'&pid='.get_the_ID());
			
			?>" class="nice_link"><?php _e("Contact Seller",'ProjectTheme'); ?></a>
				
                <?php } ?>
		</p>
	</li>
	
	
	<?php
	
						dynamic_sidebar( 'project-widget-area' );
	echo '</ul>';
	echo '</div>';


//===============================================================================================
?>

<?php
	
	$ProjectTheme_adv_code_project_page_below_content = get_option('ProjectTheme_adv_code_project_page_below_content');
	if(!empty($ProjectTheme_adv_code_project_page_below_content))
	{
		echo '<div class="padd10 full_width" style="padding-top:0">'.$ProjectTheme_adv_code_project_page_below_content.'</div> <div class="clear10"></div>';
		
	}


?>


    
    </div></div></div>

    

<?php
	get_footer();
?>