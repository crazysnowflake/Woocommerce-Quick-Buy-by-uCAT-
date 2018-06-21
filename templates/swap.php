<?php
/**
 * Swap product quick buy
 *
 * This template can be overridden by copying it to yourtheme/uwcqb/swap.php.
 *
 * @author 		Elena Zhyvohliad
 * @package 	uWC_Quick_Buy/Templates
 * @version     1.0.0
 * @global     $product WC_Product
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$products = [];
foreach ($ids as $i => $id) {

    $product = wc_get_product($id);
    if (!$product->is_purchasable()) {
        continue;
    }
    $products[] = $product;
    if( count($products) === 2 ) break;
}
!empty($products) || exit;


$product_from = $products[0];
$product_to   = $products[1];
 ?>

	<form class="cart quick-swap-form" action="<?php echo esc_url( get_permalink() ); ?>"
          method="post" enctype='multipart/form-data'
          data-swaprate_from="<?php echo isset($swaprate[0]) ? $swaprate[0] : 1; ?>"
          data-swaprate_to="<?php echo isset($swaprate[1]) ? $swaprate[1] : 1; ?>" >

        <div class="features-coloured-icon-boxes-iconcontainer" data-content-item-container="true">
            <i data-cp-fa="true" class="features-coloured-icon fa fa-exchange"></i>
        </div>
        <h4><?php _e('Swap', 'uwcqb');  ?></h4>

        <input type="hidden" name="email" value="<?php echo $email; ?>">

        <div class="field-amount">
            <?php _e('What gold do you have?', 'uwcqb');  ?>
        </div>
        <div class="swap-labels">
            <label>
                <input type="hidden" name="swap_from" value="<?php echo isset($swap[0]) ? $swap[0] : $product_from->get_title();  ?>">
                <input type="radio" name="swap_type" checked value="<?php echo isset($swap[0]) ? $swap[0] : $product_from->get_title();  ?> > <?php echo isset($swap[0]) ? $swap[0] : $product_from->get_title();  ?>">
                <?php echo isset($swap[0]) ? $swap[0] : $product_from->get_title();  ?>
            </label>
            <label>
                <input type="hidden" name="swap_to" value="<?php echo isset($swap[1]) ? $swap[1] : $product_to->get_title();  ?>">
                <input type="radio" name="swap_type" value="<?php echo isset($swap[1]) ? $swap[1] : $product_to->get_title();  ?> > <?php echo isset($swap[0]) ? $swap[0] : $product_from->get_title();  ?>">
                <?php echo isset($swap[1]) ? $swap[1] : $product_to->get_title();  ?>
            </label>
        </div>

        <div class="field_qty">

            <div class="input-group input-group-left">
                <div class="input-group-prepend">
                    <span class="input-group-text">Q</span>
                </div>
                <?php
                uwcqb_quantity_input( array(
                    'placeholder'  => isset($swap[0]) ? $swap[0] : $product_from->get_title(),
                    'input_name'  => 'quantity_from',
                    'min_value'   => 0,
                    'input_value' => '',
                ) );
                ?>
            </div>
            <div class="input-group input-group-right">
                <?php
                uwcqb_quantity_input( array(
                    'placeholder'  => isset($swap[1]) ? $swap[1] : $product_to->get_title(),
                    'input_name'  => 'quantity_to',
                    'min_value'   => 0,
                    'input_value' => '',
                ) );
                ?>
                <div class="input-group-append">
                    <span class="input-group-text">Q</span>
                </div>
            </div>
            <span class="selected-currency"><?php echo get_woocommerce_currency_symbol(); ?></span>
        </div>
        <?php foreach ($fields as $field){ ?>
            <div><input type="text" required name="uwcqb_field[<?php echo $field; ?>]" data-attribute_name="<?php echo $field; ?>" class="uwcqb_field" placeholder="<?php echo $field; ?>"></div>
        <?php } ?>


		<button type="submit" name="swap" value="1" class="single_add_to_cart_button btn button yellow"><?php echo _e('Swap now', 'uwcqb'); ?></button>

    </form>

