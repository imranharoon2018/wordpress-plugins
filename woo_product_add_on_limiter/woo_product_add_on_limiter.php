<?php
/**
 * @package woo_product_add_on_limiter
 * @version 1.7.2
 */
/*
Plugin Name: woo_product_add_on_limiter 
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: This plugin is for limiting itmes product add on. requires woocommerce and Product Add-Ons
Author: imran
Version: 1.0.2
Author URI: http://google.com
*/
add_action("wp_head",function(){
	
	?>
<style>
.error_msg{
	color: #d41772;
}
.correct_msg{
	color: #6d6d6d;
}
</style>
	<?php
});
$addon_names_for_js = array();
$addon_max_limit_for_js =array() ;

require_once plugin_dir_path( __FILE__ ) .'woo_product_add_on_limiter_cart_validation.php';
require_once plugin_dir_path( __FILE__ ) .'woo_product_add_on_limiter_admin.php';
require_once plugin_dir_path( __FILE__ ) .'woo_product_addon_quickview_action.php';
require_once plugin_dir_path( __FILE__ ) .'woo_product_addon_wc_cart_action.php';
require_once plugin_dir_path( __FILE__ ) .'woo_product_addon_plugin_action.php';
require_once plugin_dir_path( __FILE__ ) .'woo_product_add_on_wp_footer_action.php';
require_once plugin_dir_path( __FILE__ ) .'woo_product_add_on_add_to_cart_filter.php';

function woo_product_addon_limiter_activated() {
 
	$woo_addon_limiter_default_text = "Please select %s of the following %s options.";
	$woo_addon_limiter_default_text = "Choose exactly 2 of the following 5 options";
	update_option("woo_addon_limiter_default_text",$woo_addon_limiter_default_text );
  /* activation code here */
}
register_activation_hook( __FILE__, 'woo_product_addon_limiter_activated' );