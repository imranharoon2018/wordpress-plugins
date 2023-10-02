<?php
function save_product_attributes_in_line_item($woo_product_attributes_in_line_item, $seperator = ","){
	$woo_product_attributes_in_line_item = explode($seperator,$woo_product_attributes_in_line_item);
	$vals = array();
	foreach($woo_product_attributes_in_line_item as $an_item){
		$vals[] = strtolower( trim($an_item) );
	}
	update_option("woo_product_attributes_in_line_item", $vals);
	
}
function is_shop_order($post_id){
	global $wpdb;
	
	$posts  = $wpdb->prefix."posts";	 
	$sql = "select count(*) as num_total from  $posts where $posts.ID= %s and post_type='shop_order'";	
	$sql  = $wpdb->prepare($sql,$post_id);
	$result= $wpdb->get_var($sql);
	if(!$result ) add_action( 'admin_notices', 'wor_notice_post_id_is_not_an_order' );

	return $result;
}

function get_total_number_of_returned_orders(){
	global $wpdb;
	$returned_order  = $wpdb->prefix."wor_returned_order"; 
		 
	$sql = "select count(*) as toatal from $returned_order;";	
	
	return $wpdb->get_var($sql);

}

function clear_sku_and_order_history(){
	global $wpdb;
	$wor_sku_meta  = $wpdb->prefix."wor_sku_meta"; 
	$wor_sku_order  = $wpdb->prefix."wor_sku_order";	 
	$sql = "delete from $wor_sku_meta";
	$wpdb->query($sql);
	$sql = "delete from $wor_sku_order";
	$wpdb->query($sql);
}
function remove_order_from_returned(){
	global $wpdb;
	
	
	if( isset($_REQUEST["order_id_mark_as_not_returned"]) && abs($_REQUEST["order_id_mark_as_not_returned"] )){
		$wor_sku_order  = $wpdb->prefix."wor_sku_order";	 
		$returned_order  = $wpdb->prefix."wor_returned_order"; 
		$sql= "delete from $returned_order  where order_id = %s";
		$wpdb->query( $wpdb->prepare( $sql, abs($_REQUEST["order_id_mark_as_not_returned"]) ) );
		
		$sql= "delete from $wor_sku_order  where order_id = %s";
		$wpdb->query( $wpdb->prepare( $sql, abs($_REQUEST["order_id_mark_as_not_returned"]) ) );
		
	}
	
}
function get_returned_orders($offset=null, $limit=null){
	
	global $wpdb;
	$returned_order  = $wpdb->prefix."wor_returned_order"; 
	$posts  = $wpdb->prefix."posts";	 
	$sql = "select $posts.ID, $posts.post_title, $posts.post_date from $posts inner join $returned_order on $posts.ID= $returned_order.order_id order by $posts.ID desc ";	
	if($offset!==null && $limit!==null){
		$sql .= " LIMIT $offset, $limit ";
	}
	$sql .= ";";
	// echo $sql;exit();
	
	return $wpdb->get_results($sql);

}


function sku_order_exists($order_id,$sku_meta_id){
	
	global $wpdb;
	$wor_sku_order  = $wpdb->prefix."wor_sku_order";
	$sql = "select 
				count(*) as total 
			from  
				$wor_sku_order 
			where 
				order_id = %s 
			and 
				sku_meta_id =%s 
			and
				is_used = 0
			and 
				is_recycled = 0
			and 
				is_avaible_in_store = 1 
				";
	$sql  = $wpdb->prepare($sql,$order_id,$sku_meta_id);
	
	return $wpdb->get_var($sql);

}

function mark_sku_order_as_recycled($order_id,$sku_meta_id){
	
	global $wpdb;
	$wor_sku_order  = $wpdb->prefix."wor_sku_order";
	$sql = "update  $wor_sku_order set is_recycled = 1 ,is_avaible_in_store = 0 where order_id = %s and sku_meta_id =%s;";
	$sql  = $wpdb->prepare($sql,$order_id,$sku_meta_id);
	
	return $wpdb->query($sql);

}
function mark_sku_order_as_not_available($order_id,$sku_meta_id){
	
	global $wpdb;
	$wor_sku_order  = $wpdb->prefix."wor_sku_order";
	$sql = "update  $wor_sku_order set is_avaible_in_store = 0  where order_id = %s and sku_meta_id =%s;";
	$sql  = $wpdb->prepare($sql,$order_id,$sku_meta_id);
	
	return $wpdb->query($sql);

}
function mark_sku_order_as_used($order_id,$sku_meta_id){
	
	global $wpdb;
	$wor_sku_order  = $wpdb->prefix."wor_sku_order";
	$sql = "update  $wor_sku_order set is_used = 1 ,is_avaible_in_store = 0 where order_id = %s and sku_meta_id =%s;";
	$sql  = $wpdb->prepare($sql,$order_id,$sku_meta_id);
	
	return $wpdb->query($sql);

}

