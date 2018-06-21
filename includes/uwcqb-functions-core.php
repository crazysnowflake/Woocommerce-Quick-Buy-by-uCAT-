<?php
/**
 * uWC_Quick_Buy Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @author 		Elena Zhyvohliad
 * @category 	Core
 * @package 	uWC_Quick_Buy/Functions
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include core functions (available in both admin and frontend).
include('uwcqb-template-functions.php');
include('uwcqb-template-hooks.php');

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 * @param string|array $var
 * @return string|array
 */
function uwcqb_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'uwcqb_clean', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}

if ( ! function_exists( 'is_ajax' ) ) {

	/**
	 * is_ajax - Returns true when the page is loaded via ajax.
	 * @return bool
	 * @since  1.0.1
	 */
	function is_ajax() {
		return defined( 'DOING_AJAX' );
	}
}
