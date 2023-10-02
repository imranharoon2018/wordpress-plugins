<?php
add_action('init','load_my_textdomain',1);
// add_action('plugins_loaded','load_my_textdomain');
function load_my_textdomain() {
		// var_dump("hi"); exit();
		load_plugin_textdomain( 'wklw', false,'wp_kinky_line/languages/' );
		// exit();
		// echo WKLW_PLUGIN_DIR.'languages/wp_kinky_line-de_DE.mo';exit();	

		}
// apply_filters( 'plugin_locale', determine_locale(), $domain );
add_filter( 'plugin_locale',function( $locale, $domain ){
	if($domain=="wklw"){
		return "de_DE";
	}
	return $locale;
},PHP_INT_MAX,2);
?>