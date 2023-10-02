<?php
function get_order_by_id($order_id){
	// var_dump(wc_get_order($order_id));var_dump($order_id);
	return wc_get_order($order_id);
}
function artist_has_this_product($artist_id,$product){
	$terms = wap_get_product_term($product);
	
	$term_id = get_term_id_by_artist_id($artist_id);
	
	foreach($terms as $a_term){
		
		if($a_term->term_id == $term_id)
			return true;
	}
	return false;
	
}

function get_artist_item_from_order($order_id,$artist_id){
	global $order_cache;
	
	$ret_item_info = array();
	if(!isset($order_cache[$order_id]))
		$order_cache[$order_id] =get_order_by_id($order_id);
	
	
	$order = $order_cache[$order_id] ;
	
	if($order != null  && is_object($order)){
		$order_items = $order->get_items();
			
		foreach($order_items as $an_item){
			$product = $an_item->get_product();
			
			if($product!=null && is_object($product)){
				
				if(artist_has_this_product($artist_id,$product)){
					$r_item["name"] = $an_item->get_name();
					$qty          = $an_item->get_quantity();
					$refunded_qty = $order->get_qty_refunded_for_item( $an_item->get_id() );
					$r_item["quantity"] = $qty  - ($refunded_qty *-1);
					$r_item["subtotal"] = $order->get_line_subtotal($an_item,false);
					
					$ret_item_info [] = $r_item;
				}
			}
			
		}
	}
	
	return $ret_item_info;
}

function wap_get_item_meta($line_item_meta){
	$arr_index = array(
		"format" => 0,
		"size" => 1,
		"frame" => 2,
		"color" => 3,
	);
	// $arr_index = array(
		// "pa_color" => 0,
		// "pa_size" => 1
		// ,
		
	// );
	// $ret = array('','','','');
	$ret = array();
	if(is_array($line_item_meta ) && count($line_item_meta) ){
		foreach($line_item_meta as $a_meta){
				
				// echo $a_meta->key. "-".$a_meta->value."<br/>";
				$key = strtolower(trim( $a_meta->key ));

				if(isset($arr_index[ $key ])){
					
					$ret[ $arr_index[ $key ] ] = array('meta_key'=>ucfirst($key),'meta_value'=> $a_meta->value );
				}
		}
	}
	return $ret;
	
	
}
function get_term_ids_by_artist_id($artist_id){
	global $wpdb;
	$termmeta=$wpdb->prefix."termmeta";
	$sql = "select term_id from  $termmeta where meta_key='".WAP_ARTIST_ID."' and meta_value =%s";
	
	return $wpdb->get_results($wpdb->prepare($sql,$artist_id));
	
}
function get_term_id_by_artist_id($artist_id){
	global $wpdb;
	$termmeta=$wpdb->prefix."termmeta";
	$sql = "select term_id from  $termmeta where meta_key='".WAP_ARTIST_ID."' and meta_value =%s limit 0,1";
	
	return $wpdb->get_var($wpdb->prepare($sql,$artist_id));
	
}
function remove_term_metas_by_term_ids($term_ids){
	foreach($term_ids as $a_term_id){
	
	
		delete_term_meta ($a_term_id->term_id,WAP_ARTIST_ID);
	}
	
}
function wap_get_saved_artist_id_by_term_id($term_id){
	
	return get_term_meta( $term_id, WAP_ARTIST_ID, true ) ;
}
/*
function wap_artist_id_exists($term_id){
	
	return get_term_meta( $term_id, WAP_ARTIST_ID, true ) ;
}*/
function delete_artist_id($term_id){
	
	delete_term_meta($term_id,WAP_ARTIST_ID);
}

function wap_save_artist_id($term_id,$wap_artist_id){
	/** before saving artist_id **/
	/** search if the artist_id exist with other terms **/
	/** if this aritst_id exists with other termmeat delete them**/
	/** this is necessarty to maintain one to one relationship**/
	// clear this artist_id
	$term_ids = get_term_ids_by_artist_id($wap_artist_id);
	
	if(count($term_ids))
		remove_term_metas_by_term_ids($term_ids);
	// clear this term id
	$saved_artist_id = wap_get_saved_artist_id_by_term_id($term_id);
	if($saved_artist_id )
		delete_artist_id($term_id);
	//add artist id against term
	add_term_meta($term_id, WAP_ARTIST_ID ,$wap_artist_id);
	
}

function get_saved_artist_comission($term_id){
	
	return get_term_meta( $term_id, WAP_ARTIST_COMISSION, true ) ;
}
function artist_comission_exists($term_id){
	
	return get_term_meta( $term_id, WAP_ARTIST_COMISSION, true ) ;
}
function delete_artist_comission($term_id){
	
	delete_term_meta($term_id,WAP_ARTIST_COMISSION);
}

