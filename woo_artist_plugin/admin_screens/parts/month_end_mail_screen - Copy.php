<h3>Setup Theme File to send mail</h3>
	
	
	<?php
		$location = trailingslashit(get_template_directory());
		$file_name = 'wap_93534_artist_mail_send.php';
		$file= $location.$file_name;
		
		if (!file_exists($file)) {
			?>
				<form>
					<input type="hidden" id = "page" name ="page" value="<?=$_REQUEST['page']?>" />
						<?php wp_nonce_field( 'do_copy_theme_file','copy_theme_file');?> 
					<input type="submit" class="button-primary" value="Copy "/><br/>Copy <?=$file_name?> from <?=WAP_PLUGIN_DIR?>theme_file/ to <br><?=$location?>
				</form>
			<?PHP
		}else{
			
			?>
			
			<form  method="post">
				<input type="hidden" id = "page" name ="page" value="<?=$_REQUEST['page']?>" />
						<?php wp_nonce_field( 'do_delete_theme_file','delete_theme_file');?> 
				<input type="submit" class="button-primary" value="Delete"/> <strong>Delete the following file:</strong> <br/><?=	$file?>
			</form>
			
			<hr/>
			<?php
			
			$month_end_mail_page_id = mail_send_page_exist();
			?>
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
			<hr/>
			
			
			<?php
			
			
			if($month_end_mail_page_id ){
			?>
				<span style="width:25%;font-weight:bold;">Set this url in your corn console:</span><input style="width:70%;" type="text"
				value= "<?=get_permalink($month_end_mail_page_id )?>?rand=<?=get_option("wap_mail_send_page_pass")?>" readonly />
				<hr/>	
			<?php
			}
		}
	?>
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
	</form>