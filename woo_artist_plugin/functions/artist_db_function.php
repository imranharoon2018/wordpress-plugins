<?php
function set_email_sent($ids ){
	global $wpdb;
	if( is_array($ids) && count($ids) ){
	$str_sql = "(".implode(",",$ids).")";
	
	$artist_order_master  = $wpdb->prefix."wap_artist_order_master";
	$sql = "
UPDATE
	$artist_order_master 
SET 
	email_sent = 1 ,
	email_sent_date = UNIX_TIMESTAMP(now())
WHERE
	id in $str_sql
	";
	// echo $sql;
	
	return $wpdb->query($sql);
	}
}
function get_order_to_send_mail(){
	global $wpdb;
	
	$artist_order_master  = $wpdb->prefix."wap_artist_order_master";
	$users  = $wpdb->prefix."users";
	$no_of_days = get_option("wap_no_of_days_to_wait");
	$sql_interval = "";
	if( $no_of_days>0 ){
		$sql_interval = " and $artist_order_master.order_date_completed < UNIX_TIMESTAMP( now( ) - INTERVAL $no_of_days DAY )		";
	}
	$sql = "
SELECT 
  $artist_order_master.id as id,
  $users.user_login as artist_name,
  $users.user_email as artist_email,
	order_id,
	artist_id,
	artist_comission,
	FROM_UNIXTIME( order_date_completed ) as order_date_display,
	order_date_completed ,
	artist_payed
	
FROM
$users  inner join 
		$artist_order_master on $users .ID = $artist_order_master.artist_id
where $artist_order_master.email_sent = 0 
and $artist_order_master.order_status in ('completed','wc-completed')
and $artist_order_master.order_date_completed is not null
$sql_interval

order by artist_id asc, order_id asc
	";
	// echo "imran123";
	 // echo $sql;
	// update_option("kingofqueen1233",$sql);
	
	return $wpdb->get_results($sql);
	
}
function get_order_for_artist_payout($offset =null , $limit = null){
	global $wpdb;
	
	$artist_order_master  = $wpdb->prefix."wap_artist_order_master";
	$users  = $wpdb->prefix."users";
	$sql_limit = "";
	if($offset =null && $limit = null)
		$sql_limit = "limit $offset , $limit";
	$sql = "
SELECT 
  $users.user_nicename as artist_name,
	order_id,
	artist_id,
	artist_comission,
	FROM_UNIXTIME( order_date_completed ) as order_date_display,
	order_date_completed ,
	artist_payed
	
FROM
$users  inner join 
		$artist_order_master on $users .ID = $artist_order_master.artist_id
WHERE
	order_date_completed < UNIX_TIMESTAMP( now( ) - INTERVAL 10 DAY )
and artist_payed <> 1 
Order by artist_id asc,order_date_completed asc ,order_id asc
	";
	
	
	return $wpdb->get_results($sql);
	
}
function get_artist_comission($artist_id){
	global $wpdb;
	$artist_info  = $wpdb->prefix."wap_artist_info";
	$sql =  "select IFNULL(artist_comission,0) as artist_comission  from  $artist_info  where artist_id =%s limit 0,1";
	
	return $wpdb->get_var($wpdb->prepare($sql,$artist_id));
}
function set_artist_comission($artist_id,$artist_comssion){
	global $wpdb;	
	$artist_info  = $wpdb->prefix."wap_artist_info";
	if(get_artist_comission($artist_id) === null){
		$sql = "insert into $artist_info (artist_comission, artist_id) values(%s,%s)";
	}else{
		
		$sql =  "update  $artist_info  set artist_comission =%s where artist_id =%s ";
	}
	// echo $wpdb->prepare($sql,$artist_comssion,$artist_id); exit();
	return $wpdb->get_var($wpdb->prepare($sql,$artist_comssion,$artist_id));
}

