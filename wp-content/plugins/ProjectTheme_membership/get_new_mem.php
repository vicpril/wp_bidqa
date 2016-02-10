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
 
 	 
	global $current_user, $wp_query;
	$pid 	=  $wp_query->query_vars['pid'];
	
	function ProjectTheme_filter_ttl($title){return __("Purchase Membership",'ProjectTheme')." - ";}
	add_filter( 'wp_title', 'ProjectTheme_filter_ttl', 10, 3 );	
	
	if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }   
	   
	
	get_currentuserinfo;   


	$uid 	= $current_user->ID;
	$cid 	= $current_user->ID;

	//=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	global $wpdb,$wp_rewrite,$wp_query;
		
 
	

//-------------------------------------

	get_header();
?>
                <div class="page_heading_me">
                        <div class="page_heading_me_inner">
                            <div class="mm_inn"><?php _e("Purchase Membership/Subscription", "ProjectTheme"); ?>  </div>
                  	            
                                        
                        </div>
                    
                    </div> 
<!-- ########## -->

<div id="main_wrapper">
		<div id="main" class="wrapper"><div class="padd10">
                

	<div id="content" >
        	
            <div class="my_box3">
            	<div class="padd10">
            
            	 
             
            	
                
				<?php
				
					$role = ProjectTheme_mems_get_current_user_role($uid);
					if($role == "service_provider") $cost = get_option('projectTheme_monthly_service_provider');
					else $cost = get_option('projectTheme_monthly_service_contractor'); 
				
				//-----------------------------------------
				
					echo '<div class="monthly_mem2">'.sprintf(__('Monthly membership cost: <strong>%s</strong>. Use the payment methods below.','ProjectTHeme'), projecttheme_get_show_price($cost)).'</div>';
					
				?>
						<div class="monthly_mem">
                        	<?php
							
								echo '<a href="'.get_bloginfo('siteurl').'/?p_action=credits_listing_mem" class="edit_project_pay_cls">'.__('Pay by Credits','ProjectTheme').'</a>';
						 
								//-------------------
							
								$ProjectTheme_paypal_enable 		= get_option('ProjectTheme_paypal_enable');
								$ProjectTheme_alertpay_enable 		= get_option('ProjectTheme_alertpay_enable');
								$ProjectTheme_moneybookers_enable 	= get_option('ProjectTheme_moneybookers_enable');
								
								
								if($ProjectTheme_paypal_enable == "yes")
									echo '<a href="'.get_bloginfo('siteurl').'/?p_action=paypal_membership_mem" class="edit_project_pay_cls">'.__('Pay by PayPal','ProjectTheme').'</a>';
								
								if($ProjectTheme_moneybookers_enable == "yes")
									echo '<a href="'.get_bloginfo('siteurl').'/?p_action=mb_membership_mem" class="edit_project_pay_cls">'.__('Pay by MoneyBookers/Skrill','ProjectTheme').'</a>';
								
								if($ProjectTheme_alertpay_enable == "yes")
									echo '<a href="'.get_bloginfo('siteurl').'/?p_action=payza_membership_mem" class="edit_project_pay_cls">'.__('Pay by Payza','ProjectTheme').'</a>';
								
								do_action('ProjectTheme_add_payment_options_to_membership', $pid);
							
							
							?>
                         
                
                </div>
                </div>
                </div>
                </div>
                
	<?php ProjectTheme_get_users_links(); ?>

</div></div></div>

<?php get_footer(); ?>