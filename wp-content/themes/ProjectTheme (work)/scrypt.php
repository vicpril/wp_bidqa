<?php

/*
  Template Name: scrypt
 */

global $wpdb;
$tax = 'project_location';

get_header();

$query = "SELECT city AS name, state, us_states.state_code AS code 
            FROM us_cities LEFT JOIN us_states 
            ON us_cities.state_code = us_states.state_code
            where state != 'null'
            ORDER BY `state` ASC, `city` ASC
            LIMIT 0, 10000";

$cities = $wpdb->get_results($query, ARRAY_A);

foreach ($cities as $city) {
    
    $slug = $city['name'] . ' ' . $city['code'];
    $name = $city['name'];
    
    $q_insert_term = "INSERT INTO `wp_terms` (`name`, `slug`, `term_group`) "
            . "VALUES ('" . $city['name'] ."', '". $slug ."', '0');";
            
    $wpdb->query($q_insert_term);
    
    $new_term_id = $wpdb->insert_id;
    
    $parent_term = term_exists($city['state'], $tax);
    $parent_term_id = $parent_term['term_id'];
    
    $q_insert_term_taxonomy = "INSERT INTO `wp_term_taxonomy` (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`)
                                VALUES ('$new_term_id', '$new_term_id', '$tax', '$name', '$parent_term_id', '0')";
    
    $wpdb->query($q_insert_term_taxonomy);
            
            
    
}



//            wp_cache_delete('all_ids', $tax);
//			wp_cache_delete('get', $tax);
//			delete_option("{$tax}_children");
//			_get_term_hierarchy($tax);


get_footer();
