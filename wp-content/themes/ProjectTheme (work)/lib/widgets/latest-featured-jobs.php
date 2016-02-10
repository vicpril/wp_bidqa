<?php

add_action('widgets_init', 'register_featured_jobs_widget');
function register_featured_jobs_widget() {
	register_widget('PricerrTheme_featured_jobs');
}

class PricerrTheme_featured_jobs extends WP_Widget {

	function PricerrTheme_featured_jobs() {
		$widget_ops = array( 'classname' => 'featured-jobs', 'description' => 'Show latest featured jobs' );
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'featured-jobs' );
		$this->WP_Widget( 'featured-jobs', 'PricerrTheme - Featured Jobs', $widget_ops, $control_ops );
	}

	function widget($args, $instance) {
		extract($args);
		
		echo $before_widget;
		
		if ($instance['title']) echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;
		$limit = $instance['show_jobs_limit'];

		if(empty($limit) || !is_numeric($limit)) $limit = 5;

				 global $wpdb;	
				 $querystr = "
					SELECT distinct wposts.* 
					FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta, $wpdb->postmeta wpostmeta2 , $wpdb->postmeta wpostmeta4
					WHERE wposts.ID = wpostmeta.post_id AND wposts.ID = wpostmeta2.post_id AND wposts.ID = wpostmeta4.post_id
					AND wpostmeta.meta_key = 'closed' 
					AND wpostmeta.meta_value = '0' 
					
					AND wpostmeta4.meta_key = 'active' 
					AND wpostmeta4.meta_value = '1' 
					
					AND wpostmeta2.meta_key = 'featured' 
					AND wpostmeta2.meta_value = '1' AND 
					
					wposts.post_status = 'publish' 
					AND wposts.post_type = 'job' 
					ORDER BY wposts.post_date DESC LIMIT ".$limit;
				
				 $pageposts = $wpdb->get_results($querystr, OBJECT);
				 
				 ?>
					
					 <?php $i = 0; if ($pageposts): ?>
					 <?php global $post; ?>
                     <?php foreach ($pageposts as $post): ?>
                     <?php setup_postdata($post); ?>
                     
                     
                     <?php PricerrTheme_small_post(); ?>
                     
                     
                     <?php endforeach; ?>
                     <?php else : ?> <?php $no_p = 1; ?>
                       <div class="padd100"><p class="center"><?php _e("Sorry, there are no posted jobs yet.",'PricerrTheme'); ?></p></div>
                        
                     <?php endif; ?>
                     
                     <?php
		
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
	
		return $new_instance;
	}

	function form($instance) { ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" 
			value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:95%;" />
		</p>
		
		
		<p>
			<label for="<?php echo $this->get_field_id('show_jobs_limit'); ?>"><?php _e('Show jobs limit'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('show_jobs_limit'); ?>" name="<?php echo $this->get_field_name('show_jobs_limit'); ?>" 
			value="<?php echo esc_attr( $instance['show_jobs_limit'] ); ?>" style="width:20%;" />
		</p>
		


			
	<?php 
	}
}



?>