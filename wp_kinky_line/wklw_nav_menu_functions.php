<?php

add_filter( 'wp_nav_menu_objects', function( $sorted_menu_items=null,$args=nul){
	
	$filtered_menu_items = array();
	foreach	($sorted_menu_items as $item){
	
		if( !(is_object($item) && property_exists($item,'ID') && $item->ID == get_option("wklw_thankyou_page_id")) )
			$filtered_menu_items[] = $item;
		
	}
	return $filtered_menu_items ;
	
},20,2);
add_filter( 'wp_nav_menu_items', function( $items,$args){

	$new_item = "";

	if(is_object($args) && property_exists($args,'theme_location')) {
		$new_item ='';
		if( $args->theme_location == 'primary' ){
			if(!is_user_logged_in()){
				$new_item = '<li id="menu-item" class="menu-item menu-item-type-custom menu-item-object-custom"><a href="'.wp_login_url().'">'.__('Login','wklw').'</a></li>' ;
			}
			if(is_user_logged_in()){
				if(wklw_is_worker(wp_get_current_user())){
					$new_item = '<li id="menu-item" class="menu-item menu-item-type-custom menu-item-object-custom"><a href="'.get_permalink(get_option("wklw_dashboard_page_id")).'">Dashboard</a></li>' ;
				}
				$new_item .= '<li id="menu-item" class="menu-item menu-item-type-custom menu-item-object-custom"><a href="'.wp_logout_url(site_url()).'">'.__('Logout','wklw').'</a></li>' ;
			}
		}
	}
	
	return $items.$new_item ;
} ,10,2);
?>