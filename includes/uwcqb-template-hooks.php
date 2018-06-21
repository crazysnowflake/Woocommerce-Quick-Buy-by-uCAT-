<?php
/**
 * uWC_Quick_Buy Template Hooks
 *
 * Action/filter hooks used for uWC_Quick_Buy functions/templates.
 *
 * @author 		Elena Zhyvohliad
 * @category 	Core
 * @package     uWC_Quick_Buy\Functions
 * @version     1.0.0
 */
defined( 'ABSPATH' ) || exit;

/**
 * Product Add to cart.
 *
 * @see uwcqb_template_single_add_to_cart()
 * @see uwcqb_simple_add_to_cart()
 * @see uwcqb_grouped_add_to_cart()
 * @see uwcqb_variable_add_to_cart()
 * @see uwcqb_external_add_to_cart()
 * @see uwcqb_single_variation()
 * @see uwcqb_single_variation_add_to_cart_button()
 */

add_action( 'uwcqb_single_product_summary', 'uwcqb_template_single_add_to_cart', 30 );
add_action( 'uwcqb_simple_add_to_cart', 'uwcqb_simple_add_to_cart', 30 );
add_action( 'uwcqb_grouped_add_to_cart', 'uwcqb_grouped_add_to_cart', 30 );
add_action( 'uwcqb_variable_add_to_cart', 'uwcqb_variable_add_to_cart', 30 );
add_action( 'uwcqb_external_add_to_cart', 'uwcqb_external_add_to_cart', 30 );
add_action( 'uwcqb_single_variation', 'uwcqb_single_variation', 10 );
add_action( 'uwcqb_single_variation', 'uwcqb_single_variation_add_to_cart_button', 20 );

add_action('uwcqb_after_add_to_cart_form', 'uwcqb_after_add_to_cart_form');