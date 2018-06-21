<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Handle frontend forms.
 *
 * @class 		uWC_Quick_Buy_Form_Handler
 * @version		1.0.0
 * @package		uWC_Quick_Buy/Classes/
 * @category	Class
 * @package     uWC_Quick_Buy
 * @author      Elena ZHyvohliad
 */
class uWC_Quick_Buy_Form_Handler {

	/**
	 * Hook in methods.
	 */
	public static function init() {
        add_filter('woocommerce_add_cart_item_data', [__CLASS__, 'add_cart_item_data'], 10, 4);
        add_filter('woocommerce_get_item_data', [__CLASS__, 'get_item_data'], 10, 2);

        // Add meta to order
        add_action('woocommerce_checkout_create_order_line_item', array(__CLASS__, 'create_order_line_item'), 10, 4);

        add_action( 'wp_loaded', array( __CLASS__, 'send_email_swap' ), 20 );
	}

    public static function add_cart_item_data ( $cart_item_data, $product_id, $variation_id, $quantity ) {

        if( isset($_REQUEST['uwcqb_field'])){
            $cart_item_data['uwcqb_field'] = $_REQUEST['uwcqb_field'];
        }
        return $cart_item_data;
    }

    public static function get_item_data ( $item_data, $cart_item ) {
        if( isset($cart_item['uwcqb_field'])) {
            foreach ($cart_item['uwcqb_field'] as $field_name => $field_val) {
                $item_data[] = [
                    'key' => $field_name,
                    'display' => $field_val
                ];
            }
        }

        return $item_data;
    }


    public static function create_order_line_item($item, $cart_item_key, $values, $order) {
        if( isset($values['uwcqb_field'])) {
            foreach ($values['uwcqb_field'] as $field_name => $field_val) {
                $item->add_meta_data($field_name, $field_val);
            }
        }
    }

    public static function send_email_swap(){
        if (  !isset( $_REQUEST['swap'] ) || !isset( $_REQUEST['email'] ) ) {
            return;
        }
        wc_nocache_headers();

        $email  = $_REQUEST['email'];
        $type   = $_REQUEST['swap_type'];
        $from   = $_REQUEST['quantity_from'];
        $to     = $_REQUEST['quantity_to'];

        $swap_from   = isset($_REQUEST['swap_from']) ? $_REQUEST['swap_from'] : '';
        $swap_to     = isset($_REQUEST['swap_to']) ? $_REQUEST['swap_to'] : '';
        $fields = isset($_REQUEST['uwcqb_field']) ? $_REQUEST['uwcqb_field'] : [];

        $blogname   = get_option( 'blogname' );
        $adminemail = get_option( 'admin_email' );

        $headers = "From: {$blogname} <{$adminemail}>" . "\r\n";

        $body = 'Swap: ' . $type  . "\r\n";
        $body .= $swap_from . ': ' . $from  . "\r\n";
        $body .= $swap_to . ': ' . $to  . "\r\n";

        foreach ($fields as $f_name => $field){
            $body .= $f_name .': ' . $field  . "\r\n";
        }

        wp_mail($email, 'Swap: ' . $type, $body, $headers);

    }
}

uWC_Quick_Buy_Form_Handler::init();
