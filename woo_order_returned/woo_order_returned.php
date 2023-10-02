<?php
/**
 * @package woo_order_returned
 * @version 1.0.1
 */
/*
Plugin Name: woo_order_returned
Plugin URI: www.google.com
Description: This is plugin allows to return line item of WC order
Author:imran
Version: 1.0.1
Author URI: http://www.google.com
*/
DEFINE ("MK_RECYCLE","marked_recycle");
DEFINE ("MK_USE","marked_use");
DEFINE ("RET_ATTS_","returned_attributes");
DEFINE ("WOR_PLUGIN_DIR",trailingslashit(plugin_dir_path( __FILE__)));
DEFINE ("WOR_PLUGIN_DIR_URL",trailingslashit(plugin_dir_url( __FILE__)));
require_once WOR_PLUGIN_DIR . 'includes/helper_functions.php';
require_once WOR_PLUGIN_DIR . 'includes/db_functions.php';
require_once WOR_PLUGIN_DIR . 'includes/debug_functions.php';


function activate_woo_order_returned() {

	require_once WOR_PLUGIN_DIR . 'includes/class-woo-order-returned-activator.php';
	Woo_Order_Returned_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_woo_order_returned' );
		
require_once WOR_PLUGIN_DIR .'includes/class-wc-order-returned.php';
function run(){
	new WC_Order_Returned();
}
run();
