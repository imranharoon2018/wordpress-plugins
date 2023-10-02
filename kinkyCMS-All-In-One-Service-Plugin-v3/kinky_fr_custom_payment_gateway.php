<?php
if($k_c_a_settings["enable_custom_payment_gateway_icon"]&&  is_plugin_active_byme("woocommerce/woocommerce.php")){
	require_once plugin_dir_path( __FILE__ ) .'libs/fr-custom-payment-gateway-icon-for-woocommerce/fr-custom-payment-gateway-icon-for-woocommerce.php';
}
?>