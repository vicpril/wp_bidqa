<?php
/*
Plugin Name: ProjectTheme Dispute Feature 
Plugin URI: http://sitemile.com/
Description: Adds the dispute feature for your Project Bidding Theme from sitemile
Author: SiteMile.com
Author URI: http://sitemile.com/
Version: 1.0
Text Domain: pt_disputes
*/

include 'my_account/disputes.php';
include 'first_run.php';

add_filter('ProjectTheme_admin_menu_after_orders', 	'ProjectTheme_dispute_module_init');
add_action('the_content',							'ProjectTheme_display_my_account_disputes');
add_action('ProjectTheme_my_account_main_menu',		'ProjectTheme_add_dispute_user_menu');
register_activation_hook( __FILE__, 'ProjectTheme_dispute_myplugin_activate' );

/*************************************************************
*
*	ProjectTheme (c) sitemile.com - function
*
**************************************************************/

function ProjectTheme_add_dispute_user_menu()
{
?>	
	<?php
    global $current_user, $wpdb, $wp_query;
    get_currentuserinfo();
    $uid = $current_user->ID;

     $querystr = "
                                        SELECT * 
                                        FROM ".$wpdb->prefix."project_disputes
                                        WHERE `solution`=0
                                        AND  `defendant` = '$uid' 
                                        ORDER BY `datemade` DESC";
                        
    $open_disputes_on_me = $wpdb->get_results($querystr);

      $open_disputes_on_me = count($open_disputes_on_me);
          
        if($open_disputes_on_me > 0)
          $scn = "<span class='notif_a'>".$open_disputes_on_me."</span>";
  ?>
    <li><a href="<?php echo get_permalink(get_option('ProjectTheme_my_account_disputes_id')); ?>"><?php printf(__("Disputes %s",'ProjectTheme'), $scn);//_e("Disputes",'ProjectTheme');?></a></li>
    	
<?php	
}


/*************************************************************
*
*	ProjectTheme (c) sitemile.com - function
*
**************************************************************/

function ProjectTheme_display_my_account_disputes( $content = '' ) 
{
	if ( preg_match( "/\[project_theme_my_account_disputes\]/", $content ) ) 
	{
		ob_start();
		ProjectTheme_my_account_disputes_area_function();
		$output = ob_get_contents();
		ob_end_clean();
		$output = str_replace( '$', '\$', $output );
		return preg_replace( "/(<p>)*\[project_theme_my_account_disputes\](<\/p>)*/", $output, $content );
		
	} 
	else {
		return $content;
	}
}


//------------------------------------------------------------------

function ProjectTheme_dispute_module_init()
{
	
	add_submenu_page('project_theme_mnu', __('Disputes','ProjectTheme'), '<img src="'.get_bloginfo('template_url').'/images/arbiter_icon.png" border="0" /> '.__('Disputes','ProjectTheme'),'10', 'disputes', 'projectTheme_disputes_screen');	
	
}



