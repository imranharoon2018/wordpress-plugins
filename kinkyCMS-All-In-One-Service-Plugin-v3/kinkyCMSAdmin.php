<?php
// code for admin menu start
register_activation_hook( __FILE__, 'set_default_kinky_cms_settings' );
add_action( 'init',function(){
	global $k_c_a_settings;
	$k_c_a_settings=get_option("k_c_a_settings");
});
function set_default_kinky_cms_settings(){
	
	$kinky_cms_admin_settings = array(
	"enable_start_download"=>true, #1. Download starten anstelle des Downloadlinks
	"enable_stop_buying_duplicate"=>true, #2. Doppelte Downloadartikel im Shop kaufen deaktiviert
	"enable_vendor_name_on_single_product_page"=>true,#3. Vendor Name auf Single Produkt Seite
	"enable_vendor_name_on_shop_page"=>true,#4. Vendor Name auf Shop Seite
	"enable_product_meta_on_shop_page"=>true,#5. Product Meta auf Shop Seite
	"allow_site_url_short_code"=>true,#5. Product Meta auf Shop Seite
	"enable_autocomplete_woocommerce_order"=>true,#6. Autocomplete Woocommerce Order
	"enable_custom_payment_gateway_icon"=>true,#7. Custom payment gateway icon
	"allow_woo_checkout_field_editor"=>true,#8. Checkout field editor
	"allow_custom_currency_for_woocommerce"=>true,#8. Checkout field editor
	"enable_vendor_name_on_best_selling_widget"=>true,#9. Vendor Name on Best selling widget
	);
	
	

	update_option("k_c_a_settings",$kinky_cms_admin_settings );
}

