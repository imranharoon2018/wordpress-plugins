<?php
add_action( 'woocommerce_product_addons_panel_before_options', function($post = null, $addon = null , $loop = null ){
	
	$checked =  isset($addon["enforce_minimum"]) && $addon["enforce_minimum"] ? " CHECKED " : "";
	$num_minimum = $addon["num_minimum"] ;
	$addon_message =get_option("woo_addon_limiter_default_text");
	if(isset($addon["addon_message"] )&& $addon["addon_message"] != "")  
		 $addon_message = $addon["addon_message"] ;
	if(!$checked)
		 $addon_message = "";
	$readonly = $checked  && $num_minimum ? "" : " readonly  ";
	$style =  $addon["type"] == "checkbox" ? "" :"display:none;";
	 	
	// if( !($addon["type"] != "checkbox" && $post!=null ))
	// $style = 	"'display:none'";;
	
	?>
		
		<div class="wc-pao-addons-secondary-settings-enforce-checkbox" id="wc-pao-addons-secondary-settings-enforce-checkbox-<?=$loop?>"   style="<?=$style?>">
		
			<table>
				<tr>
					<td ><label style="margin-left:13px;padding-top:5px;" for="pao_force_cb_<?=$loop?>">
						<input <?=$checked ?> type="checkbox" id="pao_force_cb_<?=$loop?>" name="pao_force_cb_<?=$loop?>"  />
						Enforce Minimum Number of checkbox
						
						</label>
					</td>
					<td>
						<table>
							<tr>
								<td>	Minimum Number of checkbox:
								</td>
								<td><input  type="text" id="pao_num_cb_<?=$loop?>" name="pao_num_cb_<?=$loop?>" value="<?=$num_minimum ?>" type="number"    <?=$readonly?> />
								</td>							
							</tr>						
						</table>
					</td>				
				</tr>
			</table>
			<table style="width:100%;" >
			<tr ><td>Addon Message<input   type="text" id="pao_addon_message_<?=$loop?>" name="pao_addon_message_<?=$loop?>" value="<?=$addon_message ?>" type="number"    /></td></tr>
			</table>
		
		</div>
		<script>
		(function($) {
				var default_msg= "<?=get_option( 'woo_addon_limiter_default_text')?>";
			$( document ).ready(function() {
				
				$('#wc-pao-addon-content-type-<?=$loop?>').change(function(){
					
					var checkbox_selected = $(this).val()=="checkbox";
						
					
						if( !checkbox_selected ){
							
							
							$('#wc-pao-addons-secondary-settings-enforce-checkbox-<?=$loop?>').css("display", "none");
							
						}else{
							
							
							$('#wc-pao-addons-secondary-settings-enforce-checkbox-<?=$loop?>').css("display", "block");
						}
					
				});
				$('#pao_force_cb_<?=$loop?>').click(function() {
					if(this.checked) {
						$("#pao_addon_message_<?=$loop?>").val(default_msg);
						$('#pao_num_cb_<?=$loop?>').removeAttr('readonly');
						console.log("disabled false");
					}
					else{
						$("#pao_addon_message_<?=$loop?>").val("");
						$('#pao_num_cb_<?=$loop?>').attr('readonly','readonly');
						console.log("disabled true");
					}
						
					
				});
			});
		})( jQuery);;
		</script>
	<?php
	
},100,3);

