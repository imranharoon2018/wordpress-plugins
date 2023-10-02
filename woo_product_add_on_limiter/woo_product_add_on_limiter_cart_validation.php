<?php
function so_validate_add_cart_item( $passed, $product_id, $quantity, $variation_id = '', $variations= '' ) {
	$ret = true;
	$product_addons = WC_Product_Addons_Helper::get_product_addons( $product_id );
	// $post_data = $_REQUEST;
	// var_dump($post_data);
	// // exit();
	// echo "<hr/>";
	// 
	 // var_dump($variation_id);echo "<hr/>";
	 // var_dump($quantity);echo "<hr/>";
	 // var_dump($passed);echo "<hr/>";
	 // var_dump($product_addons);echo "<hr/>";
	foreach($product_addons as $an_addon){
		if(		isset($an_addon["enforce_minimum"]) && 
				$an_addon["enforce_minimum"] && isset($an_addon["num_minimum"]) 
			){
			$addon_name = "addon-".$an_addon["field_name"];
			
			if(!isset($_REQUEST[$addon_name]))
				return false;			
			if(!is_array($_REQUEST[$addon_name]))
				return false;
			if(count($_REQUEST[$addon_name]) < $an_addon["num_minimum"])
				return false;
			
				
				
		}
	}
	// var_dump("ever here");exit();
	return $ret;
}
// add_filter( 'woocommerce_add_to_cart_validation', 'so_validate_add_cart_item', 10, 5 );
?>