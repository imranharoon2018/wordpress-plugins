<?php add_action( 'woocommerce_after_add_to_cart_form', function (){
// add_action( 'woocommerce_before_add_to_cart_button', function (){
	
	global $product;
	$product_id  = $product->get_id();
	$product_addons = WC_Product_Addons_Helper::get_product_addons(  $product_id  );
	$groups = "";
	$lengths = "";
	// var_dump($product_addons );exit();
	foreach($product_addons as $addon){
		
		if($addon["type"]=="checkbox"){
			if( isset($addon["enforce_minimum"]) && $addon["enforce_minimum"] && $addon["num_minimum"]){
					
					
					$addon_validation_length =  abs($addon["num_minimum" ]);
					$addon_validation_group_name = "addon-".$addon["field_name"];
					$groups .= ",".$addon_validation_group_name;
					$lengths .= ",". abs($addon["num_minimum" ]);
					
			}
		}
	}
		$groups =ltrim($groups,",");
		$lengths =ltrim($lengths,",");
	?>	
	<input type="hidden" id="woo_addon_limiter_target_group_names_<?=$product_id?>" name="woo_addon_limiter_target_group_names_<?=$product_id?>"  value ="<?=$groups?>"/>
	<input type="hidden" id="woo_addon_limiter_target_group_lengths_<?=$product_id?>"  name="woo_addon_limiter_target_group_lengths_<?=$product_id?>"  value ="<?=$lengths?>"/>
	<?php
	 // exit();
	return $cart_item_data;
},20,2);;
?>