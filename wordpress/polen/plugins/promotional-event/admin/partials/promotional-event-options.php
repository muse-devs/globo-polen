<form method="post" id="create" action="/">
    <h1>Criar Cupons</h1>
    <p>Adicionar a quantidade de cupons</p>
    <div class="form-row" style="display: flex;align-items:center;">
        <div class="form-group col-md-2">
            <input type="text" class="form-control" id="inputEmail4" placeholder="Quantidade de Cupons atuais">
        </div>
        <div class="form-group col-md-8 range-slider">
            <input class="range-slider__range" id="qty" type="range" name="qty" value="0" step="10" max="200">
            <span class="range-slider__value">0</span>
        </div>
        <div class="form-group col-md-2">
            <button type="submit" class="btn btn-primary">Adicionar cupons</button>
        </div>
    </div>
</form>

<script>

    jQuery( "#create" ).submit(function( event ) {
        event.preventDefault();
        let qty = jQuery('#qty').val();
        jQuery.ajax({
            type: 'post',
            dataType: 'json',
            url: "<?php echo admin_url('admin-ajax.php'); ?>",
            data: {
                action: 'create_coupons',
                qty: qty,
            },
            success: function (response) {
                console.log('aqui');
                console.log(response);
            }
        }).always(function() {
           console.log('ok');
        });
    });

</script>