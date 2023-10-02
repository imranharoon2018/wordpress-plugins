<?php
/**
 * @package peepso_broadcast_message
 * @version 1.7.2
 */
/*
Plugin Name: peepso_broadcast_message
Plugin URI: www.google.com
Description: This is a plugin that allows admin to send message to all peepso user
Author:Imran
Version: 1.7.2
Author URI: http://www.google.com
*/

add_action( 'admin_menu', 'show_peepso_broadcast_message_menu' );
function show_peepso_broadcast_message_menu() {
add_submenu_page( "peepso",  'Peepso BroadCast Message', 'Peepso BroadCast Messsage','manage_options','broadcast-message', 'show_broadcast_window' );
}
function show_broadcast_window(){
	if (  current_user_can( 'manage_options' ) ) {

	
		if(		isset($_REQUEST["broad_cast_message_text"]) && 
				isset($_REQUEST["roles_to_send_message_to"])  
		
			){
				
				$x = array(
					'role'    => $_REQUEST["roles_to_send_message_to"],
					'fields' => 'ID',
				);
				$subject = "Message from admin";
				$message = $_REQUEST["broad_cast_message_text"];
				$user_ids = get_users( $x );
				$participants = array_map('intval',$user_ids);
				
				$creator_id = get_current_user_id();
				
				$model = new PeepSoMessagesModel();
				$msg_id = $model->create_new_conversation($creator_id, $message, $subject, $participants);
						if (is_wp_error($msg_id)) {
							
							
								?>
								<div class="notice notice-error  is-dismissible">
									<p>Error sending message: <?=$msg_id->get_error_message()?> </p>
								</div>
								<?php
							
						} else {
							$ps_messages = PeepSoMessages::get_instance();
							do_action('peepso_messages_new_message', $msg_id);
							
							PeepSoMessagesPlugin::new_message($msg_id);
							
								?>
								<div class="notice notice-error  is-dismissible">
									<p>Message sent success. Message id : <?=$msg_id?> </p>
								</div>
								<?php
						
						
						}
			}
	}

?>
	
	<div class= "wrap">
	<form method="post">
	<input type="hidden" value="<?=$_REQUEST["page"]?>" id="page" name="page" />
		<p>
			Select Roles <select id="roles_to_send_message_to" name="roles_to_send_message_to">
				<option selected >...Select</option>
				<option value= "author">Author</option>
				<option value="subscriber">Subscriber</option>				
		</select>
		</p>
		Message:
		<p style="margin-top: 0px;">
			
			<textarea rows="5" cols="100" id = "broad_cast_message_text" name = "broad_cast_message_text" ></textarea>
			
		</p>
		<input type="submit" id="send_boradcast_message" name = "send_borad_cast_message" class="button-primary" />
	</form>
	</div>
	<?php
}