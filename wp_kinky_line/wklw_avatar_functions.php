<?php
// apply_filters( 'get_avatar_data', $args, $id_or_email );
add_filter( 'get_avatar_data', 'apply_avatar_url',PHP_INT_MAX,2);
add_filter( 'user_profile_picture_description', 'show_profile_picture_change_input',PHP_INT_MAX,2);
function show_profile_picture_change_input($description, $profileuser ){
	$user_id = false;
	if( is_object( $profileuser ) && property_exists($profileuser, 'ID'))
		$user_id = $profileuser->ID;
	if( is_array( $profileuser ) && isset($profileuser['ID']) )
		$user_id = $profileuser['ID'];
	if( wklw_is_worker(get_user_by('ID',$user_id)) ){
	?>
		<p><input type= "file" id="wklw_avatar" name="wklw_avatar" /></p>
	<?php
	}
	return $description;
}
// apply_filters( 'user_profile_picture_description', $description, $profileuser );
function get_user_id($id_or_email){
	if(is_numeric($id_or_email)){
		return $id_or_email;
	}elseif(is_string($id_or_email) && is_email($id_or_email)){
		$user = get_user_by('email',$id_or_email);	
		if(is_object($user) && !empty($user->ID))
			return $user->ID;
	}elseif ( is_object( $id_or_email ) && isset( $id_or_email->comment_ID ) ) {
		$comment = get_comment( $id_or_email );
		if(is_object($comment) && !empty($comment->user_id))
			return $user_id->ID;
	}elseif ( $id_or_email instanceof WP_User ) {
		// User object.
		if(!empty($id_or_email->ID))
			return  $id_or_email->ID;
	} elseif ( $id_or_email instanceof WP_Post ) {
		if( !empty($id_or_email->post_author) )
			return (int) $id_or_email->post_author;
	}
	return false;
		
}
function apply_avatar_url( $args, $id_or_email){
	$user_id = get_user_id($id_or_email);
	$user = get_user_by( 'ID', $user_id);
	if(wklw_is_worker($user) && !empty($args["url"])){
		$wklw_avatar = get_user_meta($user_id,'wklw_avatar',true);
		if( $wklw_avatar  && is_array($wklw_avatar) && $wklw_avatar["url"])
			$args["url"] = $wklw_avatar["url"];
	}
	
	return $args;
}

?>