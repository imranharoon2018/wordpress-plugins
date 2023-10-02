<?php 
$user = wp_get_current_user();
$user_id = null;
if( is_object($user) && property_exists($user,'ID') )
	$user_id  = $user->ID;
$online_status =get_online_status_by_user_id($user_id );
$online_msg = "Offline";
if($online_status==1){
	$online_msg = " Online";
}

$display_name = is_object($user) &&!empty($user->display_name)?$user->display_name:"";

$routing_no = get_user_meta($user_id,"wklw_routing_no",true);
$routing_no = get_user_meta($user_id,"wklw_routing_no",true);
$phone_no = get_user_meta($user_id,"wklw_phone_no",true);
$call_rate = get_user_meta($user_id,'wklw_call_rate',true);
$avatar_url = get_avatar_url( $user_id, array( 'size' => 128 ) );
?>
<div class="wklw_container">
<!-- <h1><?=$online_msg?></h1> -->
	<div class="wklw_row">
		<div class="col-100 wklw_dashboard_header">
			<h3>User Dashboard</h3>
		</div>
	</div>

	<div class="wklw_row">
		<div class="col-100 buttoncontainer">
			<input type="hidden" name= "wklw_is_online"  id= "wklw_is_online" value="<?=$online_status ?>" />
			 <form method = "post" >
			 <input type="submit" value="Offline" id="wklw_btn_offline" class="btn_offline"/>
			 
			 <?php wp_nonce_field( 'do_wklw_go_offline','wklw_go_offline' );?> 
			 </form>
			  <form method = "post">
			 <input type="submit" value="Online" id="wklw_btn_online" class="btn_online"/>
			 <?php wp_nonce_field( 'do_wklw_go_online','wklw_go_online' );?> 
			 </form>	
		</div>
	</div>

 
 <h2><?=__('Details','wklw')?></h2>
 
<?=__('Online Status','wklw')?>: <?=$online_msg ?><br/>
<?=__('Display Name','wklw')?>: <?=$display_name?><br/>
<?=__('Private Number','wklw')?>: <?=$phone_no?> <?=__('Please contact us if you want to change Private Number','wklw')?>.<br/>
<?=__('Service Number','wklw')?>: <?=$routing_no?>.<br/>
<?=__('Call rate','wklw')?>: <?=$call_rate?>Eur/Min.<br/>
<?=__('Avatar','wklw')?>:<br/>
 <img src = "<?=$avatar_url?>" class="wklw_avatar" />
 <form method="post" enctype="multipart/form-data" >
 <?php wp_nonce_field( 'do_wklw_user_avtar_change','wklw_user_avtar_change' );?> 
 <p>
 <input type="file" id="wklw_avatar" name="wklw_avatar"/> <span id="wklw_avatar_msg" style="color:red"><?=isset($_REQUEST["wklw_avatar_msg"])?$_REQUEST["wklw_avatar_msg"]:"" ?></span>
 </p>
 <p>
 <input type="submit" value="<?=__('Save','wklw')?>"/>
 </p>
 </form>
	 
</div>