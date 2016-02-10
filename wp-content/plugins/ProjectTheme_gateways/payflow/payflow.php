<?php

add_filter('ProjectTheme_payment_methods_tabs', 					'ProjectTheme_add_new_payflow_tab');
add_filter('ProjectTheme_payment_methods_content_divs', 			'ProjectTheme_add_new_payflow_cnt');
add_filter('ProjectTheme_payment_methods_action',					'ProjectTheme_add_new_payflow_pst');
add_filter('ProjectTheme_deposit_methods', 						'ProjectTheme_add_payflow_deposit',0,1);

function ProjectTheme_add_payflow_deposit($uid = '')
{
	
				
				$ProjectTheme_payflow_enable = get_option('ProjectTheme_payflow_enable');
				if($ProjectTheme_payflow_enable == "yes"):
				
				?>
                <style>
				
				.checkout {
  width: 500px;
  margin: 0;
  padding: 15px;
  background: #f3f6fa;
  border: 1px solid;
  border-color: #c2cadb #bbc5d6 #b7c0cd;
  border-radius: 7px;
  -webkit-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.15);
  box-shadow: 0 1px 5px rgba(0, 0, 0, 0.15);
}
.checkout > p {
  zoom: 1;
}
.checkout > p:before, .checkout > p:after {
  content: '';
  display: table;
}
.checkout > p:after {
  clear: both;
}
.checkout > p + p {
  margin-top: 15px;
}

.checkout-header {
  position: relative;
  margin: -15px -15px 15px;
}

