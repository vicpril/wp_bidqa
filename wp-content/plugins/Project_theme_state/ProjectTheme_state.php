<?php
/*
Plugin Name: _ProjectTheme State
Description: Adds a US city
Version: 1.1
*/

//--------------------------------------------------


function ProjectTheme_state_activate(){
    
    $parent = term_exists('Massachusetts', 'project_location' );
    
    echo '123123123'. $parent;
    
    var_dump($parent);
    $args = array(
        'alias_of'=>'boston'
        ,'description'=>'Boston'
        ,'parent'=>0
        ,'slug'=>'boston'
    );
    wp_insert_term( $term, $taxonomy, $args );
    
    register_taxonomy();
    
}

register_activation_hook(__FILE__, 'ProjectTheme_state_activate');

