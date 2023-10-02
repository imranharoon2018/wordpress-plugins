<?php
/**
 * @package peepso_show_attachment
 * @version 1
 */
/*
Plugin Name: peepso_show_attachment
Plugin URI: www.google.com
Description: Implements shortcode [peepso_show_attachment user_id] that lists the attachments in  a grid made by peepso user
Author:Imran
Version: 1
Author URI: http://www.google.com
*/
//use short code [peepso_show_attachment]
$file_msg  = "Bild nicht vorhanden";

add_action( 'wp_enqueue_scripts', function(){
	wp_enqueue_style('peepso_show_attachment-styles', trailingslashit(plugin_dir_url( __FILE__ )). 'assets/css/peepso_show_attachment.css', array());	
	wp_enqueue_style('peepso_show_attachment_lightbox-styles', trailingslashit(plugin_dir_url( __FILE__ )). 'assets/lightbox/css/lightbox.min.css', array());	
	wp_enqueue_script('peepso_show_attachment_lightbox-script', trailingslashit(plugin_dir_url( __FILE__ )). 'assets/lightbox/js/lightbox.min.js', array());	
	wp_enqueue_script('peepso_show_attachment-script', trailingslashit(plugin_dir_url( __FILE__ )). 'assets/js/script.js', array());	
});

add_shortcode( 'peepso_show_attachment', 'handle_peepso_show_attachment' );

function get_total_number_of_attachment($user_id){
	global $wpdb ;
	$peepso_photos = $wpdb->prefix."peepso_photos";
	$peepso_message_recipients = $wpdb->prefix."peepso_message_recipients";
	$posts = $wpdb->prefix."posts";
	$users = $wpdb->prefix."users";
	$sql="
SELECT 
  count(*) as total
FROM 
  $peepso_photos 
  INNER JOIN $posts on $posts.ID = $peepso_photos.pho_post_id 
  and $posts.post_type = 'peepso-message' 
  and $posts.post_author != %d 
  Inner join $peepso_message_recipients  on $peepso_photos.pho_post_id = $peepso_message_recipients.mrec_msg_id 
  and $peepso_message_recipients.mrec_user_id = %d	
"
	;
	$sql  = $wpdb->prepare($sql, $user_id, $user_id);
	
	return $wpdb->get_var($sql);
}
function get_attachments($user_id,$offset  = "0",$limit= 30){
	global $wpdb ;
	$peepso_photos = $wpdb->prefix."peepso_photos";
	$peepso_message_recipients = $wpdb->prefix."peepso_message_recipients";
	$posts = $wpdb->prefix."posts";
	$users = $wpdb->prefix."users";
	$sql="
SELECT 
  $posts.post_author as sender_id, 
  $peepso_photos.pho_id AS photo_id, 
  $peepso_photos.pho_post_id AS photo_post_id 
FROM 
  $peepso_photos 
  INNER JOIN $posts on $posts.ID = $peepso_photos.pho_post_id 
  and $posts.post_type = 'peepso-message' 
  and $posts.post_author != %d 
  Inner join $peepso_message_recipients  on $peepso_photos.pho_post_id = $peepso_message_recipients.mrec_msg_id 
  and $peepso_message_recipients.mrec_user_id = %d	
order by $peepso_photos.pho_post_id desc
limit %d, %d	

;";
	
	$sql  = $wpdb->prepare($sql, $user_id, $user_id,$offset,$limit);
	
	$results = $wpdb->get_results($sql);
	return $results;
}
function get_photo_location($post_photos,$photo_id){
	$location = null;
	foreach($post_photos as $a_photo){
		if($a_photo->pho_id==$photo_id){
			$location = $a_photo->location;
			break;
		}
	}
	return $location;
}
function remote_file_exists($remoteFile){
	$handle = @fopen($remoteFile, 'r');
	return $handle;

	
}
function handle_peepso_show_attachment( $atts, $content ){
	global $file_msg ;
	$user_id = get_current_user_id();
	if(!$user_id) return $content;
	
	
	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	$limit = 20; // number of rows in page
	$offset = ( $pagenum - 1 ) * $limit;
	$total = get_total_number_of_attachment($user_id);
	$num_of_pages = ceil( $total / $limit );
	
	$results = get_attachments($user_id,$offset,$limit);
	ob_start();
	
	echo "<div class='p_s_a_bg container'>";
    
    echo "<div class='p_s_a_row'>";
		foreach($results as $a_result){
			$photos_model = new PeepSoPhotosModel();
			
			$photo_location = get_photo_location($photos_model->get_post_photos($a_result->photo_post_id),$a_result->photo_id);
			$file_exists =   remote_file_exists($photo_location);
			
			$sender  =  get_user_by('id', $a_result->sender_id);
			$profile_url = esc_url(get_author_posts_url($a_result->sender_id));
			
			echo "<div class='p_s_a_col-3 nopadding shadow custm_mycread_container'>";
			if( $file_exists && $photo_location ){
				echo "<div class='p_s_a_img_container'><a href='".$photo_location ."' data-lightbox='photos_for_".$user_id."'>"."<img class='p_s_a_img' src=".esc_url( $photo_location)." /></a></div>";          
			}else{
				echo "<div class='p_s_a_img_container' style='width:100%;height:165px;text-align:center;padding-top:60px;'/>$file_msg</div>";          
			}
			echo "<div class='p_s_a_name'><a class='p_s_a_name_link' href='".$profile_url."'>".ucfirst($sender->user_login)."</a>";
			echo '<br/><button class="p_s_a_open_chat_window dokan-btn dokan-btn-theme dokan-follow-store-button dokan-btn-sm" data-user_id="'.$a_result->sender_id.'">Nachricht senden<i class="dashicons dashicons-email-alt store_banner_dash_icons"></i></button>';
			echo "</div>";//echo "<div class='p_s_a_name'>
			echo "</div>";
		
			
						
		}
	echo "</div>";
	echo "</div>";

	$arr_query_arg = array(
							'pagenum' => '%#%'
						) ;

	$page_links = paginate_links( array(
			// 'base' => add_query_arg( 'pagenum', '%#%' ),
			'base' => add_query_arg($arr_query_arg),
			'format' => '',
			'prev_text' => __( '&laquo;&nbsp;Vorherige Seite', 'text-domain' ),
			'next_text' => __( 'Nächste Seite&nbsp;&raquo;', 'text-domain' ),
			'total' => $num_of_pages,
			'current' => $pagenum
		) );

		if ( $page_links ) {
			echo '<div class="paginate_tablenav">' . $page_links . '</div>';
		}
							
	$out = ob_get_clean();
	return $content.$out;

}