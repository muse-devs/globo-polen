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

$user = wp_get_current_user();
$stripe_customer_id = get_user_option( '_stripe_customer_id', $user->ID );

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
	            'email' =>  get_userdata($user->ID)->user_email
	        )
	    );
	    update_user_option( $user->ID, '_stripe_customer_id', $customer->id );
	}

    if( isset( $customer->id ) ) {

		$payment_method_id = '';

		if( isset( $_POST['zcwc_stripe_connect_credit_card_hash'] ) ) { 
			$hash = wc_clean( $_POST['zcwc_stripe_connect_credit_card_hash'] );
			$payment_method = \Stripe\PaymentMethod::create( array(
				'type' => 'card',
				'card' => array( 'token' => $hash ) 
			));

			$payment_method->attach( array('customer' => $customer->id ) );
			$token = $this->set_payment_method_token( $payment_method );

			return array(
	    		'result' => 'success',
	    		'redirect' => wc_get_endpoint_url( 'payment-methods', '', wc_get_page_permalink('myaccount')  )
	    	);
		} else {
			throw new Exception( esc_html__('Problem encrypting your credit card. Please verify your credit card information.', 'woocommerce-stripe-connect' ) );
		}
    } else {
    	throw new Exception( esc_html__( 'Problem creating customer on Stripe. Please try again later.', 'woocommerce-stripe-connect' ) );
    }
} catch (Exception $e) {
	wc_add_notice( $e->getMessage(), 'error' );
	return;
}