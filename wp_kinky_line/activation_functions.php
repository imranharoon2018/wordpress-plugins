<?php
function wklw_create_default_options(){
	update_option("wklw_default_callrate","2.99");
	
}
function wklw_add_kinky_line_worker_role(){
	add_role( KINKY_LINE_WORKER, 'Kinky Line Worker', [
			'read' => true
		]
	);
}

function wklw_get_page_by_title( $page_title ) {
    global $wpdb;
 
    if ( is_array( $post_type ) ) {
        $post_type           = esc_sql( $post_type );
        $post_type_in_string = "'" . implode( "','", $post_type ) . "'";
        $sql                 = $wpdb->prepare(
            "
            SELECT ID
            FROM $wpdb->posts
            WHERE post_title = %s 
			AND
			post_status='publish'
			AND
			post_type='page' 
			Limit 0,1
        ",
            $page_title
        );
    } else {
        $sql = $wpdb->prepare(
            "
            SELECT ID
            FROM $wpdb->posts
            WHERE post_title = %s 
			AND
			post_status='publish'
			AND
			post_type='page' 
			Limit 0,1
        ",
            $page_title
        );
    }
 
    $page = $wpdb->get_var( $sql );
 
    if ( $page ) {
        return get_post( $page, $output );
    }
}
function wklw_create_artist_pages(){
	$option_name_for_page_id = array(
		'Sign Up' => "wklw_signup_page_id",
		'Dashboard' => "wklw_dashboard_page_id",
		'Listing' => "wklw_listing_page_id",
		'Thank You' => "wklw_thankyou_page_id"
		
	);
	
	$page_datas = array(
		array(
		  'post_title'    => 'Sign Up',
		  'post_content'  => '<!-- wap:shortcode -->[wklw_sign_up_form]<!-- /wap:shortcode -->',
		  'post_status'   => 'publish',
		  'post_author'   => get_current_user_id(),
		  'post_type'   => 'page'
		),array(
		  'post_title'    => 'Dashboard',
		  'post_content'  => '<!-- wap:shortcode -->[wklw_dashboard]<!-- /wap:shortcode -->',
		  'post_status'   => 'publish',
		  'post_author'   => get_current_user_id(),
		  'post_type'   => 'page'
		),array(
		  'post_title'    => 'Listing',
		  'post_content'  => '<!-- wap:shortcode -->[wklw_listing]<!-- /wap:shortcode -->',
		  'post_status'   => 'publish',
		  'post_author'   => get_current_user_id(),
		  'post_type'   => 'page'
		),array(
		  'post_title'    => 'Thank You',
		  'post_content'  => '<!-- wap:shortcode -->[wklw_thankyou_for_signingup]<!-- /wap:shortcode -->',
		  'post_status'   => 'publish',
		  'post_author'   => get_current_user_id(),
		  'post_type'   => 'page'
		)
	);
	foreach($page_datas as $a_page){
		$post_id =  wklw_get_page_by_title( $a_page['post_title']);
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
		}else{
			if(is_object($post_id))
				if(property_exists($post_id,'ID'))
					$post_id = $post_id->ID;
			update_option($option_name_for_page_id[$a_page['post_title']],$post_id);
		}	
		
	}
	
	
	
	
}
function wklw_create_tables(){
	global $wpdb;
	$wklw_not_allowed = $wpdb->prefix."wklw_not_allowed";
	$sql = "
	CREATE TABLE IF NOT EXISTS  $wklw_not_allowed  (
	  id int(11) NOT NULL AUTO_INCREMENT,
	  user_id int(11),
	  PRIMARY KEY (id) 
	)
	";
	$wpdb->query($sql);
	

	
	$wklw_online = $wpdb->prefix."wklw_online";
	$sql = "
	CREATE TABLE IF NOT EXISTS  $wklw_online  (
	  id int(11) NOT NULL AUTO_INCREMENT,
	  user_id int(11),	  
	  online_start bigint(20) ,
	  last_heart_beat bigint(20),  	  
	  online_status integer,
	  PRIMARY KEY (id) 
	)
	";
	
	$wpdb->query($sql);
	
	$wklw_worker_ids = $wpdb->prefix."wklw_worker_ids";
	$sql = "
	CREATE TABLE IF NOT EXISTS  $wklw_worker_ids  (
	  id int(11) NOT NULL AUTO_INCREMENT,
	  user_id int(11),	  
	   PRIMARY KEY (id) 
	)
	";
	
	$wpdb->query($sql);
	
}
?>