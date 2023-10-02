<?php
/**
 * @package voucher_addon_plugin
 * @version 1.0.0
 */
/*
Plugin Name: voucher_addon_plugin
Plugin URI: www.google.com
Description: This Plugin allows store worker to reedem voucher by loginginto a templated page. Requires woocommerce and woocommerce pdf product voucher
Author: imran
Version: 1.0.0
Author URI: www.google.com
*/
include_once("functions.php");	
add_action( 'init', 'vap_may_redirect_to_page_redeemer' );
add_action('admin_menu', 'voucher_addon_plugin_menu');
add_action('wp_login', 'vap_handle_voucher_redeemer_login', 10, 2);
add_action('wc_pdf_product_vouchers_voucher_created', 'vap_handle_voucher_created', 10, 2);
function vap_handle_voucher_created( $voucher, $args ){
	$voucher_number = $voucher->get_voucher_number();
	$max_range = 1000000 < PHP_INT_MAX ? 1000000 : PHP_INT_MAX;
	$random_int  = random_int(1000,$max_range );
	$meta_key = "redeemer_key";
	$post_id = vap_get_voucher_post_id_from_voucher_number($voucher_number);
	if($post_id){
		while(){
			
		}
	}
}
function vap_handle_voucher_redeemer_login( $user_login, $user ) {
		
		$url_to_redirect = get_permalink(absint(get_option('redeemer_home_page_id')));
	
		
		if( count($user->roles ) == 1 && ($user->roles[0]=="voucher_frontend_redeemer") ){
			wp_safe_redirect($url_to_redirect);	
			exit();
		}
    
}

function vap_may_redirect_to_page_redeemer(){
	
	
	if(isset($_REQUEST['txt_qr_code']) && isset($_REQUEST['voucher_plugin_get_voucher'])){
		$voucher_object = vap_get_voucher_object_by_bar_code($_REQUEST['txt_qr_code']);
		if($voucher_object){
			$url = get_permalink(absint(get_option('redeemer_page_id')));
			$url .=  "?get_voucher_obj=1&voucher_number=".$voucher_object->get_voucher_number();
			//var_dump($url);exit();
			wp_safe_redirect($url);
			exit();
		}else{
			$_REQUEST['error_message'] = "Sorry !!! The voucher does not exist.";
		}
	}
	
}



if(isset($_REQUEST['save_redeemer_home_page_id'])){
	// vap_save_redeemer_home_page_id($_REQUEST['redeemer_home_page_id']);
	add_action('admin_init', 'vap_save_redeemer_home_page_id');
}


if(isset($_REQUEST['submit_create_voucher_redeemer_role'])){
	// vap_create_voucher_redeemer_role();
	add_action('admin_init', 'vap_create_voucher_redeemer_role');
	
}

if(isset($_REQUEST['submit_remove_voucher_redeemer_role'])){
	// vap_remove_voucher_redeemer_role();
	add_action('admin_init', 'vap_remove_voucher_redeemer_role');
}

if(isset($_REQUEST['save_redeemer_page_id'])){
	// vap_remove_voucher_redeemer_role();
	add_action('admin_init', 'vap_save_redeemer_page_id');
}


function voucher_addon_plugin_menu() {
    add_submenu_page( 'woocommerce', 'Voucher Frontend Settings', 'Voucher Frontend Settings', 'manage_options', 'voucher-frontend-settings', 'voucher_frontend_callback' ); 
}


function voucher_frontend_callback() {

    ?>
		<p>
			<form  method="POST">
				<input type="hidden" id="page" name="page" value="voucher-frontend-settings" />				
				
				<input type="hidden" id="page" name="page" value="voucher-frontend-settings" />
				<?php if(!get_role('voucher_frontend_redeemer')) {?>
				
					<input type="submit" class="button button-primary" value="Create Reedemer Role"  id= "submit_create_voucher_redeemer_role" name = "submit_create_voucher_redeemer_role"/>
				
				<?php } else {?>
					
					<input type="submit" class="button button-primary" value="Remove Reedemer Role"  id= "submit_remove_voucher_redeemer_role" name = "submit_remove_voucher_redeemer_role"/>
				<?php } ?>
				
			</form>
		</p>
		<p>
		<form method="POST">
			<input type="hidden" id="page" name="page" value="voucher-frontend-settings" />
			<table>
				<tr><td>Redeemer Home Page ID</td><td>:</td><td><input type="text" id="redeemer_home_page_id" name="redeemer_home_page_id" value="<?=get_option('redeemer_home_page_id')?>" /> </td><td><input type="submit" class="button button-primary" value="Save" name="save_redeemer_home_page_id" id="save_redeemer_home_page_id" /></td></tr>
			</table>
				
				
			</form>
		</p>
		
		<p>
		<form method="POST">
			<input type="hidden" id="page" name="page" value="voucher-frontend-settings" />
			<table>
				<tr><td>Redeemer Page Id</td><td>:</td><td><input type="text" id="redeemer_page_id" name="redeemer_page_id" value="<?=get_option('redeemer_page_id')?>" /> </td><td><input type="submit" class="button button-primary" value="Save" name="save_redeemer_page_id" id="save_redeemer_page_id" /></td></tr>
			</table>
				
				
			</form>
		</p>
		
		
	<?php

}

?>