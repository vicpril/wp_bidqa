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
	<!DOCTYPE html>
	<html lang="en">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!--
	<title>
	BidQA
	<?php wp_title( );  ?>
    </title> -->

<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;


   // Add the blog name.
	bloginfo( 'name' );

	wp_title( '|', true, 'left' );

	

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>





 
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,500,300,600, 900,800' rel='stylesheet' type='text/css'>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,900,700,500' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Alegreya+Sans:400,700,900' rel='stylesheet' type='text/css'>
     <link href='https://fonts.googleapis.com/css?family=Bitter:400,700' rel='stylesheet' type='text/css'>
 
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url') ?>/css/normalize.css" />
    <link rel="stylesheet" href="<?php bloginfo('template_url') ?>/js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_enqueue_script("jquery"); ?>

	<?php

		wp_head();

	?>	

<script>

		jQuery(document).ready(function() {
    (function() {
        //settings
        var fadeSpeed = 200, fadeTo = 0.5, topDistance = 30;
        var topbarME = function() { jQuery('#header').fadeTo(fadeSpeed,1); }, topbarML = function() { jQuery('#header').fadeTo(fadeSpeed,fadeTo); };
        var inside = false;
        //do
		x_height = jQuery(document).height()-jQuery(window).height();
		if(screen.width > 1199)
       jQuery(window).scroll(function() {
            position = jQuery(window).scrollTop();
            
            /*console.log(x_height);*/
            if (position > 150 && x_height > 350){  
                //add events
               // topbarML();
             //   $('#header').bind('mouseenter',topbarME);
              //  $('#header').bind('mouseleave',topbarML);
			   jQuery('#header').addClass('uberbar2');
			   jQuery('#header #logo').addClass('logo_under_way');
			   jQuery('#cssmenu > ul > li > a').addClass('skr1');
			   
			   
                inside = true;
            }
            else {
               //  topbarME();
				//  $('#header').unbind('mouseenter',topbarME);
              //  $('#header').unbind('mouseleave',topbarML);
                jQuery('#header').removeClass('uberbar2');
			   jQuery('#header #logo').removeClass('logo_under_way');
			   jQuery('#cssmenu > ul > li > a').removeClass('skr1');
                inside = false;
            }
        });
    })();

    $('.excerpt-thing').on('click','.work_timer',function(){    	

    	var pid = $(this).attr('pid');
    	var act = $(this).attr('act');
    	var t_this = $(this);
    	var t_this_parent = $(this).parent();
    	var back_url = window.location.href;
    	if ($(this).attr('act')=='start'){
    		$(this).parent().next().show();
    		$(this).parent().next().find('.cansel_timer').click(function(){
    			$(this).parent().parent().hide();
    		});
    		$(this).parent().next().find('.accept_timer').click(function(){
    			var chekker = $(this).parent().find('.timer_accept');
    			if (chekker.prop("checked")){
    				
    				$.ajax({
    				  type: 'POST',
    				  url: "<?php echo get_bloginfo('siteurl'); ?>/?p_action=work_timer",
    				  data: {'pid':pid,'act':act, 'back_url':back_url},
    				  async:false,
    				  success: function( data ) {
			    	    t_this.parent().html( data );
			    	 	cur_time = t_this_parent.find('.active_timer').attr('cur_time');
						selector = t_this_parent.find('.active_timer').attr('id');						
						clocking_timer (cur_time,selector);
			    	  }
    				});
    				 
    				 $(this).parent().parent().hide();
    			}
    			else{
    				chekker.parent().css('color','red');
    			}
    		});
	    }
	    else{

	    	$.ajax({
    				  type: 'POST',
    				  url: "<?php echo get_bloginfo('siteurl'); ?>/?p_action=work_timer",
    				  data: {'pid':pid,'act':act, 'back_url':back_url},
    				  async:false,
    				  success: function( data ) {
			    	    t_this.parent().html( data );
			    	  	stop_my_int = t_this_parent.find('.not_active_timer').attr('id');
					    clearInterval_by_webbook(stop_my_int);
			    	  	
			    	  }
    				});

	    	/*$.post( "<?php echo get_bloginfo('siteurl'); ?>/?p_action=work_timer",{'pid':pid,'act':act, 'back_url':back_url}, function( data ) {
	    	  t_this.parent().html( data );
	    	  	stop_my_int = t_this_parent.find('.not_active_timer').attr('id');
			    clearInterval_by_webbook(stop_my_int);
	    	  	
	    	});*/
	    }    	
    });
});
	
	</script>
	

	 <?php	 
	 	
		$ProjectTheme_color_for_footer = get_option('ProjectTheme_color_for_footer');
		if(!empty($ProjectTheme_color_for_footer))
		{
			echo '<style> #footer { background:#'.$ProjectTheme_color_for_footer.' }</style>';	
		}
		
		
		$ProjectTheme_color_for_bk = get_option('ProjectTheme_color_for_bk');
		if(!empty($ProjectTheme_color_for_bk))
		{
			echo '<style> body { background:#'.$ProjectTheme_color_for_bk.' }</style>';	
		}
		
		$ProjectTheme_color_for_top_links = get_option('ProjectTheme_color_for_top_links');
		if(!empty($ProjectTheme_color_for_top_links))
		{
			echo '<style> .top-bar { background:#'.$ProjectTheme_color_for_top_links.' }</style>';	
		}
		
		
		//----------------------
		
	 	$ProjectTheme_home_page_layout = get_option('ProjectTheme_home_page_layout');
		if(ProjectTheme_is_home()):
			if($ProjectTheme_home_page_layout == "4"):
				echo '<style>#content { float:right } #left-sidebar { float:left; }</style>';
			endif;
			
			if($ProjectTheme_home_page_layout == "5"):
				echo '<style>#content { width:100%; }  </style>';
			endif;
			
			if($ProjectTheme_home_page_layout == "3"):
				echo '<style>#content { width:395px } .title_holder { width:285px; } #left-sidebar{	float:left;margin-right:15px;}
				 </style>';
			endif;
			
			
			if($ProjectTheme_home_page_layout == "2"):
				echo '<style>#content { width:395px } #left-sidebar{ float:right } #left-sidebar{ margin-right:15px; } .title_holder { width:285px; }
				 </style>';
			endif;
		
		endif;
	 
	 
	 ?>
	 
     <script type="text/javascript">
		
		var $ = jQuery;
		
	function suggest(inputString){
	
		if(inputString.length == 0) {
			jQuery('#suggestions').fadeOut();
		} else {
		jQuery('#big-search').addClass('load');
			jQuery.post("<?php bloginfo('siteurl'); ?>/?autosuggest=1", {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					jQuery('#suggestions').fadeIn();
					jQuery('#suggestionsList').html(data);
					jQuery('#big-search').removeClass('load');
				}
			});
		}
	}

	function fill(thisValue) {
		jQuery('#big-search').val(thisValue);
		setTimeout("$('#suggestions').fadeOut();", 600);
	}
	
	<?php
	
	if(is_home()):
	
		$quant_slider 		= 5;
		$quant_slider_move 	= 1;
		$slider_pause 		= 5000;
		$slider_speed		= 1000;
		
		$quant_slider 		= apply_filters('ProjectTheme_quantity_slider_filter', 		$quant_slider);
		$quant_slider_move 	= apply_filters('ProjectTheme_quantity_slider_move_filter', $quant_slider_move);
		$slider_pause 		= apply_filters('ProjectTheme_slider_pause_filter', 		$slider_pause);
		$slider_speed 		= apply_filters('ProjectTheme_slider_speed_filter', 		$slider_speed);
		
	?>
	
	$(document).ready(function(){
		//jQuery(function(){
	  /*jQuery('#slider2').bxSlider({
	  	slideWidth: 10,
		auto: true,
		speed: <?php echo $slider_speed; ?>,
		pause: <?php echo $slider_pause; ?>,
		autoControls: false,
		displaySlideQty: <?php echo $quant_slider; ?>,
    	moveSlideQty: <?php echo $quant_slider_move; ?>
	  });
	  
	  home_slider = jQuery("#project-home-page-main-inner").show();*/
	  var slid_width = parseInt($( '#project-home-page-main-inner' ).width()/5);
	  maxSlides = 5;
	  if (slid_width<195){
	  	slid_width = parseInt($( '#project-home-page-main-inner' ).width()/4);
	  	maxSlides = 4;
	  }
	  if (slid_width<195){
	  	slid_width = parseInt($( '#project-home-page-main-inner' ).width()/3);
	  	maxSlides = 3;
	  }
	  if (slid_width<195){
	  	slid_width = parseInt($( '#project-home-page-main-inner' ).width()/2);
	  	maxSlides = 2;
	  }
	  if (slid_width<195){
	  	slid_width = parseInt($( '#project-home-page-main-inner' ).width()/1);
	  	maxSlides = 1;
	  }
	  jQuery("#project-home-page-main-inner").show();
	  home_slider = jQuery('#slider2').bxSlider({
	  	auto: true,
		speed: 1000,
		pause: 5000,
		//autoControls: false,
		pager: false,
		minSlides: 1,
		maxSlides:maxSlides,
    	moveSlides: 1,
    	autoHover:true,
    	slideWidth: slid_width
    	
	  });
	  $('.bx-next, .bx-prev').hover(
	  		function(){
	  			home_slider.stopAuto();
	  		},
	  		function(){
	  			home_slider.startAuto();
	  		}
	  	);

	  if(maxSlides=1){
		var marg = (slid_width-185)/2;
		marg = marg+'px';
		$('.slider-post').css('margin-left',marg);
	  }
	  else{
		marg = '0px';
		$('.slider-post').css('margin-left',marg);
	  }

	  $( window ).resize(function() {
				    //if($( window ).width()<1000){				    	
				    	var slid_width = parseInt($('#project-home-page-main-inner').width()/5);
				    		  maxSlides = 5;
				    		  if (slid_width<195){
				    		  	slid_width = parseInt($( '#project-home-page-main-inner' ).width()/4);
				    		  	maxSlides = 4;
				    		  }
				    		  if (slid_width<195){
				    		  	slid_width = parseInt($( '#project-home-page-main-inner' ).width()/3);
				    		  	maxSlides = 3;
				    		  }
				    		  if (slid_width<195){
				    		  	slid_width = parseInt($( '#project-home-page-main-inner' ).width()/2);
				    		  	maxSlides = 2;
				    		  }
				    		  if (slid_width<195){
				    		  	slid_width = parseInt($( '#project-home-page-main-inner' ).width()/1);
				    		  	maxSlides = 1;
				    		  }
				  		home_slider.reloadSlider({
				  			auto: true,
				  					speed: 1000,
				  					pause: 5000,
				  					
				  					//autoControls: false,
				  					pager: false,
				  					minSlides: 1,
				  					maxSlides:maxSlides,
				  			    	moveSlides: 1,
				  			    	autoHover:true,
				  			    	slideWidth: slid_width
				  		}); 
				  		if(maxSlides=1){
				  			var marg = (slid_width-185)/2;
				  			marg = marg+'px';
				  			$('.slider-post').css('margin-left',marg);
				  		}
				  		else{
				  			marg = '0px';
				  			$('.slider-post').css('margin-left',marg);
				  		} 	
				   // }
				  });
	  
	  
	});	
	
	<?php endif; ?>
	
 
  
			(function($){
			jQuery(document).ready(function(){
				  
			
			jQuery("#cssmenu").menumaker({
			   title: "<?php _e('User Menu','ProjectTheme'); ?>",
			   format: "multitoggle"
			});
			
			jQuery("#cssmenu2").menumaker({
			   title: "<?php echo 'Main Menu'.$u_t;/*_e('Main Menu'.$u_t,'ProjectTheme');*/ ?>",
			   format: "multitoggle"
			});
			
			});
			})(jQuery);
				
	</script>
    
	
    <!--[if IE]>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url') ?>/css/all-ie.css" />
    <![endif]-->
    
    <?php do_action('ProjectTheme_before_head_tag_closes'); ?>
    
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>


