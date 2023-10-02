<?php
// /**
 // * @package wp_kinky_line
 // * @version 1
 // */
// /*
// Plugin Name: wp_kinky_line
// Plugin URI: www.google.com
// Description: First Version of wp_kinky_line. create roles for workers and pages for sign up ,dashboard ,listing and thankyou on activation
// Author: Imran
1// Author URI: www.google.com
// */
define('WKLW_PLUGIN_DIR',trailingslashit(plugin_dir_path(__FILE__)));
define('WKLW_PLUGIN_URL',trailingslashit( plugin_dir_url(__FILE__)));
define('KINKY_LINE_WORKER','wp_kinky_line_worker');
define('KINKY_LINE_WORKER_SCANS','wp_kinky_line_worker_scans');

require_once WKLW_PLUGIN_DIR."wklw_textdomain_functions.php";
require_once WKLW_PLUGIN_DIR."wklw_verify_file.php";
require_once WKLW_PLUGIN_DIR."wklw_not_allowed_functions.php";
require_once WKLW_PLUGIN_DIR."functions.php";
require_once WKLW_PLUGIN_DIR."activation_functions.php";
require_once WKLW_PLUGIN_DIR."wklw_online_db_functions.php";
require_once WKLW_PLUGIN_DIR."shortcode_functions.php";
require_once WKLW_PLUGIN_DIR."signup_field_validation_functions.php";
require_once WKLW_PLUGIN_DIR."worker_functions.php";
require_once WKLW_PLUGIN_DIR."action_functions.php";
require_once WKLW_PLUGIN_DIR."frontend_worker_functions.php";
require_once WKLW_PLUGIN_DIR."wklw_heartbeat_functions.php";
require_once WKLW_PLUGIN_DIR."wklw_role_functions.php";
require_once WKLW_PLUGIN_DIR."listing_functions.php";
require_once WKLW_PLUGIN_DIR."wklw_admin_functions.php";
require_once WKLW_PLUGIN_DIR."wklw_avatar_functions.php";
require_once WKLW_PLUGIN_DIR."wklw_nav_menu_functions.php";
require_once WKLW_PLUGIN_DIR."wklw_dashboard_functions.php";
require_once WKLW_PLUGIN_DIR."wklw_users_list_functions.php";


	
register_activation_hook( __FILE__, function(){
		wklw_create_artist_pages();
		wklw_add_kinky_line_worker_role();
		wklw_create_default_options();
		wklw_create_tables();
});
add_action( 'wp_enqueue_scripts', 'wklw_enqueue_scripts'  );
// add_action( 'wp_loaded', function(){


add_action('init', function(){
	// set_online_status_to_offline_based_on_last_heartbeat();
});
add_action('init', function(){
    if( ! session_id() ) {
        session_start();
    }
	
});

add_action( 'init', function(){

	may_create_worker();
	
});
add_filter( 'login_redirect', function( $redirect_to='',  $requested_redirect_to='',  $user=''){
	if ( !is_wp_error( $user ) ) {

		if(wklw_is_worker($user)) 
			return get_permalink(get_option("wklw_dashboard_page_id"));
		
	}
	return $redirect_to;
} ,PHP_INT_MAX,3  );
add_action( 'edit_user_profile', 'wklw_show_user_meta_on_edit_profile'  );


		
function wklw_enqueue_scripts($settings = array()){
			
			wp_enqueue_style('wklw_sign_up-styles', trailingslashit(plugin_dir_url( __FILE__ )). 'assets/css/wklw_sign_up.css', array());		
			// load_plugin_textdomain( 'wklw', false,'wp_kinky_line/languages/' );
			global $post;
			if(is_object($post) && $post->ID == get_option("wklw_signup_page_id")){
				// var_dump("bye");exit();
				wp_enqueue_script('wklw_sign_up-script', trailingslashit(plugin_dir_url( __FILE__ )). 'assets/js/wklw_sign_up.js', array( 'jquery'));
				
				wp_localize_script('wklw_sign_up-script', 'wklw_signup', array(
						'max_file_size' => wp_max_upload_size(),
						'msg_password' => __('Passwords does not match','wklw'),
						'msg_file_size' => __('File is too big','wklw'),
						'msg_file_type' => __('Use jpeg, jpg, png format','wklw'),
						
						)
				);
				
			
			}
			
			// wp_enqueue_script('wklw_hb-script', trailingslashit(plugin_dir_url( __FILE__ )). 'assets/js/hb.js', array( 'jquery','heartbeat' ));
			
			
			if(is_object($post) && $post->ID == get_option("wklw_dashboard_page_id")){
				wp_enqueue_script('wklw_dashboard-script', trailingslashit(plugin_dir_url( __FILE__ )). 'assets/js/wklw_dashboard.js', array( 'jquery','heartbeat', ));
				wp_localize_script('wklw_dashboard-script', 'wklw_dashboard', array(
						'max_file_size' => wp_max_upload_size()));
					
					
				if( wklw_is_worker(wp_get_current_user()) || wklw_is_admin()){
				
					// wp_enque	ue_script('wklw_hb-worker-script', trailingslashit(plugin_dir_url( __FILE__ )). 'assets/js/hb-worker.js', array( 'jquery','heartbeat' ));
					wp_localize_script('wklw_hb-worker-script', 'wklw_data', array(
						'user_id' => get_current_user_id(),
						'msg_file_size' => __('File is too big','wklw'),
						'msg_file_type' => __('Use jpeg, jpg, png format','wklw')
						)
						
					);	
				}
				
			}
			if(is_object($post) && ($post->ID == get_option("wklw_listing_page_id") || $post->ID == get_option("wklw_dashboard_page_id"))){
				wp_enqueue_style('wklw_listing-styles', trailingslashit(plugin_dir_url( __FILE__ )). 'assets/css/wklw_listing.css', array());
			}
			if(is_object($post) && $post->ID == get_option("wklw_listing_page_id")){
				
				wp_enqueue_script('wklw_hb-listing-script', trailingslashit(plugin_dir_url( __FILE__ )). 'assets/js/hb-listing.js', array( 'jquery','heartbeat' ));				
				wp_localize_script('wklw_hb-listing-script', 'wklw_listing_data', array(
				
						'ajax_url' =>admin_url( 'admin-ajax.php' ),
						'online_user_ids' => wklw_get_online_user_ids(),
						'msg_call_me_now' => __('Call Me Now','wklw'),
						'msg_offline' =>  __('Offline','wklw')
					));	
			}
}
  
add_shortcode( 'wklw_sign_up_form', 'wklw_show_sign_up_form' );
add_shortcode( 'wklw_dashboard', 'wklw_show_dashboard' );
add_shortcode( 'wklw_listing', 'wklw_show_listing' );
add_shortcode( 'wklw_thankyou_for_signingup', 'wklw_show_thankyou' );
?>