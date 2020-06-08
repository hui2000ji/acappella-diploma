<?php
/**
 * Handles Post Setting metabox HTML
 *
 * @package Countdown Timer Ultimate
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

global $post;

$prefix = WPCDT_META_PREFIX; // Metabox prefix

// get timezon from WP settings
$current_offset = get_option('gmt_offset');
$tzstring 		= get_option('timezone_string');

// Remove old Etc mappings. Fallback to gmt_offset.
if ( false !== strpos($tzstring,'Etc/GMT') ){
	$tzstring = '';
}

if ( empty($tzstring) ) { // Create a UTC+- zone if no timezone string exists
	if ( 0 == $current_offset ) {
		$tzstring = 'UTC+0';
	} elseif ($current_offset < 0) {
		$tzstring = 'UTC' . $current_offset;
	} else {
		$tzstring = 'UTC+' . $current_offset;
	}
}

// Getting saved values
$date 					= get_post_meta( $post->ID, $prefix.'timer_date', true );
$date 					= ($date != '') ? $date : current_time('Y-m-d H:m:s');

$timer_width 			= get_post_meta( $post->ID, $prefix.'timer_width', true );
$timer_width 			= ($timer_width != '') ? $timer_width : '';

$timer_day_text 		= get_post_meta( $post->ID, $prefix.'timer_day_text', true );
$timer_day_text 		= ($timer_day_text != '') ? $timer_day_text : 'Days';

$timer_hour_text 		= get_post_meta( $post->ID, $prefix.'timer_hour_text', true );
$timer_hour_text 		= ($timer_hour_text != '') ? $timer_hour_text : 'Hours';

$timer_minute_text 		= get_post_meta( $post->ID, $prefix.'timer_minute_text', true );
$timer_minute_text 		= ($timer_minute_text != '') ? $timer_minute_text : 'Minutes';

$timer_second_text 		= get_post_meta( $post->ID, $prefix.'timer_second_text', true );
$timer_second_text 		= ($timer_second_text != '') ? $timer_second_text : 'Seconds';

$animation 				= get_post_meta( $post->ID, $prefix.'timercircle_animation', true );

$circlewidth 			= get_post_meta( $post->ID, $prefix.'timercircle_width', true );
$circlewidth 			= ($circlewidth != '') ? $circlewidth : 0.1;

$backgroundwidth 		= get_post_meta( $post->ID, $prefix.'timerbackground_width', true );
$backgroundwidth 		= ($backgroundwidth != '') ? $backgroundwidth : 1.2;

$backgroundcolor 		= get_post_meta( $post->ID, $prefix.'timerbackground_color', true );
$backgroundcolor 		= ($backgroundcolor != '') ? $backgroundcolor : '';

$is_days 				= get_post_meta( $post->ID, $prefix.'is_timerdays', true );
$is_days 				= ($is_days != '') ? $is_days : 1;

$daysbackgroundcolor	= get_post_meta( $post->ID, $prefix.'timerdaysbackground_color', true );
$daysbackgroundcolor 	= ($daysbackgroundcolor != '') ? $daysbackgroundcolor : '';

$is_hours 				= get_post_meta( $post->ID, $prefix.'is_timerhours', true );
$is_hours 				= ($is_hours != '') ? $is_hours : 1;

$hoursbackgroundcolor	= get_post_meta( $post->ID, $prefix.'timerhoursbackground_color', true );
$hoursbackgroundcolor 	= ($hoursbackgroundcolor != '') ? $hoursbackgroundcolor : '';

$is_minutes 			= get_post_meta( $post->ID, $prefix.'is_timerminutes', true );
$is_minutes 			= ($is_minutes != '') ? $is_minutes : 1;

$minutesbackgroundcolor	= get_post_meta( $post->ID, $prefix.'timerminutesbackground_color', true );
$minutesbackgroundcolor = ($minutesbackgroundcolor != '') ? $minutesbackgroundcolor : '';

$is_seconds 			= get_post_meta( $post->ID, $prefix.'is_timerseconds', true );
$is_seconds 			= ($is_seconds != '') ? $is_seconds : 1;

$secondsbackgroundcolor	= get_post_meta( $post->ID, $prefix.'timersecondsbackground_color', true );
$secondsbackgroundcolor = ($secondsbackgroundcolor != '') ? $secondsbackgroundcolor : '';
?>

<table class="form-table wpcdt-post-sett-table">
	<tbody>
		<tr>
			<td colspan="2">
				<div class="wpcdt-notice">
					<?php echo sprintf( __('Countdown Timer Ultimate works with WordPress timezone which you had set from <a href="%s" target="_blank">General Setting</a> page.', 'countdown-timer-ultimate'), admin_url('options-general.php') ); ?> <br/>
					<?php echo __('Your Current timezone is', 'countdown-timer-ultimate') .' : '. $tzstring; ?>
				</div>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="wpcdt-countdown-datepicker"><?php _e('Expiry Date', 'countdown-timer-ultimate'); ?></label>
			</th>
			<td>
			<input type="text" id="wpcdt-countdown-datepicker" class="wpcdt-countdown-datepicker" name="<?php echo $prefix; ?>timer_date" value="<?php echo wpcdt_escape_attr($date); ?>" /><br/>
			<span class="description"><?php _e('Select timer expiry Date and Time.', 'countdown-timer-ultimate'); ?></span>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="wpcdt-countdown-animation"><?php _e('Choose Animation', 'countdown-timer-ultimate'); ?></label>
			</th>
			<td>
				<select id="wpcdt-countdown-animation" class="wpcdt-countdown-animation" name="<?php echo $prefix; ?>timercircle_animation">
				<option value="smooth" <?php if($animation == 'smooth'){echo 'selected'; } ?>>Smooth</option>
				<option value="ticks" <?php if($animation == 'ticks'){echo 'selected'; } ?>>Ticks</option>
				</select><br/>
				<span class="description"><?php _e('Select circle animation style.', 'countdown-timer-ultimate'); ?></span>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label><?php _e('Circle Width', 'countdown-timer-ultimate'); ?></label>
			</th>
			<td>
				<input type="hidden" class="wpcdt-number" min="0.0033333333333333335" max="0.13333333333333333" step="0.003333333" name="<?php echo $prefix; ?>timercircle_width" value="<?php echo wpcdt_escape_attr($circlewidth); ?>" />
				<div class="wpcdt-circle-slider"></div>
				<span class="description"><?php _e('Adjust circle width.', 'countdown-timer-ultimate'); ?></span>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label><?php _e('Background Width', 'countdown-timer-ultimate'); ?></label>
			</th>
			<td>
				<input type="hidden" class="wpcdt-number" min="0.1" max="3" step="0.1" name="<?php echo $prefix; ?>timerbackground_width" value="<?php echo wpcdt_escape_attr($backgroundwidth); ?>" />
				<div class="wpcdt-background-slider"></div>
				<span class="description"><?php _e('Adjust circle background width.', 'countdown-timer-ultimate'); ?></span>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="wpcdt-countdown-width"><?php _e('Countdown Width', 'countdown-timer-ultimate'); ?></label>
			</th>
			<td>
				<input type="number" id="wpcdt-countdown-width" min="1" class="wpcdt-countdown-width" name="<?php echo $prefix; ?>timer_width" value="<?php echo wpcdt_escape_attr($timer_width); ?>" /> <?php _e('Px', 'countdown-timer-ultimate'); ?><br/>
				<span class="description"><?php _e('Enter countdown timer width. Leave empty for default width.', 'countdown-timer-ultimate'); ?></span>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label><?php _e('Background Color', 'countdown-timer-ultimate'); ?></label>
			</th>
			<td>
				<input type="text" class="wplc-color-box" name="<?php echo $prefix; ?>timerbackground_color" value="<?php echo wpcdt_escape_attr($backgroundcolor); ?>" />
				<span class="description"><?php _e('Please select background color.', 'countdown-timer-ultimate'); ?></span>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label><?php _e('Foreground Color', 'countdown-timer-ultimate'); ?></label>
			</th>
			<td>
				<table>
					<tr>
						<td>
							<label><input type="checkbox" name="<?php echo $prefix; ?>is_timerdays" value="1" <?php checked($is_days,1); ?> /><input type="text" id="wpcdt-countdown-day-text" class="wpcdt-countdown-day-text" name="<?php echo $prefix; ?>timer_day_text" value="<?php echo wpcdt_escape_attr($timer_day_text); ?>"></label><br/>
							<input type="text" class="wplc-color-box" name="<?php echo $prefix; ?>timerdaysbackground_color" value="<?php echo wpcdt_escape_attr($daysbackgroundcolor); ?>">
							<span class="description"><?php _e('Select Day circle color.', 'countdown-timer-ultimate'); ?></span>
						</td>

						<td>
							<label><input type="checkbox" name="<?php echo $prefix; ?>is_timerhours" value="1" <?php checked($is_hours,1); ?>><input type="text" id="wpcdt-countdown-hour-text" class="wpcdt-countdown-hour-text" name="<?php echo $prefix; ?>timer_hour_text" value="<?php echo wpcdt_escape_attr($timer_hour_text); ?>"></label><br/>
							<input type="text" class="wplc-color-box" name="<?php echo $prefix; ?>timerhoursbackground_color" value="<?php echo wpcdt_escape_attr($hoursbackgroundcolor); ?>">
							<span class="description"><?php _e('Select Hours circle color.', 'countdown-timer-ultimate'); ?></span>
						</td>
					</tr>
					<tr>
						<td>
							<label><input type="checkbox" name="<?php echo $prefix; ?>is_timerminutes" value="1" <?php checked($is_minutes,1); ?>><input type="text" id="wpcdt-countdown-minutes-text" class="wpcdt-countdown-minutes-text" name="<?php echo $prefix; ?>timer_minute_text" value="<?php echo wpcdt_escape_attr($timer_minute_text); ?>"></label><br/>
							<input type="text" class="wplc-color-box" name="<?php echo $prefix; ?>timerminutesbackground_color" value="<?php echo wpcdt_escape_attr($minutesbackgroundcolor); ?>">
							<span class="description"><?php _e('Select Minute circle color.', 'countdown-timer-ultimate'); ?></span>
						</td>

						<td>
							<label><input type="checkbox" name="<?php echo $prefix; ?>is_timerseconds" value="1" <?php checked($is_seconds,1); ?>><input type="text" id="wpcdt-countdown-seconds-text" class="wpcdt-countdown-seconds-text" name="<?php echo $prefix; ?>timer_second_text" value="<?php echo wpcdt_escape_attr($timer_second_text); ?>"></label><br/>
							<input type="text" class="wplc-color-box" name="<?php echo $prefix; ?>timersecondsbackground_color" value="<?php echo wpcdt_escape_attr($secondsbackgroundcolor); ?>">
							<span class="description"><?php _e('Select Second circle color.', 'countdown-timer-ultimate'); ?></span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</tbody>
</table><!-- end .wpcdt-post-sett-table -->