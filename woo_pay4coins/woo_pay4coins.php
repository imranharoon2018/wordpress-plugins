<?php
/**
 * @package woo_pay4coins
 * @version 1
 */
/*
Plugin Name: woo_pay4coins
Plugin URI: www.google.com
Description: This plugin integrates pay4coins woocommerce getway requires.woocommerce
Author:Imran
Version: 1
Author URI: http://www.google.com
*/
// 
add_action( 'plugins_loaded', 'init_your_gateway_class' );
function init_your_gateway_class() {
    class WC_Gateway_Pay4Coins extends WC_Payment_Gateway {
		
		/**
		 * Whether or not logging is enabled
		 *
		 * @var bool
		 */
		// public static $log_enabled = false;
		public function __construct(){
			$this->id                = 'pay4coins';
			// $this->has_fields        = false;
			$this->order_button_text = __( 'Proceed to Pay4Coins', 'woocommerce' );
			$this->method_title      = __( 'Pay4Coins Standard', 'woocommerce' );
			/* translators: %s: Link to WC system status page */
			$this->method_description = __( 'Pay4Coins Standard redirects customers to Pay4Coins  to enter their payment information.', 'woocommerce' );
			$this->supports           = array(
				'products'
				// ,
				// 'refunds',
			);
			
			$this->init_form_fields();
			$this->init_settings();
			
			$this->title        = $this->get_option( 'title' );
			$this->pay4coins_shop_id        = $this->get_option( 'pay4coins_shop_id' );
			$this->pay4coins_api_key        = $this->get_option( 'pay4coins_api_key' );
			$this->pay4coins_api_url        = $this->get_option( 'pay4coins_api_url' );
			// self::$log_enabled    = $this->debug;
			// echo $this->pay4coins_shop_id;exit();
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		}
		public function init_form_fields(){
			$this->form_fields = array(
				
				'title' => array(
					'title' => __( 'Title', 'woocommerce' ),
					'type' => 'text',
					'default' => __( 'Pay4Coins', 'woocommerce' ),
					'desc_tip'      => true,
				),
				'pay4coins_api_key' => array(
					'title' => __( 'Pay4Coins API Key', 'woocommerce' ),
					'type' => 'text',
					'default' => ''
				),
				'pay4coins_shop_id' => array(
					'title' => __( 'Pay4Coins Shop ID' ),
					'type' => 'text',
					'default' => ''
				),
				'pay4coins_api_url' => array(
					'title' => __( 'Pay4Coins API URL' ),
					'type' => 'text',
					'default' => ''
				)
				
			);
			
		}
	
		public function process_payment( $order_id ) {
			global $woocommerce;
			$order = new WC_Order( $order_id );

			// Mark as on-hold (we're awaiting the cheque)
			// $order->update_status('on-hold', __( 'Awaiting cheque payment', 'woocommerce' ));

			// Remove cart
			// $woocommerce->cart->empty_cart();

			// Return thankyou redirect
			$yourAPIurl = wc_get_endpoint_url( 'order-received', '', wc_get_checkout_url() );
			$yourCustomerEmail = $order->get_billing_email();			 

			$yourCustomerMemberId=$order->get_user_id();
			$cart_items = WC()->cart->get_cart_contents();
			$yourProductName = "";
			foreach($cart_items  as $key=>$data){
				
				$yourProductName .= $data['data']->get_name();
				if($key != $last_key)
					$yourProductName .= ",";
				
			}
			$yourProductPrice = WC()->cart->get_cart_contents_total() ;
			$yourCustomerUsername = get_userdata($order->user_id)->user_login;
			
			$p4c_shop_id =  $this->get_option( 'pay4coins_shop_id' );
			$p4c_api_key =  $this->get_option( 'pay4coins_api_key' );
			
		
			$parameter = array(
				"api_url"       => $yourAPIurl,
				"api_version"   => 'v1',				
				"email"         => $yourCustomerEmail,
			#    "error_url"     => $yourErroURL,
				"member_id"     => $yourCustomerMemberId,
				"name"          => $yourProductName,
				"price"         => $yourProductPrice,
				"shop_id"       => $p4c_shop_id,
				"username"      => $yourCustomerUsername,
				#"user_variable_1" => $optParam1
				#"user_variable_1" => $optParam2,
				#"user_variable_1" => $optParam3,
				#"user_variable_1" => $optParam4
			);

			// remove empty GET parameters
			$parameter = array_filter($parameter, "strlen");
			//sorting alphabetical
			ksort($parameter);
			// create http-query
			$parameter = http_build_query($parameter);
			// create hash from query
			$hash = hash("sha512", $parameter.$p4c_api_key);
			// create URL
			$url = $this->get_option( 'pay4coins_api_url' ).'?'.$parameter.'&hash='.$hash;
			return array(
				'result' => 'success',
				'redirect' =>$url
				// 'redirect' => $this->get_return_url( $order )
			);
		}
	}
}
		


function add_your_gateway_class( $methods ) {
    $methods[] = 'WC_Gateway_Pay4Coins'; 
    return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'add_your_gateway_class' );