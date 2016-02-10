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


?>

 


	<div id="footer">
	<div id="colophon" class="wrapper">	
	<?php
	$new_online_stats 	= view_all_users_online();
	$register_users 	= count_users();
	$published_posts 	= wp_count_posts('project')->publish;
	?>
<!--		
<div class="col-md-4 col-lg-4 col-s-12"><span class="numbers"><?php echo $new_online_stats;?></span><span class="numbers_text"><a href="https://bidqa.com/online/" class="userlink numbers_text toppadnone">users online</a></span></div>  -->
    
 <div class="col-md-4 col-lg-4 col-s-12"><span class="numbers"><?php echo $new_online_stats;?></span><span class="numbers_text"><a href="https://bidqa.com/advanced-search/" class="userlink numbers_text toppadnone">users online</a></span></div>   
    
<div class="col-md-4 col-lg-4 col-s-12"><span class="numbers"><?php echo $register_users['total_users'];?></span><span class="numbers_text"><a href=
"https://bidqa.com/my-account/" class="userlink">registered users</a></span></div>
<div class="col-md-4 col-lg-4 col-s-12"><span class="numbers"><?php echo $published_posts;?></span><span class="numbers_text"><a href="https://bidqa.com/all-posted-projects/" class="userlink">projects</a></span></div>
		<?php
                get_sidebar( 'footer' );
        ?>
        
        
            <div id="site-info">
                <div class="padd10">
                

                        <div id="site-info-left">					
                            <h3>Copyright (c) <?php echo date (Y); ?> @QAUBER.com</h3>
                          
                                   
                        </div>
                        
                        <div id="site-info-right">
                            <?php echo stripslashes(get_option('ProjectTheme_right_side_footer')); ?>
                        </div>

                
                </div>
            </div>
        
        
        </div>
    </div>

</div>


<?php

	$ProjectTheme_enable_google_analytics = get_option('ProjectTheme_enable_google_analytics');
	if($ProjectTheme_enable_google_analytics == "yes"):		
		echo stripslashes(get_option('ProjectTheme_analytics_code'));	
	endif;
	
	//----------------
	
	$ProjectTheme_enable_other_tracking = get_option('ProjectTheme_enable_other_tracking');
	if($ProjectTheme_enable_other_tracking == "yes"):		
		echo stripslashes(get_option('ProjectTheme_other_tracking_code'));	
	endif;


?>


	<?php 	
            wp_footer();
 ?>
</body>
<script type="text/javascript" language="javascript" src="<?php echo get_bloginfo('template_url'); ?>/js/app/project/new.project.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo get_bloginfo('template_url'); ?>/js/app/project/projects.js"></script>

<script type="text/javascript" language="javascript" src="<?php echo get_bloginfo('template_url'); ?>/js/app/app.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url') ?>/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script>
// $("#users").fancybox({
// 				'titlePosition'		: 'inside',
// 				'transitionIn'		: 'none',
// 				'transitionOut'		: 'none'
// 			});
</script>
</html>