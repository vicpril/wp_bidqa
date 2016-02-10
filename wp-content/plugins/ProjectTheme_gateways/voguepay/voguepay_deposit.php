<?php

function ProjectTheme_add_vgspay_deposit($uid = '')
{
	
				
				$ProjectTheme_voguepay_enable = get_option('ProjectTheme_voguepay_enable');
				if($ProjectTheme_voguepay_enable == "yes"):
				
				?>
                
                
                <strong><?php _e('Deposit money by Voguepay','pt_gateways'); ?></strong><br/><br/>
                
                <form method="post" action="<?php bloginfo('siteurl'); ?>/?p_action=voguepay_deposit_pay">
                <?php _e("Amount to deposit:","pt_gateways"); ?>  <input type="text" size="10" name="amount" /> <?php echo projectTheme_currency(); ?>
                &nbsp; &nbsp; <input type="submit" name="deposit" value="<?php _e('Deposit','pt_gateways'); ?>" /></form>
    			<br/><br/>
                <?php endif; 
	
	
}


function ProjectTheme_vgspay_deposit_payment()
{
	
	global $wp_query, $wpdb, $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;


	$business = get_option('ProjectTheme_voguepay_id');
	if(empty($business)) die('ERROR. Please input your Voguepay ID.');
	//-------------------------------------------------------------------------
	
		
	
		$my_total = $_POST['amount'];

	//----------------------------------------------
	$additional_payfast = 0;
	$additional_payfast = apply_filters('ProjectTheme_filter_paypal_listing_additional', $additional_paypal, $pid);

	$total = $my_total + $additional_payfast;
	


	
	//---------------------------------
	
	$tm 			= current_time('timestamp',0);
	$cancel_url 	= get_bloginfo("siteurl").'/?p_action=vgspay_deposit_response&uid='.$uid;
	$response_url 	= get_bloginfo('siteurl').'/?p_action=vgspay_deposit_response';
	$ccnt_url		= get_permalink(get_option('ProjectTheme_my_account_page_id'));//get_bloginfo('siteurl').'/?p_action=edit_project&paid=ok&pid=' . $pid;
	$currency 		= get_option('ProjectTheme_currency');
	
	//https://www.payfast.co.za/eng/process
	//https://sandbox.payfast.co.za/eng/process
		
?>


<html>
<head><title>Processing PayFast Payment...</title></head>
<body onLoad="document.frmPay.submit();">
<center><h3><?php _e('Please wait, your order is being processed...', 'ProjectTheme'); ?></h3></center>

	
    
    <form method='POST' action='https://voguepay.com/pay/' name="frmPay" id="frmPay">

<input type='hidden' name='v_merchant_id' value='<?php echo get_option('ProjectTheme_voguepay_id'); ?>' />
<input type='hidden' name='merchant_ref' value='<?php echo $uid.'_'.$tm; ?>' />
<input type='hidden' name='memo' value='<?php echo 'Deposit Money' ?>' />

<input type='hidden' name='notify_url' value='<?php echo $response_url; ?>' />
<input type='hidden' name='success_url' value='<?php echo $ccnt_url ?>' />
<input type='hidden' name='fail_url' value='<?php echo $ccnt_url ?>' />

<input type='hidden' name='total' value='<?php echo $total; ?>' />
 

</form>


 

</body>
</html>	

<?php	
	
}

function VGSPAY_get_data2($url) {
  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch, CURLOPT_URL, $url);
  //curl_setopt($ch, CURLOPT_POST, TRUE);             // Use POST method
  //curl_setopt($ch, CURLOPT_POSTFIELDS, "var1=1&var2=2&var3=3");  // Define POST data values
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,true);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $data = curl_exec($ch);
  print_r($data);
  
  curl_close($ch);
  return $data;
}


function ProjectTheme_vgspay_deposit_response()
{
		$fch = "demo-1400243125";
		if(1) { //isset($_POST['transaction_id'])){
			//get the full transaction details as an xml from voguepay
			$xml = ('https://voguepay.com/?v_transaction_id=demo-1400243125' );
			//parse our new xml
			//$xml_elements = new SimpleXMLElement($xml);
			//create new array to store our transaction detail
			//************************************************************************
			
			// Set the URL to visit
			$url = $xml;
			
			// In this example we are referring to a page that handles xml
			$headers = array( "Content-Type: text/xml",);
			
			// Initialise Curl
			$curl = curl_init();
			if ($curl === false)
			{
				throw new Exception(' cURL init failed');
			}
			
			// Configure curl for website
			curl_setopt($curl, CURLOPT_URL, $xml);
			
			// Set up to view correct page type
			curl_setopt($curl, CURLOPT_HTTPHEADER,  $headers);
			
			// Turn on SSL certificate verfication
			curl_setopt($curl, CURLOPT_CAPATH, "/usr/local/www/vhosts/<yourdomainname>/httpdocs/cacert.pem");
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
			
			// Tell the curl instance to talk to the server using HTTP POST
			curl_setopt($curl, CURLOPT_POST, 1);
			
			// 1 second for a connection timeout with curl
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
			// Try using this instead of the php set_time_limit function call
			curl_setopt($curl, CURLOPT_TIMEOUT, 60);
			
			// Causes curl to return the result on success which should help us avoid using the writeback option
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			
			$result = curl_exec($curl);
			
			if($result == false) echo "errorare";
			echo curl_error($curl)."|".curl_errno($curl)."|";
			
			//*****************************************
			echo "Asd" . $result;
			exit;
			$transaction = array();
			//loop through the $xml_elements and populate our $transaction array
			foreach($xml_elements as $key => $value) 
			{
				$transaction[$key]=$value;
			}
			/*
			Now we have the following keys in our $transaction array
			$transaction['merchant_id'],
			$transaction['transaction_id'],
			$transaction['email'],
			$transaction['total'], 
			$transaction['merchant_ref'], 
			$transaction['memo'],
			$transaction['status'],
			$transaction['date'],
			$transaction['referrer'],
			$transaction['method']
			*/
			
			//if($transaction['total'] == 0)die('Invalid total');
			//if($transaction['status'] != 'Approved')die('Failed transaction');
			
			//------------------------------------------
			
			
			$c  	= $transaction['merchant_ref'];
			$c 		= explode('|',$c);
			
			$uid				= $c[0];
			$tm					= $c[1];
			
			//-------------------
			
				$mc_gross = $transaction['total'];
				
				$cr = projectTheme_get_credits($uid);
				projectTheme_update_credits($uid,$mc_gross + $cr);
				
				update_option('ProjectTheme_deposit_'.$uid.$datemade, "1");
				$reason = __("Deposit through Voguepay.","pt_gateways"); 
				projectTheme_add_history_log('1', $reason, $mc_gross, $uid);
			
				$user = get_userdata($uid);	
		
			mail("andreisaioc@gmail.com","vgspay", print_r($transaction, true));
			
			
			/*You can do anything you want now with the transaction details or the merchant reference.
			You should query your database with the merchant reference and fetch the records you saved for this transaction.
			Then you should compare the $transaction['total'] with the total from your database.*/
		}
		
		
		
	
}

?>