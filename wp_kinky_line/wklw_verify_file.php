<?php
function wklw_verify_scan($input_name){
		// We are only allowing images
	$allowedMimes = array(
		'jpg|jpeg|jpe' => 'image/jpeg',		
		'png'          => 'image/png',
	);
	 
	$fileInfo = wp_check_filetype(basename($_FILES[$input_name]['name']), $allowedMimes);
	if (!empty($fileInfo['ext'])) {
		// This file is valid
	} else {
		// return array( "valid" => false, "msg" => __('The file type is not allowed','wklw');
		return array( "valid" => false, "msg" => __('Use jpeg, jpg, png format','wklw'));
	}
	 // __('An account already use this Email','wklw');;
	// if (!@getimagesize($_FILES[$input_name]['tmp_name']))
		// return array( "valid" => false, "msg" => "The image is not valid");

	$file_size = filesize($_FILES[$input_name]['tmp_name']);
	update_option("wklw_18_".time(),$_FILES);
	update_option("wklw_19_".time(),$input_name);
	update_option("wklw_20",$_FILES[$input_name]['tmp_name']);
	// update_option("wklw_20",$file_size);
	
	// wklw_log("wklw_".time(),18,$_FILES[$input_name]['tmp_name']);
	// wklw_log("wklw_".time(),19,$file_size);
	// wklw_log("wklw_2_".time(),20,$file_size);
	if( $file_size == 0 )
		return array( "valid" => false, "msg" =>  __('File is empty','wklw'));
	
	if( $file_size > wp_max_upload_size() )
		return array( "valid" => false, "msg" =>  __('File is too big','wklw'));
	
	return array( "valid" => true, "msg" => "");
	
	
}
?>