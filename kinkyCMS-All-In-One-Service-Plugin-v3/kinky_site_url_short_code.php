<?php

if($k_c_a_settings["allow_site_url_short_code"]){
	add_action( 'init', function() {

		add_shortcode( 'site_url', function( $atts = null, $content = null ) {
			return site_url();
		} );

	} );
}//if($k_c_a_settings["allow_site_url_short_code"]){
?>