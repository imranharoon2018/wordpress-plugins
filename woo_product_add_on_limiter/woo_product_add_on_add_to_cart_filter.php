<?php
	 function woo_addon_limiter_check_forced_minimum_addons( $product_id){
		
		 $ret = false;
		$product_addons = WC_Product_Addons_Helper::get_product_addons( $product_id );
		
		foreach($product_addons as $an_addon){
			if(		isset($an_addon["enforce_minimum"]) && 
					$an_addon["enforce_minimum"] && isset($an_addon["num_minimum"]) &&  abs($an_addon["num_minimum"])
					
				){
					$ret=true;
			}
		
		}
		
		return $ret;
	 }
add_filter( 'woocommerce_product_add_to_cart_text',  'woo_addon_limiter_add_to_cart_text' , PHP_INT_MAX, 2 );
	 function woo_addon_limiter_add_to_cart_text($text, $product = null){
		 if ( null === $product ) {
			global $product;
		}
		
		if ( ! is_a( $product, 'WC_Product' ) ) {
			return $text;
		}
		
		if ( ! is_single( $product->get_id() ) ) {
			if ( woo_addon_limiter_check_forced_minimum_addons( $product->get_id() ) ) {
				$text = apply_filters( 'filter_woo_addon_limiter_add_to_cart_text', __( 'Select options', 'woocommerce-product-addons' ) );
			}
		}

		return $text;
		 
	 }
		
add_filter( 'woocommerce_add_to_cart_url',  'woo_addon_limiter_add_to_cart_url' , 10, 2 );
add_filter( 'woocommerce_product_add_to_cart_url',  'woo_addon_limiter_add_to_cart_url' , 10, 2 );
	
		 function woo_addon_limiter_add_to_cart_url( $url, $product = null ) {
				
		if ( null === $product ) {
			global $product;
		}

		if ( ! is_a( $product, 'WC_Product' ) ) {
			return $url;
		}

		if ( ! is_single( $product->get_id() ) && in_array( $product->get_type(), apply_filters( 'filter_woo_addon_limiter_add_to_cart_product_types', array( 'subscription', 'simple' ) ) ) && ( ! isset( $_GET['wc-api'] ) || 'WC_Quick_View' !== $_GET['wc-api'] ) ) {
			if ( woo_addon_limiter_check_forced_minimum_addons( $product->get_id() ) ) {
				$url = apply_filters( 'filter_woo_addon_limiter_add_to_cart_url', get_permalink( $product->get_id() ) );
			}
		}
		
		return $url;
	}
	add_filter( 'woocommerce_product_supports',  'woo_addon_limiter_ajax_add_to_cart_supports' , PHP_INT_MAX, 3 );
 function woo_addon_limiter_ajax_add_to_cart_supports( $supports, $feature, $product ) {
	
		if ( 'ajax_add_to_cart' === $feature && woo_addon_limiter_check_forced_minimum_addons( $product->get_id() ) ) {
			$supports = false;
		}

		return $supports;
	}