<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * PayPal Mass Payments Class.
 *
 * Mass Payments by PayPal to mass pay vendor commission.
 *
 * @category Payout
 * @package  WooCommerce Product Vendors/PayPal Masspay
 * @version  2.0.35
 * @since 2.0.0
 */
class WC_Product_Vendors_Stripe_Connect_MassPay { // implements WC_Product_Vendors_Vendor_Payout_Interface {
	private $gateway;
	private $client_id;
	private $sandbox;
	protected $table_name = WC_PRODUCT_VENDORS_COMMISSION_TABLE;

	/**
	 * Constructor
	 *
	 * @access public
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param array of objects $commissions
	 * @return bool
	 */
	public function __construct() {
		$this->gateway 		= ZCWC_Stripe_Connect_Gateway::get_instance();
	    $this->client_id 	= $this->gateway->get_stripe_client_id();
	    $this->sandbox 		= $this->gateway->sandbox;

		return true;
	}

	/**
	 * Sends payment 
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 * @return bool
	 */
	public function do_payment( $commissions ) {
		if ( empty( $commissions ) ) {
			//throw new Exception( __( 'No commission to pay', 'woocommerce-product-vendors' ) );
			return;
		}

		$order_transfers = $failed_transfers = $paid_commissions = array();

		$charge_id = null;
		$order_id = $this->gateway->get_current_order_id();
		if( null != $order_id ) {
			$order = wc_get_order( $order_id );

			if( is_a( $order, 'WC_Order' ) ) {
				$charge_id = get_post_meta( $order->get_id(), '_stripe_charge', true );
			}
		}

		\Stripe\Stripe::setAppInfo(
		    'WooCommerce Stripe Connect',
		    '1.0.0',
		    site_url(),
		    'pp_partner_HF4cic88ihICiq'
		);
		\Stripe\Stripe::setApiKey( $this->gateway->get_stripe_secret_key());
		\Stripe\Stripe::setApiVersion("2019-05-16");

		// add each commission item
		foreach ( $commissions as $commission ) {

			// $vendor_data 	= WC_Product_Vendors_Utils::get_vendor_data_by_id( $commission->vendor_id );
			// $stripe_data 	= get_term_meta( $commission->vendor_id, 'stripe_data', true );
			
			$stripe_data = array();
			$stripe_data['stripe_user_id'] = 'acct_1CraZLKSW1JOYSdK';

			$vendor_data = array();
			$vendor_data['name'] = 'Cubo9';

			if ( empty( $vendor_data ) || empty( $stripe_data ) ) {
				continue;
			}

			if( !isset( $stripe_data['stripe_user_id'] ) || '' == $stripe_data['stripe_user_id'] ) {
				continue;
			}

			try{

				$args = array(
					'amount' => $this->gateway->money_format( $commission->total_commission_amount ),
					'currency' => get_woocommerce_currency(),
					'destination' => $stripe_data['stripe_user_id'],
					'description' => sprintf( 
				  		esc_html__('Transfer from %s (%s) to (%s)', 'woocommerce-stripe-connect' ),
				  		get_bloginfo('name'),
				  		home_url('/'),
				  		$vendor_data['name']
				  	),
				  	'metadata' => array(
				  		'from_name' => get_bloginfo('name'),
				  		'from_url' => home_url('/'),
				  		'to_vendor' => $vendor_data['name']
				  	)
				);

				if( null != $charge_id ) {
					$args['source_transaction'] = $charge_id;
				}

				$args = apply_filters( 'zcsc_stripe_transfer_args', $args, $commission );

	            $order_transfers[ $commission->order_id ][] = \Stripe\Transfer::create( $args );

	            $this->gateway->update_commission_status( $commission->id, $commission->order_item_id, 'paid' );
	            
            } catch( Exception $err ) {

                $failed_transfers[] = array(
                    'error' => true,
                    'message' => $err->getMessage(),
                    'vendor_id' => $commission->vendor_id,
                    'amount' => $commission->total_commission_amount
                );
            
            }			
		}

		if( !empty( $order_transfers ) ) {
			foreach( $order_transfers as $order_id => $transfers ) {
				$transfer_ids = array();
				foreach( $transfers as $transfer ) {
					$transfer_ids[] = array(
						'id' => $transfer->id,
						'url' => $this->gateway->get_transfer_url( $transfer->id )
					);
				}
				update_post_meta( $order_id, '_stripe_transfers', serialize( $transfer_ids ) );
			}
		}

		$this->gateway->set_current_order_id( null );

		if ( ! empty( $failed_transfers ) ) {
			throw new Exception( sprintf( __('Some transfers failed: %s', 'woocommerce-stripe-connect'), end($failed_transfers)['message'] ) );
		}

		return true;
	}

}