<script src="<?php bloginfo('template_url') ?>/js/vegas.min.js"></script>
<script src="<?php bloginfo('template_url') ?>/js/webbook.js"></script>
<link rel="stylesheet" href="<?php bloginfo('template_url') ?>/css/vegas.css">
    
    <script>
	jQuery(document).ready(function(){
	jQuery(".home_blur").vegas({
    slides: [
        { src: "<?php bloginfo('template_url') ?>/images/sc1.jpg" },
        { src: "<?php bloginfo('template_url') ?>/images/sc2.jpg" },
        { src: "<?php bloginfo('template_url') ?>/images/sc3.jpg" },
        { src: "<?php bloginfo('template_url') ?>/images/sc4.jpg" }
    ]
});});
	
	</script>
<?php	if ( is_user_logged_in() ) {
	$user = new WP_User( $user_ID );
	if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
		foreach ( $user->roles as $role )
		$role;
	}
}
?>

	</head>
	<body <?php body_class(); if($role=="service_provider") { echo 'id="'.$role.'"'; } else { echo 'id="all-role-body"'; }?> >

	<div id="light" class="white_content"><a href = "javascript:void(0)" onclick = "document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'">Close</a>
	<h2 style="text-align:center;">Must be login as Project Owner</h2>
	</div>
	
	<?php do_action('ProjectTheme_after_body_tag_open'); ?>

	<?php
	/*
	//1 step
	$del_terms = get_terms( 'project_location', 'hide_empty=0');	
	foreach ($del_terms as $key => $del_term) {
		wp_delete_term( $del_term->term_id, 'project_location' );
	}*/

	
	
	/*
	//2 step
	global $wpdb;	
	$s = "select * from meta_location where in_location IS NULL";
	$r = $wpdb->get_results($s);

	foreach ($r as $key => $rr) {
		$array='';
			$args = array(
				'alias_of'=>sanitize_title($rr->local_name)
				,'description'=>$rr->local_name
				,'parent'=>0
				,'slug'=>sanitize_title($rr->local_name)
			);
			//var_dump(get_term_by( 'name', $rr->local_name, 'project_location'));

			if (!$array=get_term_by( 'name', $rr->local_name, 'project_location','ARRAY_A')){
				//var_dump($array);
				$array = wp_insert_term( $rr->local_name, 'project_location', $args );
				//var_dump($array);
			}
			if(is_object($array)){
				continue;
			}
			//echo "</br>----------------------------------------------------------------------------------------------------</br>";
			

			$s2 = 'select * from meta_location where type LIKE "%RE%" AND in_location='.$rr->id;
			$r2 = $wpdb->get_results($s2);
			
			foreach ($r2 as $k => $rr2) {
				$args = array(
					'alias_of'=>sanitize_title($rr2->local_name)
					,'description'=>$rr2->local_name
					,'parent'=>$array['term_id']
					,'slug'=>sanitize_title($rr2->local_name)
				);

				if (!get_term_by( 'name', $rr2->local_name, 'project_location')){
					$aa = wp_insert_term( $rr2->local_name, 'project_location', $args );
				}
				//var_dump(get_term_by( 'name', $rr2->local_name, 'project_location'));
			}
	}
*/
	
	//3 step
	/*global $wpdb;
	$top_terms = get_terms( 'project_location', 'hide_empty=0&parent=0');
		$s = 'select * from meta_location where id=230';
		$r = $wpdb->get_results($s);

		
		$parent1 = get_term_by( 'name', trim($r[0]->local_name), 'project_location','ARRAY_A');
		$parent2 = get_term_by( 'name', trim($r[1]->local_name), 'project_location','ARRAY_A');


			if($parent1){
				$s1 = 'select * from meta_location where type LIKE "%RE%" AND in_location='.$r[0]->id;
				$r1 = $wpdb->get_results($s1);				
				
				foreach ($r1 as $k1 => $rr1) {
					if (get_term_by( 'name', $rr1->local_name, 'project_location')){continue;}
					else{
						$args = array(
							'alias_of'=>sanitize_title($rr1->local_name)
							,'description'=>$rr1->local_name
							,'parent'=>$parent1['term_id']
							,'slug'=>sanitize_title($rr1->local_name)
						);
						$aa = wp_insert_term( $rr1->local_name, 'project_location', $args );						
						$i++;
					}
				}
			}*/
	
