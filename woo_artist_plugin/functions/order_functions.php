<?php

function wap_other_order_status_change($order_id){
	if($order_id!=null){
				$order = wc_get_order($order_id);
				
				if($order != null && is_object($order)){
				
					$artist_information = wap_get_artist_information_from_order($order);
					
					$count = 0;
					if(is_array($artist_information) && count($artist_information)>0)					
						foreach($artist_information as $artist_info){
							wap_store_order_details($order->get_id(),$artist_info);
							
						}
				}
			}
	
}
function wap_artist_order_completed($order_id){
		if($order_id!=null){
				$order = wc_get_order($order_id);
				
				if($order != null && is_object($order)){
					
					$artist_information = wap_get_artist_information_from_order($order);
					
					$count = 0;
					if(is_array($artist_information) && count($artist_information)>0)					
						foreach($artist_information as $artist_info){
							wap_store_order_details($order->get_id(),$artist_info);
							
						}
				}
			}
}
function wap_artist_order_cancelled($order_id){
		if($order_id!=null){
				$order = wc_get_order($order_id);
				
				if($order != null && is_object($order)){
					
					$artist_information = wap_get_artist_information_from_order($order);
					
					$count = 0;
					if(is_array($artist_information) && count($artist_information)>0)					
						foreach($artist_information as $artist_info){
							wap_store_order_details($order->get_id(),$artist_info);
							wap_create_and_send_order_cancelled_email($order->get_id(),$artist_info);
						}
				}
			}
}
function wap_artist_order_processing($order_id){
	
			if($order_id!=null){
				$order = wc_get_order($order_id);
				if($order != null && is_object($order)){
						
						$artist_information = wap_get_artist_information_from_order($order);
						
					$count = 0;
					if(is_array($artist_information) && count($artist_information)>0)					
						foreach($artist_information as $artist_info){
							wap_store_order_details($order->get_id(),$artist_info);
							
							wap_create_and_send_order_processing_email($order->get_id(),$artist_info);
						}
				}
			}
}

?>