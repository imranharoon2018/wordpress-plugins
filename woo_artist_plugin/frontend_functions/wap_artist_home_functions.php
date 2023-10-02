<?php 
if ( ! function_exists( 'wp_handle_upload' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
}
add_action( 'wp_ajax_wap_get_tags_to_display', 'wap_get_tags_to_display' );

function wap_get_tags_to_display() {
	$taxonomies = get_option("wap_selected_taxonomy_for_tags")?get_option("wap_selected_taxonomy_for_tags"):[];
	
	$results = array();
	if(count($taxonomies)){
		global $wpdb;
		$term_taxonomy = $wpdb->prefix."term_taxonomy";
		$terms = $wpdb->prefix."terms";
		$str = " %s " ;
		for($i=1;$i<count($taxonomies);$i++){
			$str .= ", %s";
		}
		
		
		$sql = "

SELECT 
	DISTINCT( $terms.NAME ) as NAME
FROM
	$terms 
WHERE
	$terms.term_id IN ( SELECT $term_taxonomy.term_id FROM $term_taxonomy WHERE $term_taxonomy.taxonomy IN ( $str ) )";
	$sql = $wpdb->prepare($sql,$taxonomies);
	
	$rows = $wpdb->get_results($sql);
	foreach($rows as $row){
		$results[]=$row->NAME;
	}
	
		
	}


	wp_send_json($results);
	
}
function get_uploads_by_user_id_and_approved($user_id,$approved){
	global $wpdb;
	$wap_artist_uploads = $wpdb->prefix."wap_artist_uploads";
	$sql = "
	select 
		artist_id,
		file_name,
		file_url,
		file_path,
		product_name,
		product_tags 
	from  
		$wap_artist_uploads 
	where 
		artist_id=%d
	and 
		is_approved =%d
	order by 
		id desc	
	";	
	$sql = $wpdb->prepare($sql,$user_id,$approved);
	
	return $wpdb->get_results($sql);
	
}
function insert_artist_upload($args){
	global $wpdb;
	$wap_artist_uploads = $wpdb->prefix."wap_artist_uploads";
	$sql = "
	INSERT INTO $wap_artist_uploads (  artist_id, file_name, file_url, file_path, product_name, product_tags )
	VALUES
	( %s,%s,%s,%s,%s,%s);
	";	
	$sql = $wpdb->prepare($sql,$args);
	$sql = str_ireplace("'null'", "null",$sql);
	$wpdb->query($sql);
	return $wpdb->insert_id;
}
add_action( 'wp_enqueue_scripts',function(){
	wp_enqueue_style('wap_artist_tagsinput-style', WAP_PLUGIN_URL. 'assets/frontend/css/tagify/tagify.css');
	
	
	wp_enqueue_script('wap_artist_tagsinput-script', WAP_PLUGIN_URL. 'assets/frontend/js/tagify/tagify.min.js', array( 'jquery'));
	wp_enqueue_script('wap_artist_home-script', WAP_PLUGIN_URL. 'assets/frontend/js/wap_artist_home.js', array( 'jquery'));	
	wp_localize_script( 'wap_artist_home-script', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234 ) );
} );
add_action( 'init',function(){
	if(isset($_REQUEST['artist_upload_from_front_end'])){
		$id_array = array();
		if(wp_verify_nonce($_REQUEST['artist_upload_from_front_end'],"do_artist_upload_from_front_end")){
			// var_dump($_FILES);
			// var_dump("here");
			// exit();
			$maxFile=5;
			$file_name_prefix = "wap_artist_art_uploadfile_";
			for($i=1;$i<=$maxFile;$i++){
				$file_name =  "wap_artist_art_uploadfile_".$i;				
				if(isset($_FILES[$file_name] ["tmp_name"])&& !empty($_FILES[$file_name] ["tmp_name"]) ){
					$file = wap_handle_upload_file_by_param($file_name);
					$id_array[]=insert_artist_upload(
						array(
							get_current_user_id(),
							$file["name"],
							$file["url"],
							$file["file"],
							null,
							null
						)
					);
					
				}
				
			}
		}		
	}
} );
?>