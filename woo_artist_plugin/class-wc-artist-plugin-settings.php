<?php // @codingStandardsIgnoreLine.
/**
 * WooCommerce Artist Plugin Settings
 *
 * @package woo_artist_plugin
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WC_Settings_Artist_Plugin', false ) ) {
	return new WC_Settings_Artist_Plugin();
}

/**
 * WC_Settings_Artist_Plugin.
 */
class WC_Settings_Artist_Plugin extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		
		$this->id    = 'artist_plugin';
		$this->label = __( 'Artist Plugin', 'woocommerce' );
		parent::__construct();
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = array(
			'' => __( 'Artist Plugin', 'woocommerce' ),
		);
		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}

	/**
	 * Get settings array.
	 *
	 * @param string $current_section Section being shown.
	 * @return array
	 */
	public function get_settings( $current_section = '' ) {
		$settings = array(
			
			array(
				'title'         => __( 'From Email Name', 'woocommerce' ),
				'desc'          => __( 'Email Account Name you want to display', 'woocommerce' ),
				'id'            => 'artist_plugin_from_email_name',
				'default'       => '',
				'type'          => 'text',
				
				'autoload'      => true,
			),
			array(
				'title'         => __( 'From Email Account', 'woocommerce' ),
				'desc'          => __( 'Email Account you want to send email from', 'woocommerce' ),
				'id'            => 'artist_plugin_from_email_account',				
				'type'          => 'email',				
				'default'		=> '',
				'autoload'      => true,
			),
			array(
				'title'         => __( 'Email Subject Line', 'woocommerce' ),
				'desc'          => __( 'Email  Subject Line', 'woocommerce' ),
				'id'            => 'artist_plugin_email_subject',				
				'type'          => 'text',				
				'autoload'      => true,
			),
			array(
				'title'         => __( 'End of Month Email Subject', 'woocommerce' ),
				'desc'          => __( 'End of Month Email Subject', 'woocommerce' ),
				'id'            => 'artist_plugin_end_of_month_email_subject',				
				'type'          => 'text',				
				'autoload'      => true,
			),
			array(
				'title'         => __( 'Admin Email Adress', 'woocommerce' ),
				'desc'          => __( 'Admin Email Adress', 'woocommerce' ),
				'id'            => 'artist_plugin_admin_email_address',				
				'type'          => 'text',				
				'autoload'      => true,
			)
			
			
			
		);

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings );
	}

	/**
	 * Output the settings.
	 */
	public function output() {
		 
		$settings = $this->get_settings();
		
		
		?>
		<table class="form-table">

			<tbody>
					<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="artist_plugin_from_email_name">From Email Name
								<!-- <span class="woocommerce-help-tip"></span> -->
								</label>
							</th>
							<td class="forminp forminp-text">
								<input name="artist_plugin_from_email_name" id="artist_plugin_from_email_name" type="text" style="" value="<?=WC_Admin_Settings :: get_option("artist_plugin_from_email_name") ?>" class="" placeholder=""> 							</td>
						</tr>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="artist_plugin_from_email_account">From Email Address
								<!-- <span class="woocommerce-help-tip"></span> -->
								</label>
							</th>
							<td class="forminp forminp-text">
								<input name="artist_plugin_from_email_account" id="artist_plugin_from_email_account" type="text" style="" value="<?=WC_Admin_Settings :: get_option("artist_plugin_from_email_account") ?>" class="" placeholder=""> 							</td>
						</tr>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="artist_plugin_email_subject">Order Processing Email Subject
								<!-- <span class="woocommerce-help-tip"></span> -->
								</label>
							</th>
							<td class="forminp forminp-text">
								<input name="artist_plugin_email_subject" id="artist_plugin_email_subject" type="text" style="" value="<?=WC_Admin_Settings :: get_option("artist_plugin_email_subject") ?>" class="" placeholder=""> 							</td>
						</tr>	
						
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="artist_plugin_order_canceled_email_subject">Order Canceled Email Subject
								<!-- <span class="woocommerce-help-tip"></span> -->
								</label>
							</th>
							<td class="forminp forminp-text">
								<input name="artist_plugin_order_canceled_email_subject" id="artist_plugin_order_canceled_email_subject" type="text" style="" value="<?=WC_Admin_Settings :: get_option("artist_plugin_order_canceled_email_subject") ?>" class="" placeholder=""> 							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="artist_plugin_end_of_month_email_subject">End of Month Email Subject
								<!-- <span class="woocommerce-help-tip"></span> -->
								</label>
							</th>
							<td class="forminp forminp-text">
								<input name="artist_plugin_end_of_month_email_subject" id="artist_plugin_end_of_month_email_subject" type="text" style="" value="<?=WC_Admin_Settings :: get_option("artist_plugin_end_of_month_email_subject") ?>" class="" placeholder=""> 							</td>
						</tr>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="artist_plugin_admin_email_address">Admin Email Address
								<!-- <span class="woocommerce-help-tip"></span> -->
								</label>
							</th>
							<td class="forminp forminp-text">
								<input name="artist_plugin_admin_email_address" id="artist_plugin_admin_email_address" type="email" style="" value="<?=WC_Admin_Settings :: get_option("artist_plugin_admin_email_address") ?>" class="" placeholder=""> 							</td>
						</tr>
						
						
				

			</tbody>
		</table>
		<?php
	}

}

return new WC_Settings_Artist_Plugin();
