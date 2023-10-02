<?php
function debug_get_sku_order_by_order_id($order_id){
	global $wpdb;
	$wor_sku_order  = $wpdb->prefix."wor_sku_order";	 
	$sql = "select id, sku_meta_id, sku, order_id, is_used, is_recycled, is_avaible_in_store from $wor_sku_order where order_id = %s";
	return $wpdb->get_results($wpdb->prepare($sql,$order_id),ARRAY_N );
}
function debug_get_sku_meta_by_meta_id($meta_id){
	global $wpdb;
	$wor_sku_meta  = $wpdb->prefix."wor_sku_meta";	 
	$sql = "select id ,sku, filtered_product_attributes from $wor_sku_meta where id = %s;";
	
	return $wpdb->get_results($wpdb->prepare($sql,$meta_id),ARRAY_N);
}
function debug_sku_meta_by_order_id($order_id){
	$rets = array();
	$order = wc_get_order($order_id);
	
	$line_items = $order->get_items( apply_filters( 'woocommerce_admin_order_item_types', 'line_item' ) );

	if(is_array($line_items))
		foreach($line_items as $item){
			
			$product = $item->get_product();
			$line_item_meta = $item->get_meta_data() ;
			if( $product != null ){
				$sku=wor_get_product_sku($product);				
				$product_attributes_in_order_line_item  = get_product_attributes_from_order_line_item_meta($line_item_meta);
				$rets [] =  array(
							'sku' => $sku,
							'meta'=>$product_attributes_in_order_line_item,
							'line_item_meta'=>$line_item_meta
				);
			}
				
		}
	return $rets;
			
}	

function create_debug_data(){
	
	
	if(isset($_REQUEST['debug_order_id'])){
			
			global $wpdb;
			$file_name = time().".sql";
			$file = WOR_PLUGIN_DIR . $file_name; 
			$file_url = WOR_PLUGIN_DIR_URL . $file_name; 
			
			$open = fopen( $file, "a" ); 
			
			$tbl_name = $wpdb->prefix."wor_sku_order";
			$order_id = $_REQUEST['debug_order_id'];
			$results = debug_get_sku_order_by_order_id($order_id);
			$meta_ids = array();
			foreach($results as $a_result){
				$sql = "INSERT INTO $tbl_name( id, sku_meta_id, sku, order_id, is_used, is_recycled, is_avaible_in_store) VALUES (%s, %s,%s,%s,%s,%s,%s);\r\n";
				$sql = $wpdb->prepare($sql,$a_result);
				$meta_ids []=$a_result[1];
				fputs($open, $sql);
				
			}
			
			foreach($meta_ids as $meta_id){
				$results = debug_get_sku_meta_by_meta_id($meta_id);
				$tbl_name = $wpdb->prefix."wor_sku_meta";
				
				foreach($results as $a_result){
					$sql = "INSERT INTO $tbl_name( id, sku, filtered_product_attributes) VALUES (%s, %s,%s)\r\n";
					$sql = $wpdb->prepare($sql,$a_result);
					fputs( $open,$sql);
				}
			}
			 fclose( $open );
			 update_option("debug_file_1_url",$file_url);
			 update_option("debug_file_1_path",$file);
			
			
		}
		// debug_order_id_2
		if(isset($_REQUEST['debug_order_id_2'])){
			
			global $wpdb;
			$file_name = time().".sql";
			$file = WOR_PLUGIN_DIR . $file_name; 
			$file_url = WOR_PLUGIN_DIR_URL . $file_name; 
			
			$open = fopen( $file, "a" ); 
			
			$tbl_name = $wpdb->prefix."wor_sku_order";
			$order_id = $_REQUEST['debug_order_id_2'];
			$results = debug_sku_meta_by_order_id($order_id);
			// var_dump($results);
			foreach($results as $a_result){
			
				
				$tbl_name = $wpdb->prefix."wor_sku_meta";
				$sql = "INSERT INTO $tbl_name(  sku, filtered_product_attributes,line_item_meta,product_attributes) VALUES (%s, %s,%s)\r\n";
				$sql = $wpdb->prepare(
									$sql,
									$a_result['sku'],
									serialize($a_result['meta']),
									serialize($a_result['line_item_meta']) 
									
									);
				
				fputs( $open,$sql);
			}
			 fclose( $open );
			 update_option("debug_file_2_url",$file_url);
			 update_option("debug_file_2_path",$file);
			
			
		}
		if(isset($_REQUEST["delete_debug_file_1"])){
			wp_delete_file(get_option("debug_file_1_path"));
			delete_option("debug_file_1_url");
			delete_option("debug_file_1_path");
			
		}
		if(isset($_REQUEST["delete_debug_file_2"])){
			wp_delete_file(get_option("debug_file_2_path"));
			delete_option("debug_file_2_url");
			delete_option("debug_file_2_path");
			
		}
}

?>