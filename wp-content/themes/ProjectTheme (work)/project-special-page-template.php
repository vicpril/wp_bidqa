<?php
/*
Template Name: Project_Special_Page
*/
?>

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
	global $post;
 
?>
<div class="page_heading_me">
	<div class="page_heading_me_inner">
    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
    	<div class="mm_inn"><?php
						
							echo $post->post_title
						
						?>
                     </div>
                    
        
<?php 

		if(function_exists('bcn_display'))
		{
		    echo '<div class="my_box3_breadcrumb breadcrumb-wrap">';	
		    bcn_display();
			echo '</div>';
		}

?>	</div>


		<?php projectTheme_get_the_search_box() ?>            
                    
    </div>
</div>


<?php projecttheme_search_box_thing() ?>

<!-- ########## -->

<div id="main_wrapper">
		<div id="main" class="wrapper"> 



<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<?php the_content(); ?>			
<?php endwhile; // end of the loop. ?>

 
</div>
</div>
<?php get_footer(); ?>