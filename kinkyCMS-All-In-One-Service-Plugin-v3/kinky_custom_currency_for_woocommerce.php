<?php
if(($k_c_a_settings["allow_custom_currency_for_woocommerce"]&&  is_plugin_active_byme("woocommerce/woocommerce.php"))){
	
	require_once plugin_dir_path( __FILE__ ) .'libs/custom-currency-for-woocommerce/custom-currency-for-woocommerce.php';
	
}
?>