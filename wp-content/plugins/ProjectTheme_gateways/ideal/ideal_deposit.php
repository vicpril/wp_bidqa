<?php


function ProjectTheme_add_ideal_basic_deposit($uid = '')
{
	
				
				$ProjectTheme_ideal_basic_enable = get_option('ProjectTheme_ideal_basic_enable');
				if($ProjectTheme_ideal_basic_enable == "yes"):
				
				?>
                
                
                <strong><?php _e('Deposit money by iDeal','pt_gateways'); ?></strong><br/><br/>
                
                <form method="post" action="<?php bloginfo('siteurl'); ?>/?p_action=ideal_basic_deposit_pay">
                <?php _e("Amount to deposit:","pt_gateways"); ?>  <input type="text" size="10" name="amount" /> <?php echo projectTheme_currency(); ?>
                &nbsp; &nbsp; <input type="submit" name="deposit" value="<?php _e('Deposit','pt_gateways'); ?>" /></form>
    			<br/><br/>
                <?php endif; 
	
	
}


?>