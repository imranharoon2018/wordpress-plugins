<?php
function wklw_validate_user_name($wklw_username){
	return validate_username($wklw_username);
	
	
}
function wklw_user_name_exists($wklw_username){
	
	return get_user_by('login',sanitize_key($wklw_username));
}
function wklw_validate_pasword( $wklw_password,$wklw_retype_password) {
	
	return $wklw_password == $wklw_retype_password;
}
function wklw_validate_first_name($wklw_first_name){
	$ret = true;
	$wklw_first_name = trim($wklw_first_name);
	if( strlen($wklw_first_name) == 0)
		$ret = false;	
	return $ret;
	
}
function wklw_validate_surname($wklw_surname){
	$ret = true;
	$wklw_surname = trim($wklw_surname);
	if( strlen($wklw_surname) == 0 )
		$ret = false;
	
	return $ret;
	
}
function wklw_validate_address1($wklw_address1){
	$ret = true;
	$wklw_address1 = trim($wklw_address1);
	if( strlen($wklw_address1) == 0 )
		$ret = false;
		
	return $ret;
	
}
function wklw_validate_address2($wklw_address2){
	$ret = true;
	$wklw_address2 = trim($wklw_address2);
	
	if( strlen($wklw_address2) == 0)
		$ret = false;	
	return $ret;
	
}
function wklw_validate_id_no($wklw_id_no){
	$ret = true;
	$wklw_id_no = trim($wklw_id_no);
	if( strlen($wklw_id_no) == 0)
		$ret = false;
	
	return $ret;
	
}
function wklw_routing_no_exist($wklw_routing_no,$user_id = null){
	
	$ret = true;
	global $wpdb;
	$usermeta = $wpdb->prefix."usermeta";
	$sql = "select user_id from $usermeta where meta_key = 'wklw_routing_no'  and meta_value = %s ";
	$params = array($wklw_routing_no);
	if( $user_id != null){
		$sql .= " and user_id != %s ";
		$params[] = $user_id;
	}
	$sql .= " limit 0,1 ";
	$sql = $wpdb->prepare($sql,$params);
	
	return $wpdb->get_var($sql);
}

function wklw_id_no_exist($wklw_id_no,$user_id = null){
	
	$ret = true;
	global $wpdb;
	$usermeta = $wpdb->prefix."usermeta";
	$sql = "select user_id from $usermeta where meta_key = 'wklw_id_no'  and meta_value = %s ";
	$params = array($wklw_id_no);
	if( $user_id != null){
		$sql .= " and user_id != %s ";
		$params[] = $user_id;
	}
	$sql .= " limit 0,1 ";
	$sql = $wpdb->prepare($sql,$params);
	wklw_log(__FILE__,66,$sql);
	return $wpdb->get_var($sql);
}

function wklw_validate_scan1($wklw_id_scan1){
	$ret = true;
	return $ret;
}
function wklw_validate_scan2($wklw_id_scan2){
	$ret = true;
	return $ret;
	
}
function wklw_validate_phone_no($wklw_phone_no){
	$ret = true;
	$wklw_phone_no = trim($wklw_phone_no);
	if( strlen($wklw_phone_no) != 5)
		$ret = false;
	
	return $ret;
	
}

function wklw_phone_no_exist($wklw_phone_no,$user_id = null){
	
	$ret = true;
	global $wpdb;
	$usermeta = $wpdb->prefix."usermeta";
	$sql = "select user_id from $usermeta where meta_key = 'wklw_phone_no'  and meta_value = %s ";
	$params = array($wklw_phone_no);
	if( $user_id != null){
		$sql .= " and user_id != %s ";
		$params[] = $user_id;
	}
	$sql .= " limit 0,1 ";
	$sql = $wpdb->prepare($sql,$params);
	return $wpdb->get_var($sql);
}

function wklw_validate_email($wklw_email){
	return is_email($wklw_email) ;
	
	
}
function wklw_email_exists($wklw_email){
	return email_exists($wklw_email);
	
}


?>