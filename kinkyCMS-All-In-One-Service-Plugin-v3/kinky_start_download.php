<?php

if($k_c_a_settings["enable_start_download"]&&  is_plugin_active_byme("woocommerce/woocommerce.php")){
	add_action( 'woocommerce_account_downloads_column_download-file', function( $download ) {
		echo '<a href="' . esc_url( $download['download_url'] ) . '">Download starten</a>';
		
	});
}
?>