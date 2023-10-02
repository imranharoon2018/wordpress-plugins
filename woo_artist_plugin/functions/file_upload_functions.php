<?php
function wap_verify_file($input_name){
		// We are only allowing images
	$allowedMimes = array(
		'jpg|jpeg|jpe' => 'image/jpeg'
		// ,		
		// 'png'          => 'image/png',
	);
	 
	$fileInfo = wp_check_filetype(basename($_FILES[$input_name]['name']), $allowedMimes);
	if (!empty($fileInfo['ext'])) {
		// This file is valid
	} else {
		// return array( "valid" => false, "msg" => __('The file type is not allowed','wklw');
		return array( "valid" => false, "msg" => __('Use jpeg, jpg  format','wap'));
	}


	$file_size = filesize($_FILES[$input_name]['tmp_name']);
	
	if( $file_size == 0 )
		return array( "valid" => false, "msg" =>  __('File is empty','wap'));
	
	if( $file_size > wp_max_upload_size() )
		return array( "valid" => false, "msg" =>  __('File is too big','wap'));
	
	return array( "valid" => true, "msg" => "");
	
	
}
function wap_set_upload_dir( $arg=array() ){
	$user_id = get_current_user_id();
	$arg["path"]= trailingslashit($arg["basedir"]).$user_id;
	$arg["url"]= trailingslashit($arg["baseurl"]).$user_id;
	return $arg;
}
 function  wap_add_dir_filter(){
	 
	 add_filter( 'upload_dir', 'wap_set_upload_dir' );
 }
 
 
 function wap_remove_dir_filter(){
	 // var_dump($data);
	 remove_filter( 'upload_dir', 'wap_remove_dir_filter' );
 }
function wap_handle_upload_file_by_param($myfile){
	$overrides = array(
		 'test_form' => false
	);
		wap_add_dir_filter(); 
		$file = wp_handle_upload( $_FILES[$myfile],$overrides );
		wap_remove_dir_filter();
		$file["name"] = isset($file["file"]) ?  wp_basename($file["file"]) : "";
	
		return $file+array("file"=>"","url"=>"","name"=>"");
}
?>