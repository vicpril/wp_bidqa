<?php
/***************************************************************************
*
*	ProjectTheme - copyright (c) - sitemile.com
*	The only project theme for wordpress on the world wide web.
*
*	Coder: Andrei Dragos Saioc
*	Email: sitemile[at]sitemile.com | andreisaioc[at]gmail.com
*	More info about the theme here: http://sitemile.com/products/wordpress-project-freelancer-theme/
*	since v1.2.5.3
*
***************************************************************************/

function ProjectTheme_my_account_disputes_area_function()
{
        $no_comment_admin=0;
        $no_comment=0;
		global $current_user, $wpdb, $wp_query;
		get_currentuserinfo();
		$uid = $current_user->ID;

        if($_POST){
            if ($_POST['disp_id']){

                $disp_id = $_POST['disp_id'];
                $closedon = current_time('timestamp',0);
                $admin_comment = trim($_POST['admin_comment']);
                if(!$admin_comment){
                    $no_comment_admin=1;    
                }
                else{
                    $no_comment_admin=0;    
                    $winner = $_POST['winner'];     
                    if(!$winner){
                        $s = "update ".$wpdb->prefix."project_disputes SET `solution`=1, `closedon`=$closedon, `admin_comment`='$admin_comment' WHERE `id`=$disp_id";    
                        $wpdb->query($s);        
                    }
                    else{
                        $s = "update ".$wpdb->prefix."project_disputes SET `solution`=1, `winner`=$winner, `closedon`=$closedon, `admin_comment`='$admin_comment' WHERE `id`=$disp_id";    
                        $wpdb->query($s);    
                    }
                    
                }
                
                

            }
            else{
                $initiator = $_POST['initiator'];
                $datemade = current_time('timestamp',0);
                $comment = trim($_POST['comment']);
                $pid_defendant = explode('/', $_POST['defendant']);
                $pid = $pid_defendant[0];
                $defendant = $pid_defendant[1];
                if ($comment){
                    $no_comment=0;
                    $s = "insert into ".$wpdb->prefix."project_disputes (initiator,pid,datemade,solution,winner,closedon,comment,defendant,admin_comment)
                    values('$initiator','$pid','$datemade',0,0,0,'$comment','$defendant','')";    
                    if ($wpdb->query($s)) {
                        $created_success=1;
                    }
                    else{
                        $created_success=0;
                    }
                }
                else{                    
                    $no_comment=1;
                }
            }
        } ?>
<div id="content" class="account-main-area">
            <?php if ($created_success){echo '<div class="saved_thing">Dispute created.</div>';} ?>
            <?php if ($no_comment){echo '<div class="errrs">You cannot leave the dispute comment blank!</div>';} ?>            
            <?php if ($no_comment_admin){echo '<div class="errrs">You cannot leave the Comment for closing dispute blank!</div>';} ?>

            
            
        <div class="my_box3">
            <div class="padd10">
        
            <div class="box_title"><?php _e("Create Dispute", "ProjectTheme"); ?></div>
            <div class="box_content"> 
            <?php 
                if (ProjectTheme_is_user_business($uid)) {
//                    $querystr = "
//                                    SELECT distinct wposts.* 
//                                    FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta 
//                                    WHERE wposts.post_author='$uid' 
//                                    AND  wposts.ID = wpostmeta.post_id 
//                                    AND wpostmeta.meta_key = 'closed' 
//                                    AND wpostmeta.meta_value = '0' 
//                                    AND wposts.post_status = 'publish' 
//                                    AND wposts.post_type = 'project'
//                                    ORDER BY wposts.post_date DESC";
                    $querystr = "
                                    SELECT distinct wposts.* 
                                    FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta 
                                    WHERE wposts.post_author='$uid' 
                                    AND  wposts.ID = wpostmeta.post_id 
                                    AND wposts.post_status = 'publish' 
                                    AND wposts.post_type = 'project'
                                    ORDER BY wposts.post_date DESC";
                    
                    $r = $wpdb->get_results($querystr);
                    
                    foreach($r as $row)
                    {
                        $pid = $row->ID;
                        $ar=1;
                        $bids = projectTheme_get_winner_bid($pid,$ar);                                                       

                        
                        
                        foreach ($bids as $key => $bid) {                   
                                $user = get_userdata($bid->uid);
                                $Defendants[$pid][$bid->uid]['user_name']=$user->user_login;                                    
                                $Defendants[$pid][$bid->uid]['post_title']=get_the_title($pid);
                            }   
                            
                    }
                    
                }
                elseif (ProjectTheme_is_user_provider($uid)) {
                   $querystr = "
                                   SELECT * 
                                   FROM ".$wpdb->prefix."project_bids
                                   WHERE `uid`='$uid' 
                                   AND  `winner` = '1'
                                   ORDER BY `date_made` DESC";
                   
                   $r = $wpdb->get_results($querystr);
                   
                   foreach($r as $row)
                   {
                       $pid = $row->pid;
                       $post = get_post($pid);
                       $user = get_userdata($post->post_author);                                                      
                       $Defendants[$pid][$post->post_author]['user_name']=$user->user_login;
                       $Defendants[$pid][$post->post_author]['post_title']=get_the_title($pid);                        
                           
                   }





                }
            ?>

                <form method="POST">
                    <input hidden name="initiator" value="<?php echo $uid; ?>">        
                    <?php //var_dump($Defendants); ?>                
                    <label>Defendant
                            <?php if (isset($Defendants)) {?>
                        <select name="defendant">
                            <?php 
                                foreach ($Defendants as $pid => $Defendant) {
                                    foreach ($Defendant as $f_uid => $Def) {
                                        echo '<option value="'.$pid.'/'.$f_uid.'">'.$Def['post_title'].' - '.$Def['user_name'].'</option>';
                                    }                                        
                                }
                            ?>    
                        </select>
                            <?php } ?>
                    </label>
                        <?php if (!isset($Defendants)) {
                            echo '<p>'. __("There are no available users for disput", "ProjectTheme") . '</p>';
                        }?>
                        
                    <br>
                    <label class="disput_comment">Comment
                        <textarea class="disput_comment" name="comment"></textarea>
                    </label><br>
                    <input type="submit" value="Create Dispute">
                </form>                         
            
            
            
            </div>
            </div>
            </div>

<?php
        $querystr = "
                                        SELECT * 
                                        FROM ".$wpdb->prefix."project_disputes
                                        WHERE `solution`=0
                                        AND  `initiator` = '$uid' 
                                        ORDER BY `datemade` DESC";
                        
        $open_disputes = $wpdb->get_results($querystr);

        $querystr = "
                                        SELECT * 
                                        FROM ".$wpdb->prefix."project_disputes
                                        WHERE `solution`=0
                                        AND  `defendant` = '$uid' 
                                        ORDER BY `datemade` DESC";
                        
        $open_disputes_on_me = $wpdb->get_results($querystr);

        $querystr = "
                                        SELECT * 
                                        FROM ".$wpdb->prefix."project_disputes
                                        WHERE `solution`=1
                                        AND  `initiator` = '$uid' 
                                        ORDER BY `datemade` DESC";
                        
        $closed_disputes = $wpdb->get_results($querystr);  

        $querystr = "
                                        SELECT * 
                                        FROM ".$wpdb->prefix."project_disputes
                                        WHERE `solution`=1
                                        AND  `defendant` = '$uid' 
                                        ORDER BY `datemade` DESC";
                        
        $closed_disputes_on_me = $wpdb->get_results($querystr);        
        
        
		
?>
<div class="clear10"></div>
    	
            <div class="my_box3">
            	<div class="padd10">
            
            	<div class="box_title"><?php _e("Open Disputes", "ProjectTheme"); ?></div>

                <div class="box_content"> 
                <div class="box_title"><?php _e("As Initiator", "ProjectTheme"); ?></div>
            	
                    <?php
                    
                        if ($open_disputes) {
                            echo '<table class="open_disputes">';
                            echo '<tr>';
                                echo '<td>Date create</td>';
                                echo '<td>Defendant</td>';
                                echo '<td>Comment</td>';   
                                //echo '<td>Winner</td>';                                
                                echo '<td>Comment for closing this dispute</td>';
                                echo '<td>Action</td>';                             
                            echo '</tr>';
                            foreach ($open_disputes as $key => $open_dispute) {
                                $user = get_userdata($open_dispute->defendant);
                                $user_winner = get_userdata($open_dispute->winner);
                                echo '<tr>';
                                    echo '<form method="POST">';
                                    //echo '<td>'.date('m-d-Y H:i',$open_dispute->datemade).'</td>';
                                    echo '<td class="conv_time">'.$open_dispute->datemade.'</td>';
                                    echo '<td>'.$user->user_login.'</td>';
                                    echo '<td class="disp_com">'.$open_dispute->comment.'</td>';  
                                    //echo '<td><label>'.$current_user->user_login.'<input type="radio" name="winner" value="'.$open_dispute->initiator.'"></label><label>'.$user->user_login.'<input type="radio" name="winner" value="'.$open_dispute->defendant.'"></label></td>';
                                    echo '<td> <textarea name="admin_comment"></textarea></td>';                                    
                                    echo '<td> <input hidden name="disp_id" value="'.$open_dispute->id.'"><input type="submit" value="Close"></td>';                                  
                                    echo '</form>';
                                echo '</tr>';
                            }
                            echo '</table>';                            
                        }
                        else{
                            _e('There are no open disputes.','ProjectTheme');
                        }
                    ?>               
                
                
                </div>

                <div class="box_content"> 
                <div class="box_title"><?php _e("As Defendant", "ProjectTheme"); ?></div>
                
                    <?php
                    
                        if ($open_disputes_on_me) {
                            echo '<table class="open_disputes">';
                            echo '<tr>';
                                echo '<td>Date create</td>';
                                echo '<td>Initiator</td>';
                                echo '<td>Comment</td>';                                
                            echo '</tr>';
                            foreach ($open_disputes_on_me as $key => $open_dispute) {
                                $user = get_userdata($open_dispute->initiator);
                                $user_winner = get_userdata($open_dispute->winner);
                                echo '<tr>';
                                    //echo '<td>'.date('m-d-Y H:i',$open_dispute->datemade).'</td>';
                                    echo '<td class="conv_time">'.$open_dispute->datemade.'</td>';
                                    echo '<td>'.$user->user_login.'</td>';
                                    echo '<td class="disp_com">'.$open_dispute->comment.'</td>';                                    
                                echo '</tr>';
                            }
                            echo '</table>';                            
                        }
                        else{
                            _e('There are no open disputes.','ProjectTheme');
                        }
                    ?>               
                
                
                </div>
                </div>
                </div>
                
                
                
                
                
            <div class="my_box3">
            	<div class="padd10">
            
            	<div class="box_title"><?php _e("Closed Disputes", "ProjectTheme"); ?></div>
                <div class="box_content"> 
                <div class="box_title"><?php _e("As Initiator", "ProjectTheme"); ?></div>
                    <?php
                    
                        if ($closed_disputes) {
                            echo '<table class="closed_disputes">';
                            echo '<tr>';
                                echo '<td>Date create</td>';
                                echo '<td>Defendant</td>';
                                echo '<td>Comment</td>';
                                echo '<td>Winner</td>';
                                echo '<td>Closed on</td>';
                                echo '<td>Verdict</td>';
                            echo '</tr>';
                            foreach ($closed_disputes as $key => $closed_dispute) {
                                $user = get_userdata($closed_dispute->defendant);
                                $user_winner = get_userdata($closed_dispute->winner);
                                echo '<tr>';
                                    //echo '<td>'.date('m-d-Y H:i',$closed_dispute->datemade).'</td>';
                                    echo '<td class="conv_time">'.$closed_dispute->datemade.'</td>';
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
                            _e('There are no closed disputes.','ProjectTheme');
                        }
                    ?>
                
                </div>
                <div class="box_content"> 
                <div class="box_title"><?php _e("As Defendant", "ProjectTheme"); ?></div>
                    <?php
                    
                        if ($closed_disputes_on_me) {
                            echo '<table class="closed_disputes">';
                            echo '<tr>';
                                echo '<td>Date create</td>';
                                echo '<td>Initiator</td>';
                                echo '<td>Comment</td>';
                                echo '<td>Winner</td>';
                                echo '<td>Closed on</td>';
                                echo '<td>Verdict</td>';
                            echo '</tr>';
                            foreach ($closed_disputes_on_me as $key => $closed_dispute) {
                                $user = get_userdata($closed_dispute->initiator);
                                $user_winner = get_userdata($closed_dispute->winner);
                                echo '<tr>';
                                    //echo '<td>'.date('m-d-Y H:i',$closed_dispute->datemade).'</td>';
                                    echo '<td class="conv_time">'.$closed_dispute->datemade.'</td>';
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
                            _e('There are no closed disputes.','ProjectTheme');
                        }
                    ?>
                
                </div>
                </div>
                </div>

                <div class="clear10"></div>
                
                
                
                
                </div>  
                 <script type="text/javascript">
            $(document).ready(function(){
                
                $('.conv_time').each(function(){
                    var tm = parseInt($(this).text())*1000;
                    console.log(tm);
                    var time = new Date(tm);
                    $(this).text(time.toLocaleString());
                });
            });
        </script> 
<?php
		ProjectTheme_get_users_links();

}
	
?>