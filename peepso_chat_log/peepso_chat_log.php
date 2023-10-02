<?php
/**
 * @package peepso_chat_log
 * @version 1.7.2
 */
/*
Plugin Name: peepso_chat_log
Plugin URI: www.google.com
Description: This plugin allows admin to see the list of user and their chat history to admin
Author:Imran
Version: 1.7.2
Author URI: http://www.google.com
*/
function get_total_number_of_messages($search = null){
	global $wpdb ;
	$peepso_message_recipients = $wpdb->prefix."peepso_message_recipients";
	$posts = $wpdb->prefix."posts";
	$users = $wpdb->prefix."users";
	$sql="
	SELECT
		count(*) as total
	FROM
		$posts
		INNER JOIN $peepso_message_recipients ON $posts.ID = $peepso_message_recipients.mrec_msg_id
		INNER JOIN $users t1 ON ( t1.ID = $posts.post_author )
		INNER JOIN $users t2 ON ( t2.ID = $peepso_message_recipients.mrec_user_id ) 
		AND $posts.post_author != $peepso_message_recipients.mrec_user_id 
		AND $posts.post_type = 'peepso-message' 
		[WHERE_CLAISE] 
	" ;	
		$where_replace = "";
		if($search != null){
			$where_replace = " where $posts.post_content like '%".$search."%'";
			$where_replace .= " or t1.user_login like '%".$search."%'";
			$where_replace .= " or t2.user_login like '%".$search."%' ";
			
		}
		$sql = str_replace("[WHERE_CLAISE]",$where_replace,$sql);	
		
		return $wpdb->get_var($sql);

}

function get_messages(	$offset=null, $limit=null ,$search = null){


	global $wpdb ;
	$peepso_message_recipients = $wpdb->prefix."peepso_message_recipients";
	$posts = $wpdb->prefix."posts";
	$users = $wpdb->prefix."users";
	$sql="
	SELECT
		$posts.ID as post_id,
		$peepso_message_recipients.mrec_msg_id,
		$posts.post_date,
		$posts.post_author AS sender_id,
		$posts.post_content AS message,
		$peepso_message_recipients.mrec_user_id AS receiver_id,
		t1.user_login AS sender,
		t2.user_login AS receiver 
	FROM
		$posts
		INNER JOIN $peepso_message_recipients ON $posts.ID = $peepso_message_recipients.mrec_msg_id
		INNER JOIN $users t1 ON ( t1.ID = $posts.post_author )
		INNER JOIN $users t2 ON ( t2.ID = $peepso_message_recipients.mrec_user_id ) 		
		AND $posts.post_author != $peepso_message_recipients.mrec_user_id 
		AND $posts.post_type = 'peepso-message' 
	 [WHERE_CLAISE] 
	ORDER BY
		$peepso_message_recipients.mrec_msg_id DESC

	";
		if($offset!==null && $limit!==null){
			$sql .= " LIMIT $offset, $limit ";
		}
		$sql .= ";";
		$where_replace = "";
		if($search != null){
			$where_replace = " where $posts.post_content like '%".$search."%'";
			$where_replace .= " or t1.user_login like '%".$search."%'";
			$where_replace .= " or t2.user_login like '%".$search."%' ";
			
		}
		$sql = str_replace("[WHERE_CLAISE]",$where_replace,$sql);
		 // echo $sql;
		 // exit();
	return $wpdb->get_results($sql);
}

add_action( 'admin_menu', 'show_peepso_chat_log_menu' );
function show_peepso_chat_log_menu() {
  
  add_submenu_page( "peepso",  'Peepso Chat Log', 'Peepso Chat Log','manage_options','chat-log', 'show_log' );
}
function show_log(){
$search = trim($_REQUEST["search_text"]);
$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
$limit = 100; // number of rows in page
$offset = ( $pagenum - 1 ) * $limit;
$total = get_total_number_of_messages($search);
$num_of_pages = ceil( $total / $limit );

$messages = get_messages($offset,$limit,$search );
?>
<div class= "wrap">
			
			<h1 class="wp-heading-inline">
			Peepso Chat Log</h1>
			<form method="post">
				<input type="hidden" value="<?=$_REQUEST["page"]?>" id="page" name="page" />
				Search : <input type= "text" id="search_text" name="search_text" /> <input type="submit" value="Search" />
			</form>		
			<hr/>
			<table class="wp-list-table widefat fixed striped table-view-list posts">
					<tr>
						<th>ID</th>
						<th>Date</th>
						<th>Sender</th>
						<th>Receiver</th>
						<th>Message</th>
					</tr>
					
					<?php foreach($messages as $a_message){?>
					<tr>
							<td><?=$a_message->mrec_msg_id?></td>
							<td><?=$a_message->post_date?></td>
							<td><a href="<?=get_edit_user_link($a_message->sender_id)?>"><?=$a_message->sender?>(id:<?=$a_message->sender_id?>)</a></td>
							<td><a href="<?=get_edit_user_link($a_message->receiver_id)?>"><?=$a_message->receiver?>(id:<?=$a_message->receiver_id?>)</a>	</td>
							<td><?=$a_message->message?> <?php 
												 $photos_model = new PeepSoPhotosModel();
												$photos = $photos_model->get_post_photos($a_message->post_id);
												if(is_array($photos))
													foreach($photos as $a_photo){
														if(property_exists( $a_photo, 'pho_thumbs'))
															if(is_array($a_photo->pho_thumbs)){
																if(isset($a_photo->pho_thumbs["s_s"])){
																	?>
																		<p>
																			<a href= "<?=$a_photo->pho_thumbs["s_s"]?>" >
																			<img width = "100px" height= "60px" src = "<?=$a_photo->pho_thumbs["s_s"]?>" />
																			</a>
																		</P>
																	<?php
																}
															}
													}												
												
												?>
							</td>
							
						</tr>
					<?php					
					}
					?>
					<tr>
						<td colspan= "5">
						
							<?php	
										$arr_query_arg = array(
																'pagenum' => '%#%'
															) ;
										if(isset($_REQUEST["search_text"])) 
											$arr_query_arg["search_text"] = $_REQUEST["search_text"];
										$page_links = paginate_links( array(
												// 'base' => add_query_arg( 'pagenum', '%#%' ),
												'base' => add_query_arg($arr_query_arg),
												'format' => '',
												'prev_text' => __( '&laquo;', 'text-domain' ),
												'next_text' => __( '&raquo;', 'text-domain' ),
												'total' => $num_of_pages,
												'current' => $pagenum
											) );

											if ( $page_links ) {
												echo '<div class="tablenav"><div>' . $page_links . '</div></div>';
											}
							?>

						</td>
					</tr>
					
			</table>
			
	</div>
	<?php
}