<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 *
 * @class 		uWC_Quick_Buy_Shortcodes
 * @version		1.0.0
 * @package		uWC_Quick_Buy/Classes
 * @category	Class
 * @author 		Elena Zhyvohliad
 */
class NYSTB_Shortcodes {
	/**
	 * Init shortcodes.
	 */
	public static function init() {
		$shortcodes = array(
			'quick-buy'                    => __CLASS__ . '::quick_buy'
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( apply_filters( "uwcqb_{$shortcode}_shortcode_tag", $shortcode ), $function );
		}
	}

	/**
	 * Shortcode Wrapper.
	 *
	 * @param string[] $function Callback function.
	 * @param array    $atts     Attributes. Default to empty array.
	 * @param array    $wrapper  Customer wrapper data.
	 *
	 * @return string
	 */
	public static function shortcode_wrapper(
		$function,
		$atts = array(),
		$wrapper = array(
			'class'  => 'uwcqb_wrapper features-coloured-icon-boxes-innerrow flexbox-list dark-text',
			'before' => null,
			'after'  => null,
		)
	) {
	    if( isset($atts['wrapperclass'])){
            $wrapper['class'] .= ' ' . $atts['wrapperclass'];
        }
		ob_start();

		// @codingStandardsIgnoreStart
		echo empty( $wrapper['before'] ) ? '<div class="' . esc_attr( $wrapper['class'] ) . '">' : $wrapper['before'];
		call_user_func( $function, $atts );
		echo empty( $wrapper['after'] ) ? '</div>' : $wrapper['after'];
		// @codingStandardsIgnoreEnd

		return ob_get_clean();
	}

	/**
	 * Calendar shortcode.
	 *
	 * @return string
	 */
	public static function quick_buy($atts) {
		wp_enqueue_script( 'uwcqb-quick_buy' );
		wp_enqueue_style( 'uwcqb-quick_buy' );
		return self::shortcode_wrapper( array( __CLASS__, 'quick_buy_output' ), $atts);
	}

	/**
	 * Quick buy shortcode.
	 * @param $atts array
	 * @return string
	 */
	public static function quick_buy_output( $atts ) {
        global $product;

        $atts = shortcode_atts( array(
            'id'         => '',
            'fields'     => '',
            'swap'       => '',
            'swaprate'   => '',
            'email'      => get_option( 'admin_email' ),
            'class'      => 'features-coloured-icon-boxes-featurecol cp3cols',
        ), $atts, 'quick-buy' );
        $ids    = explode(',', $atts['id']);
        $fields = explode(',', $atts['fields']);
        $fields = array_map('trim', $fields);
        if( !empty($atts['id']) && !empty($ids) ){
            foreach ($ids as $id){

                $product = wc_get_product($id);
                if ( $product && ! $product->is_purchasable() ) {
                    continue;
                }
                ?>
                <div class="uwcqb_quick_buy_column <?php echo esc_attr( $atts['class'] ); ?>" id="product-<?php echo $product->get_id(); ?>" >
                    <?php
                    do_action( 'uwcqb_' . $product->get_type() . '_add_to_cart', $fields);
                    ?>
                </div>
                <?php

            }

            if( !empty($atts['swap']) || !empty($atts['email']) ){
                ?>
                    <div class="uwcqb_quick_buy_column <?php echo esc_attr( $atts['class'] ); ?>" id="product-<?php echo $product->get_id(); ?>" >
                        <?php
                        wc_get_template( 'swap.php', array(
                            'ids'    => $ids,
                            'fields'     => $fields,
                            'swaprate'   => array_map('trim', explode(':', $atts['swaprate'])),
                            'swap'       => array_map('trim', explode(',', $atts['swap'])),
                            'email'      => $atts['email'],
                        ),
                            uWCQB()->template_path(), uWCQB()->plugin_path() . '/templates/');
                        ?>
                    </div>
                <?php
            };
        };

	}
}

NYSTB_Shortcodes::init();