.checkout-title {
  padding: 0 15px;
  line-height: 38px;
  font-size: 13px;
  font-weight: bold;
  color: #7f889e;
  text-shadow: 0 1px rgba(255, 255, 255, 0.7);
  background: #eceff5;
  border-bottom: 1px solid #c5ccdb;
  border-radius: 7px 7px 0 0;
  background-image: -webkit-linear-gradient(top, #f5f8fb, #e9edf3);
  background-image: -moz-linear-gradient(top, #f5f8fb, #e9edf3);
  background-image: -o-linear-gradient(top, #f5f8fb, #e9edf3);
  background-image: linear-gradient(to bottom, #f5f8fb, #e9edf3);
  -webkit-box-shadow: inset 0 1px white;
  box-shadow: inset 0 1px white;
}
.checkout-title:before {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 2px;
  -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
}

.checkout-price {
  position: absolute;
  top: -14px;
  right: -14px;
  width: 40px;
  font: 14px/40px Helvetica, Arial, sans-serif;
  color: white;
  text-align: center;
  text-shadow: 0 -1px 1px rgba(0, 0, 0, 0.3);
  text-indent: -1px;
  letter-spacing: -1px;
  background: #e54930;
  border: 1px solid;
  border-color: #b33323 #ab3123 #982b1f;
  border-radius: 21px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  background-image: -webkit-linear-gradient(top, #f75a3b, #d63b29);
  background-image: -moz-linear-gradient(top, #f75a3b, #d63b29);
  background-image: -o-linear-gradient(top, #f75a3b, #d63b29);
  background-image: linear-gradient(to bottom, #f75a3b, #d63b29);
  -webkit-box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.3), 0 1px 2px rgba(0, 0, 0, 0.2);
  box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.3), 0 1px 2px rgba(0, 0, 0, 0.2);
}
.checkout-price:before {
  content: '';
  position: absolute;
  top: 3px;
  bottom: 3px;
  left: 3px;
  right: 3px;
  border: 2px solid #f5f8fb;
  border-radius: 18px;
  -webkit-box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.2), inset 0 -1px 1px rgba(0, 0, 0, 0.25), 0 -1px 1px rgba(0, 0, 0, 0.25);
  box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.2), inset 0 -1px 1px rgba(0, 0, 0, 0.25), 0 -1px 1px rgba(0, 0, 0, 0.25);
}

input {
  margin: 0;
  line-height: normal;
  font-family: inherit;
  font-size: 100%;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}

.checkout-input {
  float: left;
  padding: 0 7px;
  height: 32px;
  color: #525864;
  background: white;
  border: 1px solid;
  border-color: #b3c0e2 #bcc5e2 #c0ccea;
  border-radius: 4px;
  background-image: -webkit-linear-gradient(top, #f6f8fa, white);
  background-image: -moz-linear-gradient(top, #f6f8fa, white);
  background-image: -o-linear-gradient(top, #f6f8fa, white);
  background-image: linear-gradient(to bottom, #f6f8fa, white);
  -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1), 0 1px rgba(255, 255, 255, 0.5);
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1), 0 1px rgba(255, 255, 255, 0.5);
}
.checkout-input:focus {
  border-color: #46aefe;
  outline: none;
  -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1), 0 0 5px #46aefe;
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1), 0 0 5px #46aefe;
}
.lt-ie9 .checkout-input {
  line-height: 30px;
}

.checkout-name {
  width: 150px;
}

.checkout-card {
  width: 210px;
}

.checkout-exp,
.checkout-cvc {
  margin-left: 15px;
  width: 45px;
}

.checkout-btn {
  width: 100%;
  height: 34px;
  padding: 0;
  font-weight: bold;
  color: white;
  text-align: center;
  text-shadow: 0 -1px 1px rgba(0, 0, 0, 0.2);
  border: 1px solid;
  border-color: #1486f9 #0f7de9 #0d6acf;
  background: #1993fb;
  border-radius: 4px;
  background-image: -webkit-linear-gradient(top, #4cb1fe, #229afc 40%, #138df6);
  background-image: -moz-linear-gradient(top, #4cb1fe, #229afc 40%, #138df6);
  background-image: -o-linear-gradient(top, #4cb1fe, #229afc 40%, #138df6);
  background-image: linear-gradient(to bottom, #4cb1fe, #229afc 40%, #138df6);
  -webkit-box-shadow: inset 0 1px rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.2);
  box-shadow: inset 0 1px rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.2);
}
.checkout-btn:active {
  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
  border-color: #075bba #0c69d2 #0f7de9;
  background-image: -webkit-linear-gradient(top, #1281dc, #1593fc);
  background-image: -moz-linear-gradient(top, #1281dc, #1593fc);
  background-image: -o-linear-gradient(top, #1281dc, #1593fc);
  background-image: linear-gradient(to bottom, #1281dc, #1593fc);
  -webkit-box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.1), 0 1px rgba(255, 255, 255, 0.5);
  box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.1), 0 1px rgba(255, 255, 255, 0.5);
}

				
				</style>
                
                
                <form class="checkout">
    <div class="checkout-header">
      <h1 class="checkout-title">
        Deposit Money Through Credit Card
        <span class="checkout-price">$$$</span>
      </h1>
    </div>
    <p>
      <input type="text" class="checkout-input checkout-name" placeholder="Your name" autofocus>
      <input type="text" class="checkout-input checkout-exp" placeholder="MM">
      <input type="text" class="checkout-input checkout-exp" placeholder="YY">
    </p>
    <p>
      <input type="text" class="checkout-input checkout-card" placeholder="4111 1111 1111 1111">
      <input type="text" class="checkout-input checkout-cvc" placeholder="CVC">
    </p>
    <p>
      <input type="submit" value="Pay Now" class="checkout-btn">
    </p>
  </form>
                
                
                
                <?php
				
				endif;
}

function ProjectTheme_add_new_payflow_pst()
{
	
	if(isset($_POST['ProjectTheme_save_payflow'])):
	
	$ProjectTheme_payflow_partner_id 	= trim($_POST['ProjectTheme_payflow_partner_id']);
	$ProjectTheme_payflow_enable 	= trim($_POST['ProjectTheme_payflow_enable']);
	$ProjectTheme_payflow_merchant_login_id = $_POST['ProjectTheme_payflow_merchant_login_id'];
	$ProjectTheme_payflow_pass = $_POST['ProjectTheme_payflow_pass'];
	
	update_option('ProjectTheme_payflow_partner_id',	$ProjectTheme_payflow_partner_id);
	update_option('ProjectTheme_payflow_enable',	$ProjectTheme_payflow_enable);
	update_option('ProjectTheme_payflow_merchant_login_id',	$ProjectTheme_payflow_merchant_login_id);
	update_option('ProjectTheme_payflow_pass',	$ProjectTheme_payflow_pass);
	
	echo '<div class="saved_thing">Info saved</div>';
	
	endif;
}


function ProjectTheme_add_new_payflow_cnt()
{
	$arr = array("yes" => __("Yes",'ProjectTheme'), "no" => __("No",'ProjectTheme'));
	
?>

<div id="tabs_payflow"  >	
          
          <form method="post" action="<?php bloginfo('siteurl'); ?>/wp-admin/admin.php?page=payment-methods&active_tab=tabs_payflow">
            <table width="100%" class="sitemile-table">
    				
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td width="200"><?php _e('Enable:','ProjectTheme'); ?></td>
                    <td><?php echo ProjectTheme_get_option_drop_down($arr, 'ProjectTheme_payflow_enable'); ?></td>
                    </tr>


   <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Partner ID:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_payflow_partner_id" value="<?php echo get_option('ProjectTheme_payflow_partner_id'); ?>"/></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('PayFlow Merchant Login ID:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_payflow_merchant_login_id" value="<?php echo get_option('ProjectTheme_payflow_merchant_login_id'); ?>"/></td>
                    </tr>
                    
                    <tr>
                    <td valign=top width="22"><?php ProjectTheme_theme_bullet(); ?></td>
                    <td ><?php _e('Payflow Password:','ProjectTheme'); ?></td>
                    <td><input type="text" size="45" name="ProjectTheme_payflow_pass" value="<?php echo get_option('ProjectTheme_payflow_pass'); ?>"/></td>
                    </tr>
                    
                    
                   
        
                    <tr>
                    <td ></td>
                    <td ></td>
                    <td><input type="submit" name="ProjectTheme_save_payflow" value="<?php _e('Save Options','ProjectTheme'); ?>"/></td>
                    </tr>
            
            </table>      
          	</form>
          
          </div>

<?php	
	
}

function ProjectTheme_add_new_payflow_tab()
{
	?>
    
    	<li><a href="#tabs_payflow">PayPal Payflow </a></li>
    
    <?php	
	
}

?>