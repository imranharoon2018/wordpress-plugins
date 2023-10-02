<?php
function wap_mail_content_type(){
	return "text/html";
}
function wap_modify_from_mail($frome_email){	
		return WC_Admin_Settings :: get_option("artist_plugin_from_email_account");
}
function wap_modify_from_email_name($wp_mail_from_name){	
		return WC_Admin_Settings :: get_option("artist_plugin_from_email_name");
}

function wap_send_mail($to, $subject, $body, $attachment_file=null,$header =  array('Content-Type: text/html; charset=UTF-8')){
	add_filter('wp_mail_content_type',"wap_mail_content_type");
	add_filter('wp_mail_from','wap_modify_from_mail');
	add_filter('wp_mail_from_name','wap_modify_from_email_name');
				
	if($attachment_file)
		$ret = wp_mail( $to, $subject, $body,$header, array($attachment_file) );
	else
		$ret = wp_mail( $to, $subject, $body,$header);
	
	remove_filter('wp_mail_content_type',"wap_mail_content_type");
	remove_filter('wp_mail_from',"wap_modify_from_mail");
	remove_filter('wp_mail_from_name',"wap_modify_from_email_name");
	return $ret;
	
}
function wap_send_month_end_email($artist_email,$attachment_file){

	$ret = false;
	$email_subject = WC_Admin_Settings :: get_option("artist_plugin_end_of_month_email_subject");	
	
	
	$email_subject = str_replace("{summary_month}", date('M'),$email_subject);
	$email_subject = str_replace("{summary_year}", date('Y'),$email_subject);
	
	
	$email_body = end_of_month_email_template($artist_email);
	// echo "<div style='border:1px solid red;'>";
	// echo $artist_email."<br/>";
	// echo $email_subject."<br/>";
	// echo $email_body."<br/>";
	// // echo $attachment_file."<br/>";
	// echo "</div>";
	$ret = wap_send_mail($artist_email,$email_subject,$email_body,$attachment_file);			
	
	
	
	return $ret;
}
/*
artist_email: string
artist_name:string
artist_comission:string
order_item_ids: array int
order_item_exists => associated array order_item_exists[item_id]=true
*/
function wap_create_and_send_order_cancelled_email($order_id,$artist_order_info){
	$artist_email = $artist_order_info["artist_email"];
	$artist_name = $artist_order_info["artist_name"];
	$artist_comission = $artist_order_info["artist_comission"];
	$order_item_ids = $artist_order_info["order_item_ids"];
	$order_item_exists = $artist_order_info["order_item_exists"];
	
	$email_subject = WC_Admin_Settings :: get_option("artist_plugin_order_canceled_email_subject");	
	$order = wc_get_order($order_id);
	if($order!=null && is_object($order)){
		$email_subject = str_replace("{order_id}",$order->get_order_number(),$email_subject);
		$email_subject = str_replace("{order_date}", wc_format_datetime($order->get_date_created() ),$email_subject);
		
		
	}
	
	
	$email_body = order_cancelled_email_template($order_id,$artist_email,$artist_comission,$order_item_ids,$order_item_exists );
	
	wap_send_mail($artist_email,$email_subject,$email_body);
	return;
}



function wap_create_and_send_order_processing_email($order_id,$artist_order_info){
	$artist_email = $artist_order_info["artist_email"];
	$artist_name = $artist_order_info["artist_name"];
	$artist_comission = $artist_order_info["artist_comission"];
	$order_item_ids = $artist_order_info["order_item_ids"];
	$order_item_exists = $artist_order_info["order_item_exists"];
	
	$email_subject = WC_Admin_Settings :: get_option("artist_plugin_email_subject");	
	$order = wc_get_order($order_id);
	if($order!=null && is_object($order)){
		$email_subject = str_replace("{order_id}",$order->get_order_number(),$email_subject);
		$email_subject = str_replace("{order_date}", wc_format_datetime($order->get_date_created() ),$email_subject);
		
		
	}
	
	
	$email_body = order_processing_email_template($order_id,$artist_email,$artist_comission,$order_item_ids,$order_item_exists );
	
	wap_send_mail($artist_email,$email_subject,$email_body);
	return;

	
}
?>