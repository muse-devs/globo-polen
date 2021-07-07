<?php
defined( 'ABSPATH' ) || die;
?>
<div class="wrap">
    <div>
        <a href="#" id="polen_create_first_order" onclick="polen_create_first_order()">Criar uma Order</a>
    </div>
    <div class="clear"></div>
    <form id="form-create-first-order">
        <input type="hidden" name="product_id" />
    </form>
</div>
<script>
document.getElementById('polen_create_first_order').addEventListener("click", function(evt){
    evt.preventDefault();
    let url = '<?= admin_url('admin-ajax.php'); ?>?action=create_first_order';
    jQuery.post(url,jQuery(formCreateName).serialize(),
    function(data){

    });
});
</script>