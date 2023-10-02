<?php
/*
* 5. Product Meta auf Shop Seite
*/		
if($k_c_a_settings["enable_product_meta_on_shop_page"] && is_plugin_active_byme("woocommerce/woocommerce.php") ){

	add_filter( 'woocommerce_product_tabs', function($product_tabs ) {
		
		add_filter( 'the_content', 'woo_add_single_product_meta_add_size_length_to_description' );
		return $product_tabs;
	});
	add_action( 'woocommerce_process_product_file_download_paths', function($parent_id, $product_id=null, $downloads=null) {
		update_option("batman_parent_id",$parent_id);
		update_option("batman_product_id",$product_id);
		update_option("batman_downloads",$downloads);
		
	},3);
	function woo_add_single_product_meta_add_size_length_to_description($content){
		global $product;
		$downloads = $product->get_downloads();
		
		$count=0;
		$output .= "";
		$output .= "
			<style>
				ul#info_container {
					
					
				}
				ul#info_container li{
					
					list-style-type:none;
				}
				span.info_label{
					font-weight:bold;
					
				}
				p.file_info{
					margin:0px;
					line-height: normal;
				}
			</style>
		";

		
		
		foreach($downloads as $id=>$a_download){
			
			$filesize=false;
			$duration=false;
			
			$url = $a_download->get_data()["file"];
			$name = $a_download->get_data()["name"];
			
			$attachment_id= attachment_url_to_postid($url);
			
			$meta = wp_get_attachment_metadata( $attachment_id);
			
			
			$output .= "<p class='file_info'>";
			$output .= "<span class='info_label'>File #".($count+1).":</span>";
			
			
			$output .= "<ul id='info_container'>";
			if ( isset( $meta['length_formatted'] ) ) {
				$output .= "<li><span class='info_label'>Dauer : </span>".$meta['length_formatted'] ."</li>";
				  ;
			}else{
					$output .= "<li><span class='info_label'>Dauer : </span></li>";
			}
			if ( isset( $meta['filesize'] ) ) {
				$output .= "<li><span class='info_label'>Dateigroesse : </span>". size_format( $meta['filesize'] )."</li>";
				
			}else{
					$output .= "<li><span class='info_label'>Dateigroesse : </span></li>";
			}
			
			$output .= "</ul>";
		
			$count++;
		}
		
		return $content.$output;
		
	}
	add_filter( 'woocommerce_product_after_tabs', function($product_tabs ) {
		
		remove_filter( 'the_content', 'woo_add_single_product_meta_add_size_length_to_description' );
		return $product_tabs;
		
	});
}//if($k_c_a_settings["enable_product_meta_on_shop_page"])
?>