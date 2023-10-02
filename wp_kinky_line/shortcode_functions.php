<?php
function wklw_show_sign_up_form(){
	ob_start();
	require_once(WKLW_PLUGIN_DIR."wklw_screens/wklw_signup.php");
	return ob_get_clean();
}
function wklw_show_dashboard(){
	ob_start();
	require_once(WKLW_PLUGIN_DIR."wklw_screens/wklw_dashboard.php");
	return ob_get_clean();
}
function wklw_show_listing(){
	ob_start();
	require_once(WKLW_PLUGIN_DIR."wklw_screens/wklw_listing.php");
	return ob_get_clean();
}
function wklw_show_thankyou(){
	ob_start();
	require_once(WKLW_PLUGIN_DIR."wklw_screens/wklw_thankyou.php");
	return ob_get_clean();
}


?>