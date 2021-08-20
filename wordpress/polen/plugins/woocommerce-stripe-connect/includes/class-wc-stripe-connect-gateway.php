<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ZCWC_Stripe_Connect_Gateway extends WC_Payment_Gateway_CC {

	const VERSION = '1.0';

	private static $instance = null;

	protected $stripe = null;

	protected $notification_response = null;

	public $current_order_id = null;

	/**
	 * Get the single instance aka Singleton
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return bool
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
			
		self::$instance = $this;

		// parent::__construct();

		if ( class_exists( 'WC_Product' ) ) {

			$this->id                 = 'zcwc_stripe_connect';
			$this->method_title       = esc_html__( 'Stripe Connect', 'woocommerce-stripe-connect' );
			$this->method_description = esc_html__( 'Accept payments by credit card using Stripe.', 'woocommerce-stripe-connect' );
			$this->order_button_text  = esc_html__( 'Proceed to payment', 'woocommerce-stripe-connect' );

			$this->has_fields         = true;

			// Load the form fields.
			$this->init_form_fields();

			// Load the settings.
			$this->init_settings();

			// Define user set variables.
			// Get setting values.
	        foreach ($this->settings as $key => $val) {
	            $this->$key = $val;
	        }

			// Active logs.
			if ( 'yes' == $this->debug ) {
				$this->log = new WC_Logger();
			}

			// Subscriptions
			$this->supports = array( 
				'products',
				'refunds',
				'tokenization',
				'subscriptions',
				'multiple_subscriptions',
				'subscription_cancellation', 
				'subscription_suspension', 
				'subscription_reactivation',
				'subscription_amount_changes',
				'subscription_date_changes',
				'subscription_payment_method_change',
				'subscription_payment_method_change_admin',
				'subscription_payment_method_change_customer',
			);
			
			// Receive the webhook
			add_action( 'woocommerce_api_zcwc_stripe_connect_gateway', array( $this, 'webhook_handler' ) );
			
			// Process the webhook
			add_action( 'valid_zcwc_stripe_connect_ipn_request', array( $this, 'process_webhook' ) );

			//Save admin options
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			
			// display scripts	
			add_action( 'wp_enqueue_scripts', array( $this, 'checkout_scripts'));

			add_filter( 'woocommerce_available_payment_gateways', array( $this, 'prepare_order_pay_page' ) );

			add_filter( 'woocommerce_payment_successful_result', array( $this, 'modify_successful_payment_result' ), 99999, 2 );

		}
	}

	/**
	 * Admin page.
	 */
	public function admin_options() {
		include dirname( __FILE__ ) . '/admin/views/html-admin-page.php';
	}

	/**
	 * Get Stripe Client ID.
	 *
	 * @return string
	 */
	public function get_stripe_client_id() {
		return 'yes' === $this->sandbox ? $this->stripe_test_client_id : $this->stripe_live_client_id;
	}



	/**
	 * Get Stripe Publishable Key.
	 *
	 * @return string
	 */
	public function get_stripe_publishable_key() {
		return 'yes' === $this->sandbox ? $this->stripe_test_publishable_key : $this->stripe_live_publishable_key;
	}

	/**
	 * Get Stripe Secret Key.
	 *
	 * @return string
	 */
	public function get_stripe_secret_key() {
		return 'yes' === $this->sandbox ? $this->stripe_test_secret_key : $this->stripe_live_secret_key;
	}


	public function manual_transfer_enabled() {
		return 'yes' === $this->manual_transfers ? true : false;
	}

	public function set_current_order_id( $order_id ) {
		$this->current_order_id = $order_id;
		return true;
	}

	public function get_current_order_id() {
		return $this->current_order_id;
	}

	public function get_mdr() {
		return $this->mdr;
	}
	
	/**
	 * Returns a value indicating the the Gateway is available or not. It's called
	 * automatically by WooCommerce before allowing customers to use the gateway
	 * for payment.
	 *
	 * @return bool
	 */
	public function is_available() {
		// Test if is valid for use.
		$available = 'yes' === $this->get_option( 'enabled' );

		return $available;
	}

	/**
	 * Get log.
	 *
	 * @return string
	 */
	protected function get_log_view() {
		if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.2', '>=' ) ) {
			return '<a href="' . esc_url( admin_url( 'admin.php?page=wc-status&tab=logs&log_file=' . esc_attr( $this->id ) . '-' . sanitize_file_name( wp_hash( $this->id ) ) . '.log' ) ) . '">' . esc_html__( 'System Status &gt; Logs', 'woocommerce-stripe-connect' ) . '</a>';
		}

		return '<code>woocommerce/logs/' . esc_attr( $this->id ) . '-' . sanitize_file_name( wp_hash( $this->id ) ) . '.txt</code>';
	}

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'   => esc_html__( 'Enable/Disable', 'woocommerce-stripe-connect' ),
				'type'    => 'checkbox',
				'label'   => esc_html__( 'Enable Stripe Marketplace', 'woocommerce-stripe-connect' ),
				'default' => 'yes',
			),
			'title' => array(
				'title'       => esc_html__( 'Title', 'woocommerce-stripe-connect' ),
				'type'        => 'text',
				'description' => esc_html__( 'This controls the title which the user sees during checkout.', 'woocommerce-stripe-connect' ),
				'desc_tip'    => true,
				'default'     => esc_html__( 'Credit Card', 'woocommerce-stripe-connect' ),
			),
			'description' => array(
				'title'       => esc_html__( 'Description', 'woocommerce-stripe-connect' ),
				'type'        => 'textarea',
				'description' => esc_html__( 'This controls the description which the user sees during checkout.', 'woocommerce-stripe-connect' ),
				'default'     => esc_html__( 'Pay with credit card', 'woocommerce-stripe-connect' ),
			),
			'descriptor' => array(
				'title'       => esc_html__( 'Statement Descriptor', 'woocommerce-stripe-connect' ),
				'type'        => 'text',
				'description' => esc_html__( 'What shows up on the credit card statement for the customer', 'woocommerce-stripe-connect' ),
				'default'     => get_bloginfo('name'),
			),
			'mdr' => array(
				'title'       => esc_html__( 'Comissão Padrão (%)', 'woocommerce-stripe-connect' ),
				'type'        => 'text',
				'description' => esc_html__( 'Comissão padrão (porcentagem) sobre venda efetuada a ser descontada do talento', 'woocommerce-stripe-connect' ),
				'default'     => '25',
			),
			'sandbox' => array(
				'title'       => esc_html__( 'Sandbox', 'woocommerce-stripe-connect' ),
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Enable Sandbox', 'woocommerce-stripe-connect' ),
				'desc_tip'    => true,
				'default'     => 'no',
				'description' => esc_html__( 'Sandbox can be used to test the payments.', 'woocommerce-stripe-connect' ),
			),
			'sandbox_cred' => array(
				'title'       => esc_html__( 'Sandbox/Test Credentials', 'woocommerce-stripe-connect' ),
				'type'        => 'title',
				'description' => '',
			),
			'stripe_test_client_id' => array(
                'title' => esc_html__('Stripe Connect Test client_id', 'woocommerce-stripe-connect'),
                'label' => esc_html__('Test client_id', 'woocommerce-stripe-connect'),
                'type' => 'text',
                'description' => esc_html__('Enter Stripe Connect Test Client Id ', 'woocommerce-stripe-connect'),
                'default' => '',
            ),
            'stripe_test_publishable_key' => array(
                'title' => esc_html__('Stripe Test Publishable Key', 'woocommerce-stripe-connect'),
                'type' => 'text',
                'description' => esc_html__('This is the API Stripe Test Publishable Key generated within the Stripe Payment gateway.', 'woocommerce-stripe-connect'),
                'default' => '',
            ),
            'stripe_test_secret_key' => array(
                'title' => esc_html__('Stripe Test Secret Key', 'woocommerce-stripe-connect'),
                'type' => 'text',
                'description' => esc_html__('This is the API Stripe Test Secret Key generated within the Stripe Payment gateway.', 'woocommerce-stripe-connect'),
                'default' => '',
            ),
            
            'production_cred' => array(
				'title'       => esc_html__( 'Production Credentials', 'woocommerce-stripe-connect' ),
				'type'        => 'title',
				'description' => '',
			),
            'stripe_live_client_id' => array(
                'title' => esc_html__('Stripe Connect Live client_id', 'woocommerce-stripe-connect'),
                'label' => esc_html__('Live client_id', 'woocommerce-stripe-connect'),
                'type' => 'text',
                'description' => esc_html__('Enter Stripe Connect Live Client Id ', 'woocommerce-stripe-connect'),
                'default' => '',
            ),
            'stripe_live_publishable_key' => array(
                'title' => esc_html__('Stripe Live Publishable Key', 'woocommerce-stripe-connect'),
                'type' => 'text',
                'description' => esc_html__('This is the API Stripe Live Publishable Key generated within the Stripe Payment gateway.', 'woocommerce-stripe-connect'),
                'default' => '',
            ),
            'stripe_live_secret_key' => array(
                'title' => esc_html__('Stripe Live Secret Key', 'woocommerce-stripe-connect'),
                'type' => 'text',
                'description' => esc_html__('This is the API Stripe Live Secret Key generated within the Stripe Payment gateway.', 'woocommerce-stripe-connect'),
                'default' => '',
            ),
            
			'behavior' => array(
				'title'       => esc_html__( 'Integration Behavior', 'woocommerce-stripe-connect' ),
				'type'        => 'title',
				'description' => '',
			),
			'cvc_on_saved' => array(
				'title'       => esc_html__( 'Ask for CVC on saved cards?', 'woocommerce-stripe-connect' ),
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Yes, ask for CVC', 'woocommerce-stripe-connect' ),
				'desc_tip'    => true,
				'default'     => 'no',
				'description' => esc_html__( 'Some countries require CVC verification on saved cards. A payment may succeed even with a failed CVC check. If this isn’t what you want, you may want to configure your Radar rules to block payments when CVC verification fails.', 'woocommerce-stripe-connect' ),
			),
			/* 'manual_transfers' => array(
				'title'       => esc_html__( 'Commission Transfers', 'woocommerce-stripe-connect' ),
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Disable Automatic Transfers', 'woocommerce-stripe-connect' ),
				'default'     => 'no',
				'description' => sprintf( 
					esc_html__( '%sATENTION%s: Automatic transfers during checkout will be bypassed and you will need to manually transfer funds from the %sCommissions%s tab. Your Stripe account must have funds in order to make manual transfers. %sYou may need to set your Stripe payout schedule to "manual%s".', 'woocommerce-stripe-connect' ),
					'<strong>',
					'</strong>',
					'<a href="'.admin_url('admin.php?page=wcpv-commissions').'">',
					'</a>',
					'<br/><strong>',
					'</strong>',
				),
			), */
			'invoice_prefix' => array(
				'title'       => esc_html__( 'Order Prefix', 'woocommerce-stripe-connect' ),
				'type'        => 'text',
				'description' => esc_html__( 'Please enter a prefix for your order numbers on stripe. This will show up on the description field of your transaction on Stripe. ie.: WC-8749', 'woocommerce-stripe-connect' ),
				'desc_tip'    => true,
				'default'     => 'WC-',
			),
			'testing' => array(
				'title'       => esc_html__( 'Gateway Testing', 'woocommerce-stripe-connect' ),
				'type'        => 'title',
				'description' => '',
			),
			'debug' => array(
				'title'       => esc_html__( 'Debug Log', 'woocommerce-stripe-connect' ),
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Enable logging', 'woocommerce-stripe-connect' ),
				'default'     => 'no',
				'description' => sprintf( esc_html__( 'Log events, such as API requests, inside %s', 'woocommerce-stripe-connect' ), $this->get_log_view() ),
			),
		);
	}


	/**
	 * Checkout scripts.
	 */
	public function checkout_scripts() {
		
		$load_scripts = false;

		if ( is_checkout() ) {
			$load_scripts = true;
		}
		if ( $this->is_available() ) {
			$load_scripts = true;
		}

		if ( false === $load_scripts ) {
			return;
		}

		$suffix     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$deps = array( 'jquery-payment', 'stripe-library', 'woocommerce-tokenization-form' );

		wp_enqueue_script( 'stripe-library', 'https://js.stripe.com/v3/', array(), null, true );
		wp_enqueue_script( 'zcwc-stripe-connect-checkout', plugins_url( 'assets/js/frontend/frontend' . $suffix . '.js', plugin_dir_path( __FILE__ ) ), $deps, self::VERSION, true );

		wp_localize_script(
			'zcwc-stripe-connect-checkout',
			'zcwc_stripe_connect_params',
			array(
				'key' 						=> $this->get_stripe_publishable_key(),
				'is_checkout' 				=> ( is_checkout() && empty( $_GET['pay_for_order'] ) ) ? 'yes' : 'no',
				'is_change_payment_page' 	=> isset( $_GET['change_payment_method'] ) ? 'yes' : 'no',
				'is_add_payment_page' 		=> is_wc_endpoint_url( 'add-payment-method' ) ? 'yes' : 'no',
				'is_pay_for_order_page' 	=> is_wc_endpoint_url( 'order-pay' ) ? 'yes' : 'no',
				'elements_options'			=> apply_filters( 'wc_stripe_elements_options', array() ),
				'elements_styling'			=> apply_filters( 'wc_stripe_elements_styling', false ),
				'elements_classes'          => apply_filters( 'wc_stripe_elements_classes', false ),
				'cvc_on_saved'				=> $this->cvc_on_saved != 'yes' ? 'no' : 'yes',
				'invalid_number'           => __( 'The card number is not a valid credit card number.', 'woocommerce-stripe-connect' ),
				'invalid_expiry_month'     => __( 'The card\'s expiration month is invalid.', 'woocommerce-stripe-connect' ),
				'invalid_expiry_year'      => __( 'The card\'s expiration year is invalid.', 'woocommerce-stripe-connect' ),
				'invalid_cvc'              => __( 'The card\'s security code is invalid.', 'woocommerce-stripe-connect' ),
				'incorrect_number'         => __( 'The card number is incorrect.', 'woocommerce-stripe-connect' ),
				'incomplete_number'        => __( 'The card number is incomplete.', 'woocommerce-stripe-connect' ),
				'incomplete_cvc'           => __( 'The card\'s security code is incomplete.', 'woocommerce-stripe-connect' ),
				'incomplete_expiry'        => __( 'The card\'s expiration date is incomplete.', 'woocommerce-stripe-connect' ),
				'expired_card'             => __( 'The card has expired.', 'woocommerce-stripe-connect' ),
				'incorrect_cvc'            => __( 'The card\'s security code is incorrect.', 'woocommerce-stripe-connect' ),
				'incorrect_zip'            => __( 'The card\'s zip code failed validation.', 'woocommerce-stripe-connect' ),
				'invalid_expiry_year_past' => __( 'The card\'s expiration year is in the past', 'woocommerce-stripe-connect' ),
				'card_declined'            => __( 'The card was declined.', 'woocommerce-stripe-connect' ),
				'missing'                  => __( 'There is no card on a customer that is being charged.', 'woocommerce-stripe-connect' ),
				'processing_error'         => __( 'An error occurred while processing the card.', 'woocommerce-stripe-connect' ),
				'invalid_request_error'    => __( 'Unable to process this payment, please try again or use alternative method.', 'woocommerce-stripe-connect' ),
				'invalid_sofort_country'   => __( 'The billing country is not accepted by SOFORT. Please try another country.', 'woocommerce-stripe-connect' ),
				'email_invalid'            => __( 'Invalid email address, please correct and try again.', 'woocommerce-stripe-connect' )
			)
		);

		$this->tokenization_script();

	}

	/**
	 * Payment form on checkout page
	 */
	public function payment_fields() {
		$user                 = wp_get_current_user();
		$display_tokenization = $this->supports( 'tokenization' ) && ( is_checkout() || isset( $_GET['pay_for_order']) );
		$total                = WC()->cart->total;
		$user_email           = '';
		$description          = $this->get_description();
		$description          = ! empty( $description ) ? $description : '';
		$firstname            = '';
		$lastname             = '';

		// If paying from order, we need to get total from order not cart.
		if ( isset( $_GET['pay_for_order'] ) && ! empty( $_GET['key'] ) ) { // wpcs: csrf ok.
			$order      = wc_get_order( wc_get_order_id_by_order_key( wc_clean( $_GET['key'] ) ) ); // wpcs: csrf ok, sanitization ok.
			$total      = $order->get_total();
			$user_email = $order->get_billing_email();
		} else {
			if ( $user->ID ) {
				$user_email = get_user_meta( $user->ID, 'billing_email', true );
				$user_email = $user_email ? $user_email : $user->user_email;
			}
		}

		if ( is_add_payment_method_page() ) {
			$firstname       = $user->user_firstname;
			$lastname        = $user->user_lastname;
		}

		ob_start();

		echo '<div
			id="zcwc_stripe_connect-payment-data"
			data-email="' . esc_attr( $user_email ) . '"
			data-full-name="' . esc_attr( $firstname . ' ' . $lastname ) . '"
			data-currency="' . esc_attr( strtolower( get_woocommerce_currency() ) ) . '"
		>';
		
		if ( 'yes' == $this->sandbox ) {
			/* translators: link to Stripe testing page */
			$description .= ' ' . sprintf( __( 'TEST MODE ENABLED. In test mode, you can use the card number 4242424242424242 with any CVC and a valid expiration date or check the <a href="%s" target="_blank">Testing Stripe documentation</a> for more card numbers.', 'woocommerce-stripe-connect' ), 'https://stripe.com/docs/testing' );
		}

		$description = trim( $description );

		echo apply_filters( 'wc_zcwc_stripe_connect_description', wpautop( wp_kses_post( $description ) ), $this->id ); // wpcs: xss ok.

		if ( $display_tokenization ) {
			$this->tokenization_script();
			$this->saved_payment_methods();
		}

		$this->elements_form();

		if ( apply_filters( 'wc_zcwc_stripe_connect_display_save_payment_method_checkbox', $display_tokenization ) && ! is_add_payment_method_page() && ! isset( $_GET['change_payment_method'] ) ) { // wpcs: csrf ok.

			$this->save_payment_method_checkbox();
		}

		do_action( 'wc_zcwc_stripe_connect_cards_payment_fields', $this->id );

		echo '</div>';

		ob_end_flush();
	}

	/**
	 * Renders the Stripe elements form.
	 *
	 * @since 4.0.0
	 * @version 4.0.0
	 */
	public function elements_form() {
		?>

		<!-- Used to display form errors -->
		<div class="stripe-connect-source-errors" role="alert"></div>
		
		<fieldset id="wc-<?php echo esc_attr( $this->id ); ?>-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">
			<?php do_action( 'woocommerce_credit_card_form_start', $this->id ); ?>

			<div class="form-row form-row-wide">
				<label for="zcwc_stripe_connect-card-number"><?php esc_html_e( 'Card Number', 'woocommerce-stripe-connect' ); ?> <span class="required">*</span></label>
				<div class="stripe-card-group">
					<div id="zcwc_stripe_connect-card-number" class="wc-stripe-elements-field">
					<!-- a Stripe Element will be inserted here. -->
					</div>

					<i class="stripe-credit-card-brand stripe-card-brand" alt="Credit Card"></i>
				</div>
			</div>

			<div class="form-row form-row-first">
				<label for="zcwc_stripe_connect-card-expiry"><?php esc_html_e( 'Expiry Date', 'woocommerce-stripe-connect' ); ?> <span class="required">*</span></label>

				<div id="zcwc_stripe_connect-card-expiry" class="wc-stripe-elements-field">
				<!-- a Stripe Element will be inserted here. -->
				</div>
			</div>

			<?php if( isset( $_GET['change_payment_method'] ) || 'yes' != $this->cvc_on_saved ) { ?>
				<div class="form-row form-row-last">
					<label for="zcwc_stripe_connect-card-cvc"><?php esc_html_e( 'Card Code (CVC)', 'woocommerce-stripe-connect' ); ?> <span class="required">*</span></label>
					<div id="zcwc_stripe_connect-card-cvc" class="wc-stripe-elements-field">
					<!-- a Stripe Element will be inserted here. -->
					</div>
				</div>
			<?php } ?>
			
			<div class="clear"></div>
			<?php do_action( 'woocommerce_credit_card_form_end', $this->id ); ?>
			<div class="clear"></div>
		
		</fieldset>

		<?php if( !isset( $_GET['change_payment_method'] ) && 'yes' == $this->cvc_on_saved ) { ?>
			<div class="clear"></div>
			<div class="form-row form-row-first">
				<label for="zcwc_stripe_connect-card-cvc"><?php esc_html_e( 'Card Code (CVC)', 'woocommerce-stripe-connect' ); ?> <span class="required">*</span></label>
				<div id="zcwc_stripe_connect-card-cvc" class="wc-stripe-elements-field">
				<!-- a Stripe Element will be inserted here. -->
				</div>
			</div>
			<div class="clear"></div>
		<?php }


	}

	/**
	 * Process the payment.
	 *
	 * @param int $order_id
	 *
	 * @return array|void
	 */
	public function process_payment( $order_id ) {
		return include( ZCWC_STRIPE_CONNECT_PATH . '/includes/process-payment.php' );
	}

	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		return include( ZCWC_STRIPE_CONNECT_PATH . '/includes/process-refund.php' );
	}

	public function add_payment_method() {
		return include( ZCWC_STRIPE_CONNECT_PATH . '/includes/process-add-payment-method.php' );
	}

	public function update_order_status( $order, $payment ) {

		// Check if order exists.
		if ( ! $order ) {
			return;
		}

		if ( isset( $payment->status ) && isset( $payment->id ) ) {
			
			update_post_meta( $order->get_id(), '_stripe_payment_data', $payment );

			if( isset( $payment->charges->data[0]->id ) ) {
				update_post_meta( $order->get_id(), '_stripe_charge', $payment->charges->data[0]->id );
			}

			switch ( $payment->status ) {
				case 'requires_payment_method' :
				case 'requires_confirmation' :
				case 'requires_action' :
					break;
				case 'processing':
				case 'succeeded':
					$order->add_order_note( esc_html__( 'Stripe: Payment authorized.', 'woocommerce-stripe-connect' ) );
					// Changing the order for processing and reduces the stock.
					$order->payment_complete( sanitize_text_field( (string) $payment->id ) );
					break;
				case 'canceled' :
					$order->update_status( 'cancelled', esc_html__( 'Stripe: Payment cancelled.', 'woocommerce-stripe-connect' ) );
					if ( function_exists( 'wc_increase_stock_levels' ) ) {
						wc_increase_stock_levels( $order->get_id() );
					}
					break;
				default :
					break;
	
			}
		}
		return;
	}


	protected function get_order_by_payment_id( $payment_id ) {
		global $wpdb;

		$query = $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} as p 
			LEFT JOIN {$wpdb->postmeta} as meta on (p.ID = meta.post_id AND meta.meta_key = %s)
			WHERE p.post_type = %s
			AND meta.meta_value = %s
			LIMIT 1",
			'_stripe_payment_id',
			'shop_order',
			$payment_id 
		);

		$order_id = $wpdb->get_var( $query );

		if( ! is_null( $order_id ) ) {
			return wc_get_order( $order_id );
		} else {
			return false;
		}

	}

	/**
	 * Get order items.
	 *
	 * @param  WC_Order $order Order data.
	 *
	 * @return array           Items list, extra amount and shipping cost.
	 */
	protected function get_order_items( $order ) {
		$items         = array();
		$extra_amount  = 0;
		$shipping_cost = 0;

		// Products.
		if ( 0 < count( $order->get_items() ) ) {
			foreach ( $order->get_items() as $order_item ) {
				if ( $order_item['qty'] ) {
					$item_total = $order->get_item_total( $order_item, false );
					if ( 0 >= (float) $item_total ) {
						continue;
					}

					$item_name = $order_item['name'];

					if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0', '<' ) ) {
						if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.4.0', '<' ) ) {
							$item_meta = new WC_Order_Item_Meta( $order_item['item_meta'] );
						} else {
							$item_meta = new WC_Order_Item_Meta( $order_item );
						}

						if ( $meta = $item_meta->display( true, true ) ) {
							$item_name .= ' - ' . $meta;
						}
					}

					$items[] = array(
						'description' => $this->sanitize_description( str_replace( '&ndash;', '-', $item_name ) ),
						'amount'      => $this->money_format( $item_total ),
						'quantity'    => $order_item['qty'],
					);
				}
			}
		}

		// Fees.
		if ( 0 < count( $order->get_fees() ) ) {
			foreach ( $order->get_fees() as $fee ) {
				if ( 0 >= (float) $fee['line_total'] ) {
					continue;
				}

				$items[] = array(
					'description' => $this->sanitize_description( $fee['name'] ),
					'amount'      => $this->money_format( $fee['line_total'] ),
					'quantity'    => 1,
				);
			}
		}

		// Taxes.
		if ( 0 < count( $order->get_taxes() ) ) {
			foreach ( $order->get_taxes() as $tax ) {
				$tax_total = $tax['tax_amount'] + $tax['shipping_tax_amount'];
				if ( 0 >= (float) $tax_total ) {
					continue;
				}

				$items[] = array(
					'description' => $this->sanitize_description( $tax['label'] ),
					'amount'      => $this->money_format( $tax_total ),
					'quantity'    => 1,
				);
			}
		}

		// Shipping Cost.
		if ( 0 < $order->get_total_shipping() ) {
			$shipping_cost = $this->money_format( $order->get_total_shipping() );
		}

		// Discount.
		if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.3', '<' ) ) {
			if ( 0 < $order->get_order_discount() ) {
				$extra_amount = '-' . $this->money_format( $order->get_order_discount() );
			}
		}
		
		return array(
			'items'         => $items,
			'extra_amount'  => $extra_amount,
			'shipping_cost' => $shipping_cost,
		);
	}

	protected function get_numbers( $string ) {
		return preg_replace( '([^0-9])', '', $string );
	}

	/**
	 * Money format.
	 *
	 * @since 1.0.0
	 * @param  int/float $value Value to fix.
	 * @return float            Fixed value.
	 */
	public function money_format( $value ) {
		// return number_format( $value, 2, '.', '' );
		return intval( number_format( $value, 2, '', '' ) );
	}

	/**
	 * Sanitize the item description.
	 * @since 1.0.0
	 * @param  string $description Description to be sanitized.
	 * @return string
	 */
	protected function sanitize_description( $description ) {
		return sanitize_text_field( substr( $description, 0, 95 ) );
	}

	/**
	 * Sanitize the item description.
	 * @since 1.0.0
	 * @param  int $transfer_id Stripe transfer id
	 * @return string
	 */
	public function get_transfer_url( $transfer_id ) {
		$url = 'yes' == $this->sandbox ? 'https://dashboard.stripe.com/test/connect/transfers/' : 'https://dashboard.stripe.com/connect/transfers/';
		return $url . $transfer_id;
	}

	/**
	 * Sanitize the item description.
	 * @since 1.2.0
	 * @param  object $order WC_Order
	 * @return array
	 */
	public function get_customer_details_from_order( $order ) {
		return array(
            'address' => array(
                'city' => $order->has_shipping_address() ? $order->get_shipping_city(): $order->get_billing_city(),
                'country' => $order->has_shipping_address() ? $order->get_shipping_country(): $order->get_billing_country(),
                'line1' => $order->has_shipping_address() ? $order->get_shipping_address_1(): $order->get_billing_address_1(),
                'line2' => $order->has_shipping_address() ? $order->get_shipping_address_2(): $order->get_billing_address_2(),
                'postal_code' => $order->has_shipping_address() ? $order->get_shipping_postcode(): $order->get_billing_postcode(),
                'state' => $order->has_shipping_address() ? $order->get_shipping_state(): $order->get_billing_state(),
            ),
            'name' => $order->get_billing_first_name().' '.$order->get_billing_last_name(),
            'phone' => $order->get_billing_phone(),
            'carrier' => $order->get_shipping_method() ? $order->get_shipping_method(): '',
        );
	}

	/**
	 * Gets the saved customer id if exists.
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 */
	public function get_stripe_customer_id( $order ) {
		$customer = get_user_option( '_stripe_customer_id', $order->get_customer_id() );

		if ( empty( $customer ) ) {
			// Try to get it via the order.
			return $order->get_meta( '_stripe_customer_id', true );
		} else {
			return $customer;
		}

		return false;
	}

	/**
	 * Check if order has subscription
	 *
	 * @since 1.2.0
	 * @param int $order_id 
	 * @return boolean
	 */
	public function has_subscription( $order_id ) {
		return ( function_exists( 'wcs_order_contains_subscription' ) && ( wcs_order_contains_subscription( $order_id ) || wcs_is_subscription( $order_id ) || wcs_order_contains_renewal( $order_id ) ) );
	}


	/**
	 * Saves payment method id to subscriptions
	 *
	 * @since 1.2.0
	 * @param int $order_id 
	 * @param string $stripe_customer_id from Stripe
	 * @param string $payment_methid_id from Stripe
	 * @return null
	 */
	protected function save_subscription_payment_method( $order_id, $stripe_customer_id, $payment_method_id ) {

		// Also store it on the subscriptions being purchased or paid for in the order
		if ( wcs_order_contains_subscription( $order_id ) ) {
			$subscriptions = wcs_get_subscriptions_for_order( $order_id );
		} elseif ( wcs_order_contains_renewal( $order_id ) ) {
			$subscriptions = wcs_get_subscriptions_for_renewal_order( $order_id );
		} else {
			$subscriptions = array();
		}

		foreach( $subscriptions as $subscription ) {
			update_post_meta( $subscription->get_id(), '_stripe_customer_id', $stripe_customer_id );
			update_post_meta( $subscription->get_id(), '_stripe_method_id', $payment_method_id );

		}
		return;
	}

	/**
	 * Saves payment method id to subscriptions
	 *
	 * @since 1.2.0
	 * @param object $payment_method Stripe paument method object
	 * @return object
	 */
	public function set_payment_method_token( $payment_method ) {
		$token = new WC_Payment_Token_CC();
		$token->set_token( $payment_method->id );
		$token->set_gateway_id( $this->id );
		$token->set_card_type( $payment_method->card->brand );
		$token->set_last4( $payment_method->card->last4 );
		$token->set_expiry_month( $payment_method->card->exp_month );
		$token->set_expiry_year( $payment_method->card->exp_year );
		$token->set_user_id( get_current_user_id() );
		$token->save();
		return $token;
	}


	/**
	 * Adds the necessary hooks to modify the "Pay for order" page in order to clean
	 * it up and prepare it for the Stripe PaymentIntents modal to confirm a payment.
	 *
	 * @since 4.2
	 * @param WC_Payment_Gateway[] $gateways A list of all available gateways.
	 * @return WC_Payment_Gateway[]          Either the same list or an empty one in the right conditions.
	 */
	public function prepare_order_pay_page( $gateways ) {
		if ( ! is_wc_endpoint_url( 'order-pay' ) || ! isset( $_GET['wc-stripe-connect-confirmation'] ) ) { // wpcs: csrf ok.
			return $gateways;
		}

		try {
			$this->prepare_intent_for_order_pay_page();
		} catch ( WC_Stripe_Exception $e ) {
			// Just show the full order pay page if there was a problem preparing the Payment Intent
			return $gateways;
		}

		add_filter( 'woocommerce_checkout_show_terms', '__return_false' );
		add_filter( 'woocommerce_pay_order_button_html', '__return_false' );
		add_filter( 'woocommerce_available_payment_gateways', '__return_empty_array' );
		add_filter( 'woocommerce_no_available_payment_methods_message', array( $this, 'change_no_available_methods_message' ) );
		add_action( 'woocommerce_pay_order_after_submit', array( $this, 'render_payment_intent_inputs' ) );

		return array();
	}

	/**
	 * Changes the text of the "No available methods" message to one that indicates
	 * the need for a PaymentIntent to be confirmed.
	 *
	 * @since 4.2
	 * @return string the new message.
	 */
	public function change_no_available_methods_message() {
		return wpautop( __( "Almost there!\n\nYour order has already been created, the only thing that still needs to be done is for you to authorize the payment with your bank.", 'woocommerce-stripe-connect' ) );
	}

	/**
	 * Prepares the Payment Intent for it to be completed in the "Pay for Order" page.
	 *
	 * @param WC_Order|null $order Order object, or null to get the order from the "order-pay" URL parameter
	 *
	 * @throws WC_Stripe_Exception
	 * @since 4.3
	 */
	public function prepare_intent_for_order_pay_page( $order = null ) {
		if ( ! isset( $order ) || empty( $order ) ) {
			$order = wc_get_order( absint( get_query_var( 'order-pay' ) ) );
		}
		$intent = $this->get_intent_from_order( $order );

		if ( ! $intent ) {
			throw new Exception( esc_html__( 'Payment Intent not found for order #' . $order->get_id(), 'woocommerce-stripe-connect' ) );
		}

		return true;
	}

	/**
	 * Renders hidden inputs on the "Pay for Order" page in order to let Stripe handle PaymentIntents.
	 *
	 * @param WC_Order|null $order Order object, or null to get the order from the "order-pay" URL parameter
	 *
	 * @throws WC_Stripe_Exception
	 * @since 4.2
	 */
	public function render_payment_intent_inputs( $order = null ) {
		if ( ! isset( $order ) || empty( $order ) ) {
			$order = wc_get_order( absint( get_query_var( 'order-pay' ) ) );
		}

		$client_secret = $order->get_meta('_stripe_connect_client_secret');

		$verification_url = add_query_arg(
			array(
				'order'            => $order->get_id(),
				'nonce'            => wp_create_nonce( 'wc_stripe_connect_confirm_pi' ),
				'redirect_to'      => rawurlencode( $this->get_return_url( $order ) ),
				'is_pay_for_order' => true,
			),
			WC_AJAX::get_endpoint( 'wc_stripe_connect_verify_intent' )
		);

		echo '<input type="hidden" id="stripe-connect-intent-id" value="' . esc_attr( $client_secret ) . '" />';
		echo '<input type="hidden" id="stripe-connect-intent-return" value="' . esc_attr( $verification_url ) . '" />';
	}

	/**
	 * Attached to `woocommerce_payment_successful_result` with a late priority,
	 * this method will combine the "naturally" generated redirect URL from
	 * WooCommerce and a payment/setup intent secret into a hash, which contains both
	 * the secret, and a proper URL, which will confirm whether the intent succeeded.
	 *
	 * @since 4.2.0
	 * @param array $result   The result from `process_payment`.
	 * @param int   $order_id The ID of the order which is being paid for.
	 * @return array
	 */
	public function modify_successful_payment_result( $result, $order_id ) {
		if ( ! isset( $result['connect_payment_intent_secret'] ) && ! isset( $result['connect_setup_intent_secret'] ) ) {
			// Only redirects with intents need to be modified.
			return $result;
		}

		// Put the final thank you page redirect into the verification URL.
		$verification_url = add_query_arg(
			array(
				'order'       => $order_id,
				'nonce'       => wp_create_nonce( 'wc_stripe_connect_confirm_pi' ),
				'redirect_to' => rawurlencode( $result['redirect'] ),
			),
			WC_AJAX::get_endpoint( 'wc_stripe_connect_verify_intent' )
		);

		if ( isset( $result['connect_payment_intent_secret'] ) ) {
			$redirect = sprintf( '#confirm-connect-pi-%s:%s', $result['connect_payment_intent_secret'], rawurlencode( $verification_url ) );
		} else if ( isset( $result['connect_setup_intent_secret'] ) ) {
			$redirect = sprintf( '#confirm-connect-si-%s:%s', $result['connect_setup_intent_secret'], rawurlencode( $verification_url ) );
		}

		return array(
			'result'   => 'success',
			'redirect' => $redirect,
		);
	}

	/**
	 * Executed between the "Checkout" and "Thank you" pages, this
	 * method updates orders based on the status of associated PaymentIntents.
	 *
	 * @since 4.2.0
	 * @param WC_Order $order The order which is in a transitional state.
	 */
	public function verify_intent_after_checkout( $order ) {
		$payment_method = $order->get_payment_method();
		if ( $payment_method !== $this->id ) {
			// If this is not the payment method, an intent would not be available.
			return;
		}

		$intent = $this->get_intent_from_order( $order );
		if ( ! $intent ) {
			// No intent, redirect to the order received page for further actions.
			return;
		}

		// A webhook might have modified or locked the order while the intent was retreived. This ensures we are reading the right status.
		clean_post_cache( $order->get_id() );
		$order = wc_get_order( $order->get_id() );

		if ( 'pending' !== $order->get_status() && 'failed' !== $order->get_status() ) {
			// If payment has already been completed, this function is redundant.
			return;
		}

		if ( $this->lock_order_payment( $order, $intent ) ) {
			return;
		}

		if ( 'setup_intent' === $intent->object && 'succeeded' === $intent->status ) {
			WC()->cart->empty_cart();
			$order->payment_complete();
		} elseif ( 'succeeded' === $intent->status ) {
			WC()->cart->empty_cart();
			$this->update_order_status( $order, $intent );
		} else if ( 'requires_payment_method' === $intent->status ) {
			// `requires_payment_method` means that SCA got denied for the current payment method.
			$this->failed_sca_auth( $order, $intent );
		}

		$this->unlock_order_payment( $order );
	}

	/**
	 * Loads the order from the current request.
	 *
	 * @since 1.3.0
	 * @throws Exception An exception if there is no order ID or the order does not exist.
	 * @return WC_Order
	 */
	public function get_order_from_request() {
		if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_GET['nonce'] ), 'wc_stripe_connect_confirm_pi' ) ) {
			throw new Exception( esc_html__( 'CSRF verification failed.', 'woocommerce-stripe-connect' ) );
		}

		// Load the order ID.
		$order_id = null;
		if ( isset( $_GET['order'] ) && absint( $_GET['order'] ) ) {
			$order_id = absint( $_GET['order'] );
		}

		// Retrieve the order.
		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			throw new Exeption( esc_html__( 'Missing order ID for payment confirmation', 'woocommerce-stripe-connect' ) );
		}

		return $order;
	}

	/**
	 * Handles exceptions during intent verification.
	 *
	 * @since 1.3
	 * @param Exception $e           The exception that was thrown.
	 * @param string              $redirect_url An URL to use if a redirect is needed.
	 */
	public function handle_error( $e, $redirect_url ) {
		// Log the exception before redirecting.
		$message = sprintf( 'PaymentIntent verification exception: %s', $e->getMessage() );
		$this->log->add( $this->id, $message );

		// `is_ajax` is only used for PI error reporting, a response is not expected.
		if ( isset( $_GET['is_ajax'] ) ) {
			exit;
		}

		wp_safe_redirect( $redirect_url );
		exit;
	}

	/**
	 * Retrieves the payment intent, associated with an order.
	 *
	 * @since 1.3
	 * @param WC_Order $order The order to retrieve an intent for.
	 * @return obect|bool     Either the intent object or `false`.
	 */
	public function get_intent_from_order( $order ) {
		$order_id = $order->get_id();

		\Stripe\Stripe::setAppInfo(
		    'WooCommerce Stripe Connect',
		    '1.0.0',
		    site_url(),
		    'pp_partner_HF4cic88ihICiq'
		);
		\Stripe\Stripe::setApiKey($this->get_stripe_secret_key());
		\Stripe\Stripe::setApiVersion("2019-05-16");

		$intent_id = $order->get_meta( '_stripe_connect_intent_id' );

		if ( $intent_id ) {
			return \Stripe\PaymentIntent::retrieve( $intent_id );
		}

		$intent_id = $order->get_meta( '_stripe_connect_setup_id' );

		if ( $intent_id ) {
			return \Stripe\SetupIntent::retrieve( $intent_id );
		}

		return false;
	}

	/**
	 * Locks an order for payment intent processing for 5 minutes.
	 *
	 * @since 1.3
	 * @param WC_Order $order  The order that is being paid.
	 * @param stdClass $intent The intent that is being processed.
	 * @return bool            A flag that indicates whether the order is already locked.
	 */
	public function lock_order_payment( $order, $intent ) {
		$order_id       = $order->get_id();
		$transient_name = 'wc_stripe_connect_processing_intent_' . $order_id;
		$processing     = get_transient( $transient_name );

		// Block the process if the same intent is already being handled.
		if ( $processing === $intent->id ) {
			return true;
		}

		// Save the new intent as a transient, eventually overwriting another one.
		set_transient( $transient_name, $intent->id, 5 * MINUTE_IN_SECONDS );

		return false;
	}

	/**
	 * Unlocks an order for processing by payment intents.
	 *
	 * @since 1.3
	 * @param WC_Order $order The order that is being unlocked.
	 */
	public function unlock_order_payment( $order ) {
		$order_id = $order->get_id();
		delete_transient( 'wc_stripe_connect_processing_intent_' . $order_id );
	}


	/**
	 * Checks if the payment intent associated with an order failed and records the event.
	 *
	 * @since 1.3
	 * @param WC_Order $order  The order which should be checked.
	 * @param object   $intent The intent, associated with the order.
	 */
	public function failed_sca_auth( $order, $intent ) {
		// If the order has already failed, do not repeat the same message.
		if ( 'failed' === $order->get_status() ) {
			return;
		}

		// Load the right message and update the status.
		$status_message = isset( $intent->last_payment_error )
			/* translators: 1) The error message that was received from Stripe. */
			? sprintf( __( 'Stripe SCA authentication failed. Reason: %s', 'woocommerce-stripe-connect' ), $intent->last_payment_error->message )
			: __( 'Stripe SCA authentication failed.', 'woocommerce-stripe-connect' );
		$order->update_status( 'failed', $status_message );
	}

	/**
	 * Checks if the payment intent associated with an order failed and records the event.
	 *
	 * @since 1.4
	 * @param int $commission_id  The commission id.
	 * @param int $order_item_id  The order item id.
	 * @param string $status The commission status.
	 */
	public function update_commission_status( $commission_id, $order_item_id, $status ) {
		$commission = new WC_Product_Vendors_Commission( new WC_Product_Vendors_Stripe_Connect_MassPay );
		return $commission->update_status( $commission_id, $order_item_id, $status );
	}

}