add_action( 'admin_menu', 'kinky_cms_settings_menu' );
function kinky_cms_settings_menu(){
add_menu_page(
        __( 'KinkyCMS Servicesx', 'textdomain' ),
        'KinkyCMS Services',
        'manage_options',
        'kinky-cms-settings-menu',
        'show_kinky_cms_settings_page',
        '',
        6
    );
}
function show_kinky_cms_settings_page(){
	$k_c_a_settings=get_option("k_c_a_settings")
	?>
	<div class="wrap">
		<h1>KinkyCMS Setting</h1>
		
		<form method="post" >
	
		<input type="hidden" name="action" id="action" value="save_kinky_cms_setting"/>
		 <?php wp_nonce_field( 'kinky_cms_settings' ); ?>
		<!-- <input type="hidden" name="page" id="page" value="<?=$_REQUST["page"]?>"/>-->
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<td><input 
							<?= $k_c_a_settings["enable_start_download"]?"checked":""; ?>
							name="enable_start_download" 
							id="enable_start_download" 
							type="checkbox" 
							class="" value="1"
							/>Download starten anstelle des Downloadlinks</td>
				</tr>
				<tr valign="top">
					<td><input 
							<?= $k_c_a_settings["enable_stop_buying_duplicate"]?"checked":""; ?>
							name="enable_stop_buying_duplicate" 
							id="enable_stop_buying_duplicate" 
							type="checkbox" 
							class="" 
							value="1"/>Doppelte Downloadartikel im Shop kaufen deaktiviert</td>
					</tr>
				<tr valign="top">
					<td><input 
							<?= $k_c_a_settings["enable_vendor_name_on_single_product_page"]?"checked":""; ?>
							name="enable_vendor_name_on_single_product_page" 
							id="enable_vendor_name_on_single_product_page" 
							type="checkbox" 
							class="" 
							value="1"/>Vendor Name auf Single Produkt Seite</td>
				</tr>
				<tr valign="top">
					
					<td><input 
						<?= $k_c_a_settings["enable_vendor_name_on_shop_page"]?"checked":""; ?>
						name="enable_vendor_name_on_shop_page" 
						id="enable_vendor_name_on_shop_page" 
						type="checkbox" 
						class="" 
						value="1"/>Vendor Name auf Shop Seite</td>
				</tr>
				<tr valign="top">
					<td><input 
						<?= $k_c_a_settings["enable_product_meta_on_shop_page"]?"checked":""; ?>
						name="enable_product_meta_on_shop_page" 
						id="enable_product_meta_on_shop_page" 
						type="checkbox" 
						class="" 
						value="1"/>Product Meta auf Shop Seite</td>
				</tr>
				<tr valign="top">
					<td><input 
						<?= $k_c_a_settings["allow_site_url_short_code"]?"checked":""; ?>
						name="allow_site_url_short_code" 
						id="allow_site_url_short_code" 
						type="checkbox" 
						class="" 
						value="1"/>Allow site_url shortcode</td>
				</tr>
				
				<tr valign="top">
					<td><input 
						<?= $k_c_a_settings["enable_autocomplete_woocommerce_order"]?"checked":""; ?>
						name="enable_autocomplete_woocommerce_order" 
						id="enable_autocomplete_woocommerce_order" 
						type="checkbox" 
						class="" 
						value="1"/>Autocomplete Woocommerce Order</td>
				</tr>
				<tr valign="top">
					<td><input 
						<?= $k_c_a_settings["enable_custom_payment_gateway_icon"]?"checked":""; ?>
						name="enable_custom_payment_gateway_icon" 
						id="enable_custom_payment_gateway_icon" 
						type="checkbox" 
						class="" 
						value="1"/>Custom payment gateway icon</td>
				</tr>
				<tr valign="top">
					<td><input 
						<?= $k_c_a_settings["allow_woo_checkout_field_editor"]?"checked":""; ?>
						name="allow_woo_checkout_field_editor" 
						id="allow_woo_checkout_field_editor" 
						type="checkbox" 
						class="" 
						value="1"/>Woocommerce Checkout field editor</td>
				</tr>
				<tr valign="top">
					<td><input 
						<?= $k_c_a_settings["allow_custom_currency_for_woocommerce"]?"checked":""; ?>
						name="allow_custom_currency_for_woocommerce" 
						id="allow_custom_currency_for_woocommerce" 
						type="checkbox" 
						class="" 
						value="1"/>Custom Currency for WooCommerce</td>
				</tr>
				
				<tr valign="top">
					<td><input 
						<?= $k_c_a_settings["enable_vendor_name_on_best_selling_widget"]?"checked":""; ?>
						name="enable_vendor_name_on_best_selling_widget" 
						id="enable_vendor_name_on_best_selling_widget" 
						type="checkbox" 
						class="" 
						value="1"/>Eanble Vendor Name on Best Selling Widget</td>
				</tr>
				
			</tbody>
		</table>
		 <input type="submit" value="Submit" 
          class="button-primary"/> 
		</form>
	</div>
	<?php

}
add_action( 'admin_init', 'kinky_cms_admin_init' );
function kinky_cms_admin_init(){
	// if ( !current_user_can( 'manage_options' ) )
		// wp_die( 'Not allowed' );
	
	if(isset($_REQUEST['action'] )&& $_REQUEST['action']=="save_kinky_cms_setting" && current_user_can( 'manage_options' ){
		$k_c_a_settings=get_option("k_c_a_settings");
		
		$k_c_a_settings["enable_start_download"] = isset($_REQUEST["enable_start_download"])?true:false;
		$k_c_a_settings["enable_stop_buying_duplicate"] = isset($_REQUEST["enable_stop_buying_duplicate"])?true:false;
		$k_c_a_settings["enable_vendor_name_on_single_product_page"] = isset($_REQUEST["enable_vendor_name_on_single_product_page"])?true:false;
		$k_c_a_settings["enable_vendor_name_on_shop_page"] = isset($_REQUEST["enable_vendor_name_on_shop_page"])?true:false;
		
		$k_c_a_settings["enable_product_meta_on_shop_page"] = isset($_REQUEST["enable_product_meta_on_shop_page"])?true:false;
		
		$k_c_a_settings["allow_site_url_short_code"] = isset($_REQUEST["allow_site_url_short_code"])?true:false;
		
		$k_c_a_settings["enable_autocomplete_woocommerce_order"] = isset($_REQUEST["enable_autocomplete_woocommerce_order"])?true:false;
		$k_c_a_settings["enable_custom_payment_gateway_icon"] = isset($_REQUEST["enable_custom_payment_gateway_icon"])?true:false;
		$k_c_a_settings["allow_woo_checkout_field_editor"] = isset($_REQUEST["allow_woo_checkout_field_editor"])?true:false;
		$k_c_a_settings["allow_custom_currency_for_woocommerce"] = isset($_REQUEST["allow_custom_currency_for_woocommerce"])?true:false;
		
		$k_c_a_settings["enable_vendor_name_on_best_selling_widget"] = isset($_REQUEST["enable_vendor_name_on_best_selling_widget"])?true:false;
	
		update_option("k_c_a_settings", $k_c_a_settings);
		wp_redirect( add_query_arg( array( 'page' => 'kinky-cms-settings-menu', 'message' => '1' ), admin_url('admin.php' ) ) );
	}
	// exit();
}
?>