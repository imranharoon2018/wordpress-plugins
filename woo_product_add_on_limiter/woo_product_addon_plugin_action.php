<?php 
add_action( 'wc_product_addon_options', function($addon ){
	
	$msg_id = "msg-addon-".$addon["field_name"];
	if( isset($addon["enforce_minimum"]) && $addon["enforce_minimum"] && $addon["num_minimum"] )
		$msg =  sprintf($addon["addon_message"], $addon["num_minimum"], count($addon["options"]));
	?>
		<p class="add_onn_message" id="<?=$msg_id?>" ><?=$msg?></p>
	<?php
});
add_filter( 'woocommerce_product_addons_save_data',function( $data=null, $i=null ){
	
	try{	
		$enforce_minimum =	isset($_REQUEST["pao_force_cb_".$i] )?1:0;// && 
		$num_check_box = trim(abs( $_REQUEST["pao_num_cb_".$i]));
		$addon_message = trim( $_REQUEST["pao_addon_message_".$i]);
		
		var_dump($num_check_box); 
		var_dump(count($data["options"])) ;
		if(count($data["options"]) < $num_check_box )//minimum is larger than available
			 $num_check_box=count($data["options"]);
		
		if($enforce_minimum){
		
			$data["enforce_minimum"] = $enforce_minimum;
			$data["num_minimum"] = $num_check_box ;
			$data["addon_message"] = $addon_message ;
		}
		
	}catch(Exception $ex){
		
	}
	return $data;
},10,2);

add_action('wc_product_addon_end',function($addon){
	 // var_dump($addon);
	global $addon_names_for_js ;
	global $addon_max_limit_for_js;
	if( isset($addon["enforce_minimum"]) && $addon["enforce_minimum"] && $addon["num_minimum"]){
		$addon_names_for_js[] = "addon-".$addon["field_name"];
		$addon_max_limit_for_js[] = $addon["num_minimum" ];
	}
});
?>