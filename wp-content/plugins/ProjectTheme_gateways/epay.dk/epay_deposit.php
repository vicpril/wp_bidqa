<?php

function ProjectTheme_add_epay_deposit($uid = '')
{
	
				
				$ProjectTheme_epay_enable = get_option('ProjectTheme_epay_enable');
				if($ProjectTheme_epay_enable == "yes"):
				
				?>
                
                
                <strong><?php _e('Deposit money by ePay.dk','pt_gateways'); ?></strong><br/><br/>
                
                <form method="post" action="<?php bloginfo('siteurl'); ?>/?p_action=epay_deposit_pay">
                <?php _e("Amount to deposit:","pt_gateways"); ?>  <input type="text" size="10" name="amount" /> <?php echo projectTheme_currency(); ?>
                &nbsp; &nbsp; <input type="submit" name="deposit" value="<?php _e('Deposit','pt_gateways'); ?>" /></form>
    			<br/><br/>
                <?php endif; 
	
	
}

function ProjectTheme_epay_deps_deposit_payment_epay()
{
	
		$c  	= $_GET['orderid'];
		$c = get_option('hsh_' . $c);
		
		$c 		= explode('_',$c);
		
		$uid				= $c[0];
		$tm					= $c[1];
		
		//mail('andreisaioc@gmail.com','get',print_r($_GET, true));
		//mail('andreisaioc@gmail.com','post',print_r($_POST, true));
		
		//-------------------
		
			$mc_gross = round($_GET['amount']/100,2);
			
			$cr = projectTheme_get_credits($uid);
			projectTheme_update_credits($uid,$mc_gross + $cr);
			
			update_option('ProjectTheme_deposit_'.$uid.$datemade, "1");
			$reason = __("Deposit through ePay.","pt_gateways"); 
			projectTheme_add_history_log('1', $reason, $mc_gross, $uid);
		
			$user = get_userdata($uid);	
	
	
}

function ProjectTheme_eps_deposit_payment_epay()
{
	
	global $wp_query, $wpdb, $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;


	$business = get_option('ProjectTheme_epay_id');
	if(empty($business)) die('ERROR. Please input your epay account number.');
	//-------------------------------------------------------------------------
	
		
	
		$my_total = $_POST['amount'];

	//----------------------------------------------
	$additional_payfast = 0;
	$additional_payfast = apply_filters('ProjectTheme_filter_paypal_listing_additional', $additional_paypal, $pid);

	$total = $my_total + $additional_payfast;
	


	
	//---------------------------------
	
	$tm 			= current_time('timestamp',0);
	$cancel_url 	= get_bloginfo("siteurl").'/?p_action=epay_deposit_response&uid='.$uid;
	$response_url 	= get_bloginfo('siteurl').'/?p_action=epay_deposit_response';
	$ccnt_url		= get_permalink(get_option('ProjectTheme_my_account_page_id'));//get_bloginfo('siteurl').'/?p_action=edit_project&paid=ok&pid=' . $pid;
	$currency 		= get_option('ProjectTheme_currency');
 
	$hsh = time(). $uid;	
	update_option('hsh_' . $hsh,  $uid.'_'.$tm);
?>


<html>
<head><title>Processing ePay Payment...</title></head>
<body onLoad="document.frmPay.submit();">
<center><h3><?php _e('Please wait, your order is being processed...', 'ProjectTheme'); ?></h3></center>

	
    <form action="https://ssl.ditonlinebetalingssystem.dk/integration/ewindow/Default.aspx" method="post" name="frmPay" id="frmPay">
    <input type="hidden" name="merchantnumber" value="<?php echo get_option('ProjectTheme_epay_id'); ?>">
    <input type="hidden" name="amount" value="<?php echo $total; ?>"> 
    <input type="hidden" name="currency" value="<?php echo $currency ?>">
    <input type="hidden" name="windowstate" value="3">  
    
    <input type="hidden" name="callbackurl" value="<?php echo $response_url; ?>">
    <input type="hidden" name="cancelurl" value="<?php echo $ccnt_url; ?>">
    <input type="hidden" name="accepturl" value="<?php echo $ccnt_url; ?>">
    <input type="hidden" name="description" value="<?php _e('Money deposit','ProjectTheme') ?>">
    <input type="hidden" name="orderid" value="<?php echo $hsh; ?>">
    
    
	</form>
    
    
  

</body>
</html>	

<?php	
	
}




?>