(function($) {
    $(document).ready(function() {
        if( $('#PolenVendorTabs').length > 0 ) {
            $('#user_login').parent().parent().hide();
            $('#send_user_notification').prop('checked', false);

            $('#PolenVendorTabs').tabs();

            $('.natureza-juridica-pj').hide();
            $('.natureza-juridica-pf').hide();

            if( $('#polen_natureza_juridica').val() == 'PJ' ) {
                $('.natureza-juridica-pj').show();
                $('.natureza-juridica-pf').hide();
            } else if( $('#polen_natureza_juridica').val() == 'PF' ) {
                $('.natureza-juridica-pf').show();
                $('.natureza-juridica-pj').hide();
            }

            $('#polen_natureza_juridica').on( 'change', function(e) {
                if( $('#polen_natureza_juridica').val() == 'PJ' ) {
                    $('.natureza-juridica-pj').show();
                    $('.natureza-juridica-pf').hide();
                } else if( $('#polen_natureza_juridica').val() == 'PF' ) {
                    $('.natureza-juridica-pf').show();
                    $('.natureza-juridica-pj').hide();
                }
            });

            $('.polen-cnpj').mask("99.999.999/9999-99");
            $('.polen-cpf').mask("999.999.999-99");
            $('.polen-phone').mask("(99) 99999-9999");
            $('.polen-cep').mask("99999-999");

            if( $('#role').val() == 'user_talent' || $('#role').val() == 'user_charity' ) {
                $("#metaboxSellerData").show();
            } else {
                $("#metaboxSellerData").hide();
            }

            if( $('#talent_category').length > 0 ) {
                $('#talent_category').select2({ 
                    placeholder: 'Selecione a(s) categoria(s)',
                    maximumSelectionLength: 5,
                    allowClear: true,
                    width: '100%', 
                });
            }

            if( $('#charity_enable').length > 0 ) {
                $('#charity_enable').on( 'click', function() {
                    if( $('#charity_enable').is(":checked") ) {
                        $('#tr_charity_to').show();
                    } else {
                        $('#tr_charity_to').hide();
                    }
                });
            }

            if( $( '#charity_to').length > 0 ) {
                $('#charity_to').select2({ 
                    placeholder: 'Informe a instituição',
                    selectOnClose: true,
                    width: '100%', 
                });
            }
        }
    });

    $(document).on('change', '#role', function() {
        if( $(this).val() == 'user_talent' ) {
            $("#metaboxSellerData").show();
        } else {
            $("#metaboxSellerData").hide();
        }
    });

    $(document).on('focusout', '#email', function() {
        $('#user_login').val( $('#email').val() );
        $('#store_email').val( $('#email').val() );
    });
})(jQuery);