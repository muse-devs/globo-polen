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
            if (!confirm("Tem certeza que deseja excluir esse cartão?")) {
                return;
            }
            braspagRemove( $(this).attr('remove-id') );
            let remove_id = document.getElementById('#payment-' + $(this).attr('remove-id'));
            remove_id.parentNode.removeChild(remove_id);
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
                if( $( '.payment-method-item' ).length === 0 ) {
                    window.location.reload();
                }
            },
            error: function( error ) {
                console.log( error );
            }
        }).done(function() {
            $( '.braspag-remove-payment' ).bind( 'click' );
        });
    }
})(jQuery);