<?php
add_action( 'user_edit_form_tag' ,'wklw_add_enctype_to_user_edit_form');
add_action( 'profile_update', 'wklw_update_worker_meta' );
add_action( 'admin_notices', 'wklw_show_profile_update_errors' );
function wklw_show_profile_update_errors(){
	
			
	if(isset($_SESSION['wklw_id_no_msg'])){	
			
  ?>
		<div class="notice notice-success is-dismissible">
			<p><?=$_SESSION['wklw_id_no_msg']?></p>
		</div>
    <?php
	}
	if(isset($_SESSION['wklw_phone_no_msg'])){
		?>
		<div class="notice notice-success is-dismissible">
			<p><?=$_SESSION['wklw_phone_no_msg']?></p>
		</div>
		<?php
	}
	if(isset($_SESSION['wklw_routing_no_msg'])){
		?>
		<div class="notice notice-success is-dismissible">
			<p><?=$_SESSION['wklw_routing_no_msg']?></p>
		</div>
		<?php
	}
	
	
}
function wklw_add_enctype_to_user_edit_form(){
	echo ' enctype="multipart/form-data" ' ; 
}
function wklw_update_routing_no($user_id){
	if( isset($_REQUEST["wklw_routing_no"]) ){
			
			$existing_user_id = wklw_routing_no_exist($_REQUEST["wklw_routing_no"] ,$user_id);
			if( $existing_user_id ){
				
				$existing_user_link = get_edit_user_link($existing_user_id );
				$_SESSION['wklw_routing_no_msg'] = "Routing No :".$_REQUEST["wklw_routing_no"]." used by <a href= '$existing_user_link' target = '_blank' >Another User</a>";
				
			}else{
				update_user_meta($user_id,"wklw_routing_no",$_REQUEST["wklw_routing_no"]);
			}		
			
			
			
		}
}

function wklw_update_id_no($user_id){
	if( isset($_REQUEST["wklw_id_no"]) ){
			$existing_user_id = wklw_id_no_exist($_REQUEST["wklw_id_no"] ,$user_id);
			if( $existing_user_id ){
				// $_REQUEST["wklw_id_no_msg"]= "Id :".$_REQUEST["wklw_id_no"]." used by Another User";
				$existing_user_link = get_edit_user_link($existing_user_id );
				$_SESSION['wklw_id_no_msg'] = "Id :".$_REQUEST["wklw_id_no"]." used by <a href= '$existing_user_link' target = '_blank' >Another User</a>";
				
			}else{
				update_user_meta($user_id,"wklw_id_no",$_REQUEST["wklw_id_no"]);
			}
			
		}
	

}

function wklw_update_phone_no($user_id){
	if( isset($_REQUEST["wklw_phone_no"]) ){
			$existing_user_id = wklw_phone_no_exist($_REQUEST["wklw_phone_no"] ,$user_id);
			if( $existing_user_id ){
				$existing_user_link = get_edit_user_link($existing_user_id );
				$_SESSION['wklw_phone_no_msg'] = "Phone No :".$_REQUEST["wklw_phone_no"]." used by <a href= '$existing_user_link' target = '_blank' >Another User</a>";
			}else{
				update_user_meta($user_id,"wklw_phone_no",$_REQUEST["wklw_phone_no"]);
			}
			
		}
	
}

function wklw_update_allowed_in_listing($user_id){
	// wlkw_insert_into_not_allowed($user_id);
	// wlkw_delete_from_not_allowed($user_id);
	if(isset($_REQUEST['wlkw_allow_listing']) && $_REQUEST['wlkw_allow_listing'] ){
		if( ! wlkw_user_id_in_not_allowed($user_id) )
			wlkw_insert_into_not_allowed($user_id);	
	}else {
		wlkw_delete_from_not_allowed($user_id);
	}
	
	
}
function wklw_update_callrate($user_id){
	// wlkw_insert_into_not_allowed($user_id);
	// wlkw_delete_from_not_allowed($user_id);
	if(isset($_REQUEST['wklw_call_rate']) && $_REQUEST['wklw_call_rate'] ){
		update_user_meta($user_id,'wklw_call_rate',$_REQUEST['wklw_call_rate']);
		// if( get_user_meta($user_id,'wklw_call_rate') )
			// update_user_meta($user_id,'wklw_call_rate',$_REQUEST['wklw_call_rate']);
		// else
			// add_user_meta($user_id,'wklw_call_rate',$_REQUEST['wklw_call_rate']);
		// var_dump("working");exit();
	}else{
		
	}
	
	
}	
function wklw_update_avatar($user_id){
	// wlkw_insert_into_not_allowed($user_id);
	// wlkw_delete_from_not_allowed($user_id);
	if( file_exists($_FILES['wklw_avatar']['tmp_name']) && is_uploaded_file($_FILES['wklw_avatar']['tmp_name']) ){
		$wklw_avatar = wklw_handle_id_scan_by_param('wklw_avatar');
		update_user_meta($user_id,"wklw_avatar",
							array(
								"path"	=> $wklw_avatar["file"],
								"url"	=> $wklw_avatar["url"],
								"name"	=> wp_basename($wklw_avatar["file"])
							)								
						);
	}else{
		
	}
}
function wklw_update_scan1($user_id){
	if(file_exists($_FILES['wklw_id_scan1']['tmp_name']) && is_uploaded_file($_FILES['wklw_id_scan1']['tmp_name'])){
			$scan = wklw_handle_id_scan_by_param('wklw_id_scan1');
			update_user_meta($user_id,"wklw_id_scan1",
								array(
									"path"	=> $scan["file"],
									"url"	=> $scan["url"],
									"name"	=> wp_basename($scan["file"])
								)								
							);
		}	
	
}	
function wklw_update_scan2($user_id){
	if(file_exists($_FILES['wklw_id_scan2']['tmp_name']) && is_uploaded_file($_FILES['wklw_id_scan2']['tmp_name'])) {
			$scan = wklw_handle_id_scan_by_param('wklw_id_scan2');
			update_user_meta($user_id,"wklw_id_scan2",
								array(
									"path"	=> $scan["file"],
									"url"	=> $scan["url"],
									"name"	=> wp_basename($scan["file"])
								)								
							);
		}
	
}	

