<?php
/**
 * @package kinkyCMS All-In-One Service Plugin
 * @version 3
 */
/*
Plugin Name: kinkyCMS All-In-One Service Plugin
Plugin URI: www.google.com
Description: kinkyCMS All-In-One Service Plugin(integration of several plugins in one
1. Shortcodes
2. Start download instead of the download link
3. Buying duplicate download items in the shop deactivated
4. Vendor Name on Single Product Page
5. Vendor name on shop page
6. Product Meta on Shop Page
7. Autocomplete Orders
8. Custom Payment Gateway Icon
9. Woo Checkout Field Editor
10.Custom Curreny
Author: Imran
Author URI: www.google.com
Version: 3
*/
// 
define('CUSTOM_CURRENCY_DIR_PATH', trailingslashit(plugin_dir_path( __FILE__ ))."libs/custom-currency-for-woocommerce/" );

require_once plugin_dir_path( __FILE__ ) .'kinkyCMSAdmin.php';
$k_c_a_settings=get_option("k_c_a_settings");
function is_plugin_active_byme( $plugin ) {
    return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
}

require_once plugin_dir_path( __FILE__ ) .'kinky_site_url_short_code.php';
require_once plugin_dir_path( __FILE__ ) .'kinky_start_download.php';
require_once plugin_dir_path( __FILE__ ) .'kinky_stop_buying_duplicate.php';
require_once plugin_dir_path( __FILE__ ) .'kinky_vendor_name_on_single_product_page.php';
require_once plugin_dir_path( __FILE__ ) .'kinky_vendor_name_on_shop_page.php';
require_once plugin_dir_path( __FILE__ ) .'kinky_product_meta_on_shop_page.php';
require_once plugin_dir_path( __FILE__ ) .'kinky_autocomplete_woocommerce_orders.php';
require_once plugin_dir_path( __FILE__ ) .'kinky_fr_custom_payment_gateway.php';
require_once plugin_dir_path( __FILE__ ) .'kinky_woo_checkout_field_editor.php';
require_once plugin_dir_path( __FILE__ ) .'kinky_custom_currency_for_woocommerce.php';
require_once plugin_dir_path( __FILE__ ) .'kinky_vendor_name_on_best_selling_widget.php';