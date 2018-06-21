/* global wc_ppec_context */
;(function( $, window, document ) {
	'use strict';

	$('.uwcqb-checkout-buttons__button').each(function(){
		var $form = $(this).closest('form'),
			$button = $(this);

        var button_enabled = true;

        var toggle_button_availability = function( available ) {
            if ( available ) {
                button_enabled = true;
                $button.css( {
                    'cursor': '',
                    '-webkit-filter': '', // Safari 6.0 - 9.0
                    'filter': '',
                } );
            } else {
                button_enabled = false;
                $button.css( {
                    'cursor': 'not-allowed',
                    '-webkit-filter': 'grayscale( 100% )', // Safari 6.0 - 9.0
                    'filter': 'grayscale( 100% )',
                } );
            }
        }

        var get_attributes = function() {
            var select = $form.find( '.variations select' ),
                data   = {},
                count  = 0,
                chosen = 0;

            select.each( function() {
                var attribute_name = $( this ).data( 'attribute_name' ) || $( this ).attr( 'name' );
                var value	  = $( this ).val() || '';

                if ( value.length > 0 ) {
                    chosen++;
                }

                count++;
                data[ attribute_name ] = value;
            } );

            return {
                'count'      : count,
                'chosenCount': chosen,
                'data'       : data
            };
        };

        var get_fields = function() {
            var fields = $form.find( '.uwcqb_field' ),
                data   = {},
                count  = 0,
                chosen = 0;

            fields.each( function() {
                var attribute_name = $( this ).data( 'attribute_name' ) || $( this ).attr( 'name' );
                var value	  = $( this ).val() || '';

                if ( value.length > 0 ) {
                    chosen++;
                }

                count++;
                data[ attribute_name ] = value;
            } );

            return {
                'count'      : count,
                'chosenCount': chosen,
                'data'       : data
            };
        };

        // It's a variations form, button availability should depend on its events
        if ( $form.hasClass('.variations_form' ) ) {
            toggle_button_availability( false );

            $form
                .on( 'show_variation', function( event, form, purchasable ) {
                    toggle_button_availability( purchasable );
                } )
                .on( 'hide_variation', function() {
                    toggle_button_availability( false );
                } );
        }

        $button.click( function( event ) {
            event.preventDefault();

            if ( ! button_enabled ) {
                return;
            }

            toggle_button_availability( false );

            var data = {
                'nonce':       wc_ppec_context.generate_cart_nonce,
                'qty':         $( '.quantity .qty', $form ).val(),
                'attributes':  $form.hasClass( '.variations_form' ) ? get_attributes().data : [],
                'add-to-cart': $( '[name=add-to-cart]', $form ).val(),
            };

            if( $( '.uwcqb_field', $form).length ){
                data['uwcqb_field'] = get_fields().data
			}

            var href = $(this).attr( 'href' );

            $.ajax( {
                type:    'POST',
                data:    data,
                url:     wc_ppec_context.ajaxurl,
                success: function( response ) {
                    window.location.href = href;
                }
            } );
        } );
	});



})( jQuery, window, document );
