<?php

if($k_c_a_settings["enable_vendor_name_on_single_product_page"] && (is_plugin_active_byme("dokan-lite/dokan.php") || is_plugin_active_byme("dokan/dokan.php")) ){
	add_action( 'woocommerce_single_product_summary', 'seller_name_on_single', 11 );
		 function seller_name_on_single(){
				   global $product;
			$seller = get_post_field( 'post_author', $product->get_id());
			$author  = get_user_by( 'id', $seller );

			$store_info = dokan_get_store_info( $author->ID );

			if ( !empty( $store_info['store_name'] ) ) { ?>
				<span class="details">
						<?php printf( '<a href="%s">%s</a>', dokan_get_store_url( $author->ID ), $author->display_name ); ?>
					</span>
				<?php
			}
		 }
}//if($k_c_a_settings["enable_vendor_name_on_single_product_page"])
?>