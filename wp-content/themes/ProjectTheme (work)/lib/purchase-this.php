<?php

	session_start();
	$pid = $_GET['jobid'];
	
	function PricerrTheme_filter_ttl($title){return __("Purchase job",'PricerrTheme')." - ";}
	add_filter( 'wp_title', 'PricerrTheme_filter_ttl', 10, 3 );	
	
	if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php?redirect_to=" . get_permalink($pid)); exit; }   
	   
	global $current_user;
	get_currentuserinfo;   
			
	global $wp_query;
	
	$post = get_post($pid);
	
	$crds = PricerrTheme_get_credits($current_user->ID);
	$price = get_post_meta($pid, 'price', true);
	if(empty($price)) $price = get_option('PricerrTheme_job_fixed_amount');

	$uid = $current_user->ID;

//===============================================================================================
		
			get_header();
			
			
			$prc		= get_post_meta($pid, "price", true);
			
			?>
            
	
        <div id="content">
        	<div class="padd10">
       
            
            	<div class="box_title"><?php echo __("Review and Choose Payment Method", 'PricerrTheme'); ?></div>
            	<div class="box_content">
                <?php do_action('PricerrTheme_before_message_purchase_gig_job'); ?>
                
               		<div class="order_total"><div class="padd10">
                   	<?php
					 
						$extra_job_add = array(); $h = 0;
						$partial_ttl = 0;
						
						
						$extra_job_arr = array();
						
						for($k=1;$k<=10;$k++)
						{
							$extra_price 	= get_post_meta($pid, 'extra'.$k.'_price', 		true);
							$extra_content 	= get_post_meta($pid, 'extra'.$k.'_content', 	true);
							
							
							if(!empty($extra_price) && !empty($extra_content)):
								if(!empty($_GET['extra' . $k])):
								
									$extra_job_add[$h]['content'] 	= $extra_content;
									$extra_job_add[$h]['price'] 	= $extra_price;
									$extra_job_add[$h]['extra_nr'] 	= $k;
									$h++;
									
									$extra_job_arr['extra_job' . $pid][$h]['extra_nr'] 	= $k;
									$extra_job_arr['extra_job' . $pid][$h]['price'] 	= $extra_price;
									
									$partial_ttl += $extra_price;
								
								endif;	
							endif;
						}
					
                    ?>
                    
                    	<table width="100%">
                        	<tr>
                            <td><?php _e('Job Base Price','PricerrTheme'); ?></td>
                            <td><?php echo PricerrTheme_get_show_price($prc); ?></td>
                            </tr>
                           
                           <?php
						   		
								
								$shipping 	= get_post_meta($pid, 'shipping', 		true);						   		
								if(!empty($shipping)):
						   
						   ?> 
                            
                            <tr>
                            <td><?php _e('Shipping','PricerrTheme'); ?></td>
                            <td><?php echo PricerrTheme_get_show_price($shipping); ?></td>
                            </tr>
                            
                            <?php
							
							else: 
							
								$shipping = 0;
							
							endif;
							
							//--------------------------------------------------------------
							
								if(count($extra_job_add) > 0){
								foreach($extra_job_add as $extra_job_add_item):
							
							?>
                            <tr>
                            <td><?php echo $extra_job_add_item['content']; ?></td>
                            <td><?php echo PricerrTheme_get_show_price($extra_job_add_item['price']); ?></td>
                            </tr>
                        
                        	<?php endforeach; } ?>
                            
                            <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            </tr>
                            
                            <tr>
                            <td><b><?php _e('Total Price to Pay','PricerrTheme'); ?></b></td>
                            <td><b><?php echo PricerrTheme_get_show_price($prc + $partial_ttl + $shipping); ?></b></td>
                            </tr>
                            
                        </table>
                    
                    
                    </div></div>
                
                
                
                
                <?php		
						
									$extrs = '';
									
									if($extra_job_arr['extra_job' . $pid])
									foreach($extra_job_arr['extra_job' . $pid] as $ar_and)
									{
										$extrs .= $ar_and['extra_nr'] . '|';
										
									}
				
						
					$PricerrTheme_paypal_enable = get_option('PricerrTheme_paypal_enable');
					if($PricerrTheme_paypal_enable == "yes"):	
					
					do_action('PricerrTheme_before_paypal_link' , $pid, $extrs);
								
				?>
					<a href="<?php bloginfo('siteurl'); ?>/?pay_for_item=paypal&jobid=<?php echo $pid; ?>&extras=<?php echo $extrs; ?>" class="post_bid_btn"><?php _e('Pay by PayPal','PricerrTheme'); ?></a> &nbsp;
                	
                <?php
					
					do_action('PricerrTheme_after_paypal_link' , $pid, $extrs);
				
					endif;
				?>
                
                <!-- #################################### -->
                
                  <?php				
					$PricerrTheme_moneybookers_enable = get_option('PricerrTheme_moneybookers_enable');
					if($PricerrTheme_moneybookers_enable == "yes"):				
				?>
					<a href="<?php bloginfo('siteurl'); ?>/?pay_for_item=moneybookers&jobid=<?php echo $pid; ?>&extras=<?php echo $extrs; ?>" class="post_bid_btn"><?php _e('Pay by Moneybookers','PricerrTheme'); ?></a> &nbsp;
                
                <?php
					endif;
				?>
                
                <!-- #################################### -->
                
                  <?php				
					$PricerrTheme_alertpay_enable = get_option('PricerrTheme_alertpay_enable');
					if($PricerrTheme_alertpay_enable == "yes"):				
				?>
					<a href="<?php bloginfo('siteurl'); ?>/?pay_for_item=payza&jobid=<?php echo $pid; ?>&extras=<?php echo $extrs; ?>" class="post_bid_btn"><?php _e('Pay by Payza','PricerrTheme'); ?></a> &nbsp;
                
                <?php
					endif;
				?>
                
                <!-- #################################### -->
                
                <?php				
					$PricerrTheme_bank_enable = get_option('PricerrTheme_bank_enable');
					if($PricerrTheme_bank_enable == "yes"):				
				?>
					<a href="<?php bloginfo('siteurl'); ?>/?pay_for_item=bank&jobid=<?php echo $pid; ?>&extras=<?php echo $extrs; ?>" class="post_bid_btn"><?php _e('Pay by Bank(Offline)','PricerrTheme'); ?></a> &nbsp;
                
                <?php
					endif;
				?>
                
                <!-- #################################### -->
                 
                <?php if($crds >= ($prc + $partial_ttl + $shipping)): ?>
                <a href="<?php bloginfo('siteurl'); ?>/?pay_for_item=credits&jobid=<?php echo $pid; ?>&extras=<?php echo $extrs; ?>" class="post_bid_btn"><?php _e('Pay with Balance','PricerrTheme'); ?></a> &nbsp; 
                <?php endif; ?>
                
                <?php do_action('PricerrTheme_purchase_job_add_payment_method', $pid, $extrs); ?>
                
                </div>
               
        
        </div></div>
            

		<div id="right-sidebar">
            <ul class="xoxo">
            <?php dynamic_sidebar( 'other-page-area' ); ?>
            </ul>
        </div>


		<?php
		get_footer();
		?>