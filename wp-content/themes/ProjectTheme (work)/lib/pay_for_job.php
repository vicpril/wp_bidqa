<?php
/*****************************************************************************
*
*	copyright(c) - sitemile.com - PricerrTheme
*	More Info: http://sitemile.com/p/pricerr
*	Coder: Saioc Dragos Andrei
*	Email: andreisaioc@gmail.com
*
******************************************************************************/

function PricerrTheme_pay_for_job_area_function()
{
	global $current_user, $wpdb;
	get_currentuserinfo();
	$uid = $current_user->ID;
	
	$pid = trim($_GET['jobid']);
	
	$post = get_post($_GET['jobid']);
	$jbnm = PricerrTheme_wrap_the_title($post->post_title, $pid);
	$prc = get_option('PricerrTheme_new_job_feat_listing_fee');
	
	if(get_post_meta($pid,'featured',true) != "1") $prc = 0;
	
	
	$PricerrTheme_new_job_listing_fee = get_option('PricerrTheme_new_job_listing_fee');
	$prc += $PricerrTheme_new_job_listing_fee;
	
	?>
	
	<div id="content">
    
    <div class="box_title"><?php echo sprintf(__('Pay for job: %s','PricerrTheme'), $jbnm); ?></div>
    <div class="box_content">
    
    <span class="skl_pay_feat">
    <?php echo sprintf(__('You are about to pay for the listing fees for your new job. <br/>The fee is <b>%s</b>. Please use the following payment methods.','PricerrTheme'), PricerrTheme_get_show_price($prc)); ?>
    </span>
    <br/><br/>
    
    <?php
		
		$PricerrTheme_paypal_enable = get_option('PricerrTheme_paypal_enable');
		if($PricerrTheme_paypal_enable == "yes")
		echo '<a class="payment_feat" href="'.get_bloginfo('siteurl').'/?jb_action=pay_featured&method=paypal&jobid='.$pid.'">'. __('Pay with PayPal','PricerrTheme').'</a> ';
	
	?>
    
    <?php
		
		$PricerrTheme_moneybookers_enable = get_option('PricerrTheme_moneybookers_enable');
		if($PricerrTheme_moneybookers_enable == "yes")
		echo '<a class="payment_feat" href="'.get_bloginfo('siteurl').'/?jb_action=pay_featured&method=moneybookers&jobid='.$pid.'">'. __('Pay with Moneybookers','PricerrTheme').'</a> ';
	
	?>
    
    <?php
		
		$PricerrTheme_alertpay_enable = get_option('PricerrTheme_alertpay_enable');
		if($PricerrTheme_alertpay_enable == "yes")
		echo '<a class="payment_feat" href="'.get_bloginfo('siteurl').'/?jb_action=pay_featured&method=payza&jobid='.$pid.'">'. __('Pay with Payza','PricerrTheme').'</a> ';
		
		
		$PricerrTheme_get_credits = PricerrTheme_get_credits($uid);
		
		if($PricerrTheme_get_credits >= $prc)
		echo '<a class="payment_feat" href="'.get_bloginfo('siteurl').'/?jb_action=pay_featured_credits&jobid='.$pid.'">'. __('Pay by Virtual Currency','PricerrTheme').'</a> ';
		
		do_action('PricerrTheme_pay_for_featured_job', $pid);
		
	?>
     
 
    </div>
    </div>
    
    
    <div id="right-sidebar">
    	<ul class="xoxo">
        	<?php dynamic_sidebar( 'other-page-area' ); ?>
        </ul>
    </div>
    
    <?php
}

?>