<?php
/*
Template Name:Artist Mail Send
*/
// echo ABSPATH; exit();
require_once(ABSPATH.'wp-load.php');


// ini_set('display_errors','On');
// ini_set('error_reporting', E_ALL );
// get_header();
// if( !get_option("wap_is_month_end_mail_send_enable") ) return;

if( !isset($_REQUEST["rand"]) ) return;
if( $_REQUEST["rand"] != get_option("wap_mail_send_page_pass") ) return;
// function create_attachment_file($artist_name){
function get_attachment_file($artist_id){
	$upload_dir  = wp_get_upload_dir();
	$path = trailingslashit($upload_dir['path']);
	$path .= "attachments/";
	$filename = time()."_".$artist_id.".csv";
	$file = $path.$filename ;
	if(!is_dir($path)){
		mkdir($path);
	}
	chmod($path,0777);
	return  $file;
	
	// mkdir($path)
	
}
 $order_cache = array();
$all_artist_payouts = get_order_to_send_mail();

$artist_order_items = array();
$artist_names = array();
$artist_emails = array();
$artist_order_comission = array();
$artist_orders = array();
$artist_order_date_completed = array();
$ids = array();
foreach($all_artist_payouts as $an_artist_payout){
	$ids[] = $an_artist_payout->id;
	$items = get_artist_item_from_order($an_artist_payout->order_id,$an_artist_payout->artist_id);
	
	
	if( !isset($artist_order_date_completed[$an_artist_payout->order_id]) )
		$artist_order_date_completed[$an_artist_payout->order_id] =date_i18n("d/m/Y", $an_artist_payout->order_date_completed);
	
	if( !isset($artist_names[$an_artist_payout->artist_id]) )
		$artist_names[$an_artist_payout->artist_id] =$an_artist_payout->artist_name;
	
	if( !isset($artist_emails[$an_artist_payout->artist_id]) )
		$artist_emails[$an_artist_payout->artist_id] =$an_artist_payout->artist_email;
	
	
	if( !isset($artist_orders[$an_artist_payout->artist_id]) )
		$artist_orders[$an_artist_payout->artist_id] =array();
	
	$artist_orders[$an_artist_payout->artist_id] [] = $an_artist_payout->order_id;
	
	if(!isset($artist_order_items[$an_artist_payout->artist_id]))
		$artist_order_items[$an_artist_payout->artist_id]=array();	
	
	if( !isset($artist_order_items[$an_artist_payout->artist_id][$an_artist_payout->order_id]) )
		$artist_order_items[$an_artist_payout->artist_id][$an_artist_payout->order_id]=array();
	
	
	if(!isset($artist_order_comission[$an_artist_payout->artist_id]))
		$artist_order_comission[$an_artist_payout->artist_id]=array();	
	
	if( !isset($artist_order_comission[$an_artist_payout->artist_id][$an_artist_payout->order_id]) )
		$artist_order_comission[$an_artist_payout->artist_id][$an_artist_payout->order_id]=$an_artist_payout->artist_comission;
	
	$count =0;
	
					
	foreach($items as $an_item){
		
		$artist_order_items[$an_artist_payout->artist_id][$an_artist_payout->order_id][]=$an_item;
		
		$count++;
	}
}
$admin_str = "artist_id,artist_email,artist_name,order_id,date_completed,product,quantity,price,royalty_percent,royalty_amount".PHP_EOL;

foreach($artist_names  as $artist_id=>$artist_name){
	$artist_file = get_attachment_file($artist_id);
	
	$artist_email = $artist_emails[$artist_id];
	$artist_name = $artist_names[$artist_id];
	// 
	$str ="order_id,date_completed,product,quantity,price,royalty_percent,royalty_amount".PHP_EOL;
	$artist_order = $artist_orders[$an_artist_payout->artist_id];
	
	$orders = $artist_orders[$artist_id];
	for($j=0; $j<count($orders);$j++){
		$order_id = $orders[$j];
		$artist_comission = $artist_order_comission[ $artist_id][$order_id];
		$items = $artist_order_items[$artist_id][$order_id];		
		for($i=0;$i<count($items);$i++ ){
			$an_item = $items[$i];
			$comission = (float)	$artist_order_comission[$artist_id][ $order_id];
			$caluclated_comission = (float)($an_item['subtotal']*$comission )/100.00;
			
			$str .= '"'.$order_id.'","'.$artist_order_date_completed[$order_id].'","'.$an_item['name'].'","'.$an_item['quantity'].'","'.$an_item['subtotal'].'","'.$comission .'","'.$caluclated_comission.'"'.PHP_EOL;
			$admin_str .= '"'.$artist_id.'","'.$artist_email.'","'.$artist_name.'","'.$order_id.'","'.$artist_order_date_completed[$order_id].'","'.$an_item['name'].'","'.$an_item['quantity'].'","'.$an_item['subtotal'].'","'.$comission .'","'.$caluclated_comission.'"'.PHP_EOL;
		}
	}
	
	file_put_contents($artist_file,$str);
	$ret=false;
	if(get_option("wap_is_month_end_mail_send_enable")){
		
		$ret = wap_send_month_end_email($artist_emails[$artist_id],$artist_file);
	}
	if( $ret  && get_option("wap_should_delete_attachemnt_file") &&  file_exists($artist_file )   ){
		unlink($artist_file);
	}
	

}
	
	$admin_file = get_attachment_file("admin");
	
	file_put_contents($admin_file,$admin_str);
	// $admin_email = WC_Admin_Settings :: get_option("artist_plugin_admin_email_address");
	$admin_email = WC_Admin_Settings :: get_option("artist_plugin_admin_email_address");
	$ret=false;
	if(get_option("wap_is_month_end_mail_send_enable")){
		
		$ret=wap_send_month_end_email($admin_email,$admin_file);
	}
	if( $ret && get_option("wap_should_delete_attachemnt_file") &&  file_exists($admin_file )  ){
			unlink($admin_file);
	}
	if( get_option("wap_mark_orders_after_email_send") )
	set_email_sent($ids);
?>
	 


<?php
// get_footer();
?>