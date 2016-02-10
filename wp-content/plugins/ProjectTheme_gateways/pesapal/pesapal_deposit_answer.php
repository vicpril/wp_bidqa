<?php

function ProjectTheme_pesapal_deposit_answer()
{
	
 
include_once('oauth.php');

$consumer_key= get_option('ProjectTheme_pesapal_key');//Register a merchant account on
                   //demo.pesapal.com and use the merchant key for testing.
                   //When you are ready to go live make sure you change the key to the live account
                   //registered on www.pesapal.com!
$consumer_secret= get_option('ProjectTheme_pesapal_secret');// Use the secret from your test
                   //account on demo.pesapal.com. When you are ready to go live make sure you 
                   //change the secret to the live account registered on www.pesapal.com!
$statusrequestAPI = 'https://www.pesapal.com/api/querypaymentstatus'; //http://demo.pesapal.com/api/querypaymentstatus';//change to      
                   //https://www.pesapal.com/api/querypaymentstatus' when you are ready to go live!
if(get_option('ProjectTheme_working_mode') == "test") 	$statusrequestAPI = 'http://demo.pesapal.com/api/querypaymentstatus';	

// Parameters sent to you by PesaPal IPN
$pesapalNotification=$_GET['pesapal_notification_type'];
$pesapalTrackingId=$_GET['pesapal_transaction_tracking_id'];
$pesapal_merchant_reference=$_GET['pesapal_merchant_reference'];

if($pesapalTrackingId!='')
{
   $token = $params = NULL;
   $consumer = new OAuthConsumer($consumer_key, $consumer_secret);
   $signature_method = new OAuthSignatureMethod_HMAC_SHA1();

   //get transaction status
   $request_status = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $statusrequestAPI, $params);
   $request_status->set_parameter("pesapal_merchant_reference", $pesapal_merchant_reference);
   $request_status->set_parameter("pesapal_transaction_tracking_id",$pesapalTrackingId);
   $request_status->sign_request($signature_method, $consumer, $token);

   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $request_status);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_HEADER, 1);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
   if(defined('CURL_PROXY_REQUIRED')) if (CURL_PROXY_REQUIRED == 'True')
   {
      $proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;
      curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
      curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
      curl_setopt ($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
   }

   $response = curl_exec($ch);

   $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
   $raw_header  = substr($response, 0, $header_size - 4);
   $headerArray = explode("\r\n\r\n", $raw_header);
   $header      = $headerArray[count($headerArray) - 1];

   //transaction status
   $elements = preg_split("/=/",substr($response, $header_size));
   $status = $elements[1];

   curl_close ($ch);
   
//UPDATE YOUR DB TABLE WITH NEW STATUS FOR TRANSACTION WITH pesapal_transaction_tracking_id $pesapalTrackingId

	$opt = get_option('prs_pesa_' . $pesapal_merchant_reference);
 
		$cust 					= $opt;
		$cust 					= explode("|",$cust);
		
		$uid				= $cust[0];
		$datemade			= $cust[1];
		 
		
		if(1): //$payment_status == "Completed"):
		
 
		
		$mc_gross 				= get_option('amnt_pesa_' . $pesapal_merchant_reference); //$_POST['amount_net']; // - $_POST['mc_fee'];

		//-----------------------------------------------------
		global $wpdb;
		$pref = $wpdb->prefix;
 
					
		
		if(count($r1) == 0)
		{
		
			
			$cr = projectTheme_get_credits($uid);
			projectTheme_update_credits($uid,$mc_gross + $cr);
			
			update_option('ProjectTheme_deposit_'.$uid.$datemade, "1");
			$reason = __("Deposit through PesaPal.","pt_gateways"); 
			projectTheme_add_history_log('1', $reason, $mc_gross, $uid);
		
			$user = get_userdata($uid);		 
		 
		}
		
		endif;

		wp_redirect(get_bloginfo('siteurl'));
    
}
 
}

?>