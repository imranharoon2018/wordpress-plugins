<?php


if($k_c_a_settings["enable_stop_buying_duplicate"] &&   is_plugin_active_byme("woocommerce/woocommerce.php")){
	function kinkyCMS_disable_repeat_purchase( $purchasable, $product ) {

		// Get the ID for the current product
		$product_id = $product->id; 
	 
		// return false if the customer has bought the product and is currently available for download
		if ( wc_customer_bought_product( get_current_user()->user_email, get_current_user_id(), $product_id ) && ($product->downloadable == 'yes') ) {
			
			if( WC()->customer != null )
				if ( $downloads = WC()->customer->get_downloadable_products() ) {

						foreach ( $downloads as $download ) :
							if ($download['product_id'] == $product->id) {
								$purchasable = false;
							}
						endforeach;

				}
		}

		return $purchasable;
	}
	add_filter( 'woocommerce_is_purchasable', 'kinkyCMS_disable_repeat_purchase', 10, 2 );

	function kinkyCMS_repeat_purchase_disabled_message() {

		global $product;
		$once = 0;
		$end = false;

		if ( wc_customer_bought_product( get_current_user()->user_email, get_current_user_id(), $product->id ) && ($product->downloadable == 'yes') ) {
			
			if( WC()->customer != null )
				if ( $downloads = WC()->customer->get_downloadable_products() ) {

					do_action( 'woocommerce_before_available_downloads' ); ?>

						<?php foreach ( $downloads as $download ) :

							if ($download['product_id'] == $product->id) {

								$once++;

								if ($end == false)
									$end = true;

								if ($once == 1) {
									echo '<div class="woocommerce"><div class="woocommerce-info wc-nonpurchasable-message"><p><strong></p>';
									echo '<div class="product-page-links">';

									//The below codes injects some CSS for smaller than desktop devices. Depending on how many download links there are, the list can get quite long, which may affect the site styling depending on the theme. Specifically a default custom theme. Remove the below echo line if this is not required.
									echo '<style>@media (max-width : 1200px){ .summary.entry-summary {width: 100%!important; display: block;} }</style>';

								}

								do_action( 'woocommerce_available_download_start', $download );

								if ( is_numeric( $download['downloads_remaining'] ) ) {
									echo apply_filters( 'woocommerce_available_download_count', '<span class="count">' . sprintf( _n( '%s download remaining', '%s downloads remaining', $download['downloads_remaining'], 'woocommerce' ), $download['downloads_remaining'] ) . '</span> ', $download );
								}

								echo apply_filters( 'woocommerce_available_download_link', '<a class="download-link-product-page" href="' . esc_url( $download['download_url'] ) . '">Download Starten</a>', $download ) ;

								do_action( 'woocommerce_available_download_end', $download );

							}

						endforeach;

							if ($end) {
								echo '</div></div></div>';
							}

					do_action( 'woocommerce_after_available_downloads' );

					}

			}

	}
	add_action( 'woocommerce_single_product_summary', 'kinkyCMS_repeat_purchase_disabled_message', 31 );
}//if($k_c_a_settings["enable_stop_buying_duplicate"])
?>