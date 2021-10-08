<?php

/**
 * @version 3.7.0
 */

use Polen\Includes\Polen_Order;
use Polen\Includes\Cart\Polen_Cart_Item_Factory;
use Polen\Includes\Polen_Checkout_Create_User;

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

$order_item_cart = Polen_Cart_Item_Factory::polen_cart_item_from_order($order);
$email_billing = $order_item_cart->get_email_to_video();

$order_array = Order_Class::polen_get_order_flow_obj($order_number, $order_status, $email_billing);

$social = social_order_is_social($order);

global $Polen_Plugin_Settings;
$whatsapp_form = $Polen_Plugin_Settings['polen_whatsapp_form'];

$number = $order->get_meta(Polen_Order::WHATSAPP_NUMBER_META_KEY);

//TODO: Mover essa vwrificacao para um class
//Verifica se a conta foi criado nesse Checkout
if( !is_user_logged_in() ) {
	$email_user = $order->get_billing_email();
	$user = get_user_by( 'email', $email_user );
	if( !empty( $user ) ) {
		$created_by = get_user_meta( $user->ID, Polen_Checkout_Create_User::META_KEY_CREATED_BY, true );
		if ( 'checkout' == $created_by ) {
			$cad_time = WC_DateTime::createFromFormat( 'Y-m-d H:i:s', $user->user_registered );
			$now = new WC_DateTime();
			$diff_time_in_seconds = $now->getTimestamp() - $cad_time->getTimestamp();
			if( ( $diff_time_in_seconds / MINUTE_IN_SECONDS ) < 10 ) {
				$new_user = true;
			}

		}
	}
}

?>
<div class="row">
	<?php if (!$social) : ?>
		<div class="col-md-12 mb-4">
			<h1>Seu vídeo foi solicitado com Sucesso</h1>
		</div>
		<?php endif; ?>
		<?php if( $new_user ) : ?>
			<div class="col-12">
				<?= polen_get_toast('Sua conta Polen foi criada com sucesso! Enviamos seus dados de acesso para o e-mail: <strong>' . $email_billing . '</strong>'); ?>
			</div>
		<?php endif; ?>
	<div class="col-12">
		<?php $social && criesp_get_thankyou_box(); ?>
	</div>
	<div class="col-md-12">
		<?php polen_get_order_flow_layout($order_array, $order_number, $number, $whatsapp_form); ?>
	</div>
</div>

<?php
if (is_user_logged_in()) :
?>
	<div class="row my-3">
		<div class="col-12">
			<a href="<?php echo wc_get_account_endpoint_url('view-order') . $order_number . '/'; ?>" class="btn btn-outline-light btn-lg btn-block">Acompanhar pedido</a>
		</div>
	</div>

<?php
endif;

session_start();
$order_name = "order_{$order_number}";
if (!isset($_SESSION[$order_name])) {
	$_SESSION[$order_name] = 1;

	// JS do GA
	echo polen_create_ga_order($order);
}

// Zapier de compra finalizada
if( isset( $Polen_Plugin_Settings['polen_zapier_new_order'] ) && $Polen_Plugin_Settings['polen_zapier_new_order'] == '1' ) {
	if( empty( $order->get_meta( 'zapier_create_order_send_email' ) ) ) {
		$order->add_meta_data( 'zapier_create_order_send_email', '1', true );
		$order->save();
		polen_zapier_thankyou( $order );
	}
}
