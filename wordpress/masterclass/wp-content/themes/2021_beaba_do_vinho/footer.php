<?php wp_footer(); ?>

<script>
    jQuery(document).ready(function($) {
        function clean_form()
        {
            $('#street').val('');
            $('#neighborhood').val('');
            $('#city').val('');
            $('#state').find('option:selected').remove();
        }
        $('#billing_postcode').blur(function() {
            let postcode = $(this).val().replace(/\D/g, '');
            let check = /^[0-9]{8}$/;

            if (check.test(postcode)) {
                $('#billing_address_1').val('...');
                $('#billing_neighborhood').val('...');
                $('#billing_city').val('...');

                $.getJSON(`https://viacep.com.br/ws/${postcode}/json/?callback=?`, function(data) {
                    if (!('erro' in data)) {
                        $('#billing_address_1').val(data.logradouro);
                        $('#billing_neighborhood').val(data.bairro);
                        $('#billing_city').val(data.localidade);
                        $('#billing_state').val(data.uf).change();
                    } else {
                        clean_form();
                    }
                });
            }
        });
    });

</script>
</div>
</body>
</html>
