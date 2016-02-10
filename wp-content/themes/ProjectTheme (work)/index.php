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

	get_header();

/**************************************************/ 
?>

<?php projecttheme_search_box_thing() ?>

<!-- ########## -->

<div id="main_wrapper">
		<div id="main" class="wrapper"> 

<?php
	
		$ProjectTheme_adv_code_home_above_content = stripslashes(get_option('ProjectTheme_adv_code_home_above_content'));
		if(!empty($ProjectTheme_adv_code_home_above_content)):
		
			echo '<div class="full_width_a_div">';
			echo stripslashes($ProjectTheme_adv_code_home_above_content);
			echo '</div>';
		
		endif;
	
	?>
    
    <!-- ################## -->
     
        <?php
		
		$ProjectTheme_home_page_layout = get_option('ProjectTheme_home_page_layout');
		
		if($ProjectTheme_home_page_layout == "3" or $ProjectTheme_home_page_layout == "4" ):
			
			    echo '<div id="left-sidebar">';
					echo '<ul class="xoxo">';
				 		dynamic_sidebar( 'home-left-widget-area' ); 
					echo '</ul>';
				   echo '</div>';
		
		endif;
		
		?>
        
    <div id="content">


	<!-- ############################# -->

	<ul class="xoxo">
    <?php
	
		dynamic_sidebar( 'main-page-widget-area' );
		$show_latest_prj = true;
		$show_latest_prj = apply_filters('ProjectTheme_show_latest_projects_index', $show_latest_prj);
		
		
		if($show_latest_prj == true):
		
	?>

        <li class="widget-container latest-posted-jobs-big">
        
        <?php include 'latest-projects.php'; ?>
        
        </li>
        <?php endif; ?>
	</ul>

	<!-- ##### -->
	</div>

	<?php if($ProjectTheme_home_page_layout != "5" && $ProjectTheme_home_page_layout != "4"): ?>
	
    <div id="right-sidebar">
    <?php
    	
		$testimonials = get_posts( array(
			'numberposts'     => 1000, // тоже самое что posts_per_page
			'offset'          => 0,
			'category'        => '',
			'orderby'         => 'post_date',
			'order'           => 'DESC',
			'include'         => '',
			'exclude'         => '',
			'meta_key'        => '',
			'meta_value'      => '',
			'post_type'       => 'testimonial',
			'post_mime_type'  => '', // image, video, video/mp4
			'post_parent'     => '',
			'post_status'     => 'publish',			
		) );
		
    	if ($testimonials){    
    	echo '<h2 class="widget-title testi_title_o">Project Owner Testimonials</h2>';		
    		echo '<ul class="testimonials_slider_owner">';
    			foreach ($testimonials as $key => $testimonial) {
    				if($testimonial->post_password=='owner'){
	    				echo '<li class="full_width">';
	    				echo do_shortcode('[testimonial_single id="'.$testimonial->ID.'" template="1" img_size="small" img_loc="before" orientation="landscape" txt_align="center" ]');	    				
	    				echo '</li>';
	    			}
    			}
    		echo '</ul>';
    		
    	if(ProjectTheme_is_user_business(get_current_user_id())){
	    	echo '<h2 class="subm_testi">Submit a Testimonial</h2>';
	    	echo '<div style="display:none;">';
	    	echo do_shortcode('[contact-form-7 id="1012" title="Testimonial"]'); 
	    	echo '</div>';
	    	$user = wp_get_current_user(); ?>
	    	<script type="text/javascript">
	    		$(document).ready(function(){
	    			$('input[name="your-name"]').val('<?php echo $user->user_login?>');
	    			$('input[name="your-name"]').prop('readonly','readonly');
	    			$('input[name="your-email"]').val('<?php echo $user->user_email?>');
	    			$('input[name="your-email"]').prop('readonly','readonly');
	    		});
	    	</script>
	    <?php 		 		
	    }
    	}

    	$testimonials = get_posts( array(
			'numberposts'     => 1000, // тоже самое что posts_per_page
			'offset'          => 0,
			'category'        => '',
			'orderby'         => 'post_date',
			'order'           => 'DESC',
			'include'         => '',
			'exclude'         => '',
			'meta_key'        => '',
			'meta_value'      => '',
			'post_type'       => 'testimonial',
			'post_mime_type'  => '', // image, video, video/mp4
			'post_parent'     => '',
			'post_status'     => 'publish',			
		) );
		
    	if ($testimonials){    	
    	echo '<h2 class="widget-title testi_title_e">QA Engineer Testimonials</h2>';			
    		echo '<ul class="testimonials_slider_engineer">';
    			foreach ($testimonials as $key => $testimonial) {
    				if($testimonial->post_password=='engineer'){
	    				echo '<li class="full_width">';
	    				echo do_shortcode('[testimonial_single id="'.$testimonial->ID.'" template="1" img_size="small" img_loc="before" orientation="landscape" txt_align="center" ]');	    				
	    				echo '</li>';
	    			}
    			}
    		echo '</ul>';
    	if(ProjectTheme_is_user_provider(get_current_user_id())){
	    	echo '<h2 class="subm_testi">Submit a Testimonial</h2>';
	    	echo '<div style="display:none;">';
	    	echo do_shortcode('[contact-form-7 id="1012" title="Testimonial"]');
	    	echo '</div>';
	    	$user = wp_get_current_user(); ?>
	    	<script type="text/javascript">
	    		$(document).ready(function(){
	    			$('input[name="your-name"]').val('<?php echo $user->user_login?>');
	    			$('input[name="your-name"]').prop('readonly','readonly');
	    			$('input[name="your-email"]').val('<?php echo $user->user_email?>');
	    			$('input[name="your-email"]').prop('readonly','readonly');
	    		});
	    	</script>
	    <?php 		
	    }
    	}
    ?>
    	<script type="text/javascript">
    		$(document).ready(function(){
    			$('.subm_testi').click(function(){
    				$(this).next().toggle();
    			});
    			testimonials_slider_owner = $('.testimonials_slider_owner').bxSlider({
					  mode: 'vertical',
					  minSlides:3,					  
					  moveSlides:1,
					  pager:false,
					  controls:false,					  
					  slideMargin: 5,
					  auto:true
					});

				testimonials_slider_engineer = $('.testimonials_slider_engineer').bxSlider({
					  mode: 'vertical',
					  minSlides:3,					  
					  moveSlides:1,
					  pager:false,
					  controls:false,					  
					  slideMargin: 5,
					  auto:true
					});    			
    		});
    	</script>
		<ul class="xoxo">
	 <?php dynamic_sidebar( 'home-right-widget-area' ); ?>
		</ul>
       </div>

	<?php endif; ?>
    
    
    <?php
	
		if($ProjectTheme_home_page_layout == "2" ):
			
			    echo '<div id="left-sidebar">';
					echo '<ul class="xoxo">';
				 		dynamic_sidebar( 'home-left-widget-area' ); 
					echo '</ul>';
				   echo '</div>';
		
		endif;
		
	
	?>
    
    </div>
    </div>
   
    
<?php

		get_footer();

?>