<h3>Setup Theme File to send mail</h3>
<table class="wp-list-table widefat fixed striped table-view-list tags">
	
	<?php
		$location = trailingslashit(get_stylesheet_directory());
		$file_name = 'wap_93534_artist_mail_send.php';
		$file= $location.$file_name;
		
		if (!file_exists($file)) {
			?>
			<tr><td>
		
				<form method="post">
					<input type="hidden" id = "page" name ="page" value="<?=$_REQUEST['page']?>" />
						<?php wp_nonce_field( 'do_copy_theme_file','copy_theme_file');?> 
					<input type="submit" class="button-primary" value="Copy "/><br/>Copy <?=$file_name?> from <?=WAP_PLUGIN_DIR?>theme_file/ to <br><?=$location?>
				</form>
			</td></tr>
			<?PHP
		}else{
			
			?>
			<tr><td>
				<form  method="post">
					<input type="hidden" id = "page" name ="page" value="<?=$_REQUEST['page']?>" />
							<?php wp_nonce_field( 'do_delete_theme_file','delete_theme_file');?> 
					<input type="submit" class="button-primary" value="Delete"/> <strong>Delete the following file:</strong> <br/><?=	$file?>
				</form>
			
			</td></tr>
			<?php
			
			$month_end_mail_page_id = mail_send_page_exist();
			?>
			<tr><td>
				<form  method="post">
						<input type="hidden" id = "page" name ="page" value="<?=$_REQUEST['page']?>" />
						<?php wp_nonce_field( 'do_create_month_end_mail_page','create_month_end_mail_page');?> 	
						<input <?= $month_end_mail_page_id ? " disabled " : "" ?> type="submit" class="button-primary" value="Create Mail Send Page"/>
						<?php 
						if($month_end_mail_page_id){
							
							?>
							Make sure <a href="<?=get_edit_post_link($month_end_mail_page_id)?>">This</a> page has its template set to 'Artist Mail Send'
							<?php
						}
						?>
				</form>
			
			</td></tr>
			
			<?php
			
			
			if($month_end_mail_page_id ){
			?>
				<tr><td>	
					<span style="width:25%;font-weight:bold;">Set one of the following command in corn jobs </span><br/>
				<input style="width:100%;" type="text"
				value= "curl <?=get_permalink($month_end_mail_page_id )?>?rand=<?=get_option("wap_mail_send_page_pass")?>" readonly />
				<br/>or<br/>
				<input style="width:100%;" type="text"
				value= "/usr/local/bin/php  <?=WAP_PLUGIN_DIR?>cron_script.php <?=get_permalink($month_end_mail_page_id )?>?rand=<?=get_option("wap_mail_send_page_pass")?>" readonly />
				
				</td></tr>
			<?php
			}
			
			
		}
	?>
</table>
	<hr/>
	<h3>Enable Month End Mail Send</h3>
	<form method="post">
		<?php wp_nonce_field( 'do_save_month_end_email_enable','save_month_end_email_enable');?> 
		<input id = "wap_is_month_end_mail_send_enable" name = "wap_is_month_end_mail_send_enable" type="checkbox" <?= get_option("wap_is_month_end_mail_send_enable") ? " CHECKED ": "" ?> >Enable Month End Mail Send <input type="submit" class="button-primary" value="save" />
		<input type="hidden" id = "page" name ="page" value="<?=$_REQUEST['page']?>" />
	</form>
	<hr/>
	<h3>Delete Attachment Files</h3>
	<form method="post">
		<?php wp_nonce_field( 'do_save_delete_attachment_file_enable','delete_attachment_file_enable');?> 
		<input id = "wap_should_delete_attachemnt_file" name = "wap_should_delete_attachemnt_file" type="checkbox" <?= get_option("wap_should_delete_attachemnt_file") ? " CHECKED ": "" ?> >Delete Attachment Files after Email <input type="submit" class="button-primary" value="save" />
		<input type="hidden" id = "page" name ="page" value="<?=$_REQUEST['page']?>" />
	</form>
	<hr/> 
	<h3>Mark Orders after sending email</h3>
	<form method="post">
		<?php wp_nonce_field( 'do_save_mark_orders_after_email_send','mark_orders_after_email_send');?> 
		<input id = "wap_mark_orders_after_email_send" name = "wap_mark_orders_after_email_send" type="checkbox" <?= get_option("wap_mark_orders_after_email_send") ? " CHECKED ": "" ?> >Mark Orders after sending email <input type="submit" class="button-primary" value="save" />
		<input type="hidden" id = "page" name ="page" value="<?=$_REQUEST['page']?>" />
	</form>	<hr/> 
	<h3>No of days to wait after order is marked completed</h3>

	<form method="post"><!-- ***IMPORTANT*** this form is saved inside function may_save_save_mark_orders_after_email_send(){
-->
		<?php wp_nonce_field( 'do_save_no_of_days_to_wait','no_of_days_to_wait');?> 
		<p><input id = "wap_no_of_days_to_wait" name = "wap_no_of_days_to_wait" type="number" value='<?= get_option("wap_no_of_days_to_wait")?>' >
		<input type="submit" class="button-primary" value="save" /></p>
		<input type="hidden" id = "page" name ="page" value="<?=$_REQUEST['page']?>" />
		(to test:keep empty or 0)<br/>
		(to deploy:10)
	</form>
	