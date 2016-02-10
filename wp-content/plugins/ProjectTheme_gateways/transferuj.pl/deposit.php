<?php

add_filter('ProjectTheme_deposit_methods', 'ProjectTheme_add_payju_deposit',0,1);

function ProjectTheme_add_payju_deposit($uid = '')
{
	
				
				$ProjectTheme_ruj_enable = get_option('ProjectTheme_ruj_enable');
				if($ProjectTheme_ruj_enable == "yes"):
				
				?>
                
                
                <strong><?php _e('Deposit money by Transferuj.pl','pt_gateways'); ?></strong><br/><br/>
                
                <form method="post" action="<?php bloginfo('siteurl'); ?>/?p_action=ruj_pay">
                <?php _e("Amount to deposit:","pt_gateways"); ?>  <input type="text" size="10" name="amount" /> <?php echo projectTheme_currency(); ?>
                &nbsp; &nbsp; <input type="submit" name="deposit" value="<?php _e('Deposit','pt_gateways'); ?>" /></form>
    			<br/><br/>
                <?php endif; 
	
	
}

function ProjectTheme_ruj_deposit_payment()
{
	
 
	global $wp_query, $wpdb, $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;


	$business = get_option('ProjectTheme_ruj_id');
	if(empty($business)) die('ERROR. Please input your transferuj.pl ID.');
	//-------------------------------------------------------------------------
	
		
	
		$my_total = $_POST['amount'];

	//----------------------------------------------
	$additional_payfast = 0;
	$additional_payfast = apply_filters('ProjectTheme_filter_paypal_listing_additional', $additional_paypal, $pid);

	$total = $my_total + $additional_payfast;
	

$total = $total  ;
	
	//---------------------------------
	
	$tm 			= current_time('timestamp',0);
	$cancel_url 	= get_bloginfo("siteurl").'/?p_action=ruj_deposit_response&uid='.$uid;
	$response_url 	= get_bloginfo('siteurl').'/?p_action=ruj_deposit_response';
	$ccnt_url		= get_permalink(get_option('ProjectTheme_my_account_page_id'));//get_bloginfo('siteurl').'/?p_action=edit_project&paid=ok&pid=' . $pid;
	$currency 		= get_option('ProjectTheme_currency');
	
	//https://www.payfast.co.za/eng/process
	//https://sandbox.payfast.co.za/eng/process
	$crc = rand(1,99).time();
	$post_code = '124321';
	$sec_code = get_option('ProjectTheme_ruj_code');
	
	$md5sum = md5($business.$total.($crc).$sec_code);
$notification_url = get_bloginfo('siteurl') . '/?notif_ruj_deposit=1';
		update_option('order_dep_' . 	$crc,  $uid.'_'.$tm);
?>
<?php //echo $uid.'_'.$tm; ?>

<html>
<head><title>Processing Transferuj Payment...</title></head>
<body onLoad="document.frmPay.submit();">
<center><h3><?php _e('Please wait, your order is being processed...', 'ProjectTheme'); ?></h3></center>

	 
    <form action="https://secure.transferuj.pl" method="post" accept-charset="utf-8" name="frmPay" id="frmPay">
    <input type="hidden" name="id" value="<?php echo $business ?>">
    <input type="hidden" name="kwota" value="<?php echo $total ?>">
    <input type="hidden" name="opis" value="<?php echo 'Deposit Payment'; ?>">
    <input type="hidden" name="crc" value="<?php echo ($crc) ?>">
    <input type="hidden" name="md5sum" value="<?php echo $md5sum ?>">
    <input type="hidden" name="wyn_url" value="<?php echo $notification_url ?>">
    <input type="hidden" name="wyn_email" value="<?php echo get_bloginfo('admin_email') ?>">
    <input type="hidden" name="pow_url" value="<?php echo $ccnt_url ?>">
    <input type="hidden" name="pow_url_blad" value="<?php echo $ccnt_url ?>">
    <input type="hidden" name="email" value="<?php echo $current_user->user_email ?>">
    <input type="hidden" name="nazwisko" value="FirstName">
    <input type="hidden" name="imie" value="LastName">

</form>
    
 
    
 

</body>
</html>	

<?php	
 
}


?>