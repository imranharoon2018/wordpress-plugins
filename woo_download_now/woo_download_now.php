<?php
/**
 * @package woo_download_now
 * @version 1.0.0
 */
/*
Plugin Name: woo_download_now
Plugin URI: www.google.com
Description: This plugin shows 'Start Download' instead of link or filename
Author:imran
Version: 1.7.2
Author URI: http://www.google.com
*/
// 
add_action( 'woocommerce_account_downloads_column_download-file', function( $download ) {
	echo '<a href="' . esc_url( $download['download_url'] ) . '">Download Starten</a>';
	
});


add_filter( 'woocommerce_available_download_link', function( $link,$download ) {
	
  return  '<a href="' . esc_url( $download['download_url'] ) . '">Download Starten</a>';
},2);