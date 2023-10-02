<?php

add_action( 'wp_ajax_wklw_get_user_by_id', 'wklw_get_user_by_id' );
add_action( 'wp_ajax_nopriv_wklw_get_user_by_id', 'wklw_get_user_by_id' );
function wklw_get_user_by_id(){
	$to_send = null;
	$user_id = $_REQUEST["wklw_user_id"];
	$user = wklw_get_user_by_user_id($user_id );
	if($user){
		$user_id = $user->ID ;
		$profile_url = esc_url(get_author_posts_url($user_id ));
		$avatar_url = esc_url( get_avatar_url( $user_id   ));
		$display_name = $user->display_name;				
		$routing_no = get_user_meta($user_id ,'wklw_routing_no',true);
		$call_rate=get_user_meta($user_id,'wklw_call_rate',true);
		$online_status="offline";
		
		if($user->online_status){
			$online_status="online";
		}
		$to_send = array(
			"user_id" =>$user_id,
			"profile_url" =>$profile_url,
			"avatar_url" =>$avatar_url,
			"display_name" =>$display_name,
			"routing_no" =>$routing_no,
			"call_rate" =>$call_rate,
			"online_status" =>$online_status,
		);
	}
	wp_send_json( array("user"=>	$to_send) );
}
function wklw_get_blocked_user_ids(){
	$ret = array();
	global $wpdb;
	$wklw_not_allowed = $wpdb->prefix."wklw_not_allowed";
	$sql = " select user_id from $wklw_not_allowed";
	$sql = $wpdb->prepare($sql,$user_id);
	$results = $wpdb->get_results($sql);
	foreach($results as $result){
		$ret[]=$result->user_id;		
	}	
	return $ret;
}

function wklw_get_user_by_user_id($user_id){

global $wpdb;
$users = $wpdb->prefix."users";
$wklw_not_allowed = $wpdb->prefix."wklw_not_allowed";
$wklw_online = $wpdb->prefix."wklw_online";
$wklw_worker_ids = $wpdb->prefix."wklw_worker_ids";
	$sql = "
SELECT
	$wklw_worker_ids.user_id,
	$users.*,
	$wklw_online.online_start,
	$wklw_online.online_status
FROM
	$wklw_worker_ids 
	INNER JOIN $users ON $wklw_worker_ids.user_id = $users.ID
	LEFT JOIN $wklw_online ON $users.ID = $wklw_online.user_id 
WHERE
	$wklw_worker_ids.user_id NOT IN ( SELECT user_id FROM $wklw_not_allowed  )
AND
$users.ID = %s  

Limit 0,1	
	";
	$sql = $wpdb->prepare($sql,$user_id);
	$results = $wpdb->get_results($sql);
	if(is_array($results) && count($results)>0 )
		return $results[0];
	return null;
}

function wklw_get_user_list_to_display(){
global $wpdb;
$users = $wpdb->prefix."users";
$wklw_not_allowed = $wpdb->prefix."wklw_not_allowed";
$wklw_online = $wpdb->prefix."wklw_online";
$wklw_worker_ids = $wpdb->prefix."wklw_worker_ids";
	$sql = "
SELECT
	$wklw_worker_ids.user_id,
	$users.*,
	$wklw_online.online_start,
	$wklw_online.online_status
FROM
	$wklw_worker_ids 
	INNER JOIN $users ON $wklw_worker_ids .user_id = $users.ID
	LEFT JOIN $wklw_online ON $users.ID = $wklw_online.user_id 
WHERE
	$wklw_worker_ids.user_id NOT IN ( SELECT user_id FROM $wklw_not_allowed  )

	ORDER BY online_status desc, online_start desc
	";
	return $wpdb->get_results($sql);
}

function wklw_get_user_list_to_for_csv(){
global $wpdb;
$users = $wpdb->prefix."users";
$wklw_not_allowed = $wpdb->prefix."wklw_not_allowed";
$wklw_online = $wpdb->prefix."wklw_online";
$wklw_worker_ids = $wpdb->prefix."wklw_worker_ids";
	$sql = "
SELECT
	$wklw_worker_ids.user_id,
	$users.*
FROM
	$wklw_worker_ids 
INNER JOIN $users ON $wklw_worker_ids.user_id = $users.ID

	ORDER BY $users.display_name
	";
	return $wpdb->get_results($sql);
}
?>