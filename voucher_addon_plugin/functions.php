<?php


function voucher_addon_plugin_user_can_redeem_voucher($user){
	$allowed_roles = array("voucher_frontend_redeemer","administrator");
	return array_intersect($allowed_roles,$user->roles);
}
function vap_get_voucher_object_by_bar_code($barcode_value){
	$ret = false;

	if(!voucher_addon_plugin_user_can_redeem_voucher(wp_get_current_user()))	
		return $ret;
	

	if ( empty( $barcode_value ) ) {
			return false;
		}
	
		$ret = false;
		$args    = array(
			'post_type'              => 'wc_voucher',
			'post_status'            => array_keys( wc_pdf_product_vouchers_get_voucher_statuses() ),			
			'fields'                 => 'ids',
			'posts_per_page'         => 1,
			'meta_key'               => '_barcode_value',
			'meta_value'             => $barcode_value,
			'update_post_term_cache' => false,
			'no_found_rows'          => true,
		);
		$temp = "";
		$found_posts = get_posts( $args );
		global $wpdb;
		

		if ( ! empty( $found_posts ) ) {
			// $voucher = wc_pdf_product_vouchers_get_voucher( $found_posts[0] );
			$ret = wc_pdf_product_vouchers_get_voucher( $found_posts[0] );
		}

	return $ret;
}
function vap_get_voucher_post_id_from_voucher_number($voucher_number){
	$ret = false;
	global $wpdb;
	
	$voucher_post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_name = %s && post_type='wc_voucher'", $voucher_number ));
	if($voucher_post){
		$ret =$voucher_post->ID;
	}
	return $voucher_post->ID;
}
function vap_get_voucher_object_by_voucher_number($voucher_number){	
	$ret = false;
	if(!voucher_addon_plugin_user_can_redeem_voucher(wp_get_current_user()))	
		return $ret;
	
	$voucher_number = ltrim(trim($voucher_number),'#'	);				
	$voucher_number = sanitize_title($voucher_number);
	
	global $wpdb;
	
	$voucher_post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_name = %s && post_type='wc_voucher'", $voucher_number ));
	if($voucher_post){
		$ret = new \WC_Voucher($voucher_post->ID);
	}
	
	return $ret;
}
function vap_redeem_by_voucher_number_and_ammount($voucher_number,$voucher_remaining_value){	
	$ret = array(
		"is_error"	=>	true,
		"message"	=>	""
	);
	if(!voucher_addon_plugin_user_can_redeem_voucher(wp_get_current_user()))	
		return $ret;
	
	$voucher_number = ltrim(trim($voucher_number),'#'	);				
	$voucher_number = sanitize_title($voucher_number);
	
	$voucher_obj = vap_get_voucher_object_by_voucher_number($voucher_number);
	if($voucher_obj){
		
			try{
				if($voucher_obj->get_remaining_value()<$voucher_remaining_value){
					$ret = array(
						"is_error"	=>	true,
						"message"	=>	 sprintf("<br/>Sorry. The value(%s) you want to redeem is more than what is available(%s) on this voucher!!!",$voucher_remaining_value,$voucher_obj->get_remaining_value())
					);
				}else{
					$voucher_obj->redeem($voucher_remaining_value);
					
					$ret = array(
						"is_error"	=>	false,
						"message"	=>	""
					);
				}
			}catch(Exception $e) {
				$ret = array(
					"is_error"	=>	true,
					"message"	=>	$e->getMessage()
				);
				
			}
		
	}else{
		$ret = array(
			"is_error"	=>	true,
			"message"	=>	"<br/>Sorry the voucher was not found!!!"
		);
	}
	
	
	return $ret;
}

function vap_save_redeemer_home_page_id(){		//exit();
		$redeemer_home_page_id	=$_REQUEST['redeemer_home_page_id'];
		//var_dump($_REQUEST);exit();
		if(!voucher_addon_plugin_user_can_redeem_voucher(wp_get_current_user())) return;	
		update_option("redeemer_home_page_id" , absint($redeemer_home_page_id) );		
} 

function vap_save_redeemer_page_id(){		//exit();
		$redeemer_page_id	=$_REQUEST['redeemer_page_id'];
		//var_dump($_REQUEST);exit();
		if(!voucher_addon_plugin_user_can_redeem_voucher(wp_get_current_user())) return;	
		update_option("redeemer_page_id" , absint($redeemer_page_id) );		
} 
function vap_remove_voucher_redeemer_role(){
	if(get_role('voucher_frontend_redeemer')){				
				remove_role('voucher_frontend_redeemer');
			}
}

function vap_create_voucher_redeemer_role(){
		if(!get_role('voucher_frontend_redeemer'))
			add_role( 'voucher_frontend_redeemer', 'Voucher Frontend Redeemer', array( 'read' => true ) );
}

?>