<?php
	
	$message = "This SKU is currently Available in Store";
	$used_dash_icon = '  ';
	$recycle_dash_icon = '  ';
	$disabled = "";
	if( !$returned_order_detail->is_avaible_in_store ){
		$message = "This SKU is Not currently Available in Store.";
		$disabled = " DISABLED ";
	} 
	if( $returned_order_detail->is_used ){
		$message = "This SKU has been used.";
		$disabled = " DISABLED ";
		$used_dash_icon = ' <span class="dashicons dashicons-saved"></span> ';
	} 
	if( $returned_order_detail->is_recycled ){
		$message = "This SKU has been recycled.";
		$disabled = " DISABLED ";
		$recycle_dash_icon = ' <span class="dashicons dashicons-saved"></span> ';
	} 
		
	
	
		
?>
	<table>
		<tr>
			<td id = "sku_availabe_status_<?=$returned_order_detail->order_id?>_<?=$returned_order_detail->sku_meta_id?>" colspan="2">Status: <?=$message?></td>
		</tr>
		
		
					
		<tr>
			<td ><button  
								data-sku_meta_id = "<?=$returned_order_detail->sku_meta_id?>" 
								data-order_id="<?=$returned_order_detail->order_id?>" 
								class="button-primary btn_available"  						
								id="btn_available_<?=$returned_order_detail->order_id?>_<?=$returned_order_detail->sku_meta_id?>" 
								<?= $disabled?>
								>Mark This SKU  Not available.
						</button></td>
						<td ><button  
								data-sku_meta_id = "<?=$returned_order_detail->sku_meta_id?>" 
								data-order_id="<?=$returned_order_detail->order_id?>" 
								class="button-primary btn_recycle_returned"  						
								id="btn_recycle_returned_<?=$returned_order_detail->order_id?>_<?=$returned_order_detail->sku_meta_id?>" 
								<?= $disabled?>
								>Mark This SKU  Recycled.
						</button></td>
		</tr>
					
		</table>
		<table>	
					
		<tr>
			<td>Used</td><td>Recycled</td>
		</tr>
		<tr>
		
			<td><?=$used_dash_icon ?></td>
			<td id="td_dash_icon_<?=$returned_order_detail->order_id?>_<?=$returned_order_detail->sku_meta_id?>"><?=$recycle_dash_icon?></td>
		</tr>
		
	</table>

