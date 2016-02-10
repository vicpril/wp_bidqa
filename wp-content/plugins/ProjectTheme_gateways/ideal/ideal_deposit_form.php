<?php

function PT_dep_basic_form_ideal()
{
	
	
	global $wp_query, $wpdb, $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;


	$business = get_option('ProjectTheme_payfast_id');
	if(empty($business)) die('ERROR. Please input your PayFast ID.');
	//-------------------------------------------------------------------------
	
		
	
		$my_total = $_GET['dep_ideal_basic'];

 
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
<head><title>Processing iDeal Payment...</title></head>
<body onLoad="document.frmPay.submit();">
<center><h3><?php _e('Please wait, your order is being processed...', 'ProjectTheme'); ?></h3></center>

	
    
    <form id="frmPay" action="https://idealtest.secure-ing.com" method="post" name="frmPay">
	<input type="hidden" name="merchantID" value="<?php echo get_option('ProjectTheme_ideal_basic_id') ?>"> 
	<input type="hidden" name="subID" value="0"> 
	<input type="hidden" name="amount" value="<?php echo $my_total ?>"> 
	<input type="hidden" name="purchaseID" value="xxxx"> 
	<input type="hidden" name="language" value="nl"> 
	<input type="hidden" name="currency" value="EUR"> 
	<input type="hidden" name="description" value="iDEAL Basic purchase"> 
	<input type="hidden" name="itemNumber1" value="xxxx"> 
	<input type="hidden" name="itemDescription1" value="xxxxx"> 
	<input type="hidden" name="itemQuantity1" value="xxxx"> 
	<input type="hidden" name="itemPrice1" value="xxxxx"> 
	<input type="hidden" name="paymentType" value="ideal"> 
	<input type="hidden" name="validUntil" value=" 2009-01-01T12:00:00:0000Z"> 
	<input type="hidden" name="PSPID" value="PSP-id"> 
	<input type="hidden" name="accepturl" value="http://www.hosturl.nl/path/accept_url.php"> 
	<input type="hidden" name="declineurl" value="http://www.hosturl.nl/path/decline_url.php"> 
	<input type="hidden" name="exceptionurl" value="http://www.hosturl.nl/path/exception_url.php"> 
	<input type="hidden" name="cancelurl" value="http://www.hosturl.nl/path/cancel_url.php"> 
	<input type="hidden" name="homeurl" value="http://www.hosturl.nl/home"> 
	 
</form>
   
 

</body>
</html>	

<?php		
	
}

?>