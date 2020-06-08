<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package Countdown Timer Ultimate
 * @since 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpcdt_Script {

	function __construct() {

		// Action to add style at front side
		add_action( 'wp_enqueue_scripts', array($this, 'wpcdt_front_style') );

		// Action to add script at front side
		add_action( 'wp_enqueue_scripts', array($this, 'wpcdt_front_script') );

		// Action to add style in backend
		add_action( 'admin_enqueue_scripts', array($this, 'wpcdt_admin_style') );

		// Action to add script in backend
		add_action( 'admin_enqueue_scripts', array($this, 'wpcdt_admin_script') );
	}

	/**
	 * Function to add style at front side
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_front_style() {

		// Registring and enqueing public css
		wp_register_style( 'wpcdt-public-css', WPCDT_URL.'assets/css/wpcdt-timecircles.css', array(), WPCDT_VERSION );
		wp_enqueue_style( 'wpcdt-public-css' );
	}

	/**
	 * Function to add script at front side
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_front_script() {

		// Registring timer script
		wp_register_script( 'wpcdt-timecircle-js', WPCDT_URL.'assets/js/wpcdt-timecircles.js', array('jquery'), WPCDT_VERSION, true );

		// Registring public script
		wp_register_script( 'wpcdt-public-js', WPCDT_URL.'assets/js/wpcdt-public-js.js', array('jquery'), WPCDT_VERSION, true );
	}

		/**
	 * Enqueue admin styles
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_admin_style( $hook ) {

		global $post_type;

		// If page is plugin setting page then enqueue script
		if( $post_type == WPCDT_POST_TYPE ) {

			wp_enqueue_style( 'wp-color-picker' );

			wp_register_style( 'wpcdt-ui-timepicker-addon', WPCDT_URL.'assets/css/wpcdt-ui-timepicker-addon.css', null, WPCDT_VERSION );
			wp_enqueue_style( 'wpcdt-ui-timepicker-addon' );

			wp_register_style( 'wpcdt-admin-css', WPCDT_URL.'assets/css/wpcdt-admin-css.css', null, WPCDT_VERSION );
			wp_enqueue_style( 'wpcdt-admin-css' );
		}
	}

	/**
	 * Enqueue admin script
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_admin_script( $hook ) {

		global $post_type;

		// If page is plugin setting page then enqueue script
		if( $post_type == WPCDT_POST_TYPE ) {

			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-ui-slider' );

			// Registring admin script
			wp_register_script( 'wpcdt-ui-timepicker-addon-js', WPCDT_URL.'assets/js/wpcdt-ui-timepicker-addon.js', array('jquery'), WPCDT_VERSION, true );
			wp_enqueue_script( 'wpcdt-ui-timepicker-addon-js' );

			wp_register_script( 'wpcdt-admin-js', WPCDT_URL.'assets/js/wpcdt-admin-js.js', array('jquery'), WPCDT_VERSION, true );
			wp_enqueue_script( 'wpcdt-admin-js' );
		}
	}
}

$wpcdt_script = new Wpcdt_Script();