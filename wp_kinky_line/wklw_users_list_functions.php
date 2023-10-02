<?php

	add_filter( 'manage_users_custom_column','wklw_show_routing_number' ,PHP_INT_MAX,3  );
	add_filter( 'manage_users_custom_column', 'wklw_show_telephone_number' ,PHP_INT_MAX,3  );
	add_filter('manage_users_columns', 'wklw_add_routing_number_column');
	add_filter('manage_users_columns', 'wklw_add_telephone_number_column');
	
add_action( 'wp_ajax_update_user_list_routing_no', 'wklw_update_user_list_routing_no' );
add_action( 'wp_ajax_update_user_list_phone_no', 'wklw_update_user_list_phone_no' );

function wklw_update_user_list_phone_no() {
	global $wpdb; // this is how you get access to the database
  
	$wklw_phone_no =  trim($_REQUEST['wklw_phone_no'] );
	$wklw_user_id =  $_REQUEST['wklw_user_id'] ;
	$exists = false;
	// echo "here"; exit();
	if(!$wklw_phone_no &&  !is_numeric($wklw_phone_no) ){
		delete_user_meta($wklw_user_id,"wklw_phone_no");
		wp_send_json(array("success"=>true,"msg"=>	"Cleared Private Number" ,"status"=>"removed"));;
	}
	
	if($wklw_phone_no)
		$exists = intval(wklw_phone_no_exist($wklw_phone_no,$wklw_user_id));
	$is_success = false;
	$status = "";
	$msg = "";
	if($exists){
		$is_success = false;
		$msg = "Another user(id:".$exists.") uses this private number:".$wklw_phone_no ;
		$status = "used_by_other";
	}else{
		if(is_numeric($wklw_user_id )){
			$phone_no_exists = get_user_meta( $wklw_user_id, "wklw_phone_no", true);
			
			if($phone_no_exists ){
				$ret = update_user_meta($wklw_user_id ,"wklw_phone_no",$wklw_phone_no );
				if($ret === true){
					$is_success = true;
					$msg = "Private Number updated";
					$status = "updated";
				}else{
					$is_success = false;
					$msg = "Private Number was not updated";
				}
			}else{
				$ret = add_user_meta(  $wklw_user_id,  "wklw_phone_no", $wklw_phone_no,true );
				$is_success = $ret?true:false; 
				if($is_success ){
					$msg = "Private Number Added";;
					$status = "added";
				}
			}
			
		}
			
	}
	wp_send_json(array("success"=>$is_success,"msg"=>	$msg,"status"=>$status ));
}
function wklw_update_user_list_routing_no() {
	global $wpdb; // this is how you get access to the database

	$wklw_routing_no =  trim($_REQUEST['wklw_routing_no'] );
	$wklw_user_id =  $_REQUEST['wklw_user_id'] ;
	$exists = false;
	if(!$wklw_routing_no &&  !is_numeric($wklw_routing_no)){
		delete_user_meta($wklw_user_id,"wklw_routing_no");
		wp_send_json(array("success"=>true,"msg"=>	"Cleared Service Number" ,"status"=>"removed"));;
	}
	
	
	if($wklw_routing_no)
	$exists = intval(wklw_routing_no_exist($wklw_routing_no,$wklw_user_id));
	$is_success = false;
	$status = "";
	$msg = "";
	if($exists){
		$is_success = false;
		$msg = "Another user(id:".$exists.") uses this service number:".$wklw_routing_no ;
		$status = "used_by_other";
	}else{
		
		if(is_numeric($wklw_user_id )){
			$routing_no_exists = get_user_meta( $wklw_user_id ,"wklw_routing_no", true);
			
			if($routing_no_exists ){
				$ret = update_user_meta($wklw_user_id ,"wklw_routing_no",$wklw_routing_no );
				if($ret === true){
					$is_success = true;
					$msg = "Service Number updated";
					$status = "updated";
				}else{
					$is_success = false;
					$msg = "Service Number was not updated";
				}
			}else{
				$ret = add_user_meta(  $wklw_user_id,  "wklw_routing_no", $wklw_routing_no,true );
				$is_success = $ret?true:false; 
				if($is_success ){
					$msg = "Service Number Added";;
					$status = "added";
				}
				
			}
		}
			
	}
	wp_send_json(array("success"=>$is_success,"msg"=>	$msg,"status"=>$status ));
}
	add_action( 'admin_enqueue_scripts', function(){
				
			// load_plugin_textdomain( 'wklw', false,'wp_kinky_line/languages/' );
			
			global $pagenow;
			
			if( $pagenow=="users.php" ){
				
				// var_dump("bye");exit();
				wp_enqueue_style('wklw_user_list-styles', WKLW_PLUGIN_URL.'assets/css/wklw_user_list.css', array());
				 wp_enqueue_script('wklw_user_list-script', WKLW_PLUGIN_URL. 'assets/js/wklw_user_list.js', array( 'jquery'));
				
				wp_localize_script('wklw_user_list-script', 'wklw_user_list',  array( 'ajax_url' => admin_url( 'admin-ajax.php' ))
				);
				
			
			}
	}  );
	function wklw_add_telephone_number_column($column_headers) {
		$column_headers['wklw_telephone_number'] = __('Private Number','wklw');
		return $column_headers;
	}
	function wklw_add_routing_number_column($column_headers) {
		$column_headers['wklw_routing_number'] = __('Service Number','wklw');
		return $column_headers;
	}

	function wklw_show_telephone_number( $output, $column_name, $user_id){
					// $user_id = 9;
					// echo "<hr/>"; var_dump($output);echo "<hr/>"; exit();
					// echo "hi"; exit();
			$ret = '';
			if($column_name=="wklw_telephone_number"){
				$user = get_user_by('id',$user_id);
			
				if(wklw_is_worker($user)){
					
					$wklw_phone_no = get_user_meta($user_id,"wklw_phone_no",true);
					// // ob_start();
					
					$ret .= '<div id ="phone_row_1_'.$user_id.'" class="wklw_row_1">';
					
					$ret .= '<p><span id="span_phone_no_'.$user_id.'">'.$wklw_phone_no .'</span></p>';
					
					$ret .= '<div class="row-actions">';
					$ret .= '<button type="button" class="button-link editinline phone_edit"  inline" aria-expanded="false" data-user_id="'.$user_id .'">Edit</button>';
					$ret .= ' | ';
					$ret .= '<button type="button" class="button-link editinline phone_clear"  inline" aria-expanded="false" data-user_id="'.$user_id .'">Clear</button>';
					$ret .= '</div>';//$ret .= '<div class="row-actions">';
					$ret .= '</div>';// $ret .= '<div id ="routing_row_1_'.$user_id.'" class="wklw_row_1">';
								
					$ret .= '<div id ="phone_row_2_'.$user_id.'"  class="wklw_row_2 wklw_hidden">';
				
					$ret .='<p><input id="wklw_phone_no_'.$user_id.'" type="text" value="'.$wklw_phone_no.'" style="width:75%" /></p>';
					$ret .='<p><input data-user_id="'.$user_id .'" id="save_phone'.$user_id.'" type="button" value="Save" class= "button-primary wklw_phone_save"  />
					<input id="cancel_'.$user_id.'" type="button" value="Cancel" class= "button-primary wklw_phone_cancel" data-user_id="'.$user_id .'" /></p>';
					$ret .='</div>';//$ret .= '<div id ="routing_row_2_'.$user_id.'"  class="wklw_row_2 wklw_hidden">';
					// $ret = ob_get_contents();
						
				}
				
			}
			return $output.$ret;
	}
	function wklw_show_routing_number( $output, $column_name, $user_id){
			// global $pagenow;
			// var_dump($pagenow); 
			// exit();
					// $user_id = 9;
			$ret = "";
			if($column_name=="wklw_routing_number"){
				
				$user = get_user_by('id',$user_id);
				
				if(wklw_is_worker($user)){
					
					$wklw_routing_no = get_user_meta($user_id,"wklw_routing_no",true);
				
					
					$ret .= '<div id ="routing_row_1_'.$user_id.'" class="wklw_row_1">';
					
					$ret .= '<p><span id="span_routing_no_'.$user_id.'">'.$wklw_routing_no .'</span></p>';
					
					$ret .= '<div class="row-actions">';
					$ret .= '<button type="button" class="button-link editinline routing_edit"  inline" aria-expanded="false" data-user_id="'.$user_id .'">Edit</button>';
					$ret .= ' | ';
					$ret .= '<button type="button" class="button-link editinline routing_clear"  inline" aria-expanded="false" data-user_id="'.$user_id .'">Clear</button>';
					$ret .= '</div>';//$ret .= '<div class="row-actions">';
					$ret .= '</div>';// $ret .= '<div id ="routing_row_1_'.$user_id.'" class="wklw_row_1">';
									
					$ret .= '<div id ="routing_row_2_'.$user_id.'"  class="wklw_row_2 wklw_hidden">';
				
					$ret .='<p><input id="wklw_routing_no_'.$user_id.'" type="text" value="'.$wklw_routing_no.'" style="width:75%" /></p>';
					$ret .='<p><input data-user_id="'.$user_id .'" id="save_routing'.$user_id.'" type="button" value="Save" class= "button-primary wklw_routing_save"  />
					<input id="cancel_'.$user_id.'" type="button" value="Cancel" class= "button-primary wklw_routing_cancel" data-user_id="'.$user_id .'" /></p>';
					$ret .='</div>';//$ret .= '<div id ="routing_row_2_'.$user_id.'"  class="wklw_row_2 wklw_hidden">';
					
				}
				
			}
			
			return $output.$ret;
	}
	?>