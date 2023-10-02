<?php
if($k_c_a_settings["enable_autocomplete_woocommerce_order"]&&  is_plugin_active_byme("woocommerce/woocommerce.php")){
	require_once plugin_dir_path( __FILE__ ) .'libs/autocomplete-woocommerce-orders/autocomplete-woocommerce-orders.php';
}
?>