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


if(!is_user_logged_in()) { wp_redirect(get_bloginfo('siteurl')."/wp-login.php"); exit; }
//-----------

	add_filter('sitemile_before_footer', 'projectTheme_my_account_before_footer');
	function projectTheme_my_account_before_footer()
	{
		echo '<div class="clear10"></div>';		
	}

	
//---------------------------------

	global $current_user;
	get_currentuserinfo();
	$uid 	= $current_user->ID;
	$post_p = get_post($pid);
	if($post_p->post_author != $uid) { echo 'ERR. Not your project.'; exit;} 
//----------------------------------	
	
	if(isset($_POST['yes']))
	{
		global $wpdb;
		$pid 		= intval($_GET['pid']);
		$uid 		= intval($_GET['uid']);
		if(!isset($pid) || !isset($uid)) exit;
		$bids 		= "SELECT * FROM ".$wpdb->prefix."project_bids WHERE pid='$pid' AND uid='$uid' order by id DESC";
		$res  		= $wpdb->get_results($bids);
		$winner 	= $res[0]->winner;
		// remove proposal
		if($winner == 0){
			$query 	= "DELETE FROM ".$wpdb->prefix."project_bids WHERE pid='$pid' AND uid='$uid'";
			$wpdb->query($query);
		}
		// remove winner
		if($winner == 1){
			$query 	= "UPDATE ".$wpdb->prefix."project_bids SET winner='0' WHERE pid='$pid' AND uid='$uid'";
			$wpdb->query($query);
			$query 	= "DELETE from ".$wpdb->prefix."postmeta where meta_key='winner' AND meta_value='$uid' AND post_id='$pid'";
			$wpdb->query($query);	
		}
		ProjectTheme_send_email_on_remove_winer($pid,$uid,$_POST['reason']);
		wp_redirect(get_permalink($pid)/*get_bloginfo('siteurl')*/);
		exit;
	}
	
	if(isset($_POST['no']))
	{
		wp_redirect(get_permalink($pid));
		exit;			
	}
	
//==========================

get_header();

?>
<div class="page_heading_me">
	<div class="page_heading_me_inner">
    <div class="main-pg-title">
    <?php $user = get_userdata($_GET['uid']);?>
    	<div class="mm_inn"><?php  printf(__("Remove QA Engineer",'ProjectTheme'), $post_p->post_title); ?>
                     </div>
                    
 </div>


		<?php projectTheme_get_the_search_box() ?>            
                    
    </div>
</div>


<?php projecttheme_search_box_thing() ?>

<div id="main_wrapper">
		<div id="main" class="wrapper"><div class="padd10">
	
			<div class="my_box3">
            	<div class="padd10">
            
            	 
                <div class="box_content">   
               <?php
			   
			   printf(__("You are rejecting proposal from %s from a project %s",'ProjectTheme'),$user->user_login, $post_p->post_title);
			   
			   ?> 
                
                <div class="clear10"></div>
               
               <form method="post" enctype="application/x-www-form-urlencoded"> 
               <h5>Enter a reason of removing:</h5>
               <textarea name="reason"></textarea>
               <input type="submit" name="yes" value="<?php _e("Yes, Remove!",'ProjectTheme'); ?>" />
               <input type="submit" name="no"  value="<?php _e("No",'ProjectTheme'); ?>" />
                
               </form>
    </div>
			</div>
			</div>
        
        
        <div class="clear100"></div>
            
    </div>
        </div>
        </div>
                
<?php
get_footer();
?>                        