<?php
defined( 'ABSPATH' ) || die;
?>
<div class="wrap">
    <div>
        <a href="#" id="polen_create_first_order" onclick="polen_create_first_order()">Criar uma Order</a>
    </div>
    <div class="clear"></div>
    <form id="form-create-first-order" action="./" method="post">
        <input type="hidden" name="product_id" value="<?= $product_id; ?>" />
    </form>
</div>
<script>
document.getElementById('polen_create_first_order').addEventListener("click", function(evt){
    evt.preventDefault();
    let url = '<?= admin_url('admin-ajax.php'); ?>';
    const formCreate = document.querySelector('#form-create-first-order');
	jQuery.ajax({
		type: "POST",
		url: url,
		data: {
			action: "create_first_order",
			product_id: <?= $product_id; ?>
		},
		success: function (response) {
            alert('bom')
		},
		error: function (jqXHR, textStatus, errorThrown) {
			alert('ruim');
		},
		complete: function () {
			
		},
	});
});
</script>