function get_returned_order_detail($order_id,$sku_meta_id){
	
	global $wpdb;
	$wor_sku_order  = $wpdb->prefix."wor_sku_order";
	$sql = "select id, sku_meta_id,sku,order_id,is_used,is_recycled,is_avaible_in_store from  $wor_sku_order where order_id = %s and sku_meta_id =%s limit 0,1";
	$sql  = $wpdb->prepare($sql,$order_id,$sku_meta_id);
	
	return $wpdb->get_row($sql);

}
function order_is_returned($order_id ){
	global $wpdb;
	$returned_order  = $wpdb->prefix."wor_returned_order";
	$sql  = "select order_id from $returned_order where order_id =%s";
	$sql  = $wpdb->prepare($sql,$order_id);
	return $wpdb->get_var($sql);
}
function save_sku_product_attributes($sku,$product_attributes_in_order_line_item){
	$insert_id = false;
	global $wpdb;
	$insert_id = sku_exists( $sku,$product_attributes_in_order_line_item );
	if( !$insert_id ){
		
		$wor_sku_meta  = $wpdb->prefix."wor_sku_meta";				
		$sql = $wpdb->prepare("insert into $wor_sku_meta  (sku,filtered_product_attributes )values(%s,%s)",$sku,serialize($product_attributes_in_order_line_item));		
		
		if($wpdb->query($sql))
			$insert_id = $wpdb->insert_id;
	}
	return $insert_id ;
}
function save_sku_meta_id_order($sku_meta_id, $sku, $order_id, $is_used = 0,$is_recycled= 0){
	$insert_id = false;	
	global $wpdb;
	
	$wor_sku_order  = $wpdb->prefix."wor_sku_order";
	$sql = $wpdb->prepare("insert into $wor_sku_order (sku_meta_id, sku,order_id,is_used ,is_recycled)values(%s,%s,%s,%s,%s)",$sku_meta_id,$sku,$order_id,$is_used,$is_recycled);
	
	if($wpdb->query($sql))
		$insert_id = $wpdb->insert_id;
	
	return $insert_id ;
}
function get_product_attributes_from_order_line_item_meta($line_item_meta){
	
	$ret = array();
	$attribute_names = get_option("woo_product_attributes_in_line_item");
	$check_table = array();
	
	foreach($attribute_names as $a_name){
		$check_table[ $a_name ] = true;
	}
	if(is_array($line_item_meta ) && count($line_item_meta) ){
		foreach($line_item_meta as $a_meta){
				
				// echo $a_meta->key. "-".$a_meta->value."<br/>";
				$key = strtolower(trim( $a_meta->key ));
				$val = strtolower(trim( $a_meta->value ));
				if(isset($check_table[ $key ]))
					$ret[ $key ] = $val;
		}
	}
	ksort($ret);
	return $ret;
	
	
}
function insert_all_sku_by_order_id($order_id){
	$order = wc_get_order($order_id);
	$line_items = $order->get_items( apply_filters( 'woocommerce_admin_order_item_types', 'line_item' ) );

	if(is_array($line_items))
		foreach($line_items as $item){
			
			$product = $item->get_product();
			$line_item_meta = $item->get_meta_data() ;
			if( $product != null ){
				$sku=wor_get_product_sku($product);	
				
				
				$product_attributes_in_order_line_item = get_product_attributes_from_order_line_item_meta($line_item_meta);
				$sku_meta_id = save_sku_product_attributes($sku,$product_attributes_in_order_line_item);				
				if( $sku_meta_id ){
					save_sku_meta_id_order($sku_meta_id , $sku, $order_id, 0, 0);
				}
				
			}
			
		}
}
function save_returned_order_to_database(){
	$inserted_returned_order_id = false;
	global $wpdb;
	$returned_order  = $wpdb->prefix."wor_returned_order";
	if(isset($_REQUEST["woo_returned_order_id"]) && is_shop_order($_REQUEST["woo_returned_order_id"])){
			if(order_is_returned($_REQUEST["woo_returned_order_id"])){
				add_action( 'admin_notices', 'wor_notice_this_order_has_been_returned_before' );
			}else{
				$sql  = "insert into $returned_order (order_id) values (%s)";
				$sql  = $wpdb->prepare($sql,$_REQUEST["woo_returned_order_id"]);
				if($wpdb->query($sql))
					$inserted_returned_order_id = $_REQUEST["woo_returned_order_id"];
			}
		
	}	
	return $inserted_returned_order_id;
	
}

function match_attributes($x,$y){
	// return count($x) == count($y) && count(array_intersect($x, $y)) == count ($x);
	return count($x) == count($y) && count(array_intersect_assoc($x, $y)) == count ($x);
}
function sku_exists($sku,$filtered_attributes){
	$ret  = false;
	global $wpdb;
	$wor_sku_meta  = $wpdb->prefix."wor_sku_meta";
	$sql = $wpdb->prepare("select id, sku, filtered_product_attributes from $wor_sku_meta  where sku = %s ",$sku);
	$results = $wpdb->get_results($sql);
	if(is_array($results))
		foreach($results as $a_result){
			if(match_attributes($filtered_attributes, unserialize($a_result->filtered_product_attributes)))
				$ret = $a_result->id;
		}
	return $ret;
	
}
function get_available_returned_order($sku_meta_id){
	global $wpdb;
	$wor_sku_order  = $wpdb->prefix."wor_sku_order";	 
	
	$sql = "select id, sku_meta_id,sku,order_id,is_used,is_recycled,is_avaible_in_store from $wor_sku_order  where sku_meta_id = %s and is_used = 0 and is_recycled = 0 and is_avaible_in_store =1  order by order_id desc ";
	$sql  = $wpdb->prepare($sql,$sku_meta_id);
	
	return $wpdb->get_results($sql);
}

?>