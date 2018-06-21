<?php
/**
 * Load assets
 *
 * @author      Elena Zhyvohliad
 * @package     uWC_Quick_Buy/Classes/Assets
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'uWC_Quick_Buy_Assets' ) ) :

/**
 * uWC_Quick_Buy_Assets Class.
 */
class uWC_Quick_Buy_Assets{

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'add_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );
	}

	/**
	 * Enqueue styles.
	 */
	public function add_styles() {
        // Theme stylesheet.
        wp_register_style( 'uwcqb-quick_buy', uWCQB()->plugin_url(). '/assets/css/quick-buy.css', array(), UWCQB_VERSION );
	}


	/**
	 * Enqueue scripts.
	 */
	public function add_scripts() {
        wp_register_script( 'uwcqb-quick_buy', uWCQB()->plugin_url(). '/assets/js/buick-buy.js', array('jquery'), UWCQB_VERSION );

        wp_register_script( 'uwc-gateway-ppec-generate-cart', uWCQB()->plugin_url(). '/assets/js/wc-gateway-ppec-generate-cart.js', array( 'jquery' ), wc_gateway_ppec()->version, true );
        wp_localize_script( 'uwc-gateway-ppec-generate-cart', 'wc_ppec_context',
            array(
                'generate_cart_nonce' => wp_create_nonce( '_wc_ppec_generate_cart_nonce' ),
                'ajaxurl'             => WC_AJAX::get_endpoint( 'wc_ppec_generate_cart' ),
            )
        );
	}

}

endif;

return new uWC_Quick_Buy_Assets();
