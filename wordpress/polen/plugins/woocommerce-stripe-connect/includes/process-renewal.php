<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Send request and get response from server.
\Stripe\Stripe::setAppInfo(
    'WooCommerce Stripe Connect',
    '1.0.0',
    site_url(),
    'pp_partner_HF4cic88ihICiq'
);
\Stripe\Stripe::setApiKey($this->get_stripe_secret_key());
\Stripe\Stripe::setApiVersion("2019-05-16");

//create customer
try {

	if ( $amount <= 0 ) {
			
		if ( 'yes' === $this->debug ) {
			$this->log->add( $this->id, 'process_subscription_payment(): Sorry, the minimum allowed order total is 0.01 to use this payment method. Amount: ' . $amount );
		}

		throw new Exception( 'error', esc_html__( 'Sorry, the minimum allowed order total is 0.01 to use this payment method.', 'woocommerce-stripe-connect' ) );
	}

	// Check for an existing intent, which is associated with the order.
	if ( $this->has_authentication_already_failed( $order ) ) {
		return;
	}

	$stripe_customer_id = get_post_meta( $order->get_id(), '_stripe_customer_id', true);
	$stripe_method_id  = get_post_meta( $order->get_id(), '_stripe_method_id', true);

	if( '' != $stripe_customer_id && '' != $stripe_method_id ) {

		$payment_params = array(
		  'amount' => $this->money_format( $order->get_total() ),
		  'currency' => $order->get_currency(),
		  'customer' => $stripe_customer_id,
		  'payment_method' => $stripe_method_id,
		  'transfer_group' => 'order_' . $order->get_id(),
		  'off_session' => true,
		  'confirm' => true,
		  'description' => sprintf('%s%s', $this->invoice_prefix, $order->get_id() ),
		  'metadata' => array( 'order' => $order->get_id() )
		);

		if('' != $this->descriptor ) {
			$payment_params['statement_descriptor'] = strlen( $this->descriptor ) > 22 ? substr( $this->descriptor, 0, 22 ) : $this->descriptor;
		}

		try {
			$paymentIntent = \Stripe\PaymentIntent::create( $payment_params );
		} catch (\Stripe\Error\Base $e) {
			$json =  $e->getJsonBody();
			if( isset( $json['error']['code'] ) && 'authentication_required' == $json['error']['code'] ) {
				unset($payment_params['off_session']);
				$payment_params['setup_future_usage'] = 'off_session';
				$paymentIntent = \Stripe\PaymentIntent::create( $payment_params );
			} else {
				throw new Exception( $e->getMessage() );
			}
		}

	    if( $paymentIntent->status == 'succeeded' ) {
	    	
	    	$this->update_order_status( $order, $paymentIntent );

	    	return true;

	    } elseif( 'requires_action' === $paymentIntent->status ) {
	    		
	    	$order->update_meta_data( '_stripe_connect_intent_id' , $paymentIntent->id );
	    	$order->update_meta_data( '_stripe_connect_client_secret', $paymentIntent->client_secret );
	    	$order->save();
	    	
	    	do_action( 'wc_gateway_stripe_connect_process_payment_authentication_required', $order, $paymentIntent );

			$error_message = __( 'This transaction requires authentication.', 'woocommerce-stripe-connect' );
			$order->add_order_note( $error_message );
			$order->update_status( 'failed', sprintf( __( 'Stripe charge awaiting authentication by user.', 'woocommerce-gateway-stripe' ) ) );
			if ( is_callable( array( $order, 'save' ) ) ) {
				$order->save();
			}			

	    } else {
	    	throw new Exception( esc_html__( 'Problem creating charge for customer. Please try again later.', 'woocommerce-stripe-connect' ) );
	    }
    } else {
    	throw new Exception( esc_html__( 'Problem creating customer on Stripe. Please try again later.', 'woocommerce-stripe-connect' ) );
    }
} catch (Exception $e) {
	return new WP_Error( 'error', $e->getMessage() );
}