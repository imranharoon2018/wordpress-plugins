<?php
if ( ! function_exists( 'wp_handle_upload' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
}
 
function wklw_create_user_login_from_user_name($user_name){
	$user_login = sanitize_key($user_name);
	$user = get_user_by('login',$user_login);
	if($user){
		return $user_login."_".time();
	}
	return $user_login;
}
function wklw_set_upload_dir( $arg=array() ){
	// var_dump($arg);

	// array(6) { ["path"]=> string(47) "D:\xampp\htdocs\xx36/wp-content/uploads/2021/07" ["url"]=> string(48) "http://localhost/xx36/wp-content/uploads/2021/07" ["subdir"]=> string(8) "/2021/07" ["basedir"]=> string(39) "D:\xampp\htdocs\xx36/wp-content/uploads" ["baseurl"]=> string(40) "http://localhost/xx36/wp-content/uploads" ["error"]=> bool(false) }
	$arg["path"]= trailingslashit($arg["basedir"]).KINKY_LINE_WORKER_SCANS;
	$arg["url"]= trailingslashit($arg["baseurl"]).KINKY_LINE_WORKER_SCANS;
	return $arg;
}
 function wklw_add_dir_filter(){
	 
	 add_filter( 'upload_dir', 'wklw_set_upload_dir' );
 }
 
 
 function wklw_remove_dir_filter(){
	 // var_dump($data);
	 remove_filter( 'upload_dir', 'wklw_set_upload_dir' );
 }
function wklw_handle_id_scan_by_param($myfile){
	$overrides = array(
		 'test_form' => false
	);
		wklw_add_dir_filter(); 
		$scan = wp_handle_upload( $_FILES[$myfile],$overrides );
		wklw_remove_dir_filter();
		
	
		return $scan+array("file"=>"","url"=>"");
}
function wklw_handle_id_scan(){
	$overrides = array(
		 'test_form' => false
	);
		wklw_add_dir_filter(); 
		$scan1 = wp_handle_upload( $_FILES['wklw_id_scan1'],$overrides );
		wklw_remove_dir_filter();
		
		wklw_add_dir_filter(); 
		$scan2 = wp_handle_upload( $_FILES['wklw_id_scan2'],$overrides );
		wklw_remove_dir_filter();
		return array(
				"scan1"=>($scan1+array("file"=>"","url"=>"")), 
				"scan2"=>$scan2+array("file"=>"","url"=>""));
}
function wklw_validate_signup_form(){
	$ret = true;
	
	if( !wklw_validate_user_name( $_REQUEST["wklw_username"]) ){
		// __('Username','wklw')
		$_REQUEST["wklw_username_msg"] = __('This username is not valid','wklw');
		$ret = false;
	}
	if( !wklw_validate_pasword( $_REQUEST["wklw_password"],$_REQUEST["wklw_retype_password"]) ){
		$_REQUEST["wklw_password_msg"] =  __('Passwords does not match','wklw');
		$ret = false;
	}
	if( wklw_user_name_exists( $_REQUEST["wklw_username"]) ){
		
		$_REQUEST["wklw_username_msg"] = __('Choose a Different User name','wklw');
		$ret = false;
	}
/*	*/
	if( !wklw_validate_first_name( $_REQUEST["wklw_first_name"]) ){
		$_REQUEST["wklw_first_name_msg"] =__('First Name cannot be empty','wklw');
		$ret = false;
	}
	if( !wklw_validate_surname( $_REQUEST["wklw_surname"]) ){
		$_REQUEST["wklw_surname_msg"] = __('Surname cannot be empty','wklw');
		$ret = false;
	}
	if( !wklw_validate_address1( $_REQUEST["wklw_address1"]) ){
		$_REQUEST["wklw_address1_msg"] = __('Address cannot be empty','wklw');
		$ret = false;
	}
	if( !wklw_validate_address2( $_REQUEST["wklw_address2"]) ){
		$_REQUEST["wklw_address2_msg"] = __('Address2 cannot be empty','wklw');
		$ret = false;
	}
	if( !wklw_validate_id_no( $_REQUEST["wklw_id_no"]) ){
		$_REQUEST["wklw_id_no_msg"] = __('Id Cannot be empty','wklw');
		$ret = false;
	}
	
	if( wklw_id_no_exist( $_REQUEST["wklw_id_no"]) ){
		$_REQUEST["wklw_id_no_msg"] = __('An account already use this Id No','wklw');	
		$ret = false;
	}
	$result = wklw_verify_scan("wklw_id_scan1");
	$ret = $result["valid"];
	$_REQUEST["wklw_id_scan1_msg"] = $result["msg"];
	
	$result = wklw_verify_scan("wklw_id_scan2");
	$ret = $result["valid"];
	$_REQUEST["wklw_id_scan2_msg"] = $result["msg"];
	
	$result = wklw_verify_scan("wklw_avatar");
	$ret = $result["valid"];
	$_REQUEST["wklw_avatar_msg"] = $result["msg"];
	/*
	if( !wklw_validate_scan1( $_REQUEST["wklw_id_scan1"]) ){
		$_REQUEST["wklw_id_scan1_msg"] = "Not valid file format";
		
		$ret = false;
	}
	if( !wklw_validate_scan2( $_REQUEST["wklw_id_scan2"]) ){
		$_REQUEST["wklw_id_scan2_msg"] = "Not valid file format";
		$ret = false;
	}*/
	/*
	if( !wklw_validate_phone_no( $_REQUEST["wklw_phone_no"]) ){
		$_REQUEST["wklw_phone_no_msg"] = "Length should be 5";
		$ret = false;
	}*/
	if( wklw_phone_no_exist( $_REQUEST["wklw_phone_no"]) ){
		$_REQUEST["wklw_phone_no_msg"] = __('An account already use this Phone No','wklw');
		$ret = false;
	}
	if( !wklw_validate_email( $_REQUEST["wklw_email"]) ){
		$_REQUEST["wklw_email_msg"] = __('This fomat is not allowed','wklw');;
		$ret = false;
	}
	if( wklw_email_exists( $_REQUEST["wklw_email"]) ){
		$_REQUEST["wklw_email_msg"] = __('An account already use this Email','wklw');;
		$ret = false;
	}
	
	
	return $ret ;
	
	
}
function wklw_reset_signup_form(){

	unset($_REQUEST['wklw_username']);
	unset($_REQUEST['wklw_first_name']);
	unset($_REQUEST['wklw_surname']);
	unset($_REQUEST['wklw_address1']);
	unset($_REQUEST['wklw_address2']);
	unset($_REQUEST['wklw_id_no']);
	unset($_REQUEST['wklw_id_scan1']);
	unset($_REQUEST['wklw_id_scan2']);
	unset($_REQUEST['wklw_phone_no']);
	unset($_REQUEST['wklw_email']);

	unset($_POST['wklw_name']);
	unset($_POST['wklw_surname']);
	unset($_POST['wklw_address1']);
	unset($_POST['wklw_address2']);
	unset($_POST['wklw_id_no']);
	unset($_POST['wklw_id_scan1']);
	unset($_POST['wklw_id_scan2']);
	unset($_POST['wklw_phone_no']);
	unset($_POST['wklw_email']);

	
}
function may_create_worker(){	wklw_log(__FILE__,62,62);
if(isset($_REQUEST['wklw_signup'])  ){
	
	wklw_log(__FILE__,64,64);
	
	// if(wp_verify_nonce( $_REQUEST['wklw_signup'], 'do_wklw_signup') && wklw_validate_signup_form() ){
	if(wp_verify_nonce( $_REQUEST['wklw_signup'], 'do_wklw_signup')   ){
	
		wklw_log(__FILE__,66,66);
		// if ( ! username_exists( $user_login ) ) {
		if (  wklw_validate_signup_form() ) {
			wklw_log(__FILE__,68,68);
			$wklw_username =  $_REQUEST["wklw_username"];
			$wklw_password =  $_REQUEST["wklw_password"];
			$wklw_first_name = $_REQUEST["wklw_first_name"];
			$wklw_surname = $_REQUEST["wklw_surname"];
			$wklw_address1 = $_REQUEST["wklw_address1" ];
			$wklw_address2 = $_REQUEST["wklw_address2"];
			$wklw_id_no = $_REQUEST["wklw_id_no"];
			$wklw_id_scan1= $_REQUEST["wklw_id_scan1"];
			$wklw_id_scan2 = $_REQUEST["wklw_id_scan2"];
			$wklw_phone_no= $_REQUEST["wklw_phone_no"];
			$wklw_email= $_REQUEST["wklw_email" ];
			

			$user_login = $wklw_username;
			$user_email = $wklw_email;
			$user_pass = $wklw_password;
		

			$user_id= wp_create_user(  $user_login,  $user_pass,  $user_email );wklw_log(__FILE__,84,$user_id);
				wklw_log(__FILE__,85,array(  $user_login,  $user_pass,  $user_email ));
			if ( !is_wp_error( $user_id ) ) {
				wklw_log(__FILE__,87,87);
				$user = get_user_by('id',$user_id);
				$user->set_role(KINKY_LINE_WORKER);
				wp_update_user(array('ID'=>$user_id,'display_name'=>$wklw_username,'nicename'=>$wklw_username) );
				add_user_meta($user_id, "wklw_first_name",$wklw_first_name,true);
				add_user_meta($user_id, "wklw_surname",$wklw_surname,true);
				add_user_meta($user_id, "wklw_address1",$wklw_address1,true);
				add_user_meta($user_id, "wklw_address2",$wklw_address2,true);
				add_user_meta($user_id, "wklw_id_no",$wklw_id_no,true);
				add_user_meta($user_id, "wklw_phone_no",$wklw_phone_no,true);
				// add_user_meta($user_id, "wklw_routing_no",$wklw_phone_no,true);
				add_user_meta($user_id, "wklw_call_rate",get_option("wklw_default_callrate"),true);
				$scans = wklw_handle_id_scan();
				add_user_meta($user_id,"wklw_id_scan1",
								array(
									"path"	=> $scans["scan1"]["file"],
									"url"	=> $scans["scan1"]["url"],
									"name"	=> wp_basename($scans["scan1"]["file"])
								),
								true
							);
				add_user_meta($user_id,"wklw_id_scan2",
					array(
						"path"	=> $scans["scan2"]["file"],
						"url"	=> $scans["scan2"]["url"],
						"name"	=> wp_basename($scans["scan2"]["file"])
					),
					true							
				);
				
				$wklw_avatar =wklw_handle_id_scan_by_param("wklw_avatar");
				add_user_meta($user_id,"wklw_avatar",
					array(
						"path"	=> $wklw_avatar["file"],
						"url"	=> $wklw_avatar["url"],
						"name"	=> wp_basename($wklw_avatar["file"])
					),
					true							
				);
				wlkw_insert_into_not_allowed($user_id);
				// wklw_log(__FILE__,170,get_permalink(get_option("wklw_thankyou_page_id")));
				// echo "hi";
					// exit()
				// $_REQUEST= null;
				wklw_reset_signup_form();
				$url = get_permalink(get_option("wklw_thankyou_page_id"));
				$url = add_query_arg( 'new_sign_up', wp_create_nonce( 'finish_new_sign_up' ), $url );
				wp_redirect($url);
				exit();
			}
			
			
		}

		
	}
}
	// exit();
}
?>