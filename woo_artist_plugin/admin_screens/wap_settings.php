<?php
$option_name_for_page_id = array(
		'Artist Dashboard' => "wap_artist_account_dashboard_page_id",
		'Artist Upload File'=> "wap_artist_upload_file_page_id",
		'Artist Sales Statitscs' => "wap_artist_artist_sales_statistics_page_id",
		'Artist Account Information' => "wap_artist_account_information_page_id",
		'Artist Sign Up' => "wap_artist_signup_page",
	);
?>

<div class="wrap">
<!--
	<h1>Woo Artist Plugin Setting</h1>
	<hr/>
	<h3>Pages:</h3>
	<table class="wp-list-table widefat fixed striped table-view-list tags">
			<tr><th>Page</th><th>Page Id</th></tr>
		<?php
		
			foreach($option_name_for_page_id as $page_title=>$option_name){
				$page_id = get_option($option_name);
				?>
				<tr>
					<td><a href="<?=get_edit_post_link($page_id)?>"><?= $page_title?></a></td>
					<td>
						<form>
							<input type="hidden" name="page" id ="page" value="<?=$_REQUEST["page"]?>" />
							<input type="number" name="<?=$option_name?>" id ="<?=$option_name?>" value="<?=$page_id?>" />
							<input type="submit" value="Save"/>
							$option_name
						<?php wp_nonce_field( 'do_save_'.$option_name,'save_'.$option_name);?> 

						</form>
					</td>
				</tr>
				
				<?php
			}
		?>
	</table>-->
<hr/>
	<h3>Upload Path:</h3>
<?php 
	$upload_dir  = wp_get_upload_dir();
	$path = trailingslashit($upload_dir['path']);
	$path .= "attachments/";
?>
<p><?=$path?></p>
<hr/>
	<h3>Defaults:</h3>
	<table class="wp-list-table widefat fixed striped table-view-list tags">
			<tr><th>Option</th><th>Value</th></tr>
				<tr>
					<td>Default Aartist Ccmission</td>
					<td>
						<form>
													<?php wp_nonce_field( 'do_save_wap_artist_default_comission','save_wap_artist_default_comission' );?> 

							<input type="hidden" name="page" id ="page" value="<?=$_REQUEST["page"]?>" />
							<input type="number" name="wap_default_artist_comission" id ="wap_default_artist_comission" value="<?=get_option("wap_default_artist_comission")?>" />
							<input type="submit" value="Save"/>
						</form>
					</td>
				</tr>
		
	</table>
	<?php
		require_once(WAP_PLUGIN_DIR."admin_screens/parts/month_end_mail_screen.php");
	?>
<!--	"Never let us be guilty of sacrificing any portion of truth on the altar of peace." -->

</div>