jQuery(document).ready(function($) {

    /**
     * Carregar bloco de informação para cada metodo de pagamento
     */
    hide_filed();

    $('form.checkout').on('change', 'input[name^="payment_method"]', function() {
        let payment_method = $('input[name="payment_method"]:checked').val();
        if (payment_method === "wc_pagarme_pix_payment_geteway") {

            hide_filed();

        } else if (payment_method === "pagarme-banking-ticket") {
            $('#pix-payment-custom').hide();
            $('#bolet-payment-custom').show();

            show_fields();

        } else {
            $('#bolet-payment-custom').hide();
            $('#pix-payment-custom').hide();

            show_fields();
        }
    });

    function show_fields()
    {
        $('#billing_address_1').val('');
        $('#billing_city').val('');
        $('#billing_postcode').val('');
        $('#billing_cellphone').val('');
        $('#billing_sex').val('').change();
        $('#billing_number').val('');
        $('#billing_neighborhood').val('');
        $('#billing_birthdate').val('');


        // Ocultar campos
        $('#billing_postcode_field').show();
        $('#billing_address_1_field').show();
        $('#billing_number_field').show();
        $('#billing_neighborhood_field').show();
        $('#billing_city_field').show();
        $('#billing_sex_field').show();
        $('#billing_birthdate_field').show();
        $('#billing_state_field').show();
    }

    function hide_filed()
    {
        $('#billing_address_1').val('Rua Pasteur 463,');
        $('#billing_city').val('Curitiba');
        $('#billing_postcode').val('80025-080');
        $('#billing_cellphone').val('(85)997785-361');
        $('#billing_sex').val('Feminino').change();
        $('#billing_state').val('PR').change();
        $('#billing_number').val('1401');
        $('#billing_neighborhood').val('Batel');
        $('#billing_birthdate').val('10/10/1996');

        // Ocultar campos
        $('#billing_postcode_field').hide();
        $('#billing_address_1_field').hide();
        $('#billing_number_field').hide();
        $('#billing_neighborhood_field').hide();
        $('#billing_city_field').hide();
        $('#billing_sex_field').hide();
        $('#billing_birthdate_field').hide();
        $('#billing_state_field').hide();
    }

});