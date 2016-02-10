<?php
	global $wpdb,$wp_rewrite,$wp_query;
	
	$pid = intval($_POST['pid']);

	global $current_user;
	get_currentuserinfo();
	$uid = $current_user->ID;

	$tm = current_time('timestamp',0);

	$post_pr = get_post($pid);

	$time_spent = get_post_meta($pid, 'time_spent', true);
	
	if (strlen($time_spent) > 0) {
		$time_spent = unserialize($time_spent);
		if ($time_spent[$uid]){
			$curent_time_spent 	= $time_spent[$uid]['count_time'];
			$str_time = convert_timestamp($curent_time_spent);
		}else{
			$curent_time_spent = 0;
			$str_time = '00:00:00';
		}
		if ($_POST['act']=='start') {
			$time_spent[$uid]['start']	= $tm;
			$time_spent[$uid]['end']	= 0;
			$time_spent 				= serialize($time_spent);
			update_post_meta($pid, 'time_spent', $time_spent);
			echo '<button class="work_timer" act="stop" pid="'.$pid.'">Stop work timer</button>';
			echo '<strong> Time spent: <span  class="active_timer" id="stopwatch'.$pid.'" cur_time="'.$curent_time_spent.'">'.$str_time.'</span></strong>';
		}elseif($_POST['act']=='stop') {
			$time_spent[$uid]['end']			=  $tm;
			if($time_spent[$uid]['start'] > 0){
				$time_spent[$uid]['count_time']	+= round($time_spent[$uid]['end']-$time_spent[$uid]['start']);
			}else{
				$time_spent[$uid]['count_time']	+= 0;
			}
			$curent_time_spent 					=  $time_spent[$uid]['count_time'];
			$str_time = convert_timestamp($curent_time_spent);
			$time_spent[$uid]['start']			=  0;		
			$time_spent 						=  serialize($time_spent);
			update_post_meta($pid, 'time_spent', $time_spent);	
			echo '<button class="work_timer" act="start" pid="'.$pid.'">Start work timer</button>';
			echo '<strong> Time spent: <span  class="not_active_timer" id="stopwatch'.$pid.'" cur_time="'.$curent_time_spent.'">'.$str_time.'</span></strong>';
		}
	}else{
			$curent_time_spent = 0;
			$str_time = '00:00:00';
		if ($_POST['act']=='start') {
			$time_spent[$uid]['start']		= $tm;
			$time_spent[$uid]['end']		= 0;
			$time_spent[$uid]['count_time'] = 0;
			$time_spent 					= serialize($time_spent);
			update_post_meta($pid, 'time_spent', $time_spent);
			echo '<button class="work_timer" act="stop" pid="'.$pid.'">Stop work timer</button>';
			echo '<strong> Time spent: <span  class="active_timer" id="stopwatch'.$pid.'" cur_time="'.$curent_time_spent.'">'.$str_time.'</span></strong>';
		}elseif ($_POST['act']=='stop') {
			$time_spent[$uid]['end']			= $tm;
			//$time_spent[$uid]['count_time']	+= $time_spent[$uid]['end']-$time_spent[$uid]['start'];
			$time_spent[$uid]['count_time']		= 0;
			$time_spent[$uid]['start']			= 0;		
			$time_spent 						= serialize($time_spent);
			update_post_meta($pid, 'time_spent', $time_spent);
			echo '<button class="work_timer" act="start" pid="'.$pid.'">Start work timer</button>';
			echo '<strong> Time spent: <span  class="not_active_timer" id="stopwatch'.$pid.'" cur_time="'.$curent_time_spent.'">'.$str_time.'</span></strong>';
		}
	}
?>
