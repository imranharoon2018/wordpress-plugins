<?php
/**
 * @package peepso_hide_subscriber_post
 * @version 1.7.2
 */
/*
Plugin Name: peepso_hide_subscriber_post
Plugin URI: www.google.com
Description: This Plugin Stops User with subscriber role to post, using peepso.Requires Peepso
Author: Imran
Version: 1.7.2
Author URI: ww.google.com
*/
	

add_filter('peepso_permissions_post_create',function($allow_post){
	 
	$user = wp_get_current_user(); // getting & setting the current user 
	if($user){
		$roles = ( array ) $user->roles;	
		if(is_array($roles))
			foreach($roles as $role){
				if($role == 'subscriber')
					$allow_post = false;
			}
	}
	return $allow_post;
	 
	
});
add_filter('peepso_activity_meta_query_args',function($args){
	
	$x = array(
    'role'    => 'subscriber',
	'fields' => 'ID',
	);
	$user_ids = get_users( $x );


	if(!isset( $args ["author__not_in"])) 
		$args ["author__not_in"] = [];
	
	$args ["author__not_in"] = array_merge($args ["author__not_in"],$user_ids);
	
	return $args;
});