<?php
function wlkw_user_id_in_not_allowed($user_id){
	global $wpdb;
	$wklw_not_allowed = $wpdb->prefix."wklw_not_allowed";
	$sql = "select count(*)  from $wklw_not_allowed where  user_id  = %s";
	$sql = $wpdb->prepare($sql,$user_id);
	
	return $wpdb->get_var($sql);
}
function wlkw_insert_into_not_allowed($user_id){
	global $wpdb;
	$wklw_not_allowed = $wpdb->prefix."wklw_not_allowed";
	$sql = "insert into  $wklw_not_allowed  (user_id )values(%s)";
	$sql = $wpdb->prepare($sql,$user_id);
	$wpdb->query($sql);
}
function wlkw_delete_from_not_allowed($user_id){
	global $wpdb;
	$wklw_not_allowed = $wpdb->prefix."wklw_not_allowed";
	$sql = "delete from  $wklw_not_allowed  where user_id = %s ";
	$sql = $wpdb->prepare($sql,$user_id);
	$wpdb->query($sql);
}

?>