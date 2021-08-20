<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$order = wc_get_order( $order_id );
$charge = get_post_meta( $order->get_id(), '_stripe_charge', true);

if( '' == $charge ) {
	return false;
}

// Send request and get response from server.
\Stripe\Stripe::setAppInfo(
    'WooCommerce Stripe Connect',
    '1.0.0',
    site_url()
);
\Stripe\Stripe::setApiKey($this->get_stripe_secret_key());
\Stripe\Stripe::setApiVersion("2019-05-16");

//create customer
try {

	$refund = \Stripe\Refund::create([
	  'charge' => $charge,
	  'amount' => $this->money_format( $amount ),
	  'reason' => in_array( $reason, array( 'duplicate', 'fraudulent', 'requested_by_customer' ) ) ? $reason : null
	]);

    if( isset( $refund->id ) && 'succeeded' == $refund->status  ) {
    	return true;
    } else {
    	return false;
    }

} catch (Exception $e) {
	return false;
}