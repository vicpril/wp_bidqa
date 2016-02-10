<?php
/*****************************************************************************
*
*	copyright(c) - sitemile.com - PricerrTheme
*	More Info: http://sitemile.com/p/pricerr
*	Coder: Saioc Dragos Andrei
*	Email: andreisaioc@gmail.com
*
******************************************************************************/
	 
	function PricerrTheme_display_social_login()
	{
		
		$images_url = get_bloginfo('template_url') . "/lib/social/img/";
		$PricerrTheme_enable_facebook_login = get_option('PricerrTheme_enable_facebook_login');
		$PricerrTheme_enable_twitter_login 	= get_option('PricerrTheme_enable_twitter_login');
		
		 if( $PricerrTheme_enable_facebook_login == "yes" ) : ?>
				<a href="javascript:void(0);" title="Facebook" class="social_connect_login_facebook"><img alt="Facebook" src="<?php echo $images_url . 'facebook_32.png' ?>" /></a>
			<?php endif; ?>
			<?php if( $PricerrTheme_enable_twitter_login == "yes" ) : ?>
				<a href="javascript:void(0);" title="Twitter" class="social_connect_login_twitter"><img alt="Twitter" src="<?php echo $images_url . 'twitter_32.png' ?>" /></a>
			<?php endif; 

		
	}
	
	add_filter('login_form', 'PricerrTheme_display_social_login');

?>