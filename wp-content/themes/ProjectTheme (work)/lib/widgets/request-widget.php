<?php

add_action('widgets_init', 'register_request_widget');
function register_request_widget() {
	register_widget('PricerrTheme_request_wdiget');
}

class PricerrTheme_request_wdiget extends WP_Widget {

	function PricerrTheme_request_wdiget() {
		$widget_ops = array( 'classname' => 'request-widget', 'description' => 'Show request bar' );
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'request-widget' );
		$this->WP_Widget( 'request-widget', 'PricerrTheme - Request Job', $widget_ops, $control_ops );
	}

	function widget($args, $instance) {
		extract($args);
		
		echo $before_widget;
		
		$nr_posts = $instance['nr_posts'];
		
		if(empty($nr_posts)) $nr_posts = 10;
		if(!is_numeric($nr_posts)) $nr_posts = 10;
		
		
		
		?>
        <div class="request_div_widget">
        <form method="post" action="<?php bloginfo('siteurl'); ?>/?jb_action=submit_request">
        <div class="request_span"><?php _e("I am looking for <br/>someone who will","PricerrTheme"); ?>:</div>
         <textarea class="request_mainbox big-search-select" name="request" ></textarea>  <br/>
         <?php	
		 echo __("in","PricerrTheme"); 
		 echo PricerrTheme_get_categories_slug("request_cat", "", "", "big-search-select"); ?>
         <input type="submit" value="<?php _e("Suggest","PricerrTheme"); ?>" name="submit_prepare_request" id="suggest_job_btn" class="i_will_continue" />
        </form>
        </div>
        
        
        <a href="<?php bloginfo('siteurl'); ?>/?post_type=request"><?php _e('See All Requests','PricerrTheme'); ?></a><br/><br/>
        
                     <?php
			
			
		$using_perm = PricerrTheme_using_permalinks();
	
		if($using_perm)	$privurl_m = get_permalink(get_option('PricerrTheme_my_account_priv_mess_page_id')). "?";
		else $privurl_m = get_bloginfo('siteurl'). "/?page_id=". get_option('PricerrTheme_my_account_priv_mess_page_id'). "&";	
		
		//-------- ---------------------------------------------	
					 
		$args = array( 'posts_per_page' => $nr_posts, 'paged' => 1, 'post_type' => 'request', 'order' => "DESC" , 'orderby'=>'date');			 
		$the_query = new WP_Query( $args );			 
		
		if($the_query->have_posts()):
		echo '<ul id="suggest_jobs">';
		while ( $the_query->have_posts() ) : $the_query->the_post();
			
			$post = get_post(get_the_ID());
			$auth = $post->post_author;
			
			$lnk = $privurl_m . 'priv_act=send&pid='.get_the_ID().'&uid='.$auth;
			
			$author = get_the_author();
			echo '<li>';
			echo '<span>'.sprintf(__('<a href="%s">%s</a> needs',"PricerrTheme"),$lnk,$author).':</span><br/>';
			echo get_the_title(); echo'<br/> <span>';
			_e("Posted in","PricerrTheme");?> <?php echo get_the_term_list( get_the_ID(), 'request_cat', '', ', ', '' );
			echo '</span>';
					
			echo '</li>';
			
		endwhile;
		echo '</ul>';
		endif;
		
					 
		
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
	
		return $new_instance;
	}

	function form($instance) { ?>

        	<p>
			<label for="<?php echo $this->get_field_id('nr_posts'); ?>"><?php _e('Number of requests'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('nr_posts'); ?>" name="<?php echo $this->get_field_name('nr_posts'); ?>" 
			value="<?php echo esc_attr( $instance['nr_posts'] ); ?>" style="width:95%;" />
		</p>

			
	<?php 
	}
}



?>