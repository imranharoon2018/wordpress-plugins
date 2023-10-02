<?php 
add_filter( 'heartbeat_received', 'wklw_update_last_heartbeat', 10, 2 );
add_filter( 'heartbeat_nopriv_received', 'wklw_update_last_heartbeat', 10, 2 );
add_filter( 'heartbeat_settings', 'wptuts_heartbeat_settings' );
function wptuts_heartbeat_settings( $settings ) {
	
    $settings['interval'] = 15; //Anything between 15-60
    return $settings;
}

function wklw_update_last_heartbeat($response,$data){	
	
	if( !empty($data['wklw_get_online_user_ids']) ){
		// set_online_status_to_offline_based_on_last_heartbeat();
		$response['wklw_online_user_ids'] = wklw_get_online_user_ids();
		$response['wklw_blocked_user_ids'] = wklw_get_blocked_user_ids();
		
	}
	if ( !empty( $data['wklw_update_last_heartbeat_user_id'] ) ) {
		// update_last_heartbeat_by_user_id($data['wklw_update_last_heartbeat_user_id'] );
		// $response['online_status'] = get_online_status_by_user_id($data['wklw_update_last_heartbeat_user_id'])
	}
	
	return $response;
	
}
?>
