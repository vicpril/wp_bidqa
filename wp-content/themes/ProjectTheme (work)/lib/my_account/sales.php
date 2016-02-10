<?php
/*****************************************************************************
*
*	copyright(c) - sitemile.com - PricerrTheme
*	More Info: http://sitemile.com/p/pricerr
*	Coder: Saioc Dragos Andrei
*	Email: andreisaioc@gmail.com
*
******************************************************************************/

function PricerrTheme_my_account_sales_area_function()
{
	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;
	
	//-------------------------------------	
	
    $pg = $_GET['pg'];
	if(!isset($pg)) $pg = 'home';	
			
			
			global $current_user;
			get_currentuserinfo();
			$uid = $current_user->ID;
			
			global $wpdb; $prefix = $wpdb->prefix;
		?>	
        

        
		<div id="content">
		<!-- page content here -->	
			 <div class="my_box3">
            	<div class="padd10">
                 
            <div class="box_title3"><?php _e("My Sales",'PricerrTheme'); ?></div>
            <div class="clear10"></div> 
            
           
            
            
            <div class="shopping_menu_dv">
       	 <ul id="shopping_menu">
            <?php
			
				$act_jb = PricerrTheme_get_number_of_active_jobs($uid);
				$del_jb = PricerrTheme_get_number_of_delivered_jobs($uid);
				$com_jb = PricerrTheme_get_number_of_completed_jobs($uid);
				$can_jb = PricerrTheme_get_number_of_cencelled_jobs($uid);

				$using_perm = PricerrTheme_using_permalinks();
	
				if($using_perm)	$sal_pg_lnk = get_permalink(get_option('PricerrTheme_my_account_sales_page_id')). "/?";
				else $sal_pg_lnk = get_bloginfo('siteurl'). "/?page_id=". get_option('PricerrTheme_my_account_sales_page_id'). "&";	
	
			
			?>
		
            
        <li><a <?php  echo ($pg == "home" ? 'class="actiove"' : ""); ?> href="<?php echo $sal_pg_lnk; ?>"><?php echo sprintf(__("Active Jobs (%s)","PricerrTheme"), $act_jb); ?></a></li>
        <li><a <?php  echo ($pg == "delivered" ? 'class="actiove"' : ""); ?> href="<?php echo $sal_pg_lnk; ?>pg=delivered"><?php echo sprintf(__("Delivered (%s)","PricerrTheme"),$del_jb); ?></a></li>
        <li><a <?php  echo ($pg == "completed" ? 'class="actiove"' : ""); ?> href="<?php echo $sal_pg_lnk; ?>pg=completed"><?php echo sprintf(__("Completed (%s)","PricerrTheme"),$com_jb); ?></a></li>
        <li><a <?php  echo ($pg == "cancelled" ? 'class="actiove"' : ""); ?> href="<?php echo $sal_pg_lnk; ?>pg=cancelled"><?php echo sprintf(__("Cancelled (%s)","PricerrTheme"),$can_jb); ?></a></li>
        </ul>
            
            
            </div>
             <div style="overflow:hidden; width:100%; float:left">  
        <?php  if($pg == "home"): ?>   
         
         
        <?php
		

		
			
			
			$s = "select distinct * from ".$prefix."job_orders orders, ".$prefix."posts posts
			 where posts.post_author='$uid' AND posts.ID=orders.pid AND orders.done_seller='0' AND 
			 orders.done_buyer='0' AND orders.date_finished='0' AND orders.closed='0' order by orders.id desc";
			 
			$r = $wpdb->get_results($s);
			
			if(count($r) == 0) echo __('No active jobs','PricerrTheme');
			else
			{
				foreach($r as $row)
				{
					PricerrTheme_show_sale($row);			
				}
			}
		
		?> 
         
        <?php elseif($pg == "delivered"): ?> 
         
         <?php
		
			global $current_user;
			get_currentuserinfo();
			$uid = $current_user->ID;
		
			global $wpdb; $prefix = $wpdb->prefix;
			
			$s = "select distinct * from ".$prefix."job_orders orders, ".$prefix."posts posts
			 where posts.post_author='$uid' AND posts.ID=orders.pid AND orders.done_seller='1' AND 
			 orders.done_buyer='0' AND orders.closed='0' order by orders.id desc";
			 
			$r = $wpdb->get_results($s);
			
			if(count($r) == 0) echo __('No delivered jobs.','PricerrTheme');
			else
			{
				foreach($r as $row)
				{
					PricerrTheme_show_sale($row);			
				}
			}
		
		?> 
         
         
         <?php elseif($pg == "cancelled"): ?> 
         
         <?php
		
			global $current_user;
			get_currentuserinfo();
			$uid = $current_user->ID;
		
			global $wpdb; $prefix = $wpdb->prefix;
			
			$s = "select distinct * from ".$prefix."job_orders orders, ".$prefix."posts posts
			 where posts.post_author='$uid' AND posts.ID=orders.pid AND orders.closed='1' order by orders.id desc";
			
			$r = $wpdb->get_results($s);
			
			if(count($r) == 0) echo __('No cancelled jobs.','PricerrTheme');
			else
			{
				foreach($r as $row)
				{
					PricerrTheme_show_sale($row);			
				}
			}
		
		?>
        
        
        <?php elseif($pg == "completed"): ?> 
         
         <?php
		
			global $current_user;
			get_currentuserinfo();
			$uid = $current_user->ID;
		
			global $wpdb; $prefix = $wpdb->prefix;
			
			$s = "select distinct * from ".$prefix."job_orders orders, ".$prefix."posts posts
			 where posts.post_author='$uid' AND posts.ID=orders.pid AND orders.done_seller='1' AND 
			 orders.done_buyer='1' AND orders.closed='0' order by orders.id desc";
			 
			$r = $wpdb->get_results($s);
			
			if(count($r) == 0) echo __('No completed jobs.','PricerrTheme');
			else
			{
				foreach($r as $row)
				{
					PricerrTheme_show_sale($row);			
				}
			}
		
		?> 
         
         
        <?php endif; ?>
        
        
        
        </div>
        </div>   </div>   </div>       
    
    
    
    <?php
	
	PricerrTheme_get_users_links();
	
}


?>