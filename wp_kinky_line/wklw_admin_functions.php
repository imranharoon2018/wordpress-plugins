<?php
add_action( 'admin_init', 'wklw_may_create_routing_csv_file');
add_action( 'admin_menu', function(){
	
	add_menu_page( 'Wp Kinky Line', 'Wp Kinky Line', 'manage_options', 'wp-kinky-line', 'wklw_show_admin', 'dashicons-welcome-widgets-menus', 90 );
});
function get_csv_file_path(){
	$upload_dir  = wp_get_upload_dir();
	$path = trailingslashit($upload_dir['path']);	
	$url = trailingslashit($upload_dir['url']);	
	$filename = time().".csv";
	
	return  array (
			'path' =>$path.$filename,
			'url' =>$url.$filename
			
			);
	
	// mkdir($path)
	
}
function wklw_may_create_routing_csv_file(){
	
if(isset($_REQUEST['create_csv_file']))
	if(wp_verify_nonce( $_REQUEST['create_csv_file'], 'do_create_csv_file')){
		
		$upload_dir = wp_get_upload_dir();
		$path = $upload_dir["path"];	
		$url = $upload_dir["url"];	
		$users = wklw_get_user_list_to_for_csv();
		$str = "Name,Phone,RoutingNo".PHP_EOL;
		foreach($users as $user){
			$name = $user->display_name;
			$phone = get_user_meta($user->ID,'wklw_routing_no',true);
			$routing_no = get_user_meta($user->ID,'wklw_routing_no',true);
			
			$str .= '"'.$name.'","'.$phone.'","'.$routing_no.'"'.PHP_EOL;//.'<br/>';
		}
		// echo $str;
		$file = get_csv_file_path();
		file_put_contents($file['path'],$str);
		update_option("wklw_routing_csv_url",$file["url"]);
		update_option("wklw_routing_csv_create_date", wp_date( 'F j, Y' ));
	}
}
function wklw_show_admin(){
	require_once(WKLW_PLUGIN_DIR."admin_screens/wklw_admin.php");
}

?>