?>

	<div id="wrapper">
		<!-- start header area -->

		<div id="header" class="container-fluid  no-padding">
			<div class="top-bar-bg">
				<div class="top-bar"> 
                
                	<div class="my-logo col-xs-6 col-sm-4 col-md-4 col-lg-3">
                    	
                        <?php
							$logo = get_option('projectTheme_logo_url');
							if(empty($logo)){
								
								$logo = get_bloginfo('template_url').'/images/logo/logo.png';
								$logo = apply_filters('ProjectTheme_logo_url', $logo);
							}
						
							$logo_options = '';
							$logo_options = apply_filters('ProjectTheme_logo_options', $logo_options);	
							
						?>
						<a class="a_logo" href="<?php bloginfo('siteurl'); ?>"><img id="logo" alt="<?php bloginfo('name'); ?>" <?php echo $logo_options; ?> src="<?php echo $logo; ?>" /></a>
                    	<div class="clear"></div>
                    </div>
                
                    <div class="col-xs-6 col-sm-8 col-md-8 col-lg-9 RARA">              
                    <div class="top-links" id="cssmenu">	<ul>						
							<?php 
								
							if(current_user_can('level_10')) {?>
                            <li>  
                            <a href="<?php echo get_admin_url(); ?>"><i class="admin-control" ></i> <?php echo __("WP-Admin",'ProjectTheme'); ?></a> </li><?php }
							
							
							do_action('ProjectTheme_top_menu_items');
						
							$menu_name = 'primary-projecttheme-header';

							if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
							$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
						
							$menu_items = wp_get_nav_menu_items($menu->term_id);
					
						
							foreach ( (array) $menu_items as $key => $menu_item ) {
								$title = $menu_item->title;
								$url = $menu_item->url;
								if(!empty($title))
								echo '<li><a href="' . $url . '">' . $title . '</a></li>';
							}
								
							} 
							
							$ProjectTheme_show_blue_menu = get_option('ProjectTheme_show_blue_menu');
							if($ProjectTheme_show_blue_menu == 'no'):
							?>
                            
                            <li><a href="<?php bloginfo('siteurl'); ?>"><?php echo __("Home",'ProjectTheme'); ?></a> </li>
                           <li> <a href="<?php echo projectTheme_advanced_search_link2(); ?>"><?php echo __("Advanced Search",'ProjectTheme'); ?></a> </li>
                            
                            
                            <?php							
							endif;
							?>
                            
                            
                            <?php
							
							global $current_user;
							get_currentuserinfo();
							$uid = $current_user->ID;

							if (ProjectTheme_is_user_business($current_user->ID)){
								$u_t='<li class="user_type">You are logged in as a Project Owner</li>';
								$user_type_button = '<a href="'.projectTheme_post_new_link().'">Post Your Project</a>';								
							}
							else{
								$u_t='<li class="user_type">You are logged in as a QA Engineer</li>';			            											
								$user_type_button = '<a href="'.get_permalink(get_option("ProjectTheme_advanced_search_page_id")).'">Find Your Project</a>';								
							}
							
							 if(ProjectTheme_is_user_business($uid)): ?>
                             
							<li><a href="<?php echo projectTheme_post_new_link(); ?>"><i class="post-new-awsome" ></i> <?php echo __("Post New",'ProjectTheme'); ?></a> </li>
                            <?php endif; ?>
                            
							<?php if(get_option('projectTheme_enable_blog') == "yes") { ?>
                            <li><a href="<?php echo projectTheme_blog_link(); ?>"><i class="blog-awsome" ></i><?php echo __("Blog",'ProjectTheme'); ?></a></li> 
							<?php } ?>
							<?php 
							
						
							
								if(is_user_logged_in())
								{
									global $current_user;
									get_currentuserinfo();
									$u = $current_user;
									
									
									?>
									<li><a href="<?php echo projectTheme_my_account_link(); ?>"><i class="account-awsome" ></i><?php echo __("My Account",'ProjectTheme'); ?></a>
                                    	<ul>
                                        	<li><a href="<?php echo get_permalink(get_option('ProjectTheme_my_account_private_messages_id')); ?>"><?php echo __("Private Messages",'ProjectTheme'); ?></a></li>
                                        	<li><a href="<?php echo get_permalink(get_option('ProjectTheme_my_account_payments_id')); ?>"><?php echo __("Payments",'ProjectTheme'); ?></a></li>
                                            <li><a href="<?php echo get_permalink(get_option('ProjectTheme_my_account_feedback_id')); ?>"><?php echo __("Feedback/Reviews",'ProjectTheme'); ?></a></li>
                                        	<li><a href="<?php echo get_permalink(get_option('ProjectTheme_my_account_personal_info_id')); ?>"><?php echo __("Personal Info",'ProjectTheme'); ?></a></li>
                                            <li><a href="<?php echo get_permalink(get_option('ProjectTheme_my_account_disputes_id')); ?>"><?php echo __("Disputes",'ProjectTheme'); ?></a></li>
									   </ul>
                                    </li>
									<li><a href="<?php echo wp_logout_url(); ?>"><i class="logout-awsome" ></i><?php echo __("Log Out",'ProjectTheme'); ?></a></li>
									
									<?php
								}
								else
									{
										
							
							?>
							
							<li><a href="<?php bloginfo('siteurl') ?>/wp-login.php?action=register"><i class="register-awsome" ></i><?php echo __("Register",'ProjectTheme'); ?></a></li>
							<li><a href="<?php bloginfo('siteurl') ?>/wp-login.php"><i class="login-awsome" ></i><?php echo __("Log In",'ProjectTheme'); ?></a></li>
							<?php } ?> </ul>
						</div>
                    
                    
				</div>
				<?php 
					if (is_user_logged_in()) {					
						echo '<div class="clear"></div><div class="welcome">Welcome '.$current_user->user_login.'</div><div class="clear"></div>';
					}
				?>
			</div> <!-- end top-bar-bg -->
			
            </div>
            </div>
            <!-- start main menu -->
            
             <?php
			
			do_action("ProjectTheme_content_before_main_menu");
			?>
