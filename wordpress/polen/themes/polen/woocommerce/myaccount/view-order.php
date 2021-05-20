<?php

/**
 * @version 3.0.0
 */
defined('ABSPATH') || exit;

$notes = $order->get_customer_order_notes();
$order_number = $order->get_order_number();
$order_status = $order->get_status();

$order_array = Order_Class::polen_get_order_flow_obj($order_number, $order_status);
?>
<div class="row">
	<div class="col-md-12 mb-5">
		<h1>Acompanhar pedido</h1>
	</div>
	<div class="col-md-12">
		<?php polen_get_order_flow_layout($order_array); ?>
	</div>
</div>

<?php

use \Polen\Includes\Polen_Order;

$order_is_completed = Polen_Order::is_completed($order);
$url_watch_video = $order_is_completed == true ? polen_get_link_watch_video_by_order_id($order_number) : '';
?>

<?php if ($order_is_completed) : ?>
	<div class="row my-3">
		<div class="col-12">
			<a href="<?php echo $url_watch_video; ?>" class="btn btn-outline-light btn-lg btn-block">Assistir vídeo</a>
		</div>
	</div>
<?php endif; ?>
