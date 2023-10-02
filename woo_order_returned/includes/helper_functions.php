<?php 
//trim and to lower sku

function wor_get_product_sku($product){
	return $product!=null ? strtolower( trim($product->get_sku())) :false;
}

function wor_notice_post_id_is_not_an_order(){
	?>
		<div class="notice notice-error  is-dismissible">
            <p>Post #<?=$_REQUEST["woo_returned_order_id"]?> is not a shop order</p>
         </div>
	<?php
}

function wor_notice_this_order_has_been_returned_before(){
	?>
		<div class="notice notice-error  is-dismissible">
            <p>Error: #<?=$_REQUEST["woo_returned_order_id"]?> has been returned once. Please remove the previous order first.</p>
         </div>
	<?php
}


?>