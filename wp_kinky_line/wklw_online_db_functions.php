<?php
function wklw_get_online_user_ids(){
	$arr_ids = array();
	global $wpdb;
	$wklw_online = $wpdb->prefix."wklw_online";
	$wklw_not_allowed = $wpdb->prefix."wklw_not_allowed";
	$sql = "
		select
			user_id
		from
			$wklw_online
		where
			online_status = 1
		
		and user_id not in(
			select user_id from $wklw_not_allowed 
		
		)
	";
	
	
	$results = $wpdb->get_results($sql);
	if(is_array($results))
	foreach($results as $result){
		$arr_ids[]=$result->user_id;
	}
	
	return $arr_ids;
}

function set_online_status_to_offline_based_on_last_heartbeat(){
	global $wpdb;
	$wklw_online = $wpdb->prefix."wklw_online";
	$sql = "
		update
			$wklw_online
		set
			online_status = 0
		
		where 
			UNIX_TIMESTAMP(now()) - last_heart_beat > 60
			
		
	";
	// $sql = $wpdb->prepare($sql);
	
	$result = $wpdb->query($sql);
}
function get_online_status_by_user_id($user_id){
	global $wpdb;
	$wklw_online = $wpdb->prefix."wklw_online";
	$sql = "
		select
			online_status
		from
		
			$wklw_online
		where 
			user_id = %s
		limit 0,1
	";
	$sql = $wpdb->prepare($sql,$user_id);
	
	$result = $wpdb->get_var($sql);
	if ( $result===null ) return 0;
	return $result;
	
}
function update_last_heartbeat_by_user_id($user_id){
	global $wpdb;
	$wklw_online = $wpdb->prefix."wklw_online";
	$sql = "
		update 
			$wklw_online 
		set 
		
			last_heart_beat = UNIX_TIMESTAMP(now()),
			online_status =1
		where 
			user_id = %s
	";
	$sql = $wpdb->prepare($sql,$user_id);
	
	return $wpdb->query($sql);
}
function delete_from_online_table($user_id){
	global $wpdb;
	$wklw_online = $wpdb->prefix."wklw_online";
	$sql = "delete from $wklw_online where user_id = %s";
	$sql = $wpdb->prepare($sql,$user_id);
	wklw_log(__FILE__,10,$sql);
	$wpdb->query($sql);
}
function wklw_user_exists_in_online($user_id){
	global $wpdb;
	$wklw_online = $wpdb->prefix."wklw_online";
	$sql = "
		select
			count(*) 
		from
			$wklw_online
		where 
			user_id = %s
	";
	$sql = $wpdb->prepare($sql,$user_id);
	
	return $wpdb->get_var($sql);
	
}
function update_online_table($user_id){
	global $wpdb;
	$wklw_online = $wpdb->prefix."wklw_online";
	$sql = "
		update 
			$wklw_online 
		set 
			online_start = UNIX_TIMESTAMP(now()),
			last_heart_beat = UNIX_TIMESTAMP(now()),
			online_status =1
		where 
			user_id = %s
	";
	$sql = $wpdb->prepare($sql,$user_id);
	
	return $wpdb->query($sql);
	
}
function insert_into_online_table($user_id){
	global $wpdb;
	$wklw_online = $wpdb->prefix."wklw_online";
	$sql = "insert into $wklw_online (user_id,online_start,last_heart_beat,online_status) values(%s,UNIX_TIMESTAMP(now()),UNIX_TIMESTAMP(now()),1)";
	$sql = $wpdb->prepare($sql,$user_id);
	
	$wpdb->query($sql);
}
function update_online_stattus_to_offline($user_id){
	global $wpdb;
	$wklw_online = $wpdb->prefix."wklw_online";
	$sql = "
		update 
			$wklw_online 
		set 
			online_status =0
		where 
			user_id = %s
	";
	$sql = $wpdb->prepare($sql,$user_id);
	
	return $wpdb->query($sql);
}
?>