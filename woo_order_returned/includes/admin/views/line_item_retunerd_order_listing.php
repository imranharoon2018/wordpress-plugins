<?php 
if( is_array($available_returned_orders) && count ($available_returned_orders) ) {	
?>
	<table>
		<tr>
			<th colspan="3">Available SKU</th>
		</tr>
		<?php

			foreach($available_returned_orders as $a_returned_order){
		?>

				<tr id="row_<?=$a_returned_order->order_id?>_<?=$a_returned_order->sku_meta_id?>">
					<td><a href="<?=get_edit_post_link($a_returned_order->order_id)?>">#<?=$a_returned_order->order_id?></a></td>
					<td><button  
								data-sku_meta_id = "<?=$a_returned_order->sku_meta_id?>" 
								data-order_id="<?=$a_returned_order->order_id?>" 
								class="button-primary btn_use"  						
								id="btn_use_<?=$a_returned_order->order_id?>_<?=$a_returned_order->sku_meta_id?>" >USE
						</button>
					</td>
					<td><button 
								data-sku_meta_id = "<?=$a_returned_order->sku_meta_id?>" 
								data-order_id="<?=$a_returned_order->order_id?>" 
								class="button-primary btn_recycle"  				
								id="btn_recycle_<?=$a_returned_order->order_id?>_<?=$a_returned_order->sku_meta_id?>">RECYCLE
						</button>
					</td>
				</tr>
		<?php	
			}//foreach($available_returned_orders as $a_returned_order){
		?>
	</table>
<?php
}//if( is_array($available_returned_orders) && count ($available_returned_orders) ) {	
?>
