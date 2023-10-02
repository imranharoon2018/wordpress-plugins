<?php
Class WC_Order_Returned{
	
	public function __construct() {
		$this->version = '1.0.0';
		$this->plugin_name = 'woo_order_returned';
		$this->init();
	}
	
	public function init(){

		require_once WOR_PLUGIN_DIR .'includes/admin/class-wc-order-returned-admin.php';
		
		$this->order_returned_admin = new WC_Order_Returned_Admin();
		
	}
}
?>