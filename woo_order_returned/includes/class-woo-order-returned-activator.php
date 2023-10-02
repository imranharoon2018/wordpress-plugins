<?php


class Woo_Order_Returned_Activator {

	/**
	 * This function creates two table on plugin activation
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$wor_sku_meta  = $wpdb->prefix."wor_sku_meta";
		$sql =  "CREATE TABLE IF NOT EXISTS  $wor_sku_meta(
					id INTEGER NOT NULL  AUTO_INCREMENT,
					sku  VARCHAR(100),
					filtered_product_attributes longtext,	
					PRIMARY KEY  (id)					
				)";
		$wpdb->query($sql);
		
		$wor_sku_order  = $wpdb->prefix."wor_sku_order";
		$sql =  "CREATE TABLE IF NOT EXISTS  $wor_sku_order(
					id INTEGER NOT NULL  AUTO_INCREMENT,
					sku_meta_id INTEGER,
					sku  VARCHAR(100),
					order_id  INTEGER,					
					is_used INTEGER DEFAULT 0,
					is_recycled INTEGER DEFAULT 0,
					is_avaible_in_store INTEGER DEFAULT 1,
					PRIMARY KEY  (id)
				)";
		$wpdb->query($sql);
		
		
		
		$returned_order  = $wpdb->prefix."wor_returned_order";
		$sql =  "CREATE TABLE IF NOT EXISTS  $returned_order(
					id INTEGER NOT NULL  AUTO_INCREMENT,
					order_id INTEGER,
					PRIMARY KEY  (id)
				)";	
	
		$wpdb->query($sql);
		// $woo_product_attributes_in_line_item = array("pa_brand","pa_orientation","pa_color","pa_size");
		$woo_product_attributes_in_line_item = array("size","frame","format");
		update_option("woo_product_attributes_in_line_item",$woo_product_attributes_in_line_item);
	}

}
