<?php

/*
Plugin Name: wpai_format_description
Description: This Plugin Customize description for partiular xml document. Requires Wp all Imports
Version: 1.0
Author: Imran
*/

if(!defined('FORMAT_DESCRIPTION_ERROR')) define('FORMAT_DESCRIPTION_ERROR', "format_description_error");
if(!defined('FORMAT_ATTACHMENT_ERROR')) define('FORMAT_ATTACHMENT_ERROR', "format_attachment_error");
if(!defined('FORMAT_PARAMS_ERROR')) define('FORMAT_PARAMS_ERROR', "format_params_error");
if(!defined('FORMAT_MANUFACTURER_ERROR')) define('FORMAT_MANUFACTURER_ERROR', "format_manufacturer_error");
if(!defined('ADD_DESCRIPTION_NODE_ERROR')) define('ADD_DESCRIPTION_NODE_ERROR', "add_description_node_error");
 
function add_wpi_format_description_style(){
	
		$handle =style_wpai_format_description;
		$url = trailingslashit( plugin_dir_url( __FILE__ )).'css/style_wpai_format_description.css';
		$arr_dep = array();
		wp_enqueue_style( $handle ,$url, false ,$arr_dep ); 
 }
function format_description($arr_description,$arr_product_no){
	
	$str_description = "";
	
	$product_no = "";
	if($arr_product_no->length ) $product_no =  $arr_product_no->item(0)->nodeValue;
	try{
		
		if(  $arr_description->length ){
			
			$str_description =trim( $arr_description->item(0)->nodeValue);
			
				$str_description = "<div class='wpai_fd_desc'>".$str_description."</div>";
		}
	}catch(Exception $e) {
		
		$format_description_error = get_option(FORMAT_DESCRIPTION_ERROR);
		if($format_description_error)
			$format_description_error .= "|-(product_no :".$product_no." - error_message : ".$e->getMessage() .")-";
		else
			$format_description_error .= "-(product_no :".$product_no." - error_message : ".$e->getMessage() .")-";
		update_option(FORMAT_DESCRIPTION_ERROR,$format_description_error );
		
	 
	}
	return $str_description;
}

function format_attachment($arr_links,$arr_description,$arr_product_no){
	$str_attachment ="";
	
	$product_no = "";
	if($arr_product_no->length ) $product_no =  $arr_product_no->item(0)->nodeValue;
	try{
		
		if($arr_links->length){
				$str_attachment .="<div class='wpai_fd_attachment'>";
				$str_attachment .="<h2 class='wpai_fd_attachment_header'>Prílohy k produktu:</h2>";
				$str_attachment .="<ul class='wpai_fd_attachment_ul'>";
				for($i=0;$i<$arr_links->length;$i++){
				
					$desc = "Document";
					if(($arr_description->item($i)) )
						$desc = trim($arr_description->item($i)->nodeValue) ;
					$str_attachment .="<li class='wpai_fd_attachment_li'><a href='".$arr_links->item($i)->nodeValue."' class='wpai_fd_attachment_link'>".$desc."</a></li>";
				}
				$str_attachment .="</ul>";
			$str_attachment .="</div>";
		}
	}catch(Exception $e) {
		
		$format_attachment_error = get_option(FORMAT_ATTACHMENT_ERROR);
		if($format_attachment_error)
			$format_attachment_error .= "|-(product_no :".$product_no." - error_message : ".$e->getMessage() .")-";
		else	
			$format_attachment_error .= "-(product_no :".$product_no." - error_message : ".$e->getMessage() .")-";
		
		update_option(FORMAT_ATTACHMENT_ERROR,$format_attachment_error );
		
	 
	}
	return $str_attachment;
	
}
function format_params($arr_params,$arr_product_no){
	
	$str_params = "";
	$product_no = "";
	if($arr_product_no->length ) $product_no =  $arr_product_no->item(0)->nodeValue;
	try{
		
		if(  $arr_params->length ){
			$str_params .="<div class='wpai_fd_params'>";
			$str_params .="<h2 class='wpai_fd_params_header'>Details:</h2>";
		
			$str_params .= "<ul class='wpai_fd_params_ul'>";
		
			for($i=0;$i<$arr_params->length;$i++){
				
				$name= str_replace("param_","", strtolower($arr_params->item($i)->nodeName));
				$str_params .=  "<li class='wpai_fd_params_li'>".ucfirst($name)." : ".($arr_params->item($i)->nodeValue) ."</li>";
				
			}
			$str_params .= "</ul>";
			$str_params .="</div>";
		}
	}catch(Exception $e) {
		
		$format_params_error = get_option(FORMAT_PARAMS_ERROR);
		if($format_params_error)
			$format_params_error .= "|-(product_no :".$product_no." - error_message : ".$e->getMessage() .")-";
		else
			$format_params_error .= "-(product_no :".$product_no." - error_message : ".$e->getMessage() .")-";
		update_option(FORMAT_PARAMS_ERROR,$format_params_error );
	 
	}
	return $str_params;
}
 function format_manufacturer($arr_manufacturer,$arr_product_no){
	
	$str_manufacturer = "";
	$product_no = "";
	if($arr_product_no->length ) $product_no =  $arr_product_no->item(0)->nodeValue;
	try{
		
		
		$url = trailingslashit(get_site_url())."vyrobca/";
		if(  $arr_manufacturer->length ){
			$url .= ($arr_manufacturer->item(0)->nodeValue);
			$url .= sanitize_title($arr_manufacturer->item(0)->nodeValue);
			$str_manufacturer .="<div class='wpai_fd_manufacturer'>";
			$str_manufacturer .="Vyrobca : <a href='".$url."'>".$arr_manufacturer->item(0)->nodeValue ."</a>";

			$str_manufacturer .="</div>";
		}
	}catch(Exception $e) {
		
		$format_manufacturer_error = get_option(FORMAT_MANUFACTURER_ERROR);
		if($format_manufacturer_error)
			$format_manufacturer_error .= "|-(product_no :".$product_no." - error_message : ".$e->getMessage() .")-";
		else
			$format_manufacturer_error = "-(product_no :".$product_no." - error_message : ".$e->getMessage() .")-";
		
		update_option(FORMAT_MANUFACTURER_ERROR,$format_manufacturer_error );
		
	 
	}
	return $str_manufacturer;
}
 
