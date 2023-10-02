<?php
function show_wap_artist_sign_up_form(){
	ob_start();
	require_once(WAP_PLUGIN_DIR."artist_screens/artist_signup_form.php");
	return ob_get_clean();
}
function add_artist_role(){
	add_role( 'wap_artist', 'Artist', [
			'read' => true
		]
	);
}
function is_artist($user){
		$roles = ( array ) $user->roles; 
		$is_artist = false;
		foreach($roles as $role){
			$is_artist = $role == 'wap_artist';
		}
		return $is_artist;
}

function may_update_artist_comission($user_id){
	if(isset($_REQUEST["wap_artist_comission"])){
		set_artist_comission($user_id,$_REQUEST["wap_artist_comission"]);
	}
		
}
function may_show_read_only_artist_comission($profileuser){
	
			if(is_artist($profileuser)){
				
				$artist_comission = get_artist_comission($profileuser->ID);
				
			?>
		<table class="form-table" >
		<tbody>
			<tr>
				<th>
				<label for="wap_artist_comission">Artist Commission</label>
				</th>
				<td>
				<?=$artist_comission?>%
				<p class="description"></p>
				</td>
			</tr>
		</tbody>
		</table>
		<?php		
			}
		}
		
		
		function may_show_artist_comission($profileuser){
	
			if(is_artist($profileuser)){
				
				$artist_comission = get_artist_comission($profileuser->ID);
				
			?>
		<table class="form-table" >
		<tbody>
			<tr>
				<th>
				<label for="wap_artist_comission">Artist Commission</label>
				</th>
				<td>
				<input type="number" id="wap_artist_comission" name="wap_artist_comission" value ="<?=$artist_comission?>" class="regular-text">%
				<p class="description"></p>
				</td>
			</tr>
		</tbody>
		</table>
		<?php		
			}
		}
		
		
		
function may_show_product_term_in_users_table( $output, $column_name, $user_id){
				// $user_id = 9;
				$ret = "";
				if($column_name=="wap_user_product_term"){
				$user = get_user_by('id',$user_id);
				
					if(is_artist($user)){
						$artist_product_terms = get_term_ids_by_artist_id($user_id);
						
						if(is_array($artist_product_terms) && count($artist_product_terms)){
							
							// $a_term = get_term_by('term_id',$artist_product_terms[0]->term_id);
							$a_term = get_term($artist_product_terms[0]->term_id,WAP_TERM);
							// var_dump($a_term); exit();
							
							$ret .=	"<a href='".get_edit_term_link($a_term->term_id)."'>".$a_term->name."</a>";
							
							for($i=1;$i<count($artist_product_terms);$i++){
								$a_term =get_term($artist_product_terms[$i]->term_id,WAP_TERM);
								$ret .=	"<br/><a href='".get_edit_term_link($a_term->term_id)."'>".$a_term->name."</a>";
							}
						}
					}
				}
				return $ret;
			}
function  may_redirect_to_artist_account_pag($redirect_to,  $requested_redirect_to,  $user){
	if ( !is_wp_error( $user ) ) {
		$roles = ( array ) $user->roles; 
		$is_artist = false;
		foreach($roles as $role){
			$is_artist = $role == 'wap_artist';
		}
		if($is_artist) 
			return get_permalink(get_option("wap_artist_account_dashboard_page_id"));
		
	}
	return $redirect_to;
		
}


function create_wap_artist_signup_page(){
	$post_id =  get_page_by_title( 'Artist Sign Up');
	
	if(!$post_id ){
		
		$artist_post = array(
		  'post_title'    => 'Artist Sign Up',
		  'post_content'  => '<!-- wap:shortcode -->[wap_artist_sign_up_form]<!-- /wap:shortcode -->',
		  'post_status'   => 'publish',
		  'post_author'   => get_current_user_id(),
		  'post_type'   => 'page'
		);
		 
		// Insert the post into the database
		$post_id = wp_insert_post( $artist_post );
		if ( !is_wp_error( $post_id ) && $post_id) 
			update_option("wap_artist_signup_page",$post_id);
	}
}

function create_wap_artist_account_pages(){
	$option_name_for_page_id = array(
		'Artist Dashboard' => "wap_artist_account_dashboard_page_id",
		'Artist Upload File'=> "wap_artist_upload_file_page_id",
		'Artist Sales Statitscs' => "wap_artist_artist_sales_statistics_page_id",
		'Artist Account Information' => "wap_artist_account_information_page_id",
	);
	
	$page_datas = array(
		array(
		  'post_title'    => 'Artist Dashboard',
		  'post_content'  => '<!-- wap:shortcode -->[wap_artist_dashboard]<!-- /wap:shortcode -->',
		  'post_status'   => 'publish',
		  'post_author'   => get_current_user_id(),
		  'post_type'   => 'page'
		),array(
		  'post_title'    => 'Artist Upload File',
		  'post_content'  => '<!-- wap:shortcode -->[wap_artist_upload_file]<!-- /wap:shortcode -->',
		  'post_status'   => 'publish',
		  'post_author'   => get_current_user_id(),
		  'post_type'   => 'page'
		),array(
		  'post_title'    => 'Artist Sales Statitscs',
		  'post_content'  => '<!-- wap:shortcode -->[wap_artist_sales_statistics]<!-- /wap:shortcode -->',
		  'post_status'   => 'publish',
		  'post_author'   => get_current_user_id(),
		  'post_type'   => 'page'
		),array(
		  'post_title'    => 'Artist Account Information',
		  'post_content'  => '<!-- wap:shortcode -->[wap_artist_account_information]<!-- /wap:shortcode -->',
		  'post_status'   => 'publish',
		  'post_author'   => get_current_user_id(),
		  'post_type'   => 'page'
		)
		
	);
	foreach($page_datas as $a_page){
		$post_id =  get_page_by_title( $a_page['post_title']);
		if(!$post_id ){
		
			$artist_post = array(
			  'post_title'    => $a_page['post_title'],
			  'post_content'  => $a_page['post_content'],
			  'post_status'   => 'publish',
			  'post_author'   => get_current_user_id(),
			  'post_type'   => 'page'
			);
			 
			// Insert the post into the database
			$post_id = wp_insert_post( $artist_post );
			if ( !is_wp_error( $post_id ) && $post_id) 
				update_option($option_name_for_page_id[$a_page['post_title']],$post_id);
		}	
		
	}
	
	
	
	
}


