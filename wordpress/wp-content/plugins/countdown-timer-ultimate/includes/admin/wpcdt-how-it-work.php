<?php
/**
 * Pro Designs and Plugins Feed
 *
 * @package Countdown Timer Ultimate
 * @since 1.1.2
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Action to add menu
add_action('admin_menu', 'wpcdt_register_design_page');

/**
 * Register plugin design page in admin menu
 * 
 * @package Countdown Timer Ultimate
 * @since 1.1.2
 */
function wpcdt_register_design_page() {
	add_submenu_page( 'edit.php?post_type='.WPCDT_POST_TYPE, __('How it works - Countdown Timer Ultimate', 'countdown-timer-ultimate'), __('How It Works', 'countdown-timer-ultimate'), 'manage_options', 'wpcdt-designs', 'wpcdt_designs_page' );
}

/**
 * Function to display plugin design HTML
 * 
 * @package Countdown Timer Ultimate
 * @since 1.1.2
 */
function wpcdt_designs_page() {

	$wpos_feed_tabs = wpcdt_help_tabs();
	$active_tab 	= isset($_GET['tab']) ? $_GET['tab'] : 'how-it-work';
?>
	<div class="wrap wpcdt-wrap">

		<h2 class="nav-tab-wrapper">
			<?php
			foreach ($wpos_feed_tabs as $tab_key => $tab_val) {
				$tab_name	= $tab_val['name'];
				$active_cls = ($tab_key == $active_tab) ? 'nav-tab-active' : '';
				$tab_link 	= add_query_arg( array( 'post_type' => WPCDT_POST_TYPE, 'page' => 'wpcdt-designs', 'tab' => $tab_key), admin_url('edit.php') );
			?>

			<a class="nav-tab <?php echo $active_cls; ?>" href="<?php echo $tab_link; ?>"><?php echo $tab_name; ?></a>

			<?php } ?>
		</h2>

		<div class="wpcdt-tab-cnt-wrp">
		<?php
			if( isset($active_tab) && $active_tab == 'how-it-work' ) {
				wpcdt_howitwork_page();
			}
			else if( isset($active_tab) && $active_tab == 'plugins-feed' ) {
				echo wpcdt_get_plugin_design( 'plugins-feed' );
			}
		?>
		</div><!-- end .wpcdt-tab-cnt-wrp -->

	</div><!-- end .wpcdt-wrap -->

<?php
}

/**
 * Gets the plugin design part feed
 *
 * @package Countdown Timer Ultimate
 * @since 1.1.2
 */
function wpcdt_get_plugin_design( $feed_type = '' ) {

	$active_tab = isset($_GET['tab']) ? $_GET['tab'] : '';

	// If tab is not set then return
	if( empty($active_tab) ) {
		return false;
	}

	// Taking some variables
	$wpos_feed_tabs = wpcdt_help_tabs();
	$transient_key 	= isset($wpos_feed_tabs[$active_tab]['transient_key']) 	? $wpos_feed_tabs[$active_tab]['transient_key'] 	: 'wpcdt_' . $active_tab;
	$url 			= isset($wpos_feed_tabs[$active_tab]['url']) 			? $wpos_feed_tabs[$active_tab]['url'] 				: '';
	$transient_time = isset($wpos_feed_tabs[$active_tab]['transient_time']) ? $wpos_feed_tabs[$active_tab]['transient_time'] 	: 172800;
	$cache 			= get_transient( $transient_key );

	if ( false === $cache ) {

		$feed 			= wp_remote_get( esc_url_raw( $url ), array( 'timeout' => 120, 'sslverify' => false ) );
		$response_code 	= wp_remote_retrieve_response_code( $feed );

		if ( ! is_wp_error( $feed ) && $response_code == 200 ) {
			if ( isset( $feed['body'] ) && strlen( $feed['body'] ) > 0 ) {
				$cache = wp_remote_retrieve_body( $feed );
				set_transient( $transient_key, $cache, $transient_time );
			}
		} else {
			$cache = '<div class="error"><p>' . __( 'There was an error retrieving the data from the server. Please try again later.', 'countdown-timer-ultimate' ) . '</div>';
		}
	}
	return $cache;	
}

/**
 * Function to get plugin feed tabs
 *
 * @package Countdown Timer Ultimate
 * @since 1.1.2
 */
function wpcdt_help_tabs() {
	$wpos_feed_tabs = array(
						'how-it-work' 	=> array(
													'name' => __('How It Works', 'countdown-timer-ultimate'),
												),
						'plugins-feed' 	=> array(
													'name' 				=> __('Our Plugins', 'countdown-timer-ultimate'),
													'url'				=> 'http://wponlinesupport.com/plugin-data-api/plugins-data.php',
													'transient_key'		=> 'wpos_plugins_feed',
													'transient_time'	=> 172800
												),
					);
	return $wpos_feed_tabs;
}

/**
 * Function to get 'How It Works' HTML
 *
 * @package Countdown Timer Ultimate
 * @since 1.1.2
 */