function save_artist_comission($term_id,$artist_comission){
	$comission_exist = artist_comission_exists($term_id);
	if( $comission_exist && $comission_exist != $artist_comission){
		update_term_meta($term_id, WAP_ARTIST_COMISSION ,$artist_comission);
	}else{
		add_term_meta($term_id, WAP_ARTIST_COMISSION ,$artist_comission);
	}
}


function get_saved_artist_name($term_id){
	
	return get_term_meta( $term_id, WAP_ARTIST_NAAME, true ) ;
}
function artist_name_exists($term_id){
	
	return get_term_meta( $term_id, WAP_ARTIST_NAAME, true ) ;
}
function delete_artist_name($term_id){
	
	delete_term_meta($term_id,WAP_ARTIST_NAAME);
}

function save_artist_name($term_id,$artist_name){
	$name_exist = artist_name_exists($term_id);
	if( $name_exist && $name_exist != $artist_name){
		update_term_meta($term_id, WAP_ARTIST_NAAME ,$artist_name);
	}else{
		add_term_meta($term_id, WAP_ARTIST_NAAME ,$artist_name);
	}
}

function get_saved_artist_email($term_id){
	
	return get_term_meta( $term_id, WAP_ARTIST_EMAIL, true ) ;
}
function artist_email_exists($term_id){
	
	return get_term_meta( $term_id, WAP_ARTIST_EMAIL, true ) ;
}
function delete_artist_email($term_id){
	
	delete_term_meta($term_id,WAP_ARTIST_EMAIL);
}

function save_artist_email($term_id,$email){
	$email_exist = artist_email_exists($term_id);
	if( $email_exist && $email_exist != $email){
		update_term_meta($term_id, WAP_ARTIST_EMAIL ,$email);
	}else{
		add_term_meta($term_id, WAP_ARTIST_EMAIL ,$email);
	}
}

function wap_get_product_term($product){
	
	$where = $product ;
	if($product->get_type() == "variation"){
 
		$parent_id  = wp_get_post_parent_id( $product->get_id());
		if($parent_id!=0)
			$where = wc_get_product($parent_id);
	}
	
	$terms = wc_get_product_terms( $where->get_id(),WAP_TERM );
	// $terms = wc_get_product_terms( $where->get_id(), 'pa_brand' );
	return $terms ;
	
}
function wap_get_artist_information_from_term_id($term_id){
	global $wpdb;
	$ret =null;
	$artist_id = wap_get_saved_artist_id_by_term_id($term_id);
	if($artist_id){
		$users = $wpdb->prefix."users";
		$wap_artist_info = $wpdb->prefix."wap_artist_info";
		$sql = "select $users.ID as artist_id, $users.user_email, $users.user_login , $wap_artist_info.artist_comission  from $users inner join $wap_artist_info on $users.ID =  $wap_artist_info.artist_id where $users.ID='%s'";
		
		$result = $wpdb->get_results($wpdb->prepare($sql,$artist_id));
		if(count($result)){
			$ret = array(
				'artist_id' => $result[0]->artist_id,
				'artist_email' =>$result[0]->user_email,
				'artist_name' => $result[0]->user_login,
				'artist_comission' => $result[0]->artist_comission
			);
		}
	}
	
	return $ret;
}
function wap_get_artist_information_from_product($product){
	$ret = array();
	$terms = wap_get_product_term( $product );
	foreach($terms as $term){
		$artist_info = wap_get_artist_information_from_term_id($term->term_id);
		if($artist_info){
			
			$artist_id = $artist_info["artist_id"];
			$artist_email = $artist_info["artist_email"];
			$artist_name = $artist_info["artist_name"];
			$artist_comission =  $artist_info["artist_comission"];
			if($artist_email){
				$ret[] = array(
					'artist_id' => $artist_id,
					'artist_email' => $artist_email,
					'artist_name' => $artist_name,
					'artist_comission' => $artist_comission
				);
			}
		}
	}
	return $ret;
	
}
function wap_get_artist_information_from_order($order){
	$arr_table = array();
	
	$items = $order->get_items();
	if(is_array($items))
			foreach($items as $item){
				$product = $item->get_product();
				$arr_artist_infomation = array();
				if( $product!=null && is_object($product) )
					$arr_artist_infomation = wap_get_artist_information_from_product($product);
				
				foreach($arr_artist_infomation as $artist){
					if( !isset($arr_table [ $artist["artist_email"] ])){
						$arr_table [ $artist["artist_email"] ] = array(
							"artist_id" => $artist["artist_id"],
							"artist_email" => $artist["artist_email"],
							"artist_name" => $artist["artist_name"],
							"artist_comission" => $artist["artist_comission"],
							"order_item_ids" => array(),
							"order_item_exists" => array()
						);
					} 
					if(!isset($arr_table [ $artist["artist_email"] ] ["order_item_exists"][$item->get_id()])){
						$arr_table [ $artist["artist_email"] ] ["order_item_ids"][] = $item->get_id();
						$arr_table [ $artist["artist_email"] ] ["order_item_exists"][$item->get_id()] = true;
					}
					
				}
				
			}
	
	return $arr_table;
}
?>