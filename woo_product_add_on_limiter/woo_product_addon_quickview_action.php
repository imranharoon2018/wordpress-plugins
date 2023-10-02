<?php
add_filter('yith_wcqv_button_html',function($button, $product_id, $content ){
	 
		global $addon_names_for_js;
		global $addon_max_limit_for_js ;
		$product_addons = WC_Product_Addons_Helper::get_product_addons($product_id );
	
		 
		foreach($product_addons as $addon){
				
			if($addon["type"]=="checkbox"){
				if( isset($addon["enforce_minimum"]) && $addon["enforce_minimum"] && $addon["num_minimum"]){
					$addon_names_for_js[] = "addon-".$addon["field_name"];
					$addon_max_limit_for_js[] = abs($addon["num_minimum" ]);;
					update_option("batman423", get_option("batman423").$product_id."-(".$addon["num_minimum" ].",".$addon["num_minimum" ].")" );
				}
				
			}
				
		}
		// var_dump($addon_names_for_js);
		// var_dump($addon_max_limit_for_js);
		// var_dump($product->get_id());
		return $button;
	},10,3);
	?>