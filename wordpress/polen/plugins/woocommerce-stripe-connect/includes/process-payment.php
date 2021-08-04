<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$using_saved_card = false; 

$order = wc_get_order( $order_id );
	
$shipping = isset( $posted['ship_to_different_address'] ) ? true : false;

// Send request and get response from server.
\Stripe\Stripe::setAppInfo(
    'WooCommerce Stripe Connect',
    '1.0.0',
    site_url(),
    'pp_partner_HF4cic88ihICiq'
);
\Stripe\Stripe::setApiKey($this->get_stripe_secret_key());
\Stripe\Stripe::setApiVersion("2019-05-16");

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

		$attach_pm = false;
    	$transfers = array();
    	$commissions_paid = array();
    	$failed_transfers = array();
    	$customer_details = $this->get_customer_details_from_order( $order ); 

		$payment_method_id = '';

		// Customer is using a saved payment method
		if ( isset( $_POST['wc-zcwc_stripe_connect-payment-token'] ) && 'new' !== $_POST['wc-zcwc_stripe_connect-payment-token'] ) {

			$token_id = wc_clean( $_POST['wc-zcwc_stripe_connect-payment-token'] );
			$token    = WC_Payment_Tokens::get( $token_id );

			// Token user ID does not match the current user... bail out of payment processing.
			if ( $token->get_user_id() !== get_current_user_id() ) {
			   throw new Exception( esc_html__( 'Payment method does not belong to current user.', 'woocommerce-stripe-connect' ) );
			}

			$using_saved_card = true;
			$payment_method_id = $token->get_token();

			if( 'yes' == $this->cvc_on_saved && ( !isset( $_POST['zcwc_stripe_connect_cvc_token'] ) || '' == trim($_POST['zcwc_stripe_connect_cvc_token']) ) ) {
				 throw new Exception( esc_html__( 'Problem encrypting your card cvc code.', 'woocommerce-stripe-connect' ) );
			}

		//saving a new payment method
		} else {

			if( isset( $_POST['zcwc_stripe_connect_credit_card_hash'] ) ) { 
				$hash = wc_clean( $_POST['zcwc_stripe_connect_credit_card_hash'] );
				$payment_method = \Stripe\PaymentMethod::create( array(
					'type' => 'card',
					'card' => array( 'token' => $hash ) 
				));

				$payment_method_id = $payment_method->id;
			} else {
				throw new Exception( esc_html__( 'Problem encrypting your credit card. Please verify your credit card information.', 'woocommerce-stripe-connect' ) );
			}

			if( isset( $_POST['wc-zcwc_stripe_connect-new-payment-method'] ) || $this->has_subscription( $order->get_id() ) ) {
				$attach_pm = true;
				$token = $this->set_payment_method_token( $payment_method );
			}
		
		}

		if ( 0 >= $order->get_total() ) {
			
			if ( $this->has_subscription( $order ) ) {

				$setup_intent_args = array(
					'payment_method' => $payment_method_id,
					'customer'       => $customer->id,
					'confirm'        => 'true',
				);
			
				$setup_intent = \Stripe\SetupIntent::create( $setup_intent_args );

				if( $setup_intent->id ) {
					update_post_meta( $order->get_id(), '_stripe_method_id', $payment_method_id);
		   			$this->save_subscription_payment_method( $order->get_id(), $customer->id, $payment_method_id );
		   		}
				
				if ( 'requires_action' === $setup_intent->status ) {
					$order->update_meta_data( '_stripe_connect_setup_id', $setup_intent->id );
					$order->save();
					return array(
						'result'              => 'success',
						'redirect'            => $this->get_return_url( $order ),
						'connect_setup_intent_secret' => $setup_intent->client_secret,
					);
				}
				
			}

			// Remove cart.
			WC()->cart->empty_cart();

			$order->payment_complete();

			// Return thank you page redirect.
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);


		} else {
		
			$payment_params = array(
			  'amount' => $this->money_format( $order->get_total() ),
			  'currency' => $order->get_currency(),
			  'customer' => $customer->id,
			  'payment_method' => $payment_method_id,
			  'transfer_group' => 'order_' . $order->get_id(),
			  'confirm' => true,
			  'description' => sprintf('%s%s', $this->invoice_prefix, $order->get_id() ),
			  'metadata' => array( 'order' => $order->get_id() )
			);

			if( $using_saved_card && 'yes' == $this->cvc_on_saved ) {
				$payment_params['payment_method_options'] = array(
					'card' => array(
						'cvc_token' => trim( $_POST['zcwc_stripe_connect_cvc_token'] )
					)
				);
			}

			if( $attach_pm ) {
				$payment_params['setup_future_usage'] = 'off_session';
				update_post_meta( $order->get_id(), '_stripe_method_id', $payment_method_id);
			}

			if('' != $this->descriptor ) {
				$payment_params['statement_descriptor'] = strlen( $this->descriptor ) > 22 ? substr( $this->descriptor, 0, 22 ) : $this->descriptor;
			}

			$paymentIntent = \Stripe\PaymentIntent::create( $payment_params );

		   	if ( 'yes' === $this->debug ) {
				$this->log->add( $this->id, 'PaymentIntent Response: ' . print_r( $paymentIntent, true ) );
			}

			if( $this->has_subscription( $order->get_id() ) ) {
		   		$this->save_subscription_payment_method( $order->get_id(), $customer->id, $payment_method_id );
		   	}

		    if( 'succeeded' === $paymentIntent->status ) {

			   	$this->update_order_status( $order, $paymentIntent, $transfers, $commissions_paid );
		   	
		    	WC()->cart->empty_cart();
	    	
		    	return array(
		    		'result' => 'success',
		    		'redirect' => $this->get_return_url( $order ),
		    	);

		    } elseif( 'requires_action' === $paymentIntent->status ) {
		    		
		    	$order->update_meta_data( '_stripe_connect_intent_id' , $paymentIntent->id );
		    	$order->update_meta_data( '_stripe_connect_client_secret', $paymentIntent->client_secret );
		    	$order->save();

		    	if ( is_wc_endpoint_url( 'order-pay' ) ) {
					$redirect_url = add_query_arg( 'wc-stripe-connect-confirmation', 1, $order->get_checkout_payment_url( false ) );

					return array(
						'result'   => 'success',
						'redirect' => $redirect_url,
					);
				} else {
					/**
					 * This URL contains only a hash, which will be sent to `checkout.js` where it will be set like this:
					 * `window.location = result.redirect`
					 * Once this redirect is sent to JS, the `onHashChange` function will execute `handleCardPayment`.
					 */

					return array(
						'result'                => 'success',
						'redirect'              => $this->get_return_url( $order ),
						'connect_payment_intent_secret' => $paymentIntent->client_secret,
					);
				}

		    } else {
		    	throw new Exception( esc_html__( 'Problem creating charge for customer. Please try again later.', 'woocommerce-stripe-connect' ) );
		    }
		}
    } else {
    	throw new Exception( esc_html__( 'Problem creating customer on Stripe. Please try again later.', 'woocommerce-stripe-connect' ) );
    }
} catch (Exception $e) {
	wc_add_notice( $e->getMessage(), 'error' );
	return;
}