/******************************************************************
*
*	Admin Menu - New Function - sitemile[at]sitemile.com
*	developed by Andrei Saioc - andreisaioc[at]gmail.com
*
*******************************************************************/
function projectTheme_disputes_screen()
{
	global $menu_admin_project_theme_bull, $wpdb, $wp_query;

	if($_POST){
	    $disp_id = $_POST['disp_id'];
	    $closedon = current_time('timestamp',0);
	    $admin_comment = $_POST['admin_comment'];	    
	    $winner = $_POST['winner'];	    

	    $s = "update ".$wpdb->prefix."project_disputes SET `solution`=1, `winner`=$winner, `closedon`=$closedon, `admin_comment`='$admin_comment' WHERE `id`=$disp_id";    
	    $wpdb->query($s);
	}
	
	$querystr = "
	                                SELECT * 
	                                FROM ".$wpdb->prefix."project_disputes
	                                WHERE `solution`=0	                                 
	                                ORDER BY `datemade` DESC";
	                
	$open_disputes = $wpdb->get_results($querystr);

	$querystr = "
	                                SELECT * 
	                                FROM ".$wpdb->prefix."project_disputes
	                                WHERE `solution`=1	                                
	                                ORDER BY `datemade` DESC";
	                
	$closed_disputes = $wpdb->get_results($querystr);


	echo '<div class="wrap">';
	echo '<div class="icon32" id="icon-options-general-arb"><br/></div>';	
	echo '<h2 class="my_title_class_sitemile">ProjectTheme Disputes</h2>';
	?>
    
        <div id="usual2" class="usual"> 
  <ul> 
    <li><a href="#tabs1" class="selected">Current Open Disputes</a></li> 
    <li><a href="#tabs2">Closed Disputes</a></li> 
  </ul> 
  <div id="tabs1" style="display: block; ">
  	<?php 
  		if ($open_disputes) {
  			echo '<table class="open_disputes">';
                echo '<tr>';
                    echo '<td>Date create</td>';
                    echo '<td>Initiator</td>';
                    echo '<td>Defendant</td>';
                    echo '<td>Comment</td>';                                
                    echo '<td>Winner</td>';                                
                    echo '<td>Admin comment</td>';
                    echo '<td>Action</td>';
                echo '</tr>';
                foreach ($open_disputes as $key => $open_dispute) {
	                $Initiator = get_userdata($open_dispute->initiator);
	                $Defendant = get_userdata($open_dispute->defendant);
	                $user_winner = get_userdata($open_dispute->winner);
	                echo '<tr>';
	                echo '<form method="POST">';
	                    echo '<td>'.date('m-d-Y H:i',$open_dispute->datemade).'</td>';
	                    echo '<td>'.$Initiator->user_login.'</td>';
	                    echo '<td>'.$Defendant->user_login.'</td>';
	                    echo '<td class="disp_com">'.$open_dispute->comment.'</td>';                                    
	                    echo '<td><label>'.$Initiator->user_login.'<input type="radio" name="winner" value="'.$open_dispute->initiator.'"></label><label>'.$Defendant->user_login.'<input type="radio" name="winner" value="'.$open_dispute->defendant.'"></label></td>';
	                    echo '<td> <textarea name="admin_comment"></textarea></td>';                                    
	                    echo '<td> <input hidden name="disp_id" value="'.$open_dispute->id.'"><input type="submit" value="Judgment"></td>';
	                echo '</form>';
	                echo '</tr>';
                }
            echo '</table>';
  		}
  		else{
  			echo "There are no open disputes.";
  		}
  	?>
    	
        
  </div> 
  <div id="tabs2" style="display: none; ">
  	<?php 
  		if ($closed_disputes) {
  			echo '<table class="closed_disputes">';
                            echo '<tr>';
                                echo '<td>Date create</td>';
                                echo '<td>Defendant</td>';
                                echo '<td>Comment</td>';
                                echo '<td>Winner</td>';
                                echo '<td>Closedon</td>';
                                echo '<td>Verdict</td>';
                            echo '</tr>';
                            foreach ($closed_disputes as $key => $closed_dispute) {
                                $user = get_userdata($closed_dispute->defendant);
                                $user_winner = get_userdata($closed_dispute->winner);
                                echo '<tr>';
                                    echo '<td>'.date('m-d-Y H:i',$closed_dispute->datemade).'</td>';
                                    echo '<td>'.$user->user_login.'</td>';
                                    echo '<td class="disp_com">'.$closed_dispute->comment.'</td>';
                                    echo '<td>'.$user_winner->user_login.'</td>';
                                    echo '<td>'.date('m-d-Y H:i',$closed_dispute->closedon).'</td>';
                                    echo '<td class="disp_com">'.$closed_dispute->admin_comment.'</td>';
                                echo '</tr>';
                            }
                            echo '</table>';
  		}
  		else{
  			echo "There are no closed disputes";
  		}
  	?>
  	
  </div> 
        </div> 
    
    
    <?php	
	
	echo '</div>';
}
//----------------------------



?>