<?php

use Polen\Includes\Cart\Polen_Cart_Item_Factory;
use Polen\Includes\Debug;

session_start();
$order_id  = filter_input( INPUT_GET, 'order', FILTER_SANITIZE_STRING );
$order_key = filter_input( INPUT_GET, 'order_key', FILTER_SANITIZE_STRING );
$order = wc_get_order( $order_id );

if( empty( $order ) ) {
    wp_safe_redirect( event_promotional_url_home() );
    exit;
}

if( $order->get_order_key() != $order_key ) {
	wp_safe_redirect( event_promotional_url_code_validation() );
    exit;
}

$order_item_cart = Polen_Cart_Item_Factory::polen_cart_item_from_order($order);
$email_billing = $order_item_cart->get_email_to_video();

$order_array = event_promotional_get_order_flow_obj($order->get_id(), $order->get_status(), $email_billing);
$order_number = $order->get_id();

get_header();
?>

<main id="primary" class="site-main">
	<div class="row">
		<div class="col-12 col-md-8 m-md-auto">
			<?php
                va_magalu_box_thank_you();

				event_promotional_get_order_flow_layout($order_array, $order_number );

                va_partners_footer();
			?>
		</div>
	</div>
</main><!-- #main -->

<?php
get_footer();