function add_description_node($node){
	$product_no = "";
	
	try{
		
		$formatted_description = "";
		$dom = new DOMDocument();
		$dom->loadXML($node->asXML());

		$arr_product_no = $dom->getElementsByTagName('PRODUCTNO');
		if($arr_product_no->length ) $product_no =  $arr_product_no->item(0)->nodeValue;
		
		$desc = $dom->getElementsByTagName('DESCRIPTION');
		$formatted_description .= format_description($desc,$arr_product_no );
		
		$arr_links = $dom->getElementsByTagName('FILE_ATTACHMENTS');
		$arr_description = $dom->getElementsByTagName('FILE_DESCRIPTION');
		$formatted_description .=format_attachment($arr_links,$arr_description,$arr_product_no);

		$selector = new DOMXPath($dom);
		$arr_params = $selector->query('.//*[starts-with(local-name(), "PARAM")]');
		$formatted_description .= format_params($arr_params,$arr_product_no);
	
		$arr_manufacturer =  $dom->getElementsByTagName('MANUFACTURER');
		$formatted_description .= format_manufacturer($arr_manufacturer,$arr_product_no);


		$original_description = $dom->getElementsByTagName('DESCRIPTION');
			
		foreach ($original_description as $temp_node) {
			$temp_node->parentNode->removeChild($temp_node);
			
		}
			
		$shopitem = $dom->getElementsByTagName('SHOPITEM');
		$new_description = $dom->getElementsByTagName('SHOPITEM')[0]->appendChild($dom->createElement('DESCRIPTION'));
		$new_description->appendChild($dom->createCDATASection( $formatted_description ));
			
		$node = simplexml_load_string($dom->saveXML());
	}catch(Exception $e) {
		
		$add_description_node_error = get_option(ADD_DESCRIPTION_NODE_ERROR);
		if($add_description_node_error)
			$add_description_node_error .= "|-(product_no :".$product_no." - error_message : ".$e->getMessage() .")-";
		else
			$add_description_node_error .= "-(product_no :".$product_no." - error_message : ".$e->getMessage() .")-";
		update_option(ADD_DESCRIPTION_NODE_ERROR,$add_description_node_error );
		
	 
	}

	return $node;

}

