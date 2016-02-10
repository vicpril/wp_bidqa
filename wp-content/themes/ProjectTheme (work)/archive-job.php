<?php
/*****************************************************************************
*
*	copyright(c) - sitemile.com - PricerrTheme
*	More Info: http://sitemile.com/p/pricerr
*	Coder: Saioc Dragos Andrei
*	Email: andreisaioc@gmail.com
*
******************************************************************************/

global $query_string;
$my_order = pricerrtheme_get_current_order_by_thing();

if($my_order == "instant")
{
	$instant = array(
		'key' => 'instant',
		'value' => "0",
		'compare' => '=');	
	
}

if($my_order == "express")
{
	$express = array(
		'key' => 'max_days',
		'value' => "1",
		'compare' => '=');	
	
}

//******************************************************
	
$closed = array(
		'key' => 'closed',
		'value' => "0",
		//'type' => 'numeric',
		'compare' => '='
);
	
$prs_string_qu = wp_parse_args($query_string);
$prs_string_qu['meta_query'] = array($closed, $instant, $express);
$prs_string_qu['posts_per_page'] = 20;


		
query_posts($prs_string_qu);

$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
$term_title = $term->name;
			
//======================================================

	get_header();
	
	$PricerrTheme_adv_code_cat_page_above_content = stripslashes(get_option('PricerrTheme_adv_code_cat_page_above_content'));
		if(!empty($PricerrTheme_adv_code_cat_page_above_content)):
		
			echo '<div class="full_width_a_div">';
			echo $PricerrTheme_adv_code_cat_page_above_content;
			echo '</div>';
		
		endif;
	

//====================================================
	
 
?>


<div class="my_new_box_title"><?php
						if(empty($term_title)) echo __("All Posted Jobs",'PricerrTheme');
						else echo sprintf( __("Latest Posted Jobs in %s",'PricerrTheme'), $term_title);
					?> </div>


<div class="filter_jobs"><div class="padd5">
        <div class="filter_div">
        <?php _e("Filter jobs by:",'PricerrTheme'); ?></div> <ul id="filter_jobs_list">
        
        <li><a href="<?php echo pricerrtheme_filter_switch_link_from_home_page('auto'); ?>" <?php echo ($my_order == "auto" ? 'class="active_link"' : ""); ?>><?php 
		_e("Auto","PricerrTheme"); ?></a></li>
        
        <li><a href="<?php echo pricerrtheme_filter_switch_link_from_home_page( 'new'); ?>" <?php echo ($my_order == "new" ? 'class="active_link"' : ""); ?>><?php 
		_e("New","PricerrTheme"); ?></a></li>

        <li><a href="<?php echo pricerrtheme_filter_switch_link_from_home_page( 'rating'); ?>" <?php echo ($my_order == "rating" ? 'class="active_link"' : ""); ?>><?php 
		_e("Rating","PricerrTheme"); ?></a></li>
        
<li><a href="<?php echo pricerrtheme_filter_switch_link_from_home_page( 'views'); ?>" <?php echo ($my_order == "views" ? 'class="active_link"' : ""); ?>><?php _e("Views","PricerrTheme"); ?></a></li>
<li><a href="<?php echo pricerrtheme_filter_switch_link_from_home_page('popularity'); ?>"<?php echo ($my_order == "popularity" ? 'class="active_link"' : ""); ?>><?php _e("Popularity","PricerrTheme"); ?></a></li>
<li><a href="<?php echo pricerrtheme_filter_switch_link_from_home_page('express'); ?>"<?php echo ($my_order == "express" ? 'class="active_link"' : ""); ?>><?php _e("Express Jobs","PricerrTheme"); ?></a></li>
<li><a href="<?php echo pricerrtheme_filter_switch_link_from_home_page('instant'); ?>"<?php echo ($my_order == "instant" ? 'class="active_link"' : ""); ?>><?php _e("Instant Delivery","PricerrTheme"); ?></a></li>
        
        
        
        </ul>
        
        </div>
        
        
        <?php
					
					$view = pricerrtheme_get_current_view_grid_list();
					echo '<div class="switchers">';
					echo '<div class="switch_view_link">'.__('Switch View:','PricerrTheme').'</div>';
					
					if($view != "grid")
					{
						echo '<a href="'.pricerrtheme_switch_link_from_home_page('grid').'" class="grid"></a>';
						echo '<a href="'.pricerrtheme_switch_link_from_home_page('list').'" class="list-selected"></a>';
					}
					else
					{
						echo '<a href="'.pricerrtheme_switch_link_from_home_page('grid').'" class="grid-selected"></a>';
						echo '<a href="'.pricerrtheme_switch_link_from_home_page('list').'" class="list"></a>';
					}
					echo '</div>';
					
					?>
        
        
        </div>



<?php 

		if(function_exists('bcn_display'))
		{
		    echo '<div class="my_box3_breadcrumb"><div class="padd10_a">';	
		    bcn_display();
			echo '</div></div>';
		}

?>	





<div id="content">




<?php if ( have_posts() ): while ( have_posts() ) : the_post(); ?>

<?php 
                     
					 if($view != "grid")
						 PricerrTheme_get_post();
					 else
					 	PricerrTheme_get_post_thumbs();
                     
                     ?>

<?php  
 		endwhile; 
		
		if(function_exists('wp_pagenavi')):
		wp_pagenavi(); endif;
		                             
     	else:
		
		echo __('No jobs posted.',"PricerrTheme");
		
		endif;
		// Reset Post Data
		wp_reset_postdata();
		 
		?>


</div>

<?php

	$opt = get_option('pricerrtheme_taxonomy_page_with_sdbr');
	if($opt != "no"):

?>
<div id="right-sidebar">
    <ul class="xoxo">
        <?php dynamic_sidebar( 'other-page-area' ); ?>
    </ul>
</div>


<?php
	endif;
	get_footer();

?>