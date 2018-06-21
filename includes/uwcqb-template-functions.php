<?php
/**
 * uWC_Quick_Buy Template
 *
 * Functions for the templating system.
 *
 * @package  uWC_Quick_Buy\Functions
 * @version  1.0.0
 */

defined( 'ABSPATH' ) || exit;


if ( ! function_exists( 'uwcqb_simple_add_to_cart' ) ) {

    /**
     * Output the simple product add to cart area.
     */
    function uwcqb_simple_add_to_cart($fields) {
        wc_get_template( 'simple.php', ['fields' => $fields], uWCQB()->template_path(), uWCQB()->plugin_path() . '/templates/');
    }
}
if ( ! function_exists( 'uwcqb_grouped_add_to_cart' ) ) {

    /**
     * Output the grouped product add to cart area.
     */
    function uwcqb_grouped_add_to_cart() {
        global $product;

        $products = array_filter( array_map( 'wc_get_product', $product->get_children() ), 'wc_products_array_filter_visible_grouped' );

        if ( $products ) {
            wc_get_template( 'grouped.php', array(
                'grouped_product'    => $product,
                'grouped_products'   => $products,
                'quantites_required' => false,
            ),
                uWCQB()->template_path(), uWCQB()->plugin_path() . '/templates/');
        }
    }
}

if ( ! function_exists( 'uwcqb_variable_add_to_cart' ) ) {

    /**
     * Output the variable product add to cart area.
     */
    function uwcqb_variable_add_to_cart($fields) {
        global $product;

        // Enqueue variation scripts.
        wp_enqueue_script( 'wc-add-to-cart-variation' );

        // Get Available variations?
        $get_variations = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );

        // Load the template.
        wc_get_template( 'variable.php', array(
            'fields' => $fields,
            'available_variations' => $get_variations ? $product->get_available_variations() : false,
            'attributes'           => $product->get_variation_attributes(),
            'selected_attributes'  => $product->get_default_attributes(),
        ),
            uWCQB()->template_path(), uWCQB()->plugin_path() . '/templates/');
    }
}
if ( ! function_exists( 'uwcqb_external_add_to_cart' ) ) {

    /**
     * Output the external product add to cart area.
     */
    function uwcqb_external_add_to_cart() {
        global $product;

        if ( ! $product->add_to_cart_url() ) {
            return;
        }

        wc_get_template( 'external.php', array(
            'product_url' => $product->add_to_cart_url(),
            'button_text' => $product->single_add_to_cart_text(),
        ),
            uWCQB()->template_path(), uWCQB()->plugin_path() . '/templates/');
    }
}



if ( ! function_exists( 'uwcqb_after_add_to_cart_form' ) ) {
    function uwcqb_after_add_to_cart_form(){
        $gateways = WC()->payment_gateways->get_available_payment_gateways();

        if ( ! isset( $gateways['ppec_paypal'] ) ) {
            return;
        }

        wp_enqueue_script( 'uwc-gateway-ppec-generate-cart');

        $settings = wc_gateway_ppec()->settings;

        $express_checkout_img_url = apply_filters( 'woocommerce_paypal_express_checkout_button_img_url', sprintf( 'https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-%s.png', $settings->button_size ) );

        ?>
        <div class="wcppec-checkout-buttons woo_pp_cart_buttons_div">

            <a href="<?php echo esc_url( add_query_arg( array( 'startcheckout' => 'true' ), wc_get_page_permalink( 'cart' ) ) ); ?>" class="wcppec-checkout-buttons__button uwcqb-checkout-buttons__button">
                <img src="<?php echo esc_url( $express_checkout_img_url ); ?>" alt="<?php _e( 'Check out with PayPal', 'woocommerce-gateway-paypal-express-checkout' ); ?>" style="width: auto; height: auto;">
            </a>
        </div>
        <?php
    }
}


if ( ! function_exists( 'uwcqb_quantity_input' ) ) {

    /**
     * Output the quantity input for add to cart forms.
     *
     * @param  array           $args Args for the input.
     * @param  WC_Product|null $product Product.
     * @param  boolean         $echo Whether to return or echo|string.
     *
     * @return string
     */
    function uwcqb_quantity_input( $args = array(), $product = null, $echo = true ) {
        if ( is_null( $product ) ) {
            $product = $GLOBALS['product'];
        }

        $defaults = array(
            'input_id'    => uniqid( 'quantity_' ),
            'placeholder'  => 'Qty',
            'input_name'  => 'quantity',
            'input_value' => '1',
            'max_value'   => apply_filters( 'woocommerce_quantity_input_max', -1, $product ),
            'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 0, $product ),
            'step'        => apply_filters( 'woocommerce_quantity_input_step', 1, $product ),
            'pattern'     => apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' ),
            'inputmode'   => apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' ),
        );

        $args = apply_filters( 'woocommerce_quantity_input_args', wp_parse_args( $args, $defaults ), $product );

        // Apply sanity to min/max args - min cannot be lower than 0.
        $args['min_value'] = max( $args['min_value'], 0 );
        $args['max_value'] = 0 < $args['max_value'] ? $args['max_value'] : '';

        // Max cannot be lower than min if defined.
        if ( '' !== $args['max_value'] && $args['max_value'] < $args['min_value'] ) {
            $args['max_value'] = $args['min_value'];
        }

        ob_start();

        wc_get_template( 'quantity-input.php', $args,  uWCQB()->template_path(), uWCQB()->plugin_path() . '/templates/');

        if ( $echo ) {
            echo ob_get_clean(); // WPCS: XSS ok.
        } else {
            return ob_get_clean();
        }
    }
}
