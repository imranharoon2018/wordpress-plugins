<?php
function wap_admin_may_save_setting(){
	$option_name_for_page_id = array(
		'Artist Dashboard' => "wap_artist_account_dashboard_page_id",
		'Artist Dashboard' => "wap_artist_account_dashboard_page_id",
		'Artist Upload File'=> "wap_artist_upload_file_page_id",
		'Artist Sales Statitscs' => "wap_artist_artist_sales_statistics_page_id",
		'Artist Account Information' => "wap_artist_account_information_page_id",
		'Artist Sign Up' => "wap_artist_signup_page",
	);
	foreach($option_name_for_page_id as $page_title=>$option_name){
		if( isset($_REQUEST['save_'.$option_name]) )
			if(wp_verify_nonce( $_REQUEST['save_'.$option_name], 'do_save_'.$option_name)){
				$option_value = isset($_REQUEST[$option_name])?trim($_REQUEST[$option_name]):false;
				if( is_numeric($option_value) );
					update_option($option_name,$option_value );
			}
	}
	if( isset($_REQUEST["save_wap_artist_default_comission"]) )
		if(wp_verify_nonce( $_REQUEST['save_wap_artist_default_comission'], 'do_save_wap_artist_default_comission')){
			if(isset($_REQUEST["wap_default_artist_comission"]) && is_numeric(trim($_REQUEST["wap_default_artist_comission"]) ) ){
				update_option("wap_default_artist_comission",trim($_REQUEST["wap_default_artist_comission"]) );
			}
		}
}
function wap_show_admin(){
	require_once(WAP_PLUGIN_DIR."admin_screens/wap_settings.php");
}
function wap_show_artist_list(){
	require_once(WAP_PLUGIN_DIR."admin_screens/wap_artist_listing.php");
}
function wap_show_artist_payout(){
	require_once(WAP_PLUGIN_DIR."admin_screens/wap_artist_payout.php");

}
function wap_show_artist_history(){
	require_once(WAP_PLUGIN_DIR."admin_screens/wap_artist_payout_history.php");
}
 function wap_may_handle_theme_file(){
	
		$file_name = 'wap_93534_artist_mail_send.php';
		if( isset($_REQUEST['copy_theme_file']) )
			if(wp_verify_nonce( $_REQUEST['copy_theme_file'], 'do_copy_theme_file')){

				$destination  =trailingslashit(get_stylesheet_directory()).$file_name;
				$source= WAP_PLUGIN_DIR.'theme_file/'.$file_name;
				copy( $source, $destination );
			}
		if( isset($_REQUEST['delete_theme_file']) )	
			if(wp_verify_nonce( $_REQUEST['delete_theme_file'], 'do_delete_theme_file')){
				$destination  =trailingslashit(get_stylesheet_directory()).$file_name;
				if( file_exists($destination ) )
					unlink($destination ) ;
				
		}
}

function may_save_month_end_email_enable(){
	if( isset($_REQUEST['save_month_end_email_enable']) )
		if(wp_verify_nonce( $_REQUEST['save_month_end_email_enable'], 'do_save_month_end_email_enable')){
			if(isset( $_REQUEST['wap_is_month_end_mail_send_enable'])){
				update_option("wap_is_month_end_mail_send_enable",true);
				
			}else{
				update_option("wap_is_month_end_mail_send_enable",false);
			}			
		}		
}

function may_save_delete_attachment_file_enable(){
	if(isset($_REQUEST['delete_attachment_file_enable']))
		if(wp_verify_nonce( $_REQUEST['delete_attachment_file_enable'], 'do_save_delete_attachment_file_enable')){
			if(isset( $_REQUEST['wap_should_delete_attachemnt_file'])){
				update_option("wap_should_delete_attachemnt_file",true);
				
			}else{
				update_option("wap_should_delete_attachemnt_file",false);
			}			
		}		
}
	
function may_save_save_mark_orders_after_email_send(){
	if(isset($_REQUEST['mark_orders_after_email_send']))
		if(wp_verify_nonce( $_REQUEST['mark_orders_after_email_send'], 'do_save_mark_orders_after_email_send')){
			if(isset( $_REQUEST['wap_mark_orders_after_email_send'])){
				update_option("wap_mark_orders_after_email_send",true);
				
			}else{
				update_option("wap_mark_orders_after_email_send",false);
			}			
		}
	if(isset($_REQUEST['no_of_days_to_wait']))
		if(wp_verify_nonce( $_REQUEST['no_of_days_to_wait'], 'do_save_no_of_days_to_wait')){
			if(isset( $_REQUEST['wap_no_of_days_to_wait']) ){
				
				update_option("wap_no_of_days_to_wait",is_numeric( $_REQUEST['wap_no_of_days_to_wait']) ? $_REQUEST['wap_no_of_days_to_wait']:false);
				
			}			
		}
		
}
				
function may_create_mail_send_page(){
	if ( isset ( $_REQUEST['create_month_end_mail_page']) )
		if(wp_verify_nonce( $_REQUEST['create_month_end_mail_page'], 'do_create_month_end_mail_page')){
			$artist_post = array(
			  'post_title'    => 'Month End Mail',
			  'post_content'  => '',
			  'post_status'   => 'publish',
			  'post_author'   => get_current_user_id(),
			  'post_type'   => 'page'
			);
			 
			// Insert the post into the database
			$post_id = wp_insert_post( $artist_post );
			if ( !is_wp_error( $post_id ) && $post_id) 
				add_post_meta( $post_id,"_wp_page_template","wap_93534_artist_mail_send.php",true);
		}
	
}

function mail_send_page_exist(){
	$ret =get_page_by_title(  'Month End Mail');
	return $ret;
	
	
}


?>