/*
artist_email: string
artist_name:string
artist_comission:string
order_item_ids: array int
order_item_exists => associated array order_item_exists[item_id]=true
*/
function wap_get_artist_order_by_time(){
	global $wpdb;
	
	$artist_order_master  = $wpdb->prefix."wap_artist_order_master";
	$sql =  "";
	
	return $wpdb->get_results($wpdb->prepare($sql,$artist_id,$order_id,$order_status ));
	
}
function wap_insert_artist_order($order_id,$artist_id,$order_status,$order_date_created,$artist_comission,$order_date_completed){
	global $wpdb;
	$query_var = array($artist_id,$order_id,$order_status,$order_date_created,$artist_comission);
	$column_date_completed="";
	$s_date_completed="";
	

	if($order_status == "completed" || $order_status == "wc-completed"){
		$column_date_completed=" , order_date_completed ";
		$s_date_completed =" , %s ";
		$query_var[] = $order_date_completed;
	}else{
		$column_date_completed=" , order_date_completed ";
		$s_date_completed =" , %s ";
		$query_var[] = 'NULL' ;
	}
		
	$artist_order_master  = $wpdb->prefix."wap_artist_order_master";
	$sql =  "insert into  $artist_order_master(artist_id ,order_id ,order_status,order_date_created ,artist_comission	$column_date_completed) values(%s,%s,%s,%s,%s $s_date_completed );";
	$sql = $wpdb->prepare($sql,$query_var);
	
	$sql = str_ireplace("'null'", "null",$sql);
	
	$wpdb->query($sql);
	
	
	return $wpdb->insert_id;;
}
function wap_update_artist_order($id, $order_id,$order_status,$order_date_completed){
	
	global $wpdb;
	$artist_order_master  = $wpdb->prefix."wap_artist_order_master";
	$query_var = array($order_status );
	$sql_order_completed=" ";	
	

	if($order_status == "completed" || $order_status == "wc-completed"){
		$sql_order_completed=",  order_date_completed =%s ";	
		$query_var[] =  $order_date_completed;
	}else{
		$sql_order_completed=",  order_date_completed =%s ";	
		$query_var[] =  'NULL';
	}
	
	$query_var [] =  $order_id ;
	
	$sql =  "update   $artist_order_master set order_status =%s $sql_order_completed where order_id  = %s";
	$sql = $wpdb->prepare($sql,  $query_var);
	
	$sql = str_ireplace("'null'", "null",$sql);
	
	$ret = $wpdb->query($sql);
	
	return $ret;
}
function wap_artist_order_exist($order_id,$artist_id){
	global $wpdb;
	$artist_order_master  = $wpdb->prefix."wap_artist_order_master";
	
	$sql =  "select id from  $artist_order_master where artist_id = %s and order_id = %d limit 0,1";
	return $wpdb->get_var($wpdb->prepare($sql,$artist_id,$order_id));
	
	
}
$my_new_count = 0;
function insert_wap_artist_order_details($master_id,$item_id,$product_id,$item_quantity,$item_refunded ,$item_subtotal,$comission_percent,$comission_earned,$order_status,$should_insert_order_detail=false ){
	global $my_new_count;
	$my_new_count++;
	
	global $wpdb;
	
	$artist_order_details  = $wpdb->prefix."wap_artist_order_details";
	if($should_insert_order_detail){
		$sql =  "insert into  $artist_order_details (master_id ,item_id ,product_id,item_quantity,item_refunded,item_subtotal,comission_percent ,comission_earned ,order_status)  values (%s  ,%s  ,%s ,%s ,%s ,%s ,%s ,%s  ,%s   );";
		
		
		$wpdb->query($wpdb->prepare($sql,$master_id,$item_id,$product_id,$item_quantity,$item_refunded ,$item_subtotal,$comission_percent,$comission_earned,$order_status) );
	}
	if(!$should_insert_order_detail){
		$sql =  "update  $artist_order_details set item_quantity=%s,item_refunded=%s,item_subtotal=%s,comission_percent =%s,comission_earned =%s,order_status=%s where master_id='%s' and  item_id=%s and  product_id=%s ";
	
		$wpdb->query($wpdb->prepare($sql,$item_quantity,$item_refunded ,$item_subtotal,$comission_percent,$comission_earned,$order_status,$master_id,$item_id,$product_id) );
	}
	

	
}

