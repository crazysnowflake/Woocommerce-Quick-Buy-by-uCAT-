/* global wc_ppec_context */
;(function( $, window, document ) {
	'use strict';

	$('.quick-buy-form').on('change', 'input[name=quantity]', function (e) {
	    var $form = $(this).closest('form'),
            price = parseFloat($form.data('price')),
            qty   = parseInt($(this).val()),
	        total = price * qty;
        $('input[name=quantity_price]', $form).val(total);
    })
        .on('change', 'input[name=quantity_price]', function (e) {
        var $form = $(this).closest('form'),
            price = parseFloat($form.data('price')),
            total = parseFloat($(this).val()),
            qty   = parseInt(total / price);
        $('input[name=quantity]', $form).val(qty);
    });

    $('.quick-swap-form').on('change', 'input[name=quantity_from]', function (e) {
        var $form = $(this).closest('form'),
            from  = parseFloat($form.data('swaprate_from')),
            to    = parseFloat($form.data('swaprate_to')),
            a     = parseFloat($(this).val()),
            b     = parseInt( (a*to) / from);
        $('input[name=quantity_to]', $form).val(b);
        console.log(from, to, a, b);
    })
        .on('change', 'input[name=quantity_to]', function (e) {
        var $form = $(this).closest('form'),
            from  = parseFloat($form.data('swaprate_from')),
            to    = parseFloat($form.data('swaprate_to')),
            b     = parseFloat($(this).val()),
            a     = parseInt( (b*from) / to);
        $('input[name=quantity_from]', $form).val(a);
    });



})( jQuery, window, document );
