<?php
add_action("admin_menu",function(){
		
		add_submenu_page( "woo-artist-plugin", "Woo Artist Plugin Front End", "Front End Setting", 'manage_options', "woo-artist-plugin-front-end-setting", "show_woo_artist_plugin_front_end_settings" );
	
},PHP_INT_MAX);

add_action("init",function(){
	// echo "hi";
	may_remove_taxonomy();
	may_save_taxonomy();
});
function may_remove_taxonomy(){
	if(isset($_REQUEST["remove_wap_taxonomy_for_tags"])){
		
		if(wp_verify_nonce($_REQUEST["remove_wap_taxonomy_for_tags"],"do_remove_wap_taxonomy_for_tags")){
			
			$val = $_REQUEST["taxonomy_to_remove"];
			$prevs = get_option("wap_selected_taxonomy_for_tags")?get_option("wap_selected_taxonomy_for_tags"):[];
			$news = array();
			foreach($prevs as $taxonomy){
				if( $val!=$taxonomy )
					$news[]=$taxonomy;
			}
			
			update_option("wap_selected_taxonomy_for_tags",$news);			 
		}
	}
}function may_save_taxonomy(){
	if(isset($_REQUEST["save_wap_taxonomy_for_tags"]))
		if(wp_verify_nonce($_REQUEST["save_wap_taxonomy_for_tags"],"do_save_wap_taxonomy_for_tags")){
			
			$val = $_REQUEST["wap_select_taxonomy_for_tag"];
			$prevs = get_option("wap_selected_taxonomy_for_tags")?get_option("wap_selected_taxonomy_for_tags"):[];
			if(!in_array($val,$prevs))
				$prevs[] = $val;
			update_option("wap_selected_taxonomy_for_tags",$prevs);			 
		}
}
function wap_get_taxonomy_to_display(){
	global $wpdb;
	$term_taxonomy = $wpdb->prefix."term_taxonomy";
	$sql = "select distinct(taxonomy)as name from $term_taxonomy";
	return $wpdb->get_results($sql);
}	
function show_woo_artist_plugin_front_end_settings(){
	$all_tax =wap_get_taxonomy_to_display();
	$selected_taxonomies = is_array(get_option("wap_selected_taxonomy_for_tags"))?get_option("wap_selected_taxonomy_for_tags"):[];
	?>
	<div class="wrap">
		<p>
			<h3>Selected Taxonomy:</h3>
			<table>
				<?php 
					foreach($selected_taxonomies as $taxonomy){
				?>
					<tr>
						
						<th><?=$taxonomy?></th>
						<td><form>
							<?php wp_nonce_field( "do_remove_wap_taxonomy_for_tags", "remove_wap_taxonomy_for_tags");?>
							<input type="submit" value="Remove" class="button-primary" />
							<input type="hidden" value="<?=$_REQUEST["page"]?>" id="page" name="page" />
							<input type="hidden" id="taxonomy_to_remove"  name="taxonomy_to_remove" value="<?=$taxonomy?>" />
							
						</form></td>
					</tr>
				<?php
					}
				?>
			</table>
		</p>
	
		<form>
		<input type="hidden" value="<?=$_REQUEST["page"]?>" id="page" name="page" />
		<?php wp_nonce_field( "do_save_wap_taxonomy_for_tags", "save_wap_taxonomy_for_tags");?>
		
		<p>
			Taxonomy :
			<select id="wap_select_taxonomy_for_tag" name="wap_select_taxonomy_for_tag">
				<option value="">Select</option>
				<?php 
					foreach($all_tax as $taxonomy){
						?>
						<option value="<?=$taxonomy->name?>"><?=$taxonomy->name?></option>
						<?php
					}
					
				?>
			</select>
		</p>
		<p>	
			<input type="submit" value="Save" class="button-primary" />
		</p>
		</form>
	</div>
	<?php
}		

?>