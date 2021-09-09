jQuery(document).ready(function($) {
    /**
     * Carregar bloco de informação para cada metodo de pagamento
     */
    $('form.checkout').on('change', 'input[name^="payment_method"]', function() {
        let payment_method = $('input[name="payment_method"]:checked').val();
        if (payment_method === "wc_pagarme_pix_payment_geteway") {
            $('#pix-payment-custom').show();
            $('#bolet-payment-custom').hide();
        } else if (payment_method === "pagarme-banking-ticket") {
            $('#pix-payment-custom').hide();
            $('#bolet-payment-custom').show();
        } else {
            $('#bolet-payment-custom').hide();
            $('#pix-payment-custom').hide();
        }
    });
});