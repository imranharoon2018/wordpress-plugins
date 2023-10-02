<?php
/**
 * Order_Returned_Admin_Ajax class.
 */



class WC_Order_Returned_Admin_Ajax {
	public function __construct() {
		$this->init();
	}
	
	public function init(){
		add_action( 'admin_enqueue_scripts',array( $this, 'enqueue_admin_js'));
	
		
		add_action( 'wp_ajax_mark_order_sku_as_used',array( $this,'mark_order_sku_as_used'));
		add_action( 'wp_ajax_mark_order_sku_as_recycled',array( $this,'mark_order_sku_as_recycled'));
		add_action( 'wp_ajax_mark_sku_as_not_available',array( $this,'mark_sku_as_not_available_in_store'));
	}
	
	public function enqueue_admin_js(){
		global $current_screen ;
		
		if( $current_screen->id == "shop_order" ){			
			
			wp_enqueue_script( 'ajax-script', WOR_PLUGIN_DIR_URL .'includes/admin/assets/js/script.js', array('jquery') );			
			wp_localize_script( 'ajax-script', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		}
	}

	public function mark_sku_as_not_available_in_store() {
			// order_id:order_id,
			// sku_meta_id:sku_meta_id,
			// action:action ,
		$ret = array(
					"success" =>false
				);
		$order_id = intval( $_REQUEST['order_id'] );
		$sku_meta_id = intval( $_REQUEST['sku_meta_id'] );
		
		if(sku_order_exists($order_id,$sku_meta_id)){
			
			$ret["success"] = mark_sku_order_as_not_available($order_id,$sku_meta_id);
			
			$x = false;
		}
		echo wp_json_encode($ret);
		wp_die();
	}
	public function mark_order_sku_as_used() {
			// order_id:order_id,
			// sku_meta_id:sku_meta_id,
			// action:action ,
		$ret = array(
					"success" =>false
				);
		$order_id = intval( $_REQUEST['order_id'] );
		$sku_meta_id = intval( $_REQUEST['sku_meta_id'] );
		
		if(sku_order_exists($order_id,$sku_meta_id)){
			
			$ret["success"] = mark_sku_order_as_used($order_id,$sku_meta_id);
			
			$x = false;
		}
		echo wp_json_encode($ret);
		wp_die();
	}
	public function mark_order_sku_as_recycled() {
		$order_id = intval( $_REQUEST['order_id'] );
		$sku_meta_id = intval( $_REQUEST['sku_meta_id'] );
		$ret = array(
					"success" =>false
				);
		if(sku_order_exists($order_id,$sku_meta_id)){
			$ret["success"] = mark_sku_order_as_recycled($order_id,$sku_meta_id);
			
			
		}
		echo wp_json_encode($ret);
		wp_die();
	}
	
	
}
	
?>