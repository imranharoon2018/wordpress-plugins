<?php
function wklw_log($file,$line,$val){
	update_option("wklw_".$file."_".$line,$val);
}
function wklw_is_worker($user){
		if( !is_object($user) ) return false;
		
		$roles = ( array ) $user->roles; 
		$is_worker = false;
		foreach($roles as $role){
			$is_worker = $role == KINKY_LINE_WORKER;
		}
		return $is_worker;
}
?>