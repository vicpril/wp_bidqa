<?php

function ProjectTheme_add_qq_deposit($uid = '')
{
	
				
				$ProjectTheme_qq_enable = get_option('ProjectTheme_qq_enable');
				if($ProjectTheme_qq_enable == "yes"):
				
				?>
                
                
                <strong><?php _e('Deposit money by Quickpay','pt_gateways'); ?></strong><br/><br/>
                
                <form method="post" action="<?php bloginfo('siteurl'); ?>/?p_action=qq_deposit_pay">
                <?php _e("Amount to deposit:","pt_gateways"); ?>  <input type="text" size="10" name="amount" /> <?php echo projectTheme_currency(); ?>
                &nbsp; &nbsp; <input type="submit" name="deposit" value="<?php _e('Deposit','pt_gateways'); ?>" /></form>
    			<br/><br/>
                <?php endif; 
	
	
}

function ProjectTheme_qq_deposit_resp()
{
	if($_POST['qpstat'] == '000')
	{	
		$md5 = $_POST['md5check'];
		if(!empty($md5))
		{
			$tranid = $_GET['tranid'];
			$gr = get_option('ACT_QUICKPAY_' . $tranid, true);
			$gr = explode("_", $gr);
			
			$uid = $gr[0];
			$tm	 = $gr[1];
			
			$amount = round($_POST['amount']/100, 2);
			
			$cr = projecttheme_get_credits($uid);
			projecttheme_update_credits($uid, $amount + $cr);
			
			update_option('projecttheme_deposit_'.$uid.$tm, "1");
			$reason = __("Deposit through Quickpay.","ProjectTheme"); 
			projecttheme_add_history_log('1', $reason, $amount, $uid);
			
		}
	}	
	
}

function ProjectTheme_qq_deposit_payment()
{
	
	global $wp_query, $wpdb, $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;

 
	//-------------------------------------------------------------------------
	
		
	
		$my_total = $_POST['amount'];

 
	


	
	//---------------------------------
	
	$tm 			= current_time('timestamp',0);
	$cancel_url 	= get_bloginfo("siteurl").'/?p_action=payfast_deposit_response&uid='.$uid;
	$response_url 	= get_bloginfo('siteurl').'/?p_action=payfast_deposit_response';
	$ccnt_url		= get_permalink(get_option('ProjectTheme_my_account_page_id'));//get_bloginfo('siteurl').'/?p_action=edit_project&paid=ok&pid=' . $pid;
	$currency 		= get_option('ProjectTheme_currency');
	
	//https://www.payfast.co.za/eng/process
	//https://sandbox.payfast.co.za/eng/process
		
?>


<html>
<head><title>Processing PayFast Payment...</title></head>
<body onLoad="document.frmPay.submit();">
<center><h3><?php _e('Please wait, your order is being processed...', 'ProjectTheme'); ?></h3></center>


 <?php
	
	$md5secret 		= get_option('ProjectTheme_qq_key');
	$merchant 		= get_option('ProjectTheme_qq_id');
 	$payurl 		= 'https://secure.quickpay.dk/form/'; 
	$amount 		= $amount_quickpay *100;
	$callbackurl 	= $response_url;
	$continueurl 	= $ccnt_url;
	$cancelurl 		= $ccnt_url;
	$ordernumber	= rand(0,999).time().$uid;
	$language		= 'en';
	$protocol		= '7';
	$testmode		= '0';
	$autocapture	= '0';
	$cardtypelock 	= 'creditcard';
	$msgtype 		= 'authorize';
	$callbackurl 	= get_bloginfo('siteurl').'/?p_action=qq_deposit_response&tranid=' . $ordernumber;
	
	//---------------------
	
	update_option('ACT_QUICKPAY_' . $ordernumber, $uid.'_'.$tm);
	
	$md5check = md5($protocol . $msgtype . $merchant . $language . $ordernumber . $amount . $currency . $continueurl . $cancelurl . $callbackurl . $autocapture . $cardtypelock . $testmode . $md5secret);
	
	echo "<form id=\"frmPay\" name=\"frmPay\" action=\"$payurl\" method=\"post\">
		<input type=\"hidden\" name=\"protocol\" value=\"$protocol\"/>
		<input type=\"hidden\" name=\"msgtype\" value=\"$msgtype\"/>
		<input type=\"hidden\" name=\"merchant\" value=\"$merchant\"/>
		<input type=\"hidden\" name=\"language\" value=\"$language\"/>
		<input type=\"hidden\" name=\"ordernumber\" value=\"$ordernumber\"/>
		<input type=\"hidden\" name=\"amount\" value=\"$amount\"/>
		<input type=\"hidden\" name=\"currency\" value=\"$currency\"/>
		<input type=\"hidden\" name=\"continueurl\" value=\"$continueurl\"/>
		<input type=\"hidden\" name=\"cancelurl\" value=\"$cancelurl\"/>
		<input type=\"hidden\" name=\"callbackurl\" value=\"$callbackurl\"/>
		<input type=\"hidden\" name=\"autocapture\" value=\"$autocapture\"/>
		<input type=\"hidden\" name=\"cardtypelock\" value=\"$cardtypelock\"/>
		<input type=\"hidden\" name=\"testmode\" value=\"$testmode\"/>
		<input type=\"hidden\" name=\"md5check\" value=\"$md5check\"/>
		
		</form>";
	
	?>
 
 
</body>
</html>	

<?php	
	
}


?>