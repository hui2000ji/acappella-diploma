<?php
/**
 * Plugin generic functions file
 *
 * @package Countdown Timer Ultimate
 * @since 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Function to unique number value
 * 
 * @package Countdown Timer Ultimate
 * @since 1.0.0
 */
function wpcdt_get_unique() {
	static $unique = 0;
	$unique++;

	return $unique;
}

/**
 * Escape Tags & Slashes. Handles escapping the slashes and tags
 *
 * @package  Countdown Timer Ultimate
 * @since 1.0.0
 */
function wpcdt_escape_attr($data){
	return esc_attr(stripslashes($data));
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 * 
 * @package Countdown Timer Ultimate Pro
 * @since 1.0
 */
function wpcdt_clean( $var ) {
    if ( is_array( $var ) ) {
        return array_map( 'wpcdt_clean', $var );
    } else {
        $data = is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
        return wp_unslash($data);
    }
}

/**
 * Sanitize number value and return fallback value if it is blank
 * 
 * @package Countdown Timer Ultimate Pro
 * @since 1.0.0
 */
function wpcdt_clean_numbers( $var, $fallback = null ) {
	$data = !empty( $var ) ? absint($var) : '';
	return ( empty($data) && $fallback ) ? $fallback : $data;
}

/**
 * Sanitize numeric value and return fallback value if it is blank
 * 
 * @package Countdown Timer Ultimate Pro
 * @since 1.0
 */
function wpcdt_clean_numeric( $var, $fallback = null ) {
    $data = !empty( $var ) ? abs($var) : '';
    return ( empty($data) && $fallback ) ? $fallback : $data;
}

/**
 * Sanitize color value and return fallback value if it is blank
 * 
 * @package Countdown Timer Ultimate Pro
 * @since 1.0.0
 */
function wpcdt_clean_colors( $color, $fallback = null ) {
    $data = sanitize_hex_color($color);
	return ( empty($data) && $fallback ) ? $fallback : $data;
}

/**
 * Function to add array after specific key
 * 
 * @package Countdown Timer Ultimate
 * @since 1.0.0
 */
function wpcdt_add_array(&$array, $value, $index, $from_last = false) {

	if( is_array($array) && is_array($value) ) {

		if( $from_last ) {
			$total_count 	= count($array);
			$index 			= (!empty($total_count) && ($total_count > $index)) ? ($total_count-$index): $index;
		}

		$split_arr  = array_splice($array, max(0, $index));
		$array      = array_merge( $array, $value, $split_arr);
	}

	return $array;
}