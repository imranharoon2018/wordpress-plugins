<?php
add_action('get_header', 'wklw_may_view_dashboard');
add_action('get_header', 'wklw_may_view_thankyou_page');
add_action('init', 'may_update_online_status');

function add_to_online($user_id){
	
	// delete_from_online_table($user_id);
	
	if( wklw_user_exists_in_online($user_id) )
		update_online_table($user_id);
	else
		insert_into_online_table($user_id);			
	
}


function may_update_online_status(){
	if( wklw_is_worker(get_user_by('ID',get_current_user_id())) || wklw_is_admin() ){
		if(isset($_REQUEST['wklw_go_offline']))
		if(wp_verify_nonce( $_REQUEST['wklw_go_offline'], 'do_wklw_go_offline')){
			update_online_stattus_to_offline(get_current_user_id());
		}
		if(isset($_REQUEST['wklw_go_online']))
		if(wp_verify_nonce( $_REQUEST['wklw_go_online'], 'do_wklw_go_online')){
			
			add_to_online(get_current_user_id());
		}
		
	}
}
function wklw_is_admin(){
	return current_user_can( 'manage_options' );
}
function wklw_can_view_dashboard(){
	$user = wp_get_current_user ();
	if(!$user) return false;
	return wklw_is_worker($user ) || wklw_is_admin()	;
	
}
function wklw_can_view_thankyou_page(){
	$ret = false;
	if(isset($_GET['new_sign_up'])){
		if(wp_verify_nonce($_GET['new_sign_up'],'finish_new_sign_up'))
			$ret = true;
	}else if(wklw_is_admin()){
		$ret = true;
	}
	return $ret;
	
	
}
function wklw_is_dashboard(){
	global $post;
	if(is_object($post) && property_exists($post,'ID'))
		return $post->ID==get_option("wklw_dashboard_page_id");
	return false;
}
function wklw_is_thankyou_page(){
	global $post;
	if(is_object($post) && property_exists($post,'ID'))
		return $post->ID==get_option("wklw_thankyou_page_id");

	return false;
}
		// 'Sign Up' => "wklw_signup_page_id",
		// 'Dashboard' => "wklw_dashboard_page_id",
		// 'Listing' => "wklw_listing_page_id",
		// 'Thank You' => "wklw_thankyou_page_id"
function wklw_may_view_dashboard(){
	if(wklw_is_dashboard()){
		if(!wklw_can_view_dashboard())
			wp_redirect( wp_login_url() );
			// wp_redirect(get_permalink(get_option("wklw_signup_page_id")));
	}
}
function wklw_may_view_thankyou_page(){
	if(wklw_is_thankyou_page()){
		if(!wklw_can_view_thankyou_page())
			 wp_redirect(get_permalink(get_option("wklw_signup_page_id")));
			// wp_redirect(get_permalink(get_option("wklw_signup_page_id")));
	}
}
// function may_change_online_status(){
	// wklw_can_view_dashboard(wp_get_current_user ())
	// global $post;
	// if($post->ID==get_option(""));
	// exit();
// }


