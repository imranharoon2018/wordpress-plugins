<?php


add_action( "saved_".WAP_TERM, function($term_id, $tt_id){
// add_action( "saved_pa_brand", function($term_id, $tt_id){
	
	
	
	$wap_artist_id = $_REQUEST[WAP_ARTIST_ID];
	wap_save_artist_id($term_id,$wap_artist_id);
	

},10,2);
add_action(WAP_TERM."_edit_form_fields",function( $tag, $taxonomy){
// add_action("pa_brand_edit_form_fields",function( $tag, $taxonomy){
	$term_id = $tag->term_id;
	$x = array(
		'role'			=> 'wap_artist'
	);
	?>
	<tr class="form-field term-display-type-wrap">
			<th scope="row" valign="top"><label>Artist</label></th>
			<td>
	<select id="<?=WAP_ARTIST_ID?>" name="<?=WAP_ARTIST_ID?>"> 
	<option value="">Select Artist</option>
	<?php
	$all_artists = get_users($x);
	$wap_artist_id= wap_get_saved_artist_id_by_term_id($term_id);
	if( is_array($all_artists) && count($all_artists) )
	foreach($all_artists as $an_artist){
		$selecteted = "";
		if($wap_artist_id == $an_artist->ID)
			$selecteted = " SELECTED ";
	?>
		<option <?=$selecteted?> value = "<?=$an_artist->ID?>"	><?=$an_artist->user_login?>(<?=$an_artist->user_email?>)</option>
	<?php
	}
	?>
	
	</select>	<?=$wap_artist_id ?> </td>
	</tr>
	<?php
},10,2);

?>