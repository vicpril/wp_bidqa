<?php
if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }

global $current_user;
get_currentuserinfo();

$PricerrTheme_admin_approve_request = get_option('PricerrTheme_admin_approve_request');

if(isset($_POST['submit_prepare_request']))
{
	$rqq = trim($_POST['request']);
	
	if(!empty($rqq))
	{
		$my_post = array();
		$my_post['post_title'] 		= $rqq;
		$my_post['post_type'] 		= 'request';
		$my_post['post_status'] 	= ($PricerrTheme_admin_approve_request == "yes" ? 'draft' : 'publish');
		$my_post['post_author'] 	= $current_user->ID;
		$pid = wp_insert_post( $my_post, true );
		
		wp_set_object_terms($pid, array($_POST['request_cat_cat']),'request_cat');
		
		wp_redirect(get_bloginfo("siteurl")."/?jb_action=submit_request");
	} else wp_redirect(get_bloginfo("siteurl")."/?jb_action=submit_request&empty_title=1");
}
else
{


get_header();

?>

	
			<div class="my_box3">
            	<div class="padd10">
            <?php
			
			if(isset($_GET['empty_title'])):
			
			?>
            
            <div class="box_title"><?php _e("Request not submitted",'PricerrTheme'); ?></div>
                <div class="box_content">   
               <?php
			   
			   _e("Your request has not been submitted for approval.<br/>Please make sure you input some words.",'PricerrTheme');
			   
			   ?>
            
            
            
            <?php else: ?>
            	<div class="box_title"><?php _e("Request Submitted",'PricerrTheme'); ?></div>
                <div class="box_content">   
               <?php
			   
			   if($PricerrTheme_admin_approve_request != "no"):
			   
					_e("Your request has been submitted for approval.",'PricerrTheme');
			   
			   else:
			   
					_e("Your request has been approved.",'PricerrTheme');
			   
			   endif;
			   ?>
               
          <?php endif; ?>     
               <div class="clear10"></div>
               
             
    </div>
			</div>
			</div>
        
        
        <div class="clear100"></div>
        <div class="clear100"></div>    
            
<?php

get_footer();


}
?>                        