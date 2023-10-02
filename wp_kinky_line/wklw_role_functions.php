<?php
function user_id_exists_in_worker_ids($user_id){
	global $wpdb;
	$wpdb->query($sql);
	$wklw_worker_ids = $wpdb->prefix."wklw_worker_ids";
	$sql = "
	select
		user_id
	from
		$wklw_worker_ids  
	where 
		user_id = %s
	limit 0,1	
	";
	$sql = $wpdb->prepare($sql,$user_id);
	
		
	return $wpdb->get_var($sql);	
}
function insert_user_id_into_worker_ids($user_id){
	global $wpdb;
	
	$wklw_worker_ids = $wpdb->prefix."wklw_worker_ids";
	$sql = "
	insert 
		into		
	$wklw_worker_ids  
		(user_id)
	values
		(%s)";
	$sql = $wpdb->prepare($sql,$user_id);	
		
	$wpdb->query($sql);	
}

function delete_user_id_from_worker_ids($user_id){
	global $wpdb;
	
	$wklw_worker_ids = $wpdb->prefix."wklw_worker_ids";
	$sql = "
	delete 	from				
		$wklw_worker_ids  
	where
		user_id = %s";
	$sql = $wpdb->prepare($sql,$user_id);	
	// echo $sql;exit();	
	$wpdb->query($sql);	
}

add_action( 'set_user_role', function($user_id, $role='', $old_roles =array()){
	
	if( wklw_is_worker (get_user_by('ID',$user_id) )){
		if( !user_id_exists_in_worker_ids($user_id) ){
			insert_user_id_into_worker_ids($user_id);
		}
		
	}
	// var_dump($old_roles); exit();
	foreach($old_roles as $a_role){
		if( strtolower( $a_role ) == strtolower(KINKY_LINE_WORKER) ){
			delete_user_meta($user_id,"wklw_routing_no");
			delete_user_id_from_worker_ids($user_id);
			break;
		}
	}
},PHP_INT_MAX,3);
?>