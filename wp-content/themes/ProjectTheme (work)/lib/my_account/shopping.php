<?php
/*****************************************************************************
*
*	copyright(c) - sitemile.com - PricerrTheme
*	More Info: http://sitemile.com/p/pricerr
*	Coder: Saioc Dragos Andrei
*	Email: andreisaioc@gmail.com
*
******************************************************************************/

function PricerrTheme_my_account_shopping_area_function()
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
			
			$act_nr = PricerrTheme_shooping_active_nr($uid);
			$rev_nr = PricerrTheme_shooping_review_nr($uid);
			$can_nr = PricerrTheme_shooping_cancelled_nr($uid);
			$com_nr = PricerrTheme_shooping_completed_nr($uid);
			
			$using_perm = PricerrTheme_using_permalinks();
	
			if($using_perm)	$shp_pg_lnk = get_permalink(get_option('PricerrTheme_my_account_shopping_page_id')). "/?";
			else $shp_pg_lnk = get_bloginfo('siteurl'). "/?page_id=". get_option('PricerrTheme_my_account_shopping_page_id'). "&";	
	
			
		?>	
        
              
        
		<div id="content">
		<!-- page content here -->	
		<div class="my_box3">
            	<div class="padd10">
                	
            <div class="box_title3"><?php _e("My Shopping",'PricerrTheme'); ?></div> 
            <div class="clear10"></div> 
            
             
                	 
            
            <div class="shopping_menu_dv">
            <ul id="shopping_menu">
         <li><a <?php  echo ($pg == "home" ? 'class="actiove"' : ""); ?> href="<?php echo $shp_pg_lnk; ?>"><?php echo sprintf(__("Active Jobs (%s)","PricerrTheme"),$act_nr ); ?></a></li>
         <li><a <?php  echo ($pg == "pending" ? 'class="actiove"' : ""); ?> href="<?php echo $shp_pg_lnk; ?>pg=pending"><?php echo sprintf(__("Pending my Review (%s)","PricerrTheme"),$rev_nr); ?></a></li>
         <li><a <?php  echo ($pg == "cancelled" ? 'class="actiove"' : ""); ?> href="<?php echo $shp_pg_lnk; ?>pg=cancelled"><?php echo sprintf(__("Cancelled (%s)","PricerrTheme"),$can_nr ); ?></a></li>
         <li><a <?php  echo ($pg == "completed" ? 'class="actiove"' : ""); ?> href="<?php echo $shp_pg_lnk; ?>pg=completed"><?php echo sprintf(__("Completed (%s)","PricerrTheme"),$com_nr ); ?></a></li>
            </ul>
            
            
            </div>
             <div style="overflow:hidden; width:100%; float:left">  
        <?php  if($pg == "home"): ?>   
         
        <?php
		
			
		
			global $wpdb; $prefix = $wpdb->prefix;
			$s = "select * from ".$prefix."job_orders where uid='$uid' AND done_seller='0' AND done_buyer='0' AND date_finished='0' AND closed='0'
			 order by id desc";
			$r = $wpdb->get_results($s);
			
			if(count($r) == 0) echo __('No active jobs','PricerrTheme');
			else
			{
				foreach($r as $row)
				{
					PricerrTheme_show_bought($row);			
				}
			}
		
		?> 
         
        <?php elseif($pg == "pending"): ?> 
         
         <?php
		
			global $current_user;
			get_currentuserinfo();
			$uid = $current_user->ID;
		
			global $wpdb; $prefix = $wpdb->prefix;
			$s = "select * from ".$prefix."job_orders where uid='$uid' AND done_seller='1' AND done_buyer='0' AND closed='0' 
			order by id desc";
			$r = $wpdb->get_results($s);
			
			if(count($r) == 0) echo __('No pending review jobs.','PricerrTheme');
			else
			{
				foreach($r as $row)
				{
					PricerrTheme_show_bought($row);			
				}
			}
		
		?> 
         
         
         <?php elseif($pg == "cancelled"): ?> 
         
         <?php
		
			global $current_user;
			get_currentuserinfo();
			$uid = $current_user->ID;
		
			global $wpdb; $prefix = $wpdb->prefix;
			$s = "select * from ".$prefix."job_orders where uid='$uid' AND closed='1' order by id desc";
			$r = $wpdb->get_results($s);
			
			if(count($r) == 0) echo __('No cancelled jobs.','PricerrTheme');
			else
			{
				foreach($r as $row)
				{
					PricerrTheme_show_bought($row);			
				}
			}
		
		?>
        
        
        <?php elseif($pg == "completed"): ?> 
         
         <?php
		
			global $current_user;
			get_currentuserinfo();
			$uid = $current_user->ID;
		
			global $wpdb; $prefix = $wpdb->prefix;
			$s = "select * from ".$prefix."job_orders where uid='$uid' AND completed='1' order by id desc";
			$r = $wpdb->get_results($s);
			
			if(count($r) == 0) echo __('No completed jobs.','PricerrTheme');
			else
			{
				foreach($r as $row)
				{
					PricerrTheme_show_bought($row);			
				}
			}
		
		?> 
         
         
        <?php endif; ?>
        
        
        
        </div>
        </div>    </div>    </div>    
    
    
    
    
    
    <?php
	
	PricerrTheme_get_users_links();
	
}


?>