<?php
/**
 * Order_Returned_Admin class.
 */
class WC_Order_Returned_Admin {
	public function __construct() {
		$this->init();
	}
	
	public function init(){
		add_action( 'admin_enqueue_scripts',array( $this, 'enqueue_style_scripts'));
		/** improtant discuss this ***/
		// add_filter( 'woocommerce_register_shop_order_post_statuses',array( $this, 'retgister_returned_status' ));
		// add_filter( 'wc_order_statuses', array( $this,'add_returned_status'));
		/** improtant discuss this ***/
		add_action( 'woocommerce_after_order_itemmeta',array( $this,'show_use_and_recycle_button'),10,3);
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 20 );
		add_action( 'woocommerce_after_register_post_type', array( $this, 'may_save_order' ), 20 );
		add_action( 'woocommerce_after_register_post_type', array( $this, 'may_reset_everything' ), 20 );
		add_action( 'woocommerce_after_register_post_type', array( $this, 'may_remove_order' ), 20 );
		add_action( 'woocommerce_after_register_post_type', array( $this, 'may_debug_returned_order' ), 20 );
		add_action( 'woocommerce_after_register_post_type', array( $this, 'may_save_product_attributes_in_line_item' ), 20 );
		// require_once 
		// require_once trailingslashit(plugin_dir_path( __FILE__ )) .'class-wc-order-returned-admin-ajax.php';
		require_once WOR_PLUGIN_DIR .'includes/admin/class-wc-order-returned-admin-ajax.php';
		$this->admin_ajax= new WC_Order_Returned_Admin_Ajax();
		
		
	}
	function enqueue_style_scripts(){
		global $current_screen ;
		
		if( $current_screen->id == "shop_order" ){
			wp_enqueue_style( 'wor-styles', plugins_url( 'assets/css/style.css', __FILE__ )   );
			
		
		}
	}
	function retgister_returned_status($order_statuses ){
		$order_statuses['wc-returned'] = array(
			'label'                     => _x( 'Returned Order', 'Order status', 'woocommerce' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			/* translators: %s: number of orders */
			'label_count'               => _n_noop( 'Returned Order <span class="count">(%s)</span>', 'Returned Order <span class="count">(%s)</span>', 'woocommerce' ),
		);

		return $order_statuses;	
	}
	function add_returned_status($order_statuses ){
		$order_statuses ['wc-returned'] = _x( 'Returned', 'Order status', 'woocommerce' );
		return $order_statuses;

	}

	
	function show_use_and_recycle_button( $item_id, $item, $product ){

		// var_dump($item);
		
		/*change june 14 starts*/
		$order = ($item!=null)?$item->get_order():null;		
		if( $order != null && $product != null ){
			$order_id = $order->get_id();
			$product_id = $product->get_id();
			$sku = wor_get_product_sku($product);
			$is_returned = order_is_returned($order_id);	
			
			$product_attributes_in_order_line_item=get_product_attributes_from_order_line_item_meta($item->get_meta_data());
			$sku_meta_id = sku_exists($sku,$product_attributes_in_order_line_item);	
			
			if(!$is_returned){
				
				$available_returned_orders =  get_available_returned_order($sku_meta_id);
				require WOR_PLUGIN_DIR .'includes/admin/views/line_item_retunerd_order_listing.php';
			}else if($is_returned){
				
				$returned_order_detail = null;		
				$returned_order_detail = get_returned_order_detail($order_id,$sku_meta_id);
				
				if($returned_order_detail){
					
					require WOR_PLUGIN_DIR .'includes/admin/views/line_item_is_available.php';
				}
			}
			
		}
		
	}
	
	public function may_save_order(){
		$order_id = save_returned_order_to_database();
		if($order_id ){
			insert_all_sku_by_order_id($order_id);
			
		}
		
	}
	public function admin_menu(){
		add_submenu_page("woocommerce","WoooCommerce Returned Order","Returned Order","manage_product_terms","woocommerce-return-order",array( $this,"show_return_order_page"));
		add_submenu_page("woocommerce","Returned Order Debug","Returned Order Debug","manage_product_terms","woocommerce-returned-order-debug",array( $this,"show_debug_page"));
	}
	public function show_return_order_page(){
		require_once WOR_PLUGIN_DIR .'includes/admin/views/returned_order_listing.php';
		
	}
	
	public function show_debug_page(){
		require_once WOR_PLUGIN_DIR .'includes/admin/views/debug_page.php';
		
	}
	
	public function may_reset_everything(){
		if(isset($_REQUEST['reset_all'])&&$_REQUEST['reset_all']==1){
			clear_sku_and_order_history();
		}
	}
	
	public function may_remove_order(){
		// exit();
		remove_order_from_returned();
		
	}
	public function may_debug_returned_order(){
		create_debug_data();
		
		
	}
	public function may_save_product_attributes_in_line_item(){
		if(isset($_REQUEST['woo_product_attributes_in_line_item']))
			save_product_attributes_in_line_item($_REQUEST['woo_product_attributes_in_line_item']);
	}
	
}
	
?>