function add_user_name_to_dir($arg){
	// var_dump($arg);
	$user_id  = get_current_user_id();
	// array(6) { ["path"]=> string(47) "D:\xampp\htdocs\xx36/wp-content/uploads/2021/07" ["url"]=> string(48) "http://localhost/xx36/wp-content/uploads/2021/07" ["subdir"]=> string(8) "/2021/07" ["basedir"]=> string(39) "D:\xampp\htdocs\xx36/wp-content/uploads" ["baseurl"]=> string(40) "http://localhost/xx36/wp-content/uploads" ["error"]=> bool(false) }
	$arg["path"]= trailingslashit($arg["basedir"]).$user_id.$arg["subdir"];
	$arg["url"]= trailingslashit($arg["baseurl"]).$user_id.$arg["subdir"];
	return $arg;
}
 function add_dir_filter(){
	 
	 add_filter( 'upload_dir', 'add_user_name_to_dir' );
 }
 
 
 function remove_dir_filter(){
	 // var_dump($data);
	 remove_filter( 'upload_dir', 'add_user_name_to_dir' );
 }
 function may_add_default_commission($user_id,$role){
	global $wpdb;
	
	if( $role == 'wap_artist' ){
		set_artist_comission($user_id,get_option("wap_default_artist_comission"));
		// $artist_info  = $wpdb->prefix."wap_artist_info";
		// $sql = "insert into $artist_info (artist_id,artist_comission) values(%s,%s)";
		// $wpdb->query($wpdb->prepare($sql,$user_id,get_option("wap_default_artist_comission")));
		// echo $wpdb->prepare($sql,$user_id,get_option("wap_default_artist_comission"));
	}
	
 }
 function save_artist_file($artist_id,$file_name,$file_path,$file_url){
	global $wpdb;
	$artist_uploads  = $wpdb->prefix."wap_artist_uploads";	
	$sql =  "insert into   $artist_uploads (artist_id ,file_name ,file_url ,file_path) values(%s,%s,%s,%s);";
	$wpdb->query($wpdb->prepare($sql,$artist_id,$file_name,$file_url,$file_path));
 }
function may_upload_file(){
	if( isset($_REQUEST['wap_artist_upload']) )
	if(wp_verify_nonce( $_REQUEST['wap_artist_upload'], 'do_wap_artist_upload')){

		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}
		// $file = wp_handle_upload( $_FILES['wap_artist_file'],$overrides );
			$overrides = array(
		// 'action'=>'editpost',
		// 'mimes'=> ["application/vnd.ms-excel"],
		// //["text/csv"],
		// 'ext'=>  [".csv"],
		// 'type'=> ["text"],
		 // 'test_type' => false,
		 'test_form' => false
	);
		// apply_filters( 'upload_dir', $cache[ $key ] );
		add_dir_filter(); 
		$file = wp_handle_upload( $_FILES['wap_artist_file'],$overrides );
		remove_dir_filter();
		
		if( isset($file["file"]) && isset($file["url"]) ){
			$file_path = $file["file"];
			$file_url = $file["url"];
			$file_name = wp_basename($file["file"]);
			save_artist_file(get_current_user_id(),$file_name,$file_path,$file_url);
		}
		
		// wp_delete_file($file["file"]);
		
	}
}

function may_create_artist(){
	if( isset($_REQUEST['wap_artist_signup']) )
	if(wp_verify_nonce( $_REQUEST['wap_artist_signup'], 'do_wap_artist_signup')){
		if ( ! username_exists( $user_login ) ) {
		
			$user_login = $_REQUEST['wap_artist_username'];
			$user_email = $_REQUEST['wap_artist_email'];
			$user_pass  = $_REQUEST['wap_artist_password'];

			$user_id= wp_create_user(  $user_login,  $user_pass,  $user_email );
			
			if ( !is_wp_error( $user_id ) ) {
				$user = get_user_by('id',$user_id);
				$user->set_role('wap_artist');
			}
			
			
		}

		
	}
}
function render_artist_account( $atts, $content ){
	ob_start();
	require_once(WAP_PLUGIN_DIR."artist_screens/artist_dashboard.php");
	return ob_get_clean();
	
}

function render_artist_upload_file( $atts, $content ){
	ob_start();
	require_once(WAP_PLUGIN_DIR."artist_screens/artist_file_upload.php");
	return ob_get_clean();
	
}
function render_artist_sales_statistics( $atts, $content ){
	ob_start();
	require_once(WAP_PLUGIN_DIR."artist_screens/artist_sales_statistics.php");
	return ob_get_clean();
	
}
function render_artist_account_information( $atts, $content ){
	ob_start();
	require_once(WAP_PLUGIN_DIR."artist_screens/artist_account_information.php");
	return ob_get_clean();
	
}
?>