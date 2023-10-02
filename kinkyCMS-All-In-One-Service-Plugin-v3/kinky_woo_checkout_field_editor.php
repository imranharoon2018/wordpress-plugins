<?php
if($k_c_a_settings["allow_woo_checkout_field_editor"]&&  is_plugin_active_byme("woocommerce/woocommerce.php")){
	require_once plugin_dir_path( __FILE__ ) .'libs/woo-checkout-field-editor-pro/checkout-form-designer.php';
}
?>