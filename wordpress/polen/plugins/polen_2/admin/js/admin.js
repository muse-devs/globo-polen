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
                    addRequired($('#cnpj-natureza-juridica-pj'));
                    removeRequired($('#cpf-natureza-juridica-pf'));
                } else if( $('#polen_natureza_juridica').val() == 'PF' ) {
                    $('.natureza-juridica-pf').show();
                    $('.natureza-juridica-pj').hide();
                    removeRequired($('#cnpj-natureza-juridica-pj'));
                    addRequired($('#cpf-natureza-juridica-pf'));
                }
            });

            $('.polen-cnpj').mask("99.999.999/9999-99");
            $('.polen-cpf').mask("999.999.999-99");
            $('.polen-phone').mask("(99) 99999-9999");
            $('.polen-cep').mask("99999-999");

            if( $('#role').val() == 'user_talent' || $('#role').val() == 'user_charity' ) {
                $(".metaboxSellerData").map( (index, element) => {
                    $(element).show();
                });
                // required do slug do talent
                if( $('#tr_talent_alias').length > 0 ) {
                    addRequired( $('#tr_talent_alias') );
                }

                if( $('#polen_natureza_juridica').val() == 'PJ') {
                    addRequired($('#cnpj-natureza-juridica-pj'));
                    removeRequired($('#cpf-natureza-juridica-pf'));
                } else {
                    removeRequired($('#cnpj-natureza-juridica-pj'));
                    addRequired($('#cpf-natureza-juridica-pf'));
                }

            } else {
                $(".metaboxSellerData").map( (index, element ) => {
                    $(element).hide();
                });
                //Tirando o required do slug do talent
                if( $('#tr_talent_alias').length > 0 ) {
                    removeRequired( $('#tr_talent_alias') );
                }
                removeRequired($('#cnpj-natureza-juridica-pj'));
                removeRequired($('#cpf-natureza-juridica-pf'));
            }

            if( $( '#polen_banco').length > 0 ) {
                $('#polen_banco').select2({ 
                    placeholder: 'Selecione o Banco',
                    selectOnClose: true,
                    width: '100%', 
                });
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
            // required do slug do talent
            if( $('#tr_talent_alias').length > 0 ) {
                addRequired( $('#tr_talent_alias') );
            }

            if( $('#polen_natureza_juridica').val() == 'PJ') {
                addRequired($('#cnpj-natureza-juridica-pj'));
                removeRequired($('#cpf-natureza-juridica-pf'));
            } else {
                removeRequired($('#cnpj-natureza-juridica-pj'));
                addRequired($('#cpf-natureza-juridica-pf'));
            }

        } else {
            $("#metaboxSellerData").hide();
            //Tirando o required do slug do talent
            if( $('#tr_talent_alias').length > 0 ) {
                removeRequired( $('#tr_talent_alias') );
            }
            removeRequired($('#cnpj-natureza-juridica-pj'));
            removeRequired($('#cpf-natureza-juridica-pf'));
        }
    });

    $(document).on('focusout', '#email', function() {
        $('#user_login').val( $('#email').val() );
        $('#store_email').val( $('#email').val() );
    });
})(jQuery);

function addRequired( element ) {
    element.addClass('form-field form-required');
}

function removeRequired( element ) {
    element.removeClass('form-field form-required');
}