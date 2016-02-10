<?php
/*
Plugin Name: ProjectTheme Radius Search
Plugin URI: http://sitemile.com/
Description: Adds a radius search feature to the advanced search in ProjectTheme.
Author: SiteMile.com
Author URI: http://sitemile.com/
Version: 1.1
Text Domain: pt_radius
*/

//--------------------------------------------------

add_filter('ProjectTheme_execute_on_submit_1','ProjectTheme_execute_on_submit_1_rad');
add_filter('ProjectTheme_adv_search_before_search','ProjectTheme_adv_search_before_search_rads');
add_filter('ProjectTheme_adv_search_add_to_form','ProjectTheme_adv_search_add_to_form_radius');

//************************************************

function ProjectTheme_adv_search_add_to_form_radius()
{
	?>
    
    <tr><td><?php _e('Zip Code or City',"ProjectTheme"); ?>:</td><td> 
                   <input class="do_input_afs" size="10" value="<?php echo $_GET['zip_code']; ?>" name="zip_code" /></td></tr>
                   
    <tr><td><?php _e('Radius',"ProjectTheme"); ?>: </td><td>
                   <input class="do_input_afs" size="10" value="<?php echo $_GET['radius']; ?>" name="radius" />
                   <?php _e('miles','ProjectTheme'); ?></td></tr>

    <script type="text/javascript">
    	$(document).ready(function(){    		
    		$( 'input[name="radius"]' ).keypress(function( event ) {
    		  var kk = event.which;
    		  console.log(kk);
    		  
    		  if((kk>47 && kk<58) || kk==13 || kk==0 || kk==8 || kk==46){
    		  	return true;
    		  }
    		  else{
    		  	return false;
    		  }
    		  
    		});

    		$( 'input[name="zip_code"]' ).keypress(function( event ) {
    		  var kkk = event.which;     		  
    		  console.log(kkk);
    		  if(((kkk>47 && kkk<58) || (kkk>95 && kkk<123) || (kkk>64 && kkk<91) || kkk==13 || kkk==32 || kkk==0 || kkk==8)){    		  
    		  	return true;
    		  }
    		  else{
    		  	return false;
    		  }
    		  
    		});
    	});
    </script>
    <?php	
}

function ProjectTheme_adv_search_before_search_rads()
{
	if(!empty($_GET['zip_code']))
	{
		global $local_long, $local_lat, $radius ; 
		
		$country = $_GET['project_location_cat'];//''; //"UK";	
		$zip 	= trim($_GET['zip_code']);
		$radius = (int) trim($_GET['radius']); 
		
		if(empty($radius)) $radius = 5;
		
		global $mak_address;
		
		$mak_address = $country.",".$zip;
		if(isset($country) && isset($zip)){
			$data 	= ProjectTheme_radius_get_geo_coordinates($country.",".$zip);
		}elseif(isset($country)){
			$data 	= ProjectTheme_radius_get_geo_coordinates($country);
		}else{
			$data 	= ProjectTheme_radius_get_geo_coordinates($zip);
		}
		$local_long = array();
		$local_lat  = array();
		for($i = 0; $i < count($data); $i++){
		$local_long[] 	= $data[$i]->lng;
		$local_lat[] 	= $data[$i]->lat;	
		}
		add_filter('posts_join', 'ProjectTheme_radius_get_lat_stuff_join' );
		add_filter('posts_join', 'ProjectTheme_radius_get_long_stuff_join' );
		add_filter('posts_where', 'ProjectTheme_radius_adv_search_where_thing');
	}
		
}

