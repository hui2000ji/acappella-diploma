<?php
/**
 * Plugin Name: Countdown Timer Ultimate
 * Plugin URI: https://www.wponlinesupport.com/plugins/
 * Description: Easy to add and display responsive Countdown timer on your website. Also work with Gutenberg shortcode block.
 * Author: WP OnlineSupport
 * Text Domain: countdown-timer-ultimate
 * Domain Path: /languages/
 * Version: 1.2.4
 * Author URI: https://www.wponlinesupport.com/
 *
 * @package WordPress
 * @author WP OnlineSupport
 */

/**
 * Basic plugin definitions
 * 
 * @package Countdown Timer Ultimate
 * @since 1.1.1
 */
if( !defined( 'WPCDT_VERSION' ) ) {
	define( 'WPCDT_VERSION', '1.2.4' ); // Version of plugin
}
if( !defined( 'WPCDT_DIR' ) ) {
	define( 'WPCDT_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( !defined( 'WPCDT_URL' ) ) {
	define( 'WPCDT_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( !defined( 'WPCDT_PLUGIN_BASENAME' ) ) {
	define( 'WPCDT_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); // plugin base name
}
if( !defined( 'WPCDT_POST_TYPE' ) ) {
	define( 'WPCDT_POST_TYPE', 'wpcdt_countdown' ); // Plugin post type
}
if( !defined( 'WPCDT_META_PREFIX' ) ) {
	define( 'WPCDT_META_PREFIX', '_wpcdt_' ); // Plugin metabox prefix
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @package Countdown Timer Ultimate
 * @since 1.0.0
 */
function wpcdt_load_textdomain() {

	global $wp_version;

	// Set filter for plugin's languages directory
	$wpcdt_lang_dir = dirname( WPCDT_PLUGIN_BASENAME ) . '/languages/';
	$wpcdt_lang_dir = apply_filters( 'wpcdt_languages_directory', $wpcdt_lang_dir );

	// Traditional WordPress plugin locale filter.
	$get_locale = get_locale();

	if ( $wp_version >= 4.7 ) {
		$get_locale = get_user_locale();
	}

	// Traditional WordPress plugin locale filter
	$locale = apply_filters( 'plugin_locale',  $get_locale, 'countdown-timer-ultimate' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'countdown-timer-ultimate', $locale );

	// Setup paths to current locale file
	$mofile_global  = WP_LANG_DIR . '/plugins/' . basename( WPCDT_DIR ) . '/' . $mofile;

	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
		load_textdomain( 'countdown-timer-ultimate', $mofile_global );
	} else { // Load the default language files
		load_plugin_textdomain( 'countdown-timer-ultimate', false, $wpcdt_lang_dir );
	}
}
add_action('plugins_loaded', 'wpcdt_load_textdomain');

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @package Countdown Timer Ultimate
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'wpcdt_install' );

/**
 * Deactivation Hook
 * 
 * Register plugin deactivation hook.
 * 
 * @package Countdown Timer Ultimate
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'wpcdt_uninstall');

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * stest default values for the plugin options.
 * 
 * @package Countdown Timer Ultimate
 * @since 1.0.0
 */
function wpcdt_install() {  

	wpcdt_register_post_type();

	// IMP need to flush rules for custom registered post type
	flush_rewrite_rules();

	if( is_plugin_active('countdown-timer-ultimate-pro/countdown-timer-ultimate-pro.php') ) {
		add_action('update_option_active_plugins', 'deactivate_countdown_pro_version');
	}
}

/**
 * Plugin Setup (On Deactivation)
 * 
 * Delete plugin options.
 * 
 * @package Countdown Timer Ultimate
 * @since 1.0.0
 */
function wpcdt_uninstall() {

	// IMP need to flush rules for custom registered post type
	flush_rewrite_rules();
}

/**
 * Deactivate free plugin
 * 
 * @package Countdown Timer Ultimate
 * @since 1.0.0
 */
function deactivate_countdown_pro_version() {
	deactivate_plugins('countdown-timer-ultimate-pro/countdown-timer-ultimate-pro.php', true);
}

/**
 * Function to display admin notice of activated plugin.
 * 
 * @package Countdown Timer Ultimate
 * @since 1.0.0
 */
function wpcdt_countdown_admin_notice() {
	
	global $pagenow;

	$dir 				= WP_PLUGIN_DIR . '/countdown-timer-ultimate-pro/countdown-timer-ultimate-pro.php';
	$notice_link 		= add_query_arg( array('message' => 'wpcdt-plugin-notice'), admin_url('plugins.php') );
	$notice_transient 	= get_transient( 'wpcdt_install_notice' );
	
	// If FREE plugin is active and PRO plugin exist
	if( $notice_transient == false && $pagenow == 'plugins.php' && file_exists( $dir ) && current_user_can( 'install_plugins' ) ) {
			echo '<div class="updated notice" style="position:relative;">
				<p>
					<strong>'.sprintf( __('Thank you for activating %s', 'countdown-timer-ultimate'), 'Countdown Timer Ultimate').'</strong>.<br/>
					'.sprintf( __('It looks like you had PRO version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'countdown-timer-ultimate'), '<strong>(<em>Countdown Timer Ultimate PRO</em>)</strong>' ).'
				</p>
				<a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
			</div>';
	}
}

// Action to display notice
add_action( 'admin_notices', 'wpcdt_countdown_admin_notice');

// Functions file
require_once( WPCDT_DIR . '/includes/wpcdt-functions.php' );

// Plugin Post Type File
require_once( WPCDT_DIR . '/includes/wpcdt-post-types.php' );

// Admin Class File
require_once( WPCDT_DIR . '/includes/admin/class-wpcdt-admin.php' );

// Script Class File
require_once( WPCDT_DIR . '/includes/class-wpcdt-script.php' );

// Shortcode File
require_once( WPCDT_DIR . '/includes/shortcode/wpcdt-shortcode.php' );

// How it work file, Load admin files
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
	require_once( WPCDT_DIR . '/includes/admin/wpcdt-how-it-work.php' );
}

/* Plugin Wpos Analytics Data Starts */
function wpos_analytics_anl31_load() {

	require_once dirname( __FILE__ ) . '/wpos-analytics/wpos-analytics.php';

	$wpos_analytics =  wpos_anylc_init_module( array(
							'id' 			=> 31,
							'file' 			=> plugin_basename( __FILE__ ),
							'name' 			=> 'Countdown Timer Ultimate',
							'slug' 			=> 'countdown-timer-ultimate',
							'type' 			=> 'plugin',
							'menu' 			=> 'edit.php?post_type=wpcdt_countdown',
							'text_domain'	=> 'countdown-timer-ultimate',
							'promotion'		=> array(
													'bundle' => array(
															'name'	=> 'Download FREE 50+ Plugins, 10+ Themes and Dashboard Plugin',
															'desc'	=> 'Download FREE 50+ Plugins, 10+ Themes and Dashboard Plugin',
															'file'	=> 'https://www.wponlinesupport.com/latest/wpos-free-50-plugins-plus-12-themes.zip'
														)
													),
							'offers'		=> array(
													'trial_premium' => array(
														'image'	=> 'http://analytics.wponlinesupport.com/?anylc_img=31',
														'link'	=> 'http://analytics.wponlinesupport.com/?anylc_redirect=31',
														'desc'	=> 'Or start using the plugin from admin menu',
													)
												),
						));

	return $wpos_analytics;
}
// Init Analytics
wpos_analytics_anl31_load();
/* Plugin Wpos Analytics Data Ends */