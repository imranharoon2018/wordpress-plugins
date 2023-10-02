
<div class="wrap">
	<h1>Wp Kinky Line</h1>
	
	<hr/>
	<h3>Settings</h3>

	<form method="post">
		<input type="hidden" name="page" id ="page" value="<?=$_REQUEST["page"]?>" />
		<input type="text" name="wklw_default_callrate" value="<?=get_option("wklw_default_callrate")?>"/>
		<input type="submit" value="Save"  class="button-primary"/>
		
		<?php wp_nonce_field( 'do_save_default_callrate','save_default_callrate');?> 

	</form>
		
<hr/>
<h3>Routing No</h3>

<form method="post">

		<input type="submit" value="Create CSV File"  class="button-primary"/>
		
		<?php wp_nonce_field( 'do_create_csv_file','create_csv_file');?> 

	</form>
	
<?php if(get_option("wklw_routing_csv_url")){?>	
	CSV: <a href="<?=get_option("wklw_routing_csv_url")?>">File</a><br/>
<?php }?>
<?php if(get_option("wklw_routing_csv_create_date")){?>	
	CSV Create Date:<?=get_option("wklw_routing_csv_create_date")?>
<?php }?>
</div>