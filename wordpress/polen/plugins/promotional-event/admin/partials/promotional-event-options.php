<form method="post" id="create" action="/">
    <h1>Criar Cupons</h1>
    <p>Adicionar a quantidade de cupons</p>
    <div class="form-row" style="display: flex;align-items:center;">
        <div class="form-group col-md-2">
            <input type="text" class="form-control" id="inputEmail4"
                   placeholder="Quantidade de Cupons atuais" value="<?php echo $count; ?>" disabled="">
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


<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
        <strong class="mr-auto">Cupons adicionados</strong>
        <small>1 min atrás</small>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        Novos cupons foram inseridos na base de dados
    </div>
</div>

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
                jQuery('.toast').fadeOut();
            }
        }).always(function() {
           jQuery('.toast').fadeIn(1000).addClass('show');
        });
    });

    jQuery('.close').click(function() {
        jQuery('.toast').fadeOut();
    });

</script>