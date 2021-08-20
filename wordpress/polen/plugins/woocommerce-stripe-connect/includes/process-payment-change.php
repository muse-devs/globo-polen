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

$order 				= wcs_get_subscription( $order_id );
$current_user 		= wp_get_current_user();
$stripe_customer_id = $this->get_stripe_customer_id( $order );

//create customer
try {

	try {
		//create customer
		if( ! empty( $stripe_customer_id ) ) {
			$customer = \Stripe\Customer::retrieve( $stripe_customer_id );
		} else {
			throw new Exception('customer_error');
		}
	} catch (Exception $e) {
		$customer = \Stripe\Customer::create(
	        array(
	            'email' =>  $order->get_billing_email()
	        )
	    );
	}

    if( isset( $customer->id ) ) {

    	update_post_meta( $order->get_id(), '_stripe_customer_id', $customer->id );
		if( is_user_logged_in() ) {
			update_user_option( get_current_user_id(), '_stripe_customer_id', $customer->id );
		}

		$payment_method_id = '';

		// Customer is using a saved payment method
		if ( isset( $_POST['wc-zcwc_stripe_connect-payment-token'] ) && 'new' !== $_POST['wc-zcwc_stripe_connect-payment-token'] ) {

			$token_id = wc_clean( $_POST['wc-zcwc_stripe_connect-payment-token'] );
			$token    = WC_Payment_Tokens::get( $token_id );

			// Token user ID does not match the current user... bail out of payment processing.
			if ( $token->get_user_id() !== get_current_user_id() ) {
			    throw new Exception( esc_html__( 'Payment method does not belong to current user.', 'woocommerce-stripe-connect' ) );
			}

			$payment_method_id = $token->get_token();

		//saving a new payment method
		} else {

			if( isset( $_POST['zcwc_stripe_connect_credit_card_hash'] ) ) { 
				
				$hash = wc_clean( $_POST['zcwc_stripe_connect_credit_card_hash'] );
				$payment_method = \Stripe\PaymentMethod::create( array(
					'type' => 'card',
					'card' => array( 'token' => $hash ) 
				));

				$payment_method_id = $payment_method->id;
				$payment_method->attach(array('customer' => $customer->id  ));
				$token = $this->set_payment_method_token( $payment_method );
			
			} else {
				throw new Exception( _esc_html__( 'Problem encrypting your credit card. Please verify your credit card information.', 'woocommerce-stripe-connect' )  );
			}
		}

		if( isset( $_POST['update_all_subscriptions_payment_method'] ) ) {
			$user_subscriptions = wcs_get_users_subscriptions( $order->get_customer_id() );
			foreach( $user_subscriptions as $id => $sub ) {
				$this->set_subscriptions_payment_method( $id, $customer->id, $payment_method_id );
			}
		} else {
			$this->set_subscriptions_payment_method( $order_id, $customer->id, $payment_method_id );
		}

		return array(
			'result' => 'success',
			'redirect' => $this->get_return_url( $order )
		);

    } else {
    	throw new Exception( esc_html__( 'Problem creating customer on Stripe. Please try again later.', 'woocommerce-stripe-connect' ) );
    }
} catch (Exception $e) {
	wc_add_notice( $e->getMessage(), 'error' );
	return;
}