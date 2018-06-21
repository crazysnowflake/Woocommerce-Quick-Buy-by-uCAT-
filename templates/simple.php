<?php
/**
 * Simple product quick buy
 *
 * This template can be overridden by copying it to yourtheme/uwcqb/simple.php.
 *
 * @author 		Elena Zhyvohliad
 * @package 	uWC_Quick_Buy/Templates
 * @version     1.0.0
 * @global     $product WC_Product
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

$availability = $product->get_availability();
 ?>

	<form class="cart quick-buy-form" action="<?php echo esc_url( get_permalink() ); ?>" method="post" enctype='multipart/form-data' data-price="<?php echo $product->get_price(); ?>">

        <?php
        $thumbnail    = $product ? $product->get_image( 'thumbnail', array( 'title' => '' ), false ) : '';
        if ($thumbnail){
        ?>
        <div class="features-coloured-icon-boxes-iconcontainer imaged" data-content-item-container="true">
            <?php
            echo wp_kses_post( $thumbnail );
            ?>
        </div>
        <?php }else{?>
            <div class="features-coloured-icon-boxes-iconcontainer" data-content-item-container="true">
                <i data-cp-fa="true" class="features-coloured-icon fa fa-money"></i>
            </div>
        <?php }?>
        <h4><?php echo $product->get_title();  ?></h4>
        <?php
        if ( ! empty( $availability['availability'] ) ) {
           ?>
            <div class="field-amount">
                <?php echo $availability['availability'];  ?>
            </div>
        <?php
        }
        ?>

        <div class="field-price">
            <?php echo $product->get_price_html(); ?>
           <!-- <span class="label-desc">per 1 million</span>-->
        </div>

        <div class="field_qty">

            <div class="input-group input-group-left">
                <div class="input-group-prepend">
                    <span class="input-group-text">Q</span>
                </div>
                <?php
                uwcqb_quantity_input( array(
                    'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
                    'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
                    'input_value' => '',
                    'placeholder'  => __( 'Quantity', 'uwcqb' ),
                ) );
                ?>
            </div>
            <div class="input-group input-group-right">
                <?php
                uwcqb_quantity_input( array(
                    'placeholder'  => '0',
                    'input_name'  => 'quantity_price',
                    'min_value'   => 0,
                    'step'        => '0.01',
                    'input_value' => ''
                ) );
                ?>
                <div class="input-group-append">
                    <span class="input-group-text"><?php echo get_woocommerce_currency_symbol(); ?></span>
                </div>
            </div>
            <span class="selected-currency"><?php echo get_woocommerce_currency_symbol(); ?></span>
        </div>

        <?php foreach ($fields as $field){ ?>
            <div><input type="text" required name="uwcqb_field[<?php echo $field; ?>]" data-attribute_name="<?php echo $field; ?>" class="uwcqb_field" placeholder="<?php echo $field; ?>"></div>
        <?php } ?>


		<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button btn button blue"><?php echo _e('Buy now', 'uwcqb'); ?></button>

        <?php do_action( 'uwcqb_after_add_to_cart_form' ); ?>
    </form>

