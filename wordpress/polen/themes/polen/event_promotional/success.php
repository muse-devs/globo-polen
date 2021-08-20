<?php

use Polen\Includes\Cart\Polen_Cart_Item_Factory;

session_start();
$order_id = $_SESSION[ Promotional_Event_Admin::SESSION_KEY_SUCCESS_ORDER_ID ];
$order = wc_get_order( $order_id );
if( empty( $order ) ) {
    wp_safe_redirect( event_promotional_url_home() );
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