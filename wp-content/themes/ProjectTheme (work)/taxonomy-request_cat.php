<?php


	get_header();


?>
<?php 

		if(function_exists('bcn_display'))
		{
		    echo '<div class="my_box3_breadcrumb"><div class="padd10_a">';	
		    bcn_display();
			echo '</div></div>';
		}


$using_perm = PricerrTheme_using_permalinks();
	
		if($using_perm)	$privurl_m = get_permalink(get_option('PricerrTheme_my_account_priv_mess_page_id')). "?";
		else $privurl_m = get_bloginfo('siteurl'). "/?page_id=". get_option('PricerrTheme_my_account_priv_mess_page_id'). "&";	
?>	


<?php if ( have_posts() ): 

	echo '<ul id="suggest_jobs">';


while ( have_posts() ) : the_post(); ?>
<?php 


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


 ?>						
<?php endwhile; 

	echo '</ul>';

endif;

get_footer(); ?>