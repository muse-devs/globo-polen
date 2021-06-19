<?php

/**
 * @version 3.7.0
 */
defined('ABSPATH') || exit;

function get_icon($bool)
{
	if ($bool) {
		return Icon_Class::polen_icon_check_o();
	} else {
		return Icon_Class::polen_icon_exclamation_o();
	}
}

$notes = $order->get_customer_order_notes();
$order_number = $order->get_order_number();
$order_status = $order->get_status();

$order_item_cart = \Polen\Includes\Cart\Polen_Cart_Item_Factory::polen_cart_item_from_order( $order );
$email_billing = $order_item_cart->get_email_to_video();

$order_array = Order_Class::polen_get_order_flow_obj($order_number, $order_status, $email_billing);
?>
<div class="row">
	<div class="col-md-12 mb-5">
		<h1>Seu vídeo foi solicitado com Sucesso</h1>
	</div>
	<div class="col-md-12">
		<?php polen_get_order_flow_layout($order_array); ?>
	</div>
</div>

<?php
if (!is_user_logged_in()) :
?>

	<div class="row my-3">
		<div class="col-12">
			<a href="/register" class="btn btn-outline-light btn-lg btn-block">Criar uma conta</a>
		</div>
	</div>

<?php
else :
?>

	<div class="row my-3">
		<div class="col-12">
			<a href="<?php echo wc_get_account_endpoint_url('view-order') . $order_number . '/'; ?>" class="btn btn-outline-light btn-lg btn-block">Acompanhar pedido</a>
		</div>
	</div>

<?php
endif;
 
$items = $order->get_items();
$item = array_pop( $items );
$product = $item->get_product();
$category_ids = $product->get_category_ids();
$category_id = array_pop( $category_ids );
$category_name = get_term_by( 'id', $category_id, 'product_cat' );
?>
<script>
gtag('event', 'purchase', {
  "transaction_id": "<?= $order->get_id(); ?>",
  "affiliation": "Polen.me",
  "value": <?= $order->get_total(); ?>,
  "currency": "BRL",
  "tax": 0.0,
  "shipping": 0,
  "items": [
    {
      "id": "<?= $item->get_product_id(); ?>",
      "name": "<?= $item->get_name(); ?>",
      "category": "<?= $category_name->name; ?>",
      "list_position": 1,
      "quantity": <?= $item->get_quantity(); ?>,
      "price": "<?=  $item->get_subtotal(); ?>"
    }
  ]
});
</script>