<!-- -->

					<?php
						$ProjectTheme_show_blue_menu = get_option('ProjectTheme_show_blue_menu');
						if($ProjectTheme_show_blue_menu == 'yes' && is_user_logged_in()/*!projecttheme_is_home()*/):
					?>
			        
			        <div class="main_menu_menu_wrap">
			       	<?php
					
					$menu_name = 'primary-projecttheme-main-header';

					if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
					$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
									
					$menu_items = wp_get_nav_menu_items($menu->term_id);
								
					$m = 0;				
					foreach ( (array) $menu_items as $key => $menu_item ) {
						$title = $menu_item->title;
						$url = $menu_item->url;
						if(!empty($title))
						$m++;
						}
					}
					
					
					 
					if($m == 0):
					
					?>
			        <div class="main_menu_menu" id="cssmenu2">
			        
			        	<ul class="jetmenu blue">
			            <li><a href="<?php bloginfo('siteurl'); ?>"><?php _e('Home','ProjectTheme'); ?></a></li>
			            
			            <?php 
						
							$adv_search_btn = true;
							$adv_search_btn = apply_filters('ProjectTheme_adv_search_btn', $adv_search_btn);
							if($adv_search_btn == true):
						
						 ?>
			            <li><a href="<?php echo get_permalink(get_option('ProjectTheme_advanced_search_page_id')); ?>"><?php _e('Project Search','ProjectTheme'); ?></a></li> 
			            <?php endif; ?>
			            
			            
			            <?php 
						
							$prov_search_btn = true;
							$prov_search_btn = apply_filters('ProjectTheme_prov_search_btn', $prov_search_btn);
							if($prov_search_btn == true):
						
						 ?>
			            <li><a href="<?php echo get_permalink(get_option('ProjectTheme_provider_search_page_id')); ?>"><?php _e('QA Engineer Search','ProjectTheme'); ?></a></li>
			            <?php endif; ?>
			            
			            
			            <?php 
						
							$ProjectTheme_all_rpojects_btn = true;
							$ProjectTheme_all_rpojects_btn = apply_filters('ProjectTheme_all_rpojects_btn', $ProjectTheme_all_rpojects_btn);
							if($ProjectTheme_all_rpojects_btn == true):
						
						 ?>
			            <li><a href="<?php echo get_permalink(get_option('ProjectTheme_all_projects_page_id')); ?>"><?php _e('All Projects','ProjectTheme'); ?></a></li>
			            <?php endif; ?>
			            
			            
			            <?php 
						
							$all_cats_btn = true;
							$all_cats_btn = apply_filters('ProjectTheme_all_cats_btn', $all_cats_btn);
							if($all_cats_btn == true):
						
						 ?> 
			            <li><a href="<?php echo get_permalink(get_option('ProjectTheme_all_categories_page_id')); ?>"><?php _e('Show All Categories','ProjectTheme'); ?></a></li> 
			            <?php endif; ?>
			            
			            
			            	<?php
					
						$ProjectTheme_enable_project_location = get_option('ProjectTheme_enable_project_location');
						if($ProjectTheme_enable_project_location == "yes"):
					
					?>
			            
			            <?php 
						
							$all_locs_btn = true;
							$all_locs_btn = apply_filters('ProjectTheme_all_locs_btn', $all_locs_btn);
							if($all_locs_btn == true):
						
						 ?>             
			            <li><a href="<?php echo get_permalink(get_option('ProjectTheme_all_locations_page_id')); ?>"><?php _e('Show All Locations','ProjectTheme'); ?></a></li> 
			            <?php endif; ?>
			            
			              <?php
								
								endif;
								
								
										do_action('ProjectTheme_main_menu_items');
								
										
										$menu_name = 'primary-projecttheme-main-header';

										if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
										$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
									
										$menu_items = wp_get_nav_menu_items($menu->term_id);
								
									
										foreach ( (array) $menu_items as $key => $menu_item ) {
											$title = $menu_item->title;
											$url = $menu_item->url;
											if(!empty($title))
											echo '<li><a href="' . $url . '">' . $title . '</a></li>';
										}
											
										}
										
										
										?>
			               <?php
			                if (is_user_logged_in()) {
			            		echo $u_t;
			                }
			               	
			               ?>             
			                       
			            </ul>
			        
			        
			        </div>
			        
			        <?php else: 
			 
					?>
			 
			       
			        <div class="main_menu_menu">
			        <div class="dcjq-mega-menu" id="<?php echo 'cssmenu2'; ?>">		
					<?php
						 
						$menu_name = 'primary-projecttheme-main-header';

						if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) 
						$nav_menu = wp_get_nav_menu_object( $locations[ $menu_name ] );					
										 			
						
						wp_nav_menu( array('menu_id' => 'jetmenu_m', 'menu_class' => 'jetmenua bluea' , 'fallback_cb' => '', 'menu' => $nav_menu, 'container' => false, 'walker' => new Project_Walker_Nav_Menu() ) );
						
					?>		
					</div>
			        </div>
			        
			        <?php endif; ?>
			        
			          </div> 
					<?php
					endif;
					?>





