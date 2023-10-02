<?php
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
	if (wklw_is_worker(wp_get_current_user())) {
	  show_admin_bar(false);
	}
}
add_action("init","may_change_user_avatar");

function may_change_user_avatar(){
	if(isset($_REQUEST['wklw_user_avtar_change']))
	if(wp_verify_nonce( $_REQUEST['wklw_user_avtar_change'], 'do_wklw_user_avtar_change') || true){
		
		$user  = wp_get_current_user();
		$user_id = ( $user!=null && property_exists($user,'ID'))?$user->ID:null;
		
		if(wklw_is_worker($user)&& $user_id){
				$result = wklw_verify_scan("wklw_avatar");
				$ret = $result["valid"];
				$_REQUEST["wklw_avatar_msg"] = $result["msg"];
				if($ret){
						$wklw_avatar =wklw_handle_id_scan_by_param("wklw_avatar");
						if($user_id != null){
							update_user_meta($user_id,"wklw_avatar",
								array(
									"path"	=> $wklw_avatar["file"],
									"url"	=> $wklw_avatar["url"],
									"name"	=> wp_basename($wklw_avatar["file"])
								)							
							);
						
						}
					
				}
		}
		
	}
}
?>