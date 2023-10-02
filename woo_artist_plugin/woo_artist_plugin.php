<?php
// /**
 // * @package woo_artist_plugin
 // * @version 995
 // */
// /*
// Plugin Name: woo_artist_plugin
// Plugin URI: http://wordpress.org/
// Description: This plugin send artist email on a sales or change status in order containg hsi product
// Author: imran
// Version: 2000
// Author URI: http://wordpress.org/
// */
define('WAP_ARTIST_EMAIL','wap_artist_email');
define('WAP_ARTIST_NAAME','wap_artist_name');
define('WAP_ARTIST_COMISSION','wap_artist_comission');
define('WAP_ARTIST_ID','wap_artist_id');
define('WAP_PLUGIN_DIR',trailingslashit(plugin_dir_path(__FILE__)));
define('WAP_PLUGIN_URL', trailingslashit(plugin_dir_url(__FILE__)));
// define('WAP_TERM',"product_cat");
define('WAP_TERM',"pa_brand");
require_once WAP_PLUGIN_DIR. 'email_templates/email_templates.php';

require_once WAP_PLUGIN_DIR. 'functions/file_upload_functions.php';
require_once WAP_PLUGIN_DIR. 'functions/artist_db_function.php';
require_once WAP_PLUGIN_DIR. 'functions/helper_functions.php';
require_once WAP_PLUGIN_DIR. 'functions/mail_functions.php';
require_once WAP_PLUGIN_DIR. 'functions/artist_functions.php';
require_once WAP_PLUGIN_DIR. 'functions/admin_functions.php';
require_once WAP_PLUGIN_DIR. 'functions/order_functions.php';

require_once WAP_PLUGIN_DIR. 'frontend_functions/wap_artist_home_functions.php';
require_once WAP_PLUGIN_DIR. 'functions/artist_front_end_settings.php';



register_activation_hook( __FILE__, function(){
		//to stop setting the corn command on every deactivate/activate
		if(!get_option("wap_mail_send_page_pass"))
			update_option("wap_mail_send_page_pass",wp_generate_password(12,false,false));
		if(!get_option("wap_is_month_end_mail_send_enable"))
			update_option("wap_is_month_end_mail_send_enable",false);
		
		update_option("wap_default_artist_comission",10.00);
		update_option('artist_plugin_email_subject','Artist Email New customer order ({order_id}) - {order_date}');
		update_option('artist_plugin_order_canceled_email_subject','Artist Email Caneled order ({order_id}) - {order_date}');
		update_option('artist_plugin_end_of_month_email_subject','Artist Email Sales Summary for {summary_month}, {summary_year}');
		create_artist_info_table();
		add_artist_role();
		// create_wap_artist_account_pages();
		// create_wap_artist_signup_page();
		
});

add_shortcode( 'wap_artist_sign_up_form', 'show_wap_artist_sign_up_form' );


if ( ! class_exists( 'Woo_Artist_Plugin', false ) ) :
 