<!-- -->



			<?php
			if(projecttheme_is_home()): 
		?>
        
        <!-- head scr -->
        
        <div class="home_blur">
        <div class="main_area_homepg">
        <?php if (ProjectTheme_is_user_provider($current_user->ID)){ ?>
        	<div class="main_tagLine"><?php _e('<br><br><br>','ProjectTheme') ?></div>
            <!-- <div class="sub_tagLine"><div class="wrps"><?php _e('We are Life in BETA !<br> ','ProjectTheme') ?></div></div>       		 -->
        <?php } elseif (ProjectTheme_is_user_business($current_user->ID)) { ?>
            <div class="main_tagLine"><?php _e('<br><br><br>','ProjectTheme') ?></div>
            <!-- <div class="sub_tagLine"><div class="wrps"><?php _e('You can start by posting a chore you need done or register to get your job.','ProjectTheme') ?></div></div>             -->
       	<?php } else {?>
       		<!--<div class="main_tagLine"><?php _e('We are Life in BETA !<br> You can Find  professional QA Engineers to get your job done<br>Absolutely   with   NO    Cost!','ProjectTheme') ?></div>-->
       		<div class="main_tagLine"><?php _e('<br><br><br>','ProjectTheme') ?></div>
		<?php } ?>       	
            <!--
            
            <form method="get" action="<?php echo get_permalink(get_option('ProjectTheme_advanced_search_page_id')) ?>">
            <div class="search_box_main">
            	<div class="search_box_main2">
                    <div class="rama1"><input type="text" placeholder="<?php _e('What service do you need? (e.g. website design)','ProjectTheme'); ?>" id="findService" name="term"></div>
                    <div class="rama1 rama2"><input type="image" src="<?php bloginfo('template_url') ?>/images/sear1.png" width="44" height="44" /></div>
                </div>
            </div>
            </form> -->
        	
            <div class="buttons_box_main">
            	<ul class="regular_ul">
            	<?php 
            		/*if ( $_GET['contr_error'] ) {
						$contr_error = 'Please sign as Project Provider to Post Your Project.';
					}
					else{
						$contr_error = 'Post Your Project';
					}*/
            	?>
                	
                	<?php if (!is_user_logged_in()) { ?>
                	<li><a href="<?php bloginfo('siteurl') ?>/wp-login.php?action=register"><?php _e("Join Us. It's Free", 'ProjectTheme' ) ?></a></li>
                	<?php }else{ ?>
          			<li><?php echo $user_type_button;?><!-- <a href="<?php //echo projectTheme_post_new_link(); ?>"><?php _e($contr_error,'ProjectTheme'); ?></a> --></li>      	
                	<?php } ?>
                </ul>
            
            </div>
        
        </div>
       	</div>
        
 
        
        
        <!-- END head scr -->
        
        <?php
		
		endif;		
			
		?>


            
            <!-- end main menu -->
           
		
        
       
        
        <?php
		
		do_action("ProjectTheme_content_after_main_menu");
		
		if( ProjectTheme_is_home()):
		
			include 'lib/slider_home.php';
			include 'lib/stretch_area.php';
		
		endif;
		
		
		?>
        
        