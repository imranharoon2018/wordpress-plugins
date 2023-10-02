<div class="wrap">
	<h1>Debug</h1>
	<form>
		<input type="hidden" id = "page" name = "page" value= "<?=$_REQUEST['page']?>" />
		<p>Returned Order id <input type = "number" id="debug_order_id"  name="debug_order_id" /></p>
		<p><input type = "submit" class= "button-primary" /></p>
	</form>
	<?php if(	get_option("debug_file_1_url")){?>
	<p>
	Download : <a href= "<?=get_option("debug_file_1_url")?>" download>file</a><br/>
	Delete : <a href="<?=trailingslashit(admin_url())."admin.php?page=woocommerce-returned-order-debug&delete_debug_file_1=1"?>">delete file</a>
	
	</p>
	<?php } ?>
	<hr/>
	<form>
		<input type="hidden" id = "page" name = "page" value= "<?=$_REQUEST['page']?>" />
		<p> Order id <input type = "number" id="debug_order_id_2"  name="debug_order_id_2" /></p>
		<p><input type = "submit" class= "button-primary" /></p>
	</form>
	<?php if(	get_option("debug_file_2_url")){?>
	<p>
	Download : <a href= "<?=get_option("debug_file_2_url")?>" download>file</a><br/>
	Delete : <a href="<?=trailingslashit(admin_url())."admin.php?page=woocommerce-returned-order-debug&delete_debug_file_2=1"?>">delete file</a>
	
	</p>
		<?php } ?>
	
</div>