class Woo_Artist_Plugin{
	/**
		 * Constructor.
		 */
		public function __construct() {
		
			add_action( 'init', array( $this, 'init' ),10,2  );
			add_action( 'init', array( $this, 'handle_admin_function' ),10,2  );
			
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'get_email_setting_page' ),10,2  );
			// add_filter( 'woocommerce_login_redirect', array( $this, 'handle_woocommerce_login_redirect' ),10,2  );
			// add_filter( 'woocommerce_login_redirect', array( $this, 'handle_woocommerce_login_redirect' ),10,2  );
			add_filter( 'login_redirect', array( $this, 'handle_artist_login_redirect' ),PHP_INT_MAX,3  );
			add_filter( 'manage_users_custom_column', array( $this, 'show_product_term_in_users_table' ),PHP_INT_MAX,3  );
			add_action( 'edit_user_profile', array( $this, 'show_artist_comission_on_edit_user_profile' )  );
			add_action( 'show_user_profile', array( $this, 'show_artist_comission_on_show_user_profile' )  );
			add_action( 'profile_update', array( $this, 'update_artist_comission' )  );
			// do_action( 'profile_update', $user_id, $old_user_data );//doin
			
			// do_action( 'edit_user_profile', $profileuser );//done
			// do_action( 'show_user_profile', $profileuser );
			
			add_filter('manage_users_columns', array( $this, 'add_product_term_column'));
			add_action( 'wap_artist_email_details', array( $this, 'artist_email_details') ,10,3 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts')  );
			add_action( 'set_user_role', array( $this, 'add_artist_comission') ,10,2 );

			add_shortcode( 'wap_artist_dashboard', 'render_artist_account' );
			add_shortcode( 'wap_artist_upload_file', 'render_artist_upload_file' );
			add_shortcode( 'wap_artist_sales_statistics', 'render_artist_sales_statistics' );
			add_shortcode( 'wap_artist_account_information', 'render_artist_account_information' );
		

			//processing order handle
			add_action( 'woocommerce_order_status_pending_to_processing', array( $this, 'handle_order_processing') ,10,1 );	
			add_action( 'woocommerce_order_status_on-hold_to_processing', array( $this, 'handle_order_processing') ,10,1 );	
			add_action( 'woocommerce_order_status_failed_to_processing', array( $this, 'handle_order_processing') ,10,1 );	
			add_action( 'woocommerce_order_status_cancelled_to_processing', array( $this, 'handle_order_processing') ,10,1 );		
			add_action( 'woocommerce_order_status_completed_to_processing', array( $this, 'handle_order_processing') ,10,1 );	
			add_action( 'woocommerce_order_status_refunded_to_processing', array( $this, 'handle_order_processing') ,10,1 );
			//canceld order handle
			add_action( 'woocommerce_order_status_processing_to_cancelled', array( $this, 'handle_order_cancelled') ,10,1 );	
			add_action( 'woocommerce_order_status_pending_to_cancelled', array( $this, 'handle_order_cancelled') ,10,1 );
			add_action( 'woocommerce_order_status_on-hold_to_cancelled', array( $this, 'handle_order_cancelled') ,10,1 );			
			add_action( 'woocommerce_order_status_failed_to_cancelled', array( $this, 'handle_order_cancelled') ,10,1 );
			add_action( 'woocommerce_order_status_completed_to_cancelled', array( $this, 'handle_order_cancelled') ,10,1 );
			add_action( 'woocommerce_order_status_refunded_to_cancelled', array( $this, 'handle_order_cancelled') ,10,1 );			
			//completed order handle
			add_action( 'woocommerce_order_status_processing_to_completed', array( $this, 'handle_order_completed') ,10,1 );	
			add_action( 'woocommerce_order_status_pending_to_completed', array( $this, 'handle_order_completed') ,10,1 );
			add_action( 'woocommerce_order_status_on-hold_to_completed', array( $this, 'handle_order_completed') ,10,1 );			
			add_action( 'woocommerce_order_status_failed_to_completed', array( $this, 'handle_order_completed') ,10,1 );			
			add_action( 'woocommerce_order_status_refunded_to_completed', array( $this, 'handle_order_completed') ,10,1 );			
			add_action( 'woocommerce_order_status_cancelled_to_completed', array( $this, 'handle_order_completed') ,10,1 );			
			
			$from_order_status_array = array(
				'processing',
				'cancelled',
				'completed',
				'pending',
				'on-hold',
				'failed',				
				'refunded',
			);
			$to_order_status_array = array(
				'pending',
				'on-hold',
				'failed',
				'cancelled',
				'refunded',
			);
			for($i=0;$i<count($from_order_status_array);$i++){
				for($j=0;$j<count($to_order_status_array);$j++){
					if($from_order_status_array[$i]!=$to_order_status_array[$j]){
						$str_status_change = 'woocommerce_order_status_'.$from_order_status_array[$i].'_to_'.$to_order_status_array[$j];
						add_action ($str_status_change, array( $this, 'handle_other_order_status_change') ,10,1 );	
					}
				}
			}
			
			add_action( 'admin_menu',  array( $this, 'show_woo_artist_admin_page') );
				
		
		}
			
		function init(){
		
			may_create_artist();
			may_upload_file();
		}
		
		function handle_admin_function() {
			wap_admin_may_save_setting();
			wap_may_handle_theme_file();
			may_create_mail_send_page();
			may_save_month_end_email_enable();
			may_save_delete_attachment_file_enable();
			may_save_save_mark_orders_after_email_send();
			 		
		}
		function show_woo_artist_admin_page() {
  
			add_menu_page( 'Woo Artist Plugin', 'Woo Artist Plugin', 'manage_options', 'woo-artist-plugin', 'wap_show_admin', 'dashicons-welcome-widgets-menus', 90 );

			}			
		function update_artist_comission($user_id){
			may_update_artist_comission($user_id);
		}
		function show_artist_comission_on_show_user_profile($profileuser){
			may_show_read_only_artist_comission($profileuser);
		}
		function show_artist_comission_on_edit_user_profile($profileuser){
			may_show_artist_comission($profileuser);
		}
		function add_product_term_column($column_headers) {
			
		  $column_headers['wap_user_product_term'] = 'Brand';
		  return $column_headers;
		}
		function show_product_term_in_users_table( $output, $column_name, $user_id){
				return may_show_product_term_in_users_table( $output, $column_name, $user_id);
			}
	
		function handle_other_order_status_change($order_id=null){
			wap_other_order_status_change($order_id);
		}
		function handle_order_completed($order_id=null){
			wap_artist_order_completed($order_id);
		}
		
		function handle_order_cancelled($order_id=null){
			wap_artist_order_cancelled($order_id);
		}
		function handle_order_processing($order_id=null){
			
			wap_artist_order_processing($order_id);
		}
		function add_artist_comission($user_id,$role=null){
			may_add_default_commission($user_id,$role);
		}
		
		function handle_artist_login_redirect( $redirect_to='',  $requested_redirect_to='',  $user=''){
			return may_redirect_to_artist_account_pag($redirect_to,  $requested_redirect_to,  $user);
		
		}
	
		function enqueue_scripts($settings = array()){
			
			// wp_enqueue_style('wap_artist_signup-styles', trailingslashit(plugin_dir_url( __FILE__ )). 'assets/css/woo_artist_sign_up_form.css', array());		
			// wp_enqueue_script('wap_artist_signup-script', trailingslashit(plugin_dir_url( __FILE__ )). 'assets/js/woo_artist_sign_up_form.js', array());
		}
		
		function get_email_setting_page($settings = array()){
		// echo __DIR__;exit();
			$settings [] =  include trailingslashit(__DIR__ ). 'class-wc-artist-plugin-settings.php';
	 
			return $settings ;
		}
		

		public function register_term_meta() {
			register_term_meta( WAP_TERM,WAP_ARTIST_EMAIL,array() ) ;
			register_term_meta( WAP_TERM,WAP_ARTIST_NAAME,array() ) ;
			register_term_meta( WAP_TERM,WAP_ARTIST_COMISSION,array() ) ;
			register_term_meta(WAP_TERM,WAP_ARTIST_ID,array() ) ;
		
			
		}

		
	
}
endif;
new Woo_Artist_Plugin();

require_once WAP_PLUGIN_DIR. 'functions/wap_term_functions.php';

add_action( 'send_artist_summery', 'bob_send_artist_summery' );

function bob_send_artist_summery() {

	$curl = curl_init();

	curl_setopt_array($curl, [
	  CURLOPT_URL => get_home_url()."/month-end-mail/?rand=V8wf9R2xLjZD",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_POSTFIELDS => "",
	  CURLOPT_SSL_VERIFYHOST => FALSE,
	  CURLOPT_SSL_VERIFYPEER => FALSE
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  echo $response;
	}

}

?>