function ProjectTheme_radius_adv_search_where_thing($where)
{
			global $local_long, $local_lat, $radius ; 
			global $wpdb;
			if(is_array($local_long)){
				$where .= " AND (";
				for($i = 0; $i < count($local_long); $i++){
					if($i > 0) $where .= " OR ";
				$where .= " ((ACOS(SIN($local_lat[$i] * PI() / 180) * SIN(`project_lat` * PI() / 180) + COS($local_lat[$i] * PI() / 180) * COS(`project_lat` * PI() / 180) * 
				COS(($local_long[$i] - `project_long`) * PI() / 180)) * 180 / PI()) * 60 * 1.1515)< $radius";
				}
				$where .= ")";
			}else{
				$where .= " AND 
				((ACOS(SIN($local_lat * PI() / 180) * SIN(`project_lat` * PI() / 180) + COS($local_lat * PI() / 180) * COS(`project_lat` * PI() / 180) * 
				COS(($local_long - `project_long`) * PI() / 180)) * 180 / PI()) * 60 * 1.1515)< $radius";
			}

			/*if(is_array($local_long)){
				$where .= " AND (";
				for($i = 0; $i < count($local_long); $i++){
					if($i > 0) $where .= " OR ";
				$where .= " (( 3959 * acos( cos( radians('".$local_lat[$i]."') ) * cos( radians( project_lat ) ) * cos( radians( project_long ) - radians('".$local_long[$i]."') ) + sin( radians('".$local_lat[$i]."') ) * sin( radians( project_lat ) ) ) )< $radius";
				}
				$where .= ")";
			}else{
				$where .= " AND 
				( 3959 * acos( cos( radians('".$local_lat."') ) * cos( radians( project_lat ) ) * cos( radians( project_long ) - radians('".$local_long."') ) + sin( radians('".$local_lat."') ) * sin( radians( project_lat ) ) ) )< $radius";
			}*/

	
		return $where;
}
	
	
function ProjectTheme_radius_get_lat_stuff_join($wp_join)
{

			global $wpdb;
			$wp_join .= " LEFT JOIN (
					SELECT post_id, meta_value as project_lat
					FROM $wpdb->postmeta
					WHERE meta_key =  'project_lat' ) AS DD1
					ON $wpdb->posts.ID = DD1.post_id ";
	
		return ($wp_join);
}
	
	
function ProjectTheme_radius_get_long_stuff_join($wp_join)
{
			global $wpdb;
			$wp_join .= " LEFT JOIN (
					SELECT post_id, meta_value as project_long
					FROM $wpdb->postmeta
					WHERE meta_key =  'project_long' ) AS DD2
					ON $wpdb->posts.ID = DD2.post_id ";
	
		return ($wp_join);
}

function ProjectTheme_execute_on_submit_1_rad($pid)
{
	$zip = get_post_meta($pid, "Location", true);
			  
	$loc 		= wp_get_post_terms( $pid, 'project_location');
	$loc_a 	= '';
			 
	foreach($loc as $l)
		$loc_a .= $l->name.',' ;
				
	$loc_a .= $zip;
		  
	$data = ProjectTheme_radius_get_geo_coordinates($loc_a);
	$long = $data[0]->lng;
	$lat  = $data[0]->lat;
			  
	update_post_meta($pid, 'project_lat', 	$lat);
	update_post_meta($pid, 'project_long', 	$long);	
}

//************************************************

function ProjectTheme_radius_get_geo_coordinates($addr)
{
	if(!empty($addr))
	{
		//http://maps.google.com/maps/geo?output=csv&q=Bucharest&key=	
		$key 		= get_option('ProjectTheme_radius_maps_api_key');
		$url 		= 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($addr).'&sensor=false&key=' . $key;  
		$data 		= ProjectTheme_radius_curl_get_data($url);
		$data 		= json_decode($data); //explode(",", $data);
		if($data->status != "OK"){
			$url  	= 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($addr).'&sensor=false';	
			$data 	= ProjectTheme_radius_curl_get_data($url);
			$data 	= json_decode($data); //explode(",", $data);
		}
		# one location result
		//return $data->results[0]->geometry->location;
		/* one */

		# all location result
		$new_arr = array();
		foreach($data->results as $value){
			$new_arr[] = $value->geometry->location;
		}
		return $new_arr;
		/* all */
	}
}

//************************************************

function ProjectTheme_radius_curl_get_data($url)
{
	  $ch = curl_init();
	  $timeout = 5;
	  curl_setopt($ch,CURLOPT_URL,$url);
	  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	  $data = curl_exec($ch);
	  curl_close($ch);
	  return $data;
} 

?>