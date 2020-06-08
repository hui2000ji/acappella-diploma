<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package Countdown Timer Ultimate
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Wpcdt_Admin {

	function __construct() {

		// Action to add admin menu
		add_action( 'admin_menu', array($this, 'wpcdt_register_menu'), 12 );

		// Action to add metabox
		add_action( 'add_meta_boxes', array($this, 'wpcdt_post_sett_metabox') );

		// Action to save metabox
		add_action( 'save_post', array($this, 'wpcdt_save_metabox_value') );

		// Admin Prior Process
		add_action( 'admin_init', array($this, 'wpcdt_admin_init_process') );

		// Action to add custom column to Timer listing
		add_filter( 'manage_'.WPCDT_POST_TYPE.'_posts_columns', array($this, 'wpcdt_posts_columns') );

		// Action to add custom column data to Timer listing
		add_action('manage_'.WPCDT_POST_TYPE.'_posts_custom_column', array($this, 'wpcdt_post_columns_data'), 10, 2);
	}

	/**
	 * Function to add menu
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_register_menu() {

		// Premium Feature Page
		add_submenu_page( 'edit.php?post_type='.WPCDT_POST_TYPE, __('Upgrade to PRO - Countdown Timer Ultimate', 'countdown-timer-ultimate'), '<span style="color:#2ECC71">'.__('Upgrade to PRO', 'countdown-timer-ultimate').'</span>', 'manage_options', 'wpcdt-premium', array($this, 'wpcdt_premium_page') );

		// Hire Us Page
		add_submenu_page( 'edit.php?post_type='.WPCDT_POST_TYPE, __('Hire Us', 'countdown-timer-ultimate'), '<span style="color:#2ECC71">'.__('Hire Us', 'countdown-timer-ultimate').'</span>', 'manage_options', 'wpcdt-hireus', array($this, 'wpcdt_hireus_page') );		
	}

	/**
	 * Premium Feature Page HTML
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_premium_page() {
		include_once( WPCDT_DIR . '/includes/admin/settings/premium.php' );
	}

	/**
	 * Hire Us Page Html
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.1.4
	 */
	function wpcdt_hireus_page() {
		include_once( WPCDT_DIR . '/includes/admin/settings/hire-us.php' );
	}

	/**
	 * Post Settings Metabox
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_post_sett_metabox() {
		add_meta_box( 'wpcdt-post-sett', __( 'WP Countdown Timer Settings - Settings', 'countdown-timer-ultimate' ), array($this, 'wpcdt_post_sett_mb_content'), WPCDT_POST_TYPE, 'normal', 'high' );
	}

	/**
	 * Post Settings Metabox HTML
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_post_sett_mb_content() {
		include_once( WPCDT_DIR .'/includes/admin/metabox/wpcdt-sett-metabox.php');
	}

	/**
	 * Function to save metabox values
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_save_metabox_value( $post_id ) {

		global $post_type;

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )                	// Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )  	// Check Revision
		|| ( $post_type !=  WPCDT_POST_TYPE ) )              					// Check if current post type is supported.
		{
			return $post_id;
		}

		$prefix = WPCDT_META_PREFIX; // Taking metabox prefix

		// General Settings
		$date 					= isset($_POST[$prefix.'timer_date'])					? wpcdt_clean($_POST[$prefix.'timer_date']) 					: '';
		$animation 				= isset($_POST[$prefix.'timercircle_animation'])		? wpcdt_clean($_POST[$prefix.'timercircle_animation']) 			: '';
		$circlewidth			= isset($_POST[$prefix.'timercircle_width'])			? wpcdt_clean($_POST[$prefix.'timercircle_width']) 				: '';
		$backgroundwidth		= isset($_POST[$prefix.'timerbackground_width'])		? wpcdt_clean_numeric($_POST[$prefix.'timerbackground_width']) 	: '';
		$backgroundcolor		= isset($_POST[$prefix.'timerbackground_color'])		? wpcdt_clean_colors($_POST[$prefix.'timerbackground_color']) 	: '';
		$timer_width 			= isset($_POST[$prefix.'timer_width'])					? wpcdt_clean_numbers($_POST[$prefix.'timer_width']) 			: '';

		// Days Settings
		$is_days				= !empty($_POST[$prefix.'is_timerdays'])				? 1 : 0;
		$days_text 				= isset($_POST[$prefix.'timer_day_text'])				? wpcdt_clean($_POST[$prefix.'timer_day_text']) 					: 'Days';
		$daysbackgroundcolor	= isset($_POST[$prefix.'timerdaysbackground_color'])	? wpcdt_clean_colors($_POST[$prefix.'timerdaysbackground_color']) 		: '';

		// Hours Settings
		$is_hours				= !empty($_POST[$prefix.'is_timerhours']) 				? 1 : 0;
		$hours_text 			= isset($_POST[$prefix.'timer_hour_text']) 				? wpcdt_clean($_POST[$prefix.'timer_hour_text']) 				: 'Hours';
		$hoursbackgroundcolor	= isset($_POST[$prefix.'timerhoursbackground_color'])	? wpcdt_clean_colors($_POST[$prefix.'timerhoursbackground_color']) 		: '';

		// minutes Settings
		$is_minutes				= !empty($_POST[$prefix.'is_timerminutes'])				? 1 : 0;
		$minutes_text 			= isset($_POST[$prefix.'timer_minute_text'])			? wpcdt_clean($_POST[$prefix.'timer_minute_text']) 				: 'Minutes';
		$minutesbackgroundcolor	= isset($_POST[$prefix.'timerminutesbackground_color']) ? wpcdt_clean_colors($_POST[$prefix.'timerminutesbackground_color']) 	: '';

		// seconds Settings
		$is_seconds				= !empty($_POST[$prefix.'is_timerseconds'])				? 1 : 0;
		$seconds_text 			= isset($_POST[$prefix.'timer_second_text'])			? wpcdt_clean($_POST[$prefix.'timer_second_text']) 				: 'Seconds';
		$secondsbackgroundcolor	= isset($_POST[$prefix.'timersecondsbackground_color']) ? wpcdt_clean_colors($_POST[$prefix.'timersecondsbackground_color']) 	: '';

		// General Settings
		update_post_meta($post_id, $prefix.'timer_date', $date);
		update_post_meta($post_id, $prefix.'timercircle_animation', $animation);
		update_post_meta($post_id, $prefix.'timercircle_width', $circlewidth);
		update_post_meta($post_id, $prefix.'timerbackground_width', $backgroundwidth);
		update_post_meta($post_id, $prefix.'timerbackground_color', $backgroundcolor);
		update_post_meta($post_id, $prefix.'timer_width', $timer_width);

		// Days Settings
		update_post_meta($post_id, $prefix.'is_timerdays', $is_days);
		update_post_meta($post_id, $prefix.'timer_day_text', $days_text);
		update_post_meta($post_id, $prefix.'timerdaysbackground_color', $daysbackgroundcolor);

		// Hours Settings
		update_post_meta($post_id, $prefix.'is_timerhours', $is_hours);
		update_post_meta($post_id, $prefix.'timer_hour_text', $hours_text);
		update_post_meta($post_id, $prefix.'timerhoursbackground_color', $hoursbackgroundcolor);

		// minutes Settings
		update_post_meta($post_id, $prefix.'is_timerminutes', $is_minutes);
		update_post_meta($post_id, $prefix.'timer_minute_text', $minutes_text);
		update_post_meta($post_id, $prefix.'timerminutesbackground_color', $minutesbackgroundcolor);

		// seconds Settings
		update_post_meta($post_id, $prefix.'is_timerseconds', $is_seconds);
		update_post_meta($post_id, $prefix.'timer_second_text', $seconds_text);
		update_post_meta($post_id, $prefix.'timersecondsbackground_color', $secondsbackgroundcolor);
	}

	/**
	 * Add custom column to Post listing page
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_posts_columns( $columns ) {

		$new_columns['wpcdt_shortcode'] = __('Shortcode', 'countdown-timer-ultimate');
		$columns = wpcdt_add_array( $columns, $new_columns, 1, true );

		return $columns;
	}

	/**
	 * Add custom column data to Post listing page
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.0.0
	 */
	function wpcdt_post_columns_data( $column, $post_id ) {

		global $post;

		// Taking some variables
		$prefix = WPCDT_META_PREFIX;

		switch ($column) {
			case 'wpcdt_shortcode':

				echo '<div class="wpcdt-shortcode-preview">[wpcdt-countdown id="'.$post_id.'"]</div> <br/>';
				break;
		}
	}

	/**
	 * Admin Prior Process
	 * 
	 * @package Countdown Timer Ultimate
	 * @since 1.1.4
	 */
	function wpcdt_admin_init_process() {

		// If plugin notice is dismissed
		if( isset($_GET['message']) && $_GET['message'] == 'wpcdt-plugin-notice' ) {
			set_transient( 'wpcdt_install_notice', true, 604800 );
		}
	}
}

$wpcdt_admin = new Wpcdt_Admin();