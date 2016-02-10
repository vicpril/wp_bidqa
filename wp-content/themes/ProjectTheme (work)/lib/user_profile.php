<?php

	global $wpdb,$wp_rewrite,$wp_query;
	$username = $wp_query->query_vars['username'];
	$uid = get_userdatabylogin($username);
	$userman = $uid; 
	$reg = $userman->user_registered;

	$uid = $uid->ID;
	$paged = $wp_query->query_vars['paged'];
	
 
	$rtg = pricerrTheme_get_seller_rating($uid)."%";
	$joined = PricerrTheme_prepare_seconds_to_words(time() - strtotime($reg));
	$last = get_user_meta( $uid, 'last_user_login', true );
	
	if(empty($last)) $act = __('no activity','PricerrTheme');
	else $act = PricerrTheme_prepare_seconds_to_words(current_time('timestamp',0) - $last)." ago";
	
//--------------------------------------------------------------------
	global $is_profile_pg, $usrusr;
	$usrusr = $username;
	$is_profile_pg = 1;
	
	get_header();
?>

<div id="content">
	
    
<div class="wrapitup">    
    <div class="profile_pic_user">
    <img width="101" height="101" border="0" src="<?php echo pricerrTheme_get_avatar($uid,101,101); ?>" />
    </div>
    
    <div class="info_user">
    	<h1 class="username_on_profile"><?php echo $userman->user_login; ?></h1>
        <p class="info_stuff_user color_lighter">
		
		<?php echo sprintf(__('Rated: %s','PricerrTheme'),$rtg); ?> &diams; 
        <?php echo sprintf(__('Joined %s ago','PricerrTheme'),$joined); ?>
         &diams; 
         <?php echo sprintf(__('last activity: %s','PricerrTheme'),$act); ?>
         
         </p>
        <p class="info_stuff_user_description">
        
       <?php echo stripslashes(get_user_meta($uid, 'personal_info', true)); ?>
        
        </p>
        
        <?php echo PricerrTheme_show_badge_user_account_panel($uid); ?>
         <?php echo PricerrTheme_show_badge_user_account_panel2($uid); ?>
        
    </div>

</div>
    

<div class="clear10"></div>
    
        
<?php

add_filter('posts_join', 'PricerrTheme_show_featured_psts' );
function PricerrTheme_show_featured_psts($wp_join)
{

		global $wpdb;
		$wp_join .= " LEFT JOIN (
				SELECT post_id, meta_value as featured_due
				FROM $wpdb->postmeta
				WHERE meta_key =  'featured' ) AS DD
				ON $wpdb->posts.ID = DD.post_id ";

	return ($wp_join);
}

add_filter('posts_orderby', 'PricerrTheme_show_featured_psts_order' );
function PricerrTheme_show_featured_psts_order( $orderby )
{
	
			$orderby = " featured_due+0 desc ";

 	return $orderby;
}



	$closed = array(
			'key' => 'closed',
			'value' => "0",
			'compare' => 'LIKE'
		);
		
		$active = array(
			'key' => 'active',
			'value' => "1",
			'compare' => 'LIKE'
		);
		

	
	$nrpostsPage = 12;
	$args = array( 'author' => $uid ,'posts_per_page' => $nrpostsPage, 'paged' => $paged, 'post_type' => 'job',
	 'meta_query' => array($closed, $active));
	$the_query = new WP_Query( $args );
		

		
		
		if($the_query->have_posts()):
		while ( $the_query->have_posts() ) : $the_query->the_post();
			
			PricerrTheme_get_post_thumbs( );
	
			
		endwhile;
	
	if(function_exists('wp_pagenavi'))
	wp_pagenavi( array( 'query' => $the_query ) );

          ?>
          
          <?php                                
     	else:
		
		echo __('No jobs posted.','PricerrTheme');
		
		endif;
		// Reset Post Data
		wp_reset_postdata();

            
					 
		?>
	




</div>


	
    <div id="right-sidebar">
		<ul class="xoxo">
	 <?php dynamic_sidebar( 'other-page-area' ); ?>
		</ul>
       </div>

<?php



	get_footer();
	
?>
