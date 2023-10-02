<?php
// function get_items

$order_cache = array();

$all_artist_payouts = get_order_for_artist_payout();
$artist_order_items = array();
$artist_names = array();
$artist_order_comission = array();
$artist_orders = array();
$artist_order_date_completed = array();

foreach($all_artist_payouts as $an_artist_payout){
	$items = get_artist_item_from_order($an_artist_payout->order_id,$an_artist_payout->artist_id);
	if( !isset($artist_order_date_completed[$an_artist_payout->order_id]) )
		$artist_order_date_completed[$an_artist_payout->order_id] =date_i18n(wc_date_format(), $an_artist_payout->order_date_completed);
	
	if( !isset($artist_names[$an_artist_payout->artist_id]) )
		$artist_names[$an_artist_payout->artist_id] =$an_artist_payout->artist_name;
	
	
	if( !isset($artist_orders[$an_artist_payout->artist_id]) )
		$artist_orders[$an_artist_payout->artist_id] =array();

		
	$artist_orders[$an_artist_payout->artist_id] [] = $an_artist_payout->order_id;
	
	
	if( !isset($artist_order_items[$an_artist_payout->artist_id]) )
		$artist_order_items[$an_artist_payout->artist_id]=array();	
	
	if( !isset($artist_order_items[$an_artist_payout->artist_id][$an_artist_payout->order_id]) )
		$artist_order_items[$an_artist_payout->artist_id][$an_artist_payout->order_id]=array();
	
	
	if(!isset($artist_order_comission[$an_artist_payout->artist_id]))
		$artist_order_comission[$an_artist_payout->artist_id]=array();	
	
	if( !isset($artist_order_comission[$an_artist_payout->artist_id][$an_artist_payout->order_id]) )
		$artist_order_comission[$an_artist_payout->artist_id][$an_artist_payout->order_id]=$an_artist_payout->artist_comission;
	
	
	foreach($items as $an_item){
		
		$artist_order_items[$an_artist_payout->artist_id][$an_artist_payout->order_id][]=$an_item;
	}
}

?>
<style>
.align_right{
	text-align:right;
	
}
table.artist_order_tables tr td{
	/*border:1px solid red;*/
	
	width:100px;
}
tr.order_total td ,tr.order_id_date td{
	font-weight:bold;
	/*border-bottom:1px solid black;*/
}
tr.order_total td {
	border-top:1px solid black;
	padding-bottom:20px;
	/*border-bottom:1px solid black;*/
}
tr.artist_total td{
	font-weight:bold;
	border-top:1px solid black;
}

</style>	
<div class="wrap">

	<h1>Artist Plugin Payout</h1>
	<hr/>
<table class= "artist_order_tables" cellspacing="0">			
<tr><th></th><th>Item</th><th>Quantity</th><th>Subtotal</th><th>Artist Comission(%)</th><th>Artist Comission(amount)</th><th>Payout</th></tr>
<?php
	foreach($artist_names as $artist_id=>$artist_name){
		?>
			
		<tr><td colspan="6"><h3>Artist: <?=$artist_name?></h3></td><td></td></tr>
		
		<?php
		$artist_total_comission = 0.0 ;
		$orders = $artist_orders[$artist_id];
		$order_count = 0;
		for($j=0; $j<count($orders);$j++){
			
			$order_count++;
			$order_id= $orders[$j];
			?>
			<tr class="order_id_date"><td  colspan="7"><?=$order_count?>. Order: #<?=$order_id?> <br/><?=$artist_order_date_completed[$order_id]?></td></tr>
			<?php
			$order_subtotal = 0.0;
			$items = $artist_order_items[$artist_id][$order_id];
			for($i=0;$i<count($items);$i++ ){
				
				 $an_item = $items[$i];
				 
				$order_subtotal += $an_item["subtotal"]
			?>
				<tr><td></td><td><?=$an_item["name"]?></td><td class="align_right"><?=$an_item["quantity"]?></td><td class="align_right"><?=wc_price($an_item["subtotal"])?></td><td></td><td></td><td></td></tr>
				
			<?php
			}
			?>
			<tr class="order_total"><td>Order Total</td><td colspan="2" class=""></td><td class="align_right"><?=wc_price($order_subtotal )?></td><td class="align_right"><?=		$artist_order_comission[$artist_id][$order_id]?>%</td><td  class="align_right"><?php
					$comission = (float)$artist_order_comission[$artist_id][$order_id];
					$caluclated_comission = (float)($order_subtotal *$comission )/100.00;
					echo wc_price($caluclated_comission);
					
				?></td><td></td></tr>
			
		<?php
			$comission = (float)	$artist_order_comission[$artist_id][ $order_id];
			$caluclated_comission = (float)($order_subtotal *$comission )/100.00;
			$artist_total_comission +=  $caluclated_comission;
		}
		?>
		<tr class="artist_total"><td colspan="5" class="align_right">Total Comission for Artist : <?=$artist_name?></td><td  class="align_right"><?=wc_price($artist_total_comission)?></td><td class="align_right"><input type="button" class="button-primary" value="Payout"/></td></tr>
		<?php
				
	}
	
?>
</table>
</div>