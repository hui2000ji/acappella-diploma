<?php
/**
 * 'wpcdt-countdown' Shortcode
 * 
 * @package Countdown Timer Ultimate
 * @since 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function wpcdt_countdown_timer( $atts, $content = null ) {

	// Shortcode Parameter
	extract(shortcode_atts(array(
		'id' => '',
	), $atts, 'wpcdt-countdown'));

	$id	= !empty($id) ? $id : '';

	wp_enqueue_script( 'wpcdt-timecircle-js' );
	wp_enqueue_script( 'wpcdt-public-js' );

	$unique		= wpcdt_get_unique();
	$prefix		= WPCDT_META_PREFIX;
	$date 		= get_post_meta($id, $prefix.'timer_date', true);
	$status 	= get_post_status( $id );
	$width 		= get_post_meta($id, $prefix.'timer_width', true);

	// Creating compitible date according to UTF GMT time zone formate for older browwser
	$timezone 		= get_option('gmt_offset');
	$tmzone 		= timezone_name_from_abbr("", $timezone*60*60 , 0) ; 	// get php time 
	$dtend 			=  new DateTime($date, new DateTimeZone($tmzone));		// end date obj
	$dtnow 			=  new DateTime("now", new DateTimeZone($tmzone));  	// currant 
	$totalseconds 	= $dtend->getTimestamp() - $dtnow->getTimestamp();		// total diffrence seconds

	if( $totalseconds < 0 ) {
		$totalseconds = 0;
	}

	ob_start();

	if ( !empty($date) && $status == 'publish'  ) { ?>
		<div class="wpcdt-countdown-wrp wpcdt-clearfix">
			<div id="wpcdt-datecount-<?php echo $unique; ?>" class="wpcdt-countdown-timer" data-timer="<?php echo $totalseconds; ?>" style="max-width:<?php echo $width; ?>px">
				<?php
					$date 					= get_post_meta($id, $prefix.'timer_date', true);
					$animation				= get_post_meta($id, $prefix.'timercircle_animation', true);
					$circlewidth			= get_post_meta($id, $prefix.'timercircle_width', true);
					$backgroundwidth		= get_post_meta($id, $prefix.'timerbackground_width', true);
					$backgroundcolor		= get_post_meta($id, $prefix.'timerbackground_color', true);
					$is_days				= get_post_meta($id, $prefix.'is_timerdays', true);
					$daysbackgroundcolor	= get_post_meta($id, $prefix.'timerdaysbackground_color', true);
					$is_hours				= get_post_meta($id, $prefix.'is_timerhours', true);
					$hoursbackgroundcolor	= get_post_meta($id, $prefix.'timerhoursbackground_color', true);
					$is_minutes				= get_post_meta($id, $prefix.'is_timerminutes', true);
					$minutesbackgroundcolor	= get_post_meta($id, $prefix.'timerminutesbackground_color', true);
					$is_seconds				= get_post_meta($id, $prefix.'is_timerseconds', true);
					$secondsbackgroundcolor	= get_post_meta($id, $prefix.'timersecondsbackground_color', true);
					$days_text 				= get_post_meta($id, $prefix.'timer_day_text', true);
					$hours_text 			= get_post_meta($id, $prefix.'timer_hour_text', true);
					$minutes_text 			= get_post_meta($id, $prefix.'timer_minute_text', true);
					$seconds_text 			= get_post_meta($id, $prefix.'timer_second_text', true);
					$date_conf 				= compact('date', 'animation', 'circlewidth', 'backgroundwidth', 'backgroundcolor', 'is_days', 'daysbackgroundcolor', 'is_hours', 'hoursbackgroundcolor', 'is_minutes', 'minutesbackgroundcolor', 'is_seconds', 'secondsbackgroundcolor', 'days_text', 'hours_text', 'minutes_text', 'seconds_text');
				?>
			</div>
			<div class="wpcdt-date-conf" data-conf="<?php echo htmlspecialchars(json_encode($date_conf)); ?>"></div>
		</div>
	<?php
		$content .= ob_get_clean();
		return $content;
	}
}

// Countdown Shortcode
add_shortcode('wpcdt-countdown', 'wpcdt_countdown_timer');