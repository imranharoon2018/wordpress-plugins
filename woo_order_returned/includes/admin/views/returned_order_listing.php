<?php
$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
$limit = 10; // number of rows in page
$offset = ( $pagenum - 1 ) * $limit;
$total = get_total_number_of_returned_orders();
$num_of_pages = ceil( $total / $limit );
?>
<div class= "wrap">
			<div>
			<h1 class="wp-heading-inline">
			Retruned Order</h1>
		
			<form method= "POST">
				<input type="hidden" value="<?=$_REQUEST["page"]?>" id="page" name="page" />
				<p >
					<label>Order No:</label><input type="number" id="woo_returned_order_id" name = "woo_returned_order_id" />

				</p>
				<p >
					<input type="submit" class="button-primary"/>

				</p>
			
			</form>
			</div>
			<div>
			<hr style="margin-top:20px;"/>
				<h1>Returned Orders:</h1>
				
				<table class="wp-list-table widefat fixed striped table-view-list posts">
					<tr>
						<th>ID</th>
						<th>Order</th>
						<th>Date</th>
						<th>Mark as 'Not Returned'</th>
					</tr>
					<?php
						$orders = get_returned_orders($offset , $limit);
						foreach($orders as $an_order){
					?>
						<tr>
							<td><?=$an_order->ID?></th>
							<td><a href="<?=get_edit_post_link($an_order->ID)?>">Order #<?=$an_order->ID?></a></td>
							<td><?=$an_order->post_date?></td>
							<td>
								<form method ="post">
									<input type="hidden" id = "order_id_mark_as_not_returned" name =  "order_id_mark_as_not_returned" value="<?=$an_order->ID?>"/>
									<input type="hidden" name="page" id="page" value= "<?=$_REQUEST['page']?>" />
									<input type="submit" class="button-primary" value="Mark as 'Not Returned'"/>
								</form>
							</td>
						</tr>
					
					<?php
						}
					?>
				</table>
			</div>
			<?php
			$page_links = paginate_links( array(
					'base' => add_query_arg( 'pagenum', '%#%' ),
					'format' => '',
					'prev_text' => __( '&laquo;', 'text-domain' ),
					'next_text' => __( '&raquo;', 'text-domain' ),
					'total' => $num_of_pages,
					'current' => $pagenum
				) );

				if ( $page_links ) {
					echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
				}
			?>
	
	
	<div>
	<hr style="margin-top:20px;"/>
		<h1 class="wp-heading-inline">
			Product Attributes</h1>
		
		<form method= "POST">
				<input type="hidden" value="<?=$_REQUEST["page"]?>" id="page" name="page" />
				<p >
					<label>Product Attributes :</label><input  disabled type="text" id="woo_product_attributes_in_line_item" name = "woo_product_attributes_in_line_item" value="<?=
																		implode(	",",
																					is_array(get_option("woo_product_attributes_in_line_item")) ?
																					get_option("woo_product_attributes_in_line_item"):
																					array()
																					)
																?>"  style = "width:60%"/>(*Please use coma to spearate each attribute name)

				</p>
				<p >
					<input disabled type="submit" class="button-primary" id= "submit_woo_product_attributes_in_line_item"/>&nbsp;
					<button  type="submit" class="button-primary" id= "enable_edit_woo_product_attributes_in_line_item" >Edit </button>

				</p>
				
			</form>
	</div>
</div>
<script>
		(function($) {
		
			 
			$( document ).ready(function() {
				$('#enable_edit_woo_product_attributes_in_line_item').click(function(event){
					event.preventDefault();
					$( "#woo_product_attributes_in_line_item" ).prop('disabled', false);
					$( "#submit_woo_product_attributes_in_line_item" ).prop('disabled', false);
					
					
				});
			});
		})( jQuery);;
</script>