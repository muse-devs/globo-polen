<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ZCWC_Stripe_Connect_Subs_Gateway extends ZCWC_Stripe_Connect_Gateway {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		if ( class_exists( 'WC_Subscriptions_Order' ) ) {

			add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, array( $this, 'scheduled_subscription_payment' ), 10, 2 );

			add_filter( 'woocommerce_subscription_payment_meta', array( $this, 'add_subscription_payment_meta' ), 10, 2 );
			add_filter( 'woocommerce_subscription_validate_payment_meta', array( $this, 'validate_subscription_payment_meta' ), 10, 2 );

			add_action( 'woocommerce_subscription_failing_payment_method_updated_' . $this->id, array( $this, 'update_failing_payment_method' ), 10, 2 );

			add_filter('wc_zcwc_stripe_connect_display_save_payment_method_checkbox', array( $this, 'hide_save_payment_methods' ), 20 , 1);
			/*
			 * WC subscriptions hooks into the "template_redirect" hook with priority 100.
			 * If the screen is "Pay for order" and the order is a subscription renewal, it redirects to the plain checkout.
			 * See: https://github.com/woocommerce/woocommerce-subscriptions/blob/99a75687e109b64cbc07af6e5518458a6305f366/includes/class-wcs-cart-renewal.php#L165
			 * If we are in the "You just need to authorize SCA" flow, we don't want that redirection to happen.
			 */
			add_action( 'template_redirect', array( $this, 'remove_order_pay_var' ), 99 );
			add_action( 'template_redirect', array( $this, 'restore_order_pay_var' ), 101 );

		}
	}

	public function process_payment( $order_id ) {

		$is_payment_change = WC_Subscriptions_Change_Payment_Gateway::$is_request_to_change_payment;
		$order_contains_failed_renewal = false;

		// Payment method changes act on the subscription not the original order
		if ( $is_payment_change ) {
			return include( ZCWC_STRIPE_CONNECT_PATH . '/includes/process-payment-change.php' );
		} else {
			return parent::process_payment( $order_id );
		}
	}
	
	/**
	 * scheduled_subscription_payment function.
	 *
	 * @param $amount_to_charge float The amount to charge.
	 * @param $renewal_order WC_Order A WC_Order object created to record the renewal payment.
	 * @access public
	 * @return void
	 */
	public function scheduled_subscription_payment( $amount, $order ) {

		if ( 'yes' === $this->debug ) {
			$this->log->add( $this->id, 'called scheduled_subscription_payment(). Order: ' . $order->get_id() );
		}

		$response = include( ZCWC_STRIPE_CONNECT_PATH . '/includes/process-renewal.php' );

		if ( is_wp_error( $response ) ) {
			$order->update_status( 'failed', sprintf( __( 'Stripe Renewal Payment Failed (%s)', 'woocommerce-stripe-connect' ), $response->get_error_message() ) );
		}

		return;
	}

	/**
	 * Include the payment meta data required to process automatic recurring payments so that store managers can
	 * manually set up automatic recurring payments for a customer via the Edit Subscription screen in Subscriptions v2.0+.
	 *
	 * @since 2.4
	 * @param array $payment_meta associative array of meta data required for automatic payments
	 * @param WC_Subscription $subscription An instance of a subscription object
	 * @return array
	 */
	public function add_subscription_payment_meta( $payment_meta, $subscription ) {
 		$payment_meta[ $this->id ] = array(
			'post_meta' => array(
				'_stripe_customer_id' => array(
					'value' => get_post_meta( $subscription->get_id(), '_stripe_customer_id', true ),
					'label' => esc_html__('Stripe Customer ID', 'woocommerce-stripe-connect')
				),
				'_stripe_method_id' => array(
					'value' => get_post_meta( $subscription->get_id(), '_stripe_method_id', true ),
					'label' => esc_html__('Stripe Payment Method ID', 'woocommerce-stripe-connect')
				),
			),
		);
 		return $payment_meta;
	}

	/**
	 * Validate the payment meta data required to process automatic recurring payments so that store managers can.
	 * manually set up automatic recurring payments for a customer via the Edit Subscription screen in Subscriptions 2.0+.
	 *
	 * @since  2.4
	 * @param  string $payment_method_id The ID of the payment method to validate
	 * @param  array  $payment_meta associative array of meta data required for automatic payments
	 * @throws Exception
	 */
	public function validate_subscription_payment_meta( $payment_method_id, $payment_meta ) {
		if ( $this->id === $payment_method_id ) {
			if ( ! isset( $payment_meta['post_meta']['_stripe_customer_id']['value'] ) || empty( $payment_meta['post_meta']['_stripe_customer_id']['value'] ) ) {
				throw new Exception( esc_html__('Stripe Customer ID is required', 'woocommerce-stripe-connect') );
			}

			if ( ! isset( $payment_meta['post_meta']['_stripe_method_id']['value'] ) || empty( $payment_meta['post_meta']['_stripe_method_id']['value'] ) ) {
				throw new Exception( esc_html__('Stripe Payment Method ID is required', 'woocommerce-stripe-connect') );
			}
		}
	}

	/**
	 * 
	 * Update failing payment method
	 *
	 * @param WC_Subscription $subscription The subscription for which the failing payment method relates.
	 * @param WC_Order        $renewal_order The order which recorded the successful payment (to make up for the failed automatic payment).
	 */
	public function update_failing_payment_method( $subscription, $renewal_order ) {
		update_post_meta( $subscription->get_id(), '_stripe_customer_id', get_post_meta( $renewal_order->get_id(), '_stripe_customer_id', true ) );
		update_post_meta( $subscription->get_id(), '_stripe_method_id', get_post_meta( $renewal_order->get_id(), '_stripe_method_id', true ) );
	}

	public function set_subscriptions_payment_method( $subscription_id, $stripe_customer_id, $stripe_method_id ) {
		update_post_meta( $subscription_id, '_stripe_customer_id', $stripe_customer_id );
		update_post_meta( $subscription_id, '_stripe_method_id', $stripe_method_id );
		update_post_meta( $subscription_id, '_payment_method', $this->id );
		update_post_meta( $subscription_id, '_payment_method_title', $this->method_title );
	}


	/**
	 * If this is the "Pass the SCA challenge" flow, remove a variable that is checked by WC Subscriptions
	 * so WC Subscriptions doesn't redirect to the checkout
	 */
	public function remove_order_pay_var() {
		global $wp;
		if ( isset( $_GET['wc-stripe-connect-confirmation'] ) ) {
			$this->order_pay_var = $wp->query_vars['order-pay'];
			$wp->query_vars['order-pay'] = null;
		}
	}

	/**
	 * Restore the variable that was removed in remove_order_pay_var()
	 */
	public function restore_order_pay_var() {
		global $wp;
		if ( isset( $this->order_pay_var ) ) {
			$wp->query_vars['order-pay'] = $this->order_pay_var;
		}
	}

	/**
	 * Checks if a renewal already failed because a manual authentication is required.
	 *
	 * @param WC_Order $renewal_order The renewal order.
	 * @return boolean
	 */
	public function has_authentication_already_failed( $renewal_order ) {
		$existing_intent = $this->get_intent_from_order( $renewal_order );

		if ( $existing_intent && 'requires_action' == $existing_intent->status ) {
			// Make sure all emails are instantiated.
			WC_Emails::instance();

			/**
			 * A payment attempt failed because SCA authentication is required.
			 *
			 * @param WC_Order $renewal_order The order that is being renewed.
			 */
			do_action( 'wc_gateway_stripe_connect_process_payment_authentication_required', $renewal_order );

			// Fail the payment attempt (order would be currently pending because of retry rules).
			$renewal_order->update_status( 'failed', sprintf( __( 'Stripe charge awaiting authentication by user.', 'woocommerce-gateway-stripe' ) ) );

			return true;
		}
		return false;
	}

	public function hide_save_payment_methods( $value ) {
		if( WC_Subscriptions_Cart::cart_contains_subscription() ) {
			return false;
		}
		return $value;
	}
	
}
