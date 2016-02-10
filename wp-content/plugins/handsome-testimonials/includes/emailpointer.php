<?php
/**
 * Template Name: Pointer to Collect Email
 *
 * Displays various alerts and communications for plugin in the backend
 *
 * @package     handsometestimonials
 * @copyright   Copyright (c) 2014, Kevin Marshall
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 *
 */

//Support Function and JS for Pointer

function hntst_email_pointer_content() {
	
	$pointer_content  = '<h3>' . __( 'Get Customers Testimonials Easy', 'hndtst_loc' ) . '</h3>';
	$pointer_content .= '<p>' . __( 'Learn how to get your customers to write you better testimonials:', 'hndtst_loc') . '</p>';
	$pointer_content .= '<p>' . __('<a href="/wp-admin/edit.php?post_type=testimonial&page=htst_getting_started">Get Our Testimonial Request Template</a>', 'hndtst_loc' ) . '</p>';
	
	?>
		<script type="text/javascript">
		//<![CDATA[
			jQuery(document).ready( function($) {
				$('#wpadminbar').pointer({
					content: '<?php echo $pointer_content; ?>',
					position: {
						edge: 'top',
						align: 'center'
					},
					close: function() {
						setUserSetting( 'hndtst_email_pointer', '1' );
					}
				}).pointer('open');
			});
		//]]>
		</script>
	<?php
	}

function fb_enqueue_wp_pointer( $hook_suffix ) {
	// Don't run on WP < 3.3
	if ( get_bloginfo( 'version' ) < '3.3' )
		return;
		
	//Define Variables
	$screen = get_current_screen();
	$screen_id = $screen->id;
	$enqueue = FALSE;
	$admin_bar = get_user_setting( 'hndtst_email_pointer', 0 ); // check settings on user
	
	// check if admin bar is active and default filter for wp pointer is true
	if ( !$admin_bar && apply_filters( 'show_wp_pointer_admin_bar', TRUE ) && $screen_id == 'testimonial' ) {
		$enqueue = TRUE;
		add_action( 'admin_print_footer_scripts', 'hntst_email_pointer_content' );
	}
    	
	// in true, include the scripts
	if ( $enqueue ) {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_script( 'utils' ); // for user settings
	}
}
add_action( 'admin_enqueue_scripts', 'fb_enqueue_wp_pointer' );

?>