function wklw_update_worker_meta($user_id){
	$user = get_user_by('ID',$user_id);
	if(wklw_is_worker($user)){
	
		wklw_update_avatar($user_id);
		wklw_update_scan1($user_id);
		wklw_update_scan2($user_id);
		
		wklw_update_routing_no($user_id);
		wklw_update_id_no($user_id);
		wklw_update_phone_no($user_id)	;
		wklw_update_allowed_in_listing($user_id)	;
		
		wklw_update_callrate($user_id);
		if( isset($_REQUEST["wklw_address2"]) ){
			update_user_meta($user_id,"wklw_address2",$_REQUEST["wklw_address2"]);
			
		}
		if( isset($_REQUEST["wklw_address1"]) ){
			update_user_meta($user_id,"wklw_address1",$_REQUEST["wklw_address1"]);
			
		}
		if( isset($_REQUEST["wklw_surname"]) ){
			update_user_meta($user_id,"wklw_surname",$_REQUEST["wklw_surname"]);
			
		}
		if( isset($_REQUEST["wklw_first_name"]) ){
			update_user_meta($user_id,"wklw_first_name",$_REQUEST["wklw_first_name"]);
			
		}
		
		
		
		
		
		
	}
}
function wklw_show_wklw_id_scan2($user_id){
	$wklw_id_scan2 = get_user_meta($user_id,"wklw_id_scan2",true);
	?>
		<tr>
			<th><label for="wklw_id_scan2">ID scan back</label></th>
			<td><a href="<?=$wklw_id_scan2["url"]?>" target="_blank"> <img width = "332" height= "180"  src="<?=$wklw_id_scan2["url"]?>"/></a><p class="description"></p></td>
		</tr>	
		<tr>
			<th><label for="wklw_id_scan2">New Scan Back</label></th>
			<td><input type="file" id="wklw_id_scan2" name="wklw_id_scan2" required /> <p class="description"></p></td>
		</tr>		

	<?php	
	
}

function wklw_show_wklw_id_scan1($user_id){
	$wklw_id_scan1 = get_user_meta($user_id,"wklw_id_scan1",true);
	?>
		<tr>
			<th><label for="wklw_id_scan1">ID scan front</label></th>
			<td><a href="<?=$wklw_id_scan1["url"]?>" target="_blank"> <img width = "332" height= "180"  src="<?=$wklw_id_scan1["url"]?>"/></a><p class="description"></p></td>
		</tr>
		<tr>
			<th><label for="wklw_id_scan1">New Scan Front</label></th>
			<td><input type="file" id="wklw_id_scan1" name="wklw_id_scan1" required  /> <p class="description"></p></td>
		</tr>
	<?php	
	
}

function wklw_show_routing_no($user_id){
	$wklw_routing_no = get_user_meta($user_id,"wklw_routing_no",true);
	
	$msg = isset($_SESSION['wklw_routing_no_msg'])?$_SESSION['wklw_routing_no_msg']:"";
	unset($_SESSION['wklw_routing_no_msg']);
	?>
		<tr>
			<th><label for="wklw_routing_no"><?=__('Service Number','wklw')?></label></th>
			<td><input type="text" id="wklw_routing_no" name="wklw_routing_no" value ="<?=$wklw_routing_no?>" class="regular-text"><p class="description"><?=$msg?></p></td>
		</tr>
	<?php	
	
}

