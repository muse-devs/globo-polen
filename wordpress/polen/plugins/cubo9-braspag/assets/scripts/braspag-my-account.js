(function($) {
    $(document).ready(function() {
        $(document).on( 'click', '.braspag-make-default-payment', function(e) {
            e.preventDefault();
            let myChild = $(this).children();
            let default_id = $(this).attr('default-id');

            braspagMakeDefault( default_id );

            $('#cards-accordion').find('.braspag-make-default-payment', function() {
                let brandName = $(this).attr('brand-name');
                let default_id = $(this).attr('default-id');
                $('#braspag-brand-name-' + default_id).html(brandName);
                if( $(this).hasClass( 'glyphicon-star' ) ) {
                    $(this).removeClass( 'glyphicon-star' ).addClass( 'glyphicon-ok' );
                }
            });

            if( myChild.hasClass( 'glyphicon-ok' ) ) {
                myChild.removeClass( 'glyphicon-ok' ).addClass( 'glyphicon-star' );
                let newBrandLabel = $( '#braspag-brand-name-' + default_id ).html() + ' (Padrão)';
                $('#braspag-brand-name-' + default_id ).html(newBrandLabel);
            } else if( myChild.hasClass( 'glyphicon-star' ) ) {
                myChild.removeClass( 'glyphicon-star' ).addClass( 'glyphicon-ok' );
            }
        });

        $(document).on( 'click', '.braspag-remove-payment', function(e) {
            e.preventDefault();
            braspagRemove( $(this).attr('remove-id') );
            let remove_id = '#payment-' + $(this).attr('remove-id');
            $(remove_id).remove();
            if( $( '#cards-accordion' ).children().length === 0 ) {
                $( '#cards-accordion' ).html('<div class="row"><div class="col-md-12 text-center"><h4>Nenhuma opção de pagamento cadastrada.</h4></div></div>');
            }
        });
    });

    function braspagMakeDefault( default_id ) {
        $.ajax({
            url: braspag.ajaxUrl,
            method: 'post',
            dataType: 'json',
            data: {
                'id': default_id,
                'action': 'braspag-default',
            },
            beforeSend: function() {
                $( '.braspag-make-default-payment' ).unbind( 'click' );
            },
            success: function( data ) {
                console.log( data );
            },
            error: function( error ) {
                console.log( error );
            }
        }).done(function() {
            $( '.braspag-make-default-payment' ).bind( 'click' );
        });
    }

    function braspagRemove( default_id ) {
        $.ajax({
            url: braspag.ajaxUrl,
            method: 'post',
            dataType: 'json',
            data: {
                'id': default_id,
                'action': 'braspag-remove',
            },
            beforeSend: function() {
                $( '.braspag-remove-payment' ).unbind( 'click' );
            },
            success: function( data ) {
                console.log( data );
            },
            error: function( error ) {
                console.log( error );
            }
        }).done(function() {
            $( '.braspag-remove-payment' ).bind( 'click' );
        });
    }
})(jQuery);