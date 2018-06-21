<?php
/**
 * Woocommerce Quick Buy Uninstall
 *
 * Uninstalling Woocommerce Quick Buy deletes user roles, pages, tables, and options.
 *
 * @author      Elena ZHyvohliad
 * @category    Core
 * @package     uWC_Quick_Buy/Uninstaller
 * @version     1.0.0
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb, $wp_version;