function wklw_show_phone_no($user_id){
	
	$wklw_phone_no = get_user_meta($user_id,"wklw_phone_no",true);
	$msg = isset($_SESSION['wklw_phone_no_msg'])?$_SESSION['wklw_phone_no_msg']:"";
	unset($_SESSION['wklw_phone_no_msg']);
	?>
		<tr>
			<th><label for="wklw_phone_no"><?=__('Private Number','wklw')?></label></th>
			<td><input type="text" id="wklw_phone_no" name="wklw_phone_no" value ="<?=$wklw_phone_no?>" class="regular-text"><p class="description"><?=$msg?></p></td>
		</tr>
	<?php	
	
}

function wklw_show_id_no($user_id){
	$wklw_id_no = get_user_meta($user_id,"wklw_id_no",true);
	
	$msg = isset($_SESSION['wklw_id_no_msg'])?$_SESSION['wklw_id_no_msg']:"";
	unset($_SESSION['wklw_id_no_msg']);
	?>
		<tr>
			<th><label for="wklw_id_no">Id No</label></th>
			<td><input type="text" id="wklw_id_no" name="wklw_id_no" value ="<?=$wklw_id_no?>" class="regular-text"><p class="description"><?=$msg?></p></td>
		</tr> 
		
	<?php	
	
}

function wklw_show_address2($user_id){
	$wklw_address2 = get_user_meta($user_id,"wklw_address2",true);
	?>
		<tr>
			<th><label for="wklw_address2">Address 2</label></th>
			<td><input type="text" id="wklw_address2" name="wklw_address2" value ="<?=$wklw_address2?>" class="regular-text"><p class="description"></p></td>
		</tr>
	<?php	
	
}

function wklw_show_address1($user_id){
	$wklw_address1 = get_user_meta($user_id,"wklw_address1",true);
	?>
		<tr>
			<th><label for="wklw_address1">Address 1</label></th>
			<td><input type="text" id="wklw_address1" name="wklw_address1" value ="<?=$wklw_address1?>" class="regular-text"><p class="description"></p></td>
		</tr>
	<?php	
	
}

function wklw_show_surname($user_id){
	$wklw_surname = get_user_meta($user_id,"wklw_surname",true);
	?>
		<tr>
			<th><label for="wklw_surname">Surname</label></th>
			<td><input type="text" id="wklw_surname" name="wklw_surname" value ="<?=$wklw_surname?>" class="regular-text"><p class="description"></p></td>
		</tr>
	<?php	
	
}

function wklw_show_first_name($user_id){
	$wklw_first_name = get_user_meta($user_id,"wklw_first_name",true);
	
	?>
		<tr>
			<th><label for="wklw_first_name">First Name</label></th>
			<td><input type="text" id="wklw_first_name" name="wklw_first_name" value ="<?=$wklw_first_name?>" class="regular-text"><p class="description"></p></td>
		</tr>
	<?php	
	
}

function wklw_show_do_not_list($user_id){
	$checked = wlkw_user_id_in_not_allowed($user_id)?" CHECKED ":"";
	?>
		<tr>
			<th><label for="wlkw_allow_listing">Don't List in Frontend</label></th>
			<td><input <?=$checked?> type="checkbox" id="wlkw_allow_listing" name="wlkw_allow_listing"  class="regular-text"><p class="description"></p></td>
		</tr>
	<?php	
	
}
function wklw_show_call_rate($user_id){
	$wklw_call_rate = get_user_meta($user_id,'wklw_call_rate',true)
	?>
			<tr>
			<th><label for="wklw_call_rate">Callrate</label></th>
			<td><input type="text" id="wklw_call_rate" name="wklw_call_rate" value ="<?=$wklw_call_rate?>" class="regular-text">Eur/Min<p class="description"></p></td>
		</tr>
	<?php	
	
}
// wklw_routing_no
function wklw_show_user_meta_on_edit_profile($profileuser){
	
	if(wklw_is_worker($profileuser)){
				
		
				
		?>
		<table class="form-table" >
		<tbody>
			<?php
			
				wklw_show_do_not_list($profileuser->ID);
				wklw_show_call_rate($profileuser->ID);
				
				wklw_show_phone_no($profileuser->ID);
				wklw_show_routing_no($profileuser->ID);
				wklw_show_id_no($profileuser->ID);
				wklw_show_first_name($profileuser->ID);
				wklw_show_surname($profileuser->ID);
				wklw_show_address1($profileuser->ID);
				wklw_show_address2($profileuser->ID);
				
				
				wklw_show_wklw_id_scan1($profileuser->ID);
				wklw_show_wklw_id_scan2($profileuser->ID);
				
			?>
		</tbody>
		</table>
	<?php		
	}
}
?>