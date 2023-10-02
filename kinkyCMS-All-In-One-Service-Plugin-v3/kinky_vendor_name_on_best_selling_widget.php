<?php

if($k_c_a_settings["enable_vendor_name_on_best_selling_widget"] && (is_plugin_active_byme("dokan-lite/dokan.php") || is_plugin_active_byme("dokan/dokan.php"))){
		
	add_action( 'dynamic_sidebar_before',function(){
		add_filter ( 'woocommerce_get_price_html','add_vendor_name_product_price_html');
		
		
	});
	function add_vendor_name_product_price_html($price){
		global $product; 

		$seller = get_post_field( 'post_author', $product->get_id());
		$author  = get_user_by( 'id', $seller );
		$seller_info = sprintf( '<a  href="%s">%s</a>', dokan_get_store_url( $author->ID ), $author->display_name );
		
		// $seller_info .= "<br/>";		//force line break here
		
		// return $price.$seller_info;		//vendor name after price
		return $seller_info.$price;		//vendor name before price
		
	}
	add_action( 'dynamic_sidebar_after',function(){
		
		remove_filter ( 'woocommerce_get_price_html','add_vendor_name_product_price_html');
		
	});

}
?>