function wap_store_order_details($order_id,$artist_order_info){
	
	$order = wc_get_order($order_id);	
	if( $order !=null && is_object($order)){
		$order_status = $order->get_status();
		$artist_id = $artist_order_info["artist_id"];
		$artist_comission = $artist_order_info["artist_comission"];
		$order_date_created = $order->get_date_created()->getTimestamp();	
		$order_date_completed = 'NULL';
		if($order->get_date_completed()!=NULL and is_object($order->get_date_completed()) )
			$order_date_completed = $order->get_date_completed()->getTimestamp();	
	

		$existing_artist_order_id = false;
		$new_order_id = false;
		$existing_artist_order_id = wap_artist_order_exist($order_id,$artist_id);
		
		$should_insert_order_detail = false;
		$master_id = false;
		if(!	$existing_artist_order_id ){
			
			$master_id =wap_insert_artist_order($order_id,$artist_id,$order_status,$order_date_created,$artist_comission ,$order_date_completed);
				$should_insert_order_detail = true;//is used to determine whether we should update or insert the detail
		}else{
			
			wap_update_artist_order($existing_artist_order_id, $order_id,$order_status,$order_date_completed);
			$master_id = $existing_artist_order_id;
		}
		
			
		$order_item_ids = $artist_order_info["order_item_ids"];
		$order_item_exists = $artist_order_info["order_item_exists"];
		$order_items = $order->get_items();
		$count = 0;
		foreach($order_items as $item){
			//$artist_id //available from above
			
			$item_id = $item->get_id();
			$product = $item->get_product();
			$count++;
			$product_id = $product->get_id();
			
			if($order_item_exists[$item_id] && $product != null & is_object($product)){
				
				$item_quantity = $item->get_quantity();
				$item_subtotal = (float) $order->get_line_subtotal($item,false)	;		
				$item_refunded =  $order->get_qty_refunded_for_item( $item_id );
				$comission_percent = (float) $artist_comission ;//avaliable from top
				$comission_earned =number_format((float)($item_subtotal*$comission_percent/100.00), 2, '.', '');
				// availble from above				
				// $order_status 
				
				insert_wap_artist_order_details($master_id,$item_id,$product_id,$item_quantity,$item_refunded ,$item_subtotal,$comission_percent,$comission_earned,$order_status,$should_insert_order_detail  );
			}
			
			
			
			
		}
	

	}
}


function create_artist_info_table(){
	global $wpdb;
	$artist_info  = $wpdb->prefix."wap_artist_info";
	$sql =  "CREATE TABLE IF NOT EXISTS  $artist_info(
				id INTEGER NOT NULL  AUTO_INCREMENT,
				artist_id INTEGER,	
				artist_comission float,
				PRIMARY KEY  (id)					
			);";
	
	$wpdb->query($sql);
	
	$artist_uploads  = $wpdb->prefix."wap_artist_uploads";
	$sql =  "CREATE TABLE IF NOT EXISTS  $artist_uploads(
				id INTEGER NOT NULL  AUTO_INCREMENT,
				artist_id INTEGER,
				file_name varchar(2000),
				file_url varchar(2000),
				file_path varchar(2000),
				PRIMARY KEY  (id)					
			);";
	$wpdb->query($sql);
	/*
	$artist_order_master  = $wpdb->prefix."wap_artist_order_master";
	$sql =  "CREATE TABLE IF NOT EXISTS  $artist_order_master(
				id INTEGER NOT NULL  AUTO_INCREMENT,
				artist_id INTEGER,	
				order_id INTEGER,
				order_status varchar(20),
				order_date_created timestamp,
				order_date_completed timestamp,
				PRIMARY KEY  (id)					
			);";
	$wpdb->query($sql);
	*/
	

    
	$artist_order_details  = $wpdb->prefix."wap_artist_order_details";
	$sql =  "CREATE TABLE IF NOT EXISTS  $artist_order_details(
				id INTEGER NOT NULL  AUTO_INCREMENT,
				master_id INTEGER,	
				item_id INTEGER,
				product_id INTEGER,				
				item_quantity INTEGER,
				item_refunded INTEGER,
				item_price float,
				item_subtotal float,
				comission_percent float,
				comission_earned float,
				order_status varchar(20),
				PRIMARY KEY  (id)					
			);";
	$wpdb->query($sql);
    	$wap_artist_order_master  = $wpdb->prefix."wap_artist_order_master";
	$sql =  "
	CREATE TABLE IF NOT EXISTS  $wap_artist_order_master  (
  id int(11) NOT NULL AUTO_INCREMENT,
  artist_id int(11),
  order_id int(11),
  order_status varchar(20) ,
  order_date_created bigint(20) ,
  order_date_completed bigint(20)  DEFAULT NULL,
  artist_payed int(11)  DEFAULT 0,
  artist_comission float(255, 0) ,
  email_sent int(255)  DEFAULT 0,
  email_sent_date bigint(20)  DEFAULT NULL,
  PRIMARY KEY (id) 
)
			";
			

		$wpdb->query($sql);
	// exit();
}
?>