add_filter('wpallimport_xml_row', 'add_description_node', 10, 1);
function wpai_format_description_admin_menu() {
    add_menu_page(
        __( 'wpai_format_description Messages', 'textdomain' ),
        'wpai_format_description Messages',
        'manage_options',
        'wpai_format_description',
        'manage_format_description_message',
        '',
        6
    );
	
}
add_action( 'admin_menu', 'wpai_format_description_admin_menu' );
add_action( 'admin_init', 'handle_remove_error_message');
function handle_remove_error_message (){
	
	// update_option(FORMAT_DESCRIPTION_ERROR,"|-(product_no:1 - error_message:msg1)-|-(product_no:2 - error_message:msg2)-|-(product_no:3 - error_message:msg3)-");
	// update_option(FORMAT_ATTACHMENT_ERROR,"|-(product_no:4 - error_message:msg4)-|-(product_no:5 - error_message:msg5)-|-(product_no:6 - error_message:msg6)-");
	// update_option(FORMAT_PARAMS_ERROR,"|-(product_no:7 - error_message:msg7)-|-(product_no:8 - error_message:msg8)-|-(product_no:9 - error_message:msg9)-");
	// update_option(FORMAT_MANUFACTURER_ERROR,"|-(product_no:10 - error_message:msg10)-|-(product_no:11 - error_message:msg11)-|-(product_no:12 - error_message:msg12)-");
	// update_option(ADD_DESCRIPTION_NODE_ERROR,"|-(product_no:13 - error_message:msg13)-|-(product_no:14 - error_message:msg14)-|-(product_no:15 - error_message:msg15)-");
	if(isset($_REQUEST['wpai_fd_remove_error_message'])){
		delete_option(FORMAT_DESCRIPTION_ERROR);
		delete_option(FORMAT_ATTACHMENT_ERROR);
		delete_option(FORMAT_PARAMS_ERROR);
		delete_option(FORMAT_MANUFACTURER_ERROR);
		delete_option(ADD_DESCRIPTION_NODE_ERROR);
	}
} 

function manage_format_description_message(){
		
	?>
	<form method = "POST">
		<input type= "submit" value ="Remove All Error Message" id="wpai_fd_remove_error_message"  name="wpai_fd_remove_error_message" class="is-primary"/>
		<input type= "hidden" id="page" name ="page" value ="wpai_format_description"/>
	</form>
	<hr/>
	Error inside format_description function:
	<?php
	$format_description_error = get_option(FORMAT_DESCRIPTION_ERROR);
	if($format_description_error){
		$arr = explode("|",$format_description_error);
		echo "<br/>Total Error:" .count($arr)."<br/>";
	
	
		foreach ($arr as $an_error){
			$an_error = str_replace("-(","",$an_error);
			$an_error = str_replace(")-","",$an_error);
			echo $an_error."<br/>";
			
		}
	}else{
		
		echo ": No error occured<br/>";
	}
	
	?>
	<hr/>
	
	Error inside format_attachment function:
	<?php
		
		$format_attachment_error = get_option(FORMAT_ATTACHMENT_ERROR);
		
		if($format_attachment_error){
			$arr = explode("|",$format_attachment_error);
			echo "<br/>Total Error:" .count($arr)."<br/>";
		
			foreach ($arr as $an_error){
				$an_error = str_replace("-(","",$an_error);
				$an_error = str_replace(")-","",$an_error);
				echo $an_error."<br/>";
				
			}
		}else{
			
			echo ": No error occured<br/>";
		}
	?>
	
	<hr/>
	
	Error inside format_params function:
	<?php
		$format_params_error = get_option(FORMAT_PARAMS_ERROR);
		if($format_params_error){
			$arr = explode("|",$format_params_error);
			echo "<br/>Total Error:" .count($arr)."<br/>";
			foreach ($arr as $an_error){
				$an_error = str_replace("-(","",$an_error);
				$an_error = str_replace(")-","",$an_error);
				echo $an_error."<br/>";
				
			}
		}else{		
			echo ": No error occured<br/>";
		}
	
	?>
	<hr/>
	
	Error inside format_manufacturer function:
	<?php
		$format_manufacturer_error = get_option(FORMAT_MANUFACTURER_ERROR);
		if($format_manufacturer_error){
			$arr = explode("|",$format_manufacturer_error);
			echo "<br/>Total Error:" .count($arr)."<br/>";
			foreach ($arr as $an_error){
				$an_error = str_replace("-(","",$an_error);
				$an_error = str_replace(")-","",$an_error);
				echo $an_error."<br/>";
				
			}
		}else{		
			echo ": No error occured<br/>";
		}
	?>
	<hr/>
	
	Error inside add_description_node function:
	<?php
		$add_description_node_error = get_option(ADD_DESCRIPTION_NODE_ERROR);
		if($add_description_node_error){
			$arr = explode("|",$add_description_node_error);
			echo "<br/>Total Error:" .count($arr)."<br/>";
			foreach ($arr as $an_error){
				$an_error = str_replace("-(","",$an_error);
				$an_error = str_replace(")-","",$an_error);
				echo $an_error."<br/>";
				
			}
		}else{		
			echo ": No error occured<br/>";
		}
} 
 ?>