function wpcdt_howitwork_page() { ?>

	<style type="text/css">
		.wpos-pro-box .hndle{background-color:#0073AA; color:#fff;}
		.wpos-pro-box .postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .wpos-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.wpcdt-wrap .wpos-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.wpcdt-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
		.upgrade-to-pro{font-size:18px; text-align:center; margin-bottom:15px;}
	</style>

	<div class="post-box-container">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">

				<!--How it workd HTML -->
				<div id="post-body-content">
					<div class="metabox-holder">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox">
								<h3 class="hndle">
									<span><?php _e( 'How It Works - Display and shortcode', 'countdown-timer-ultimate' ); ?></span>
								</h3>

								<div class="inside">
									<table class="form-table">
										<tbody>
											<tr>
												<th>
													<label><?php _e('Getting Started', 'countdown-timer-ultimate'); ?></label>
												</th>
												<td>
													<ul>
														<li><?php _e('Step-1: This plugin creates a Countdown Timer Ultimate tab in WordPress menu section', 'countdown-timer-ultimate'); ?></li>
														<li><?php _e('Step-2: Add Timer.', 'countdown-timer-ultimate'); ?></li>
														<li><?php _e('Step-3: Display timer on any POST OR PAGE of your website.', 'countdown-timer-ultimate'); ?></li>
													</ul>
												</td>
											</tr>

											<tr>
												<th>
													<label><?php _e('Plugin Shortcodes', 'countdown-timer-ultimate'); ?></label>
												</th>
												<td>
													<span class="wpcdt-shortcode-preview">[wpcdt-countdown id="1"]</span> – <?php _e('Countdown Timer', 'countdown-timer-ultimate'); ?> <br/>
												</td>
											</tr>

											<tr>
												<th>
													<label><?php _e('Need Support?', 'countdown-timer-ultimate'); ?></label>
												</th>
												<td>
													<p><?php _e('Check plugin document for shortcode parameters and demo for designs.', 'countdown-timer-ultimate'); ?></p><br/>
													<a class="button button-primary" href="https://docs.wponlinesupport.com/countdown-timer-ultimate/" target="_blank"><?php _e('Documentation', 'countdown-timer-ultimate'); ?></a>
													<a class="button button-primary" href="https://demo.wponlinesupport.com/countdown-timer-ultimate-demo/" target="_blank"><?php _e('Demo for Designs', 'countdown-timer-ultimate'); ?></a>
												</td>
											</tr>
										</tbody>
									</table>
								</div><!-- .inside -->
							</div><!-- #general -->
						</div><!-- .meta-box-sortables ui-sortable -->
					</div><!-- .metabox-holder -->
				</div><!-- #post-body-content -->

				<!--Upgrad to Pro HTML -->
				<div id="postbox-container-1" class="postbox-container">
					<div class="metabox-holder wpos-pro-box">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox" style="">
								<h3 class="hndle">
									<span><?php _e( 'Upgrate to Pro', 'countdown-timer-ultimate' ); ?></span>
								</h3>
								<div class="inside">
									<ul class="wpos-list">
										<li>12+ stunning cool designs for clock and timer.</li>
										<li>Fully customized clock.</li>
										<li>Create unlimited Countdowns Timer</li>
										<li>Create Countdown in pages/posts</li>
										<li>Custom css</li>
										<li>Templating Feature Support</li>
										<li>Easy to integrate with e-commerce coupons like WooCommerce and Easy Digital Downloads.</li>
										<li>Various parameters for clock like background color, text color and etc.</li>
										<li>Option to show/hide Days, hours, minutes and seconds.</li>
										<li>Clock expiration event. Display your desired text on complition of timer.</li>
										<li>Light weight and fast.</li>
										<li>Fully responsive</li>
										<li>100% Multi language</li>
									</ul>
									<div class="upgrade-to-pro">Gain access to <strong>Album and Image Gallery Plus Lightbox</strong> included in <br /><strong>Essential Plugin Bundle</div>
									<a class="button button-primary wpos-button-full" href="https://www.wponlinesupport.com/wp-plugin/countdown-timer-ultimate/?ref=WposPratik&utm_source=WP&utm_medium=WP-Plugins&utm_campaign=Upgrade-PRO" target="_blank"><?php _e('Go Premium ', 'countdown-timer-ultimate'); ?></a>
									<p><a class="button button-primary wpos-button-full" href="https://demo.wponlinesupport.com/prodemo/countdown-timer-ultimate-pro/circle-countdown-timer/" target="_blank"><?php _e('View PRO Demo ', 'countdown-timer-ultimate'); ?></a>	</p>
								</div><!-- .inside -->
							</div><!-- #general -->
						</div><!-- .meta-box-sortables ui-sortable -->
					</div><!-- .metabox-holder -->

					<!-- Help to improve this plugin! -->
					<div class="metabox-holder">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox">
									<h3 class="hndle">
										<span><?php _e( 'Help to improve this plugin!', 'countdown-timer-ultimate' ); ?></span>
									</h3>
									<div class="inside">
										<p><?php _e('Enjoyed this plugin? You can help by rate this plugin', 'countdown-timer-ultimate'); ?> <a href="https://wordpress.org/support/plugin/countdown-timer-ultimate/reviews/" target="_blank">5 stars!</a></p>
									</div><!-- .inside -->
							</div><!-- #general -->
						</div><!-- .meta-box-sortables ui-sortable -->
					</div><!-- .metabox-holder -->
				</div><!-- #post-container-1 -->
			</div><!-- #post-body -->
		</div><!-- #poststuff -->
	</div><!-- #post-box-container -->
<?php }