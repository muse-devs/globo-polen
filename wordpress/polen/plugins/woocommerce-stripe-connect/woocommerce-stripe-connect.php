<?php
/**
 * Plugin Name: WooCommerce Stripe Connect
 * Version: 1.6.0
 * Plugin URI: https://codecanyon.net/item/stripe-connect-for-woocommerce-product-vendors
 * Description: WooCommerce Stripe Connect plugin integration for WooCommerce Product Vendors.
 * Author: Felipe Rinaldi
 * Author URI: https://codecanyon.net/user/feliperinaldi
 * Requires at least: 4.4.0
 * Requires PHP: 7.0
 * Tested up to: 5.7.2
 * WC requires at least: 3.0
 * WC tested up to: 5.4.1
 * Text Domain: woocommerce-stripe-connect
 * Domain Path: /languages
 *
 * @package WordPress
 * @author FelipeRinaldi
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ZCWC_STRIPE_CONNECT_VERSION', '1.6.0' );

class ZCWC_Stripe_Connect {

	protected $gateway;

	private static $_instance = null;

	/**
	 * Get the single instance aka Singleton
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return bool
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Prevent cloning
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return bool
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'woocommerce-stripe-connect' ), ZCWC_STRIPE_CONNECT_VERSION );
	}

	/**
	 * Prevent unserializing instances
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return bool
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'woocommerce-stripe-connect' ), ZCWC_STRIPE_CONNECT_VERSION );
	}

	/**
	 * Construct
	 *
	 * @access private
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return bool
	 */
	private function __construct() {
		
		add_action( 'plugins_loaded', array( $this, 'init' ), 1 );

		return true;
	}

	/**
	 * Define constants
	 *
	 * @access private
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return bool
	 */
	private function define_constants() {
		global $wpdb;

		define( 'ZCWC_STRIPE_CONNECT_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'ZCWC_STRIPE_CONNECT_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		
		return true;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return bool
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'zcwc_stipe_connect_plugin_locale', get_locale(), 'woocommerce-stripe-connect' );

		load_textdomain( 'woocommerce-stripe-connect', trailingslashit( WP_LANG_DIR ) . 'woocommerce-stripe-connect/woocommerce-stripe-connect' . '-' . $locale . '.mo' );

		load_plugin_textdomain( 'woocommerce-stripe-connect', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		return true;
	}

	/**
	 * Include all files needed
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return bool
	 */
	public function dependencies() {
		
		require_once( dirname( __FILE__ ) . '/includes/masspay/class-wc-product-vendors-stripe-masspay.php' );
		require_once( dirname( __FILE__ ) . '/includes/class-wc-stripe-connect-gateway.php' );
		require_once( dirname( __FILE__ ) . '/includes/class-wc-stripe-connect-subs-gateway.php' );
		
		return true;
	}


	/**
	 * Init
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 2.1.5
	 * @return bool
	 */
	public function init() {

		$this->define_constants();
		$this->load_plugin_textdomain();
		$this->dependencies();
		
		require_once ZCWC_STRIPE_CONNECT_PATH . '/vendor/autoload.php';

		if( ! class_exists('WooCommerce') ) {
			add_action( 'admin_notices', array( $this, 'woocommerce_missing_notice' ) );
			return;
		}

		if ( ! function_exists( 'phpversion' ) ||  version_compare( phpversion(), '5.5', '<' ) ) {
			add_action( 'admin_notices', array( $this, 'php_version_notice' ) );
			return;
		}

		/* if( ! class_exists('WC_Product_Vendors_Utils')) {
			add_action( 'admin_notices', array( $this, 'woocommerce_product_vendors_notice' ) );
			return;
		} */

		add_filter( 'plugin_action_links_'. plugin_basename(__FILE__), array( $this, 'plugin_action_links' ) );

		add_filter( 'woocommerce_payment_gateways', array( $this, 'register_gateway' ), 99, 1 );

		// registers vendor menus
		add_action( 'admin_menu', array( $this, 'register_vendor_menus' ), 999 );

		add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'admin_order_display_stripe_transfers' ) );

		add_action( 'wcpv_commission_added', array( $this, 'handle_commission' ) );

		add_filter( 'bulk_actions-toplevel_page_wcpv-commissions', array( $this, 'add_commission_bulk_actions' ) );

		add_action( 'wcpv_commission_list_bulk_action', array( $this, 'process_bulk_action' ) );

		add_action( 'woocommerce_process_product_meta_subscription', array( $this, 'save_product_pass_shipping_tax_field_general' ) );
		add_action( 'woocommerce_process_product_meta_variable-subscription', array( $this, 'save_product_pass_shipping_tax_field_general' ) );

		add_action( 'wc_ajax_wc_stripe_connect_verify_intent', 
			array( $this, 'verify_intent' ) );

		// Modify emails emails.
		add_filter( 'woocommerce_email_classes', array( $this, 'add_emails' ), 20 );

	}



	/**
	 * Add stripe link.
	 *
	 * @param array $links payment links
	 *
	 * @return array payment links
	 */
	function plugin_action_links($links)
	{
	    $links[] = '<a href="'.esc_url(admin_url('admin.php?page=wc-settings&tab=checkout&section=zcwc_stripe_connect')).'">'.esc_html__('Settings', 'woocommerce-stripe-connect').'</a>';

	    return $links;
	}

	/**
	 * Register the gateway for use
	 */
	public function register_gateway( $methods ) {
		
		if ( class_exists( 'WC_Subscriptions_Order' ) && function_exists( 'wcs_create_renewal_order' ) ) {
			$methods[] = 'ZCWC_Stripe_Connect_Subs_Gateway';
		} else {
			$methods[] = 'ZCWC_Stripe_Connect_Gateway';
		} 

		return $methods;
	}

	function register_vendor_menus() {

		//if ( WC_Product_Vendors_Utils::auth_vendor_user() ) {

			//if ( WC_Product_Vendors_Utils::is_admin_vendor() ) {

				add_menu_page( esc_html__( 'Stripe Settings', 'woocommerce-stripe-connect' ), esc_html__( 'Stripe Settings', 'woocommerce-stripe-connect' ), 'manage_product', 'zcwc-stripe-connect', array( $this, 'render_settings_page' ), 'dashicons-admin-settings', 62.78 );
			//}
		//}

		return true;
	}

	/**
	 * Renders the stripe settings page
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return bool
	 */
	public function render_settings_page() {

		global $zcwc_stripe_connect_notices;

		$this->revoke_stripe();
		$this->get_auth_stripe();
		// grab the newly saved settings
		$stripe_user_id = $stripeLiveMode = $account_type = $charges_enabled = $account_link = '';
		$redirect_uri = admin_url('admin.php?page=zcwc-stripe-connect');
		$gateway = ZCWC_Stripe_Connect_Gateway::get_instance();
		$stripe_data = get_term_meta( get_current_user_id(), 'stripe_data', true );

	    $stripe_sandbox = $gateway->sandbox;
	    if ($stripe_sandbox == 'yes') {
            $client_secret = $gateway->stripe_test_secret_key;
            $client_id = $gateway->stripe_test_client_id;
        } else {
            $client_secret = $gateway->stripe_live_secret_key;
            $client_id = $gateway->stripe_live_client_id;
        }

	    $connect_standard_url = 'https://connect.stripe.com/oauth/authorize?response_type=code&client_id='.$client_id.'&redirect_uri='.urlencode($redirect_uri).'&scope=read_write';

		$connect_express_url = 'https://connect.stripe.com/express/oauth/authorize?response_type=code&client_id='.$client_id.'&redirect_uri='.urlencode($redirect_uri);


		if( isset( $stripe_data['stripe_user_id'] ) && '' != $stripe_data['stripe_user_id'] ) {
	    	$stripe_user_id = $stripe_data['stripe_user_id'];
	    	$stripeLiveMode = $stripe_data['stripe_livemode'] ? 'Yes' : 'No';

	    	try {
		    	\Stripe\Stripe::setAppInfo(
				    'WooCommerce Stripe Connect',
				    '1.0.0',
				    site_url(),
				    'pp_partner_HF4cic88ihICiq'
				);
				\Stripe\Stripe::setApiKey($gateway->get_stripe_secret_key());
				\Stripe\Stripe::setApiVersion("2019-05-16");
				$account = \Stripe\Account::retrieve( $stripe_user_id );

				if( isset( $account->charges_enabled ) && true === $account->charges_enabled  ) {
					$charges_enabled = true;
					$account_type = $account->type;
					
					if( 'express' == $account->type ) {

						$account_link = get_transient('zcsc_account_link');

						if( false === $account_link ) {

							$account_links = \Stripe\AccountLink::create([
							  'account' => $stripe_user_id,
							  'refresh_url' => $redirect_uri,
							  'return_url' => $redirect_uri,
							  'type' => 'account_onboarding',
							]);

							if( isset( $account_links->url ) && '' != $account_links->url ) {
								$account_link = $account_links->url;
								$seconds = absint( $account_links->expires_at ) - time();
								set_transient('zcsc_account_link', $account_link, $seconds );
							}

						}
					}
				} else {
					$zcwc_stripe_connect_notices['error'] = __('Please finish connecting your Stripe account.', 'woocommerce-stripe-connect' );
				}
			} catch (Exception $e) {
				$zcwc_stripe_connect_notices['error'] = $e->getMessage();
			}

			if( empty( $zcwc_stripe_connect_notices ) ) {
				$zcwc_stripe_connect_notices['success'] = esc_html__('Connected Successfully', 'woocommerce-stripe-connect');
			}

	    }


		include( ZCWC_STRIPE_CONNECT_PATH . '/includes/admin/views/html-stripe-store-settings-page.php' );

		return true;
	}

	/**
	 * Authenticating stripe details.
	 *
	 * @return void
	 */
	public function get_auth_stripe() {
	    
	    global $zcwc_stripe_connect_notices;

	    $zcwc_stripe_connect_notices = is_array( $zcwc_stripe_connect_notices ) ? $zcwc_stripe_connect_notices : array();
	    $error_warning = '';
	    $client_id = '';
	    $success_message = '';
	    $code = '';

	    if ( isset( $_GET['code'] ) ) {
	        
	        $code = $_GET['code'];
	        $client_secret = '';
	        $gateway = ZCWC_Stripe_Connect_Gateway::get_instance();
	        $stripe_sandbox = $gateway->sandbox;
	        if ($stripe_sandbox == 'yes') {
	            $client_secret = $gateway->stripe_test_secret_key;
	            $client_id = $gateway->stripe_test_client_id;
	        } else {
	            $client_secret = $gateway->stripe_live_secret_key;
	            $client_id = $gateway->stripe_live_client_id;
	        }

	        $token_request_body = array(
	            'grant_type' => 'authorization_code',
	            'client_id' => $client_id,
	            'code' => $code,
	            'client_secret' => $client_secret,
	        );

	        $req = curl_init('https://connect.stripe.com/oauth/token');
	        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($req, CURLOPT_POST, true);
	        curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
	        curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($req, CURLOPT_SSL_VERIFYHOST, 2);
	        curl_setopt($req, CURLOPT_VERBOSE, true);
	        
	        $respcode = curl_getinfo($req, CURLINFO_HTTP_CODE);
	        $resp = json_decode(curl_exec($req), true);
	        
	        if (isset($resp['error'])) {
	            $error_warning = $resp['error'].':'.$resp['error_description'];
	        } else {
	            $access_token = $resp['access_token'];
	            
	            $this->add_stripe_connect( $resp );
	            
	            $success_message = esc_html__('Connected Successfully', 'woocommerce-stripe-connect');
	        }
	        curl_close($req);

	    } elseif (isset($_GET['error'])) {
	        
	        $error_warning = $_GET['error_description'];
	    
	    }

	    if ('' !== $error_warning && !defined('DOING_AJAX')) {
	    	$zcwc_stripe_connect_notices['error'] = $error_warning;
	    } elseif ('' !== $success_message && !defined('DOING_AJAX')) {
	    	$zcwc_stripe_connect_notices['success'] = $success_message;
	    	echo '<script>window.location.href = "'.admin_url('admin.php?page=zcwc-stripe-connect').'";</script>';
	    }
	}

	/**
	 * Revoke Stripe account access.
	 *
	 * @return void
	 */
	public function revoke_stripe() {
	    
	    global $zcwc_stripe_connect_notices;

	    $zcwc_stripe_connect_notices = is_array( $zcwc_stripe_connect_notices ) ? $zcwc_stripe_connect_notices : array();
	    $error_warning = '';
	    $client_id = '';
	    $success_message = '';
	    $code = '';

	    if ( isset( $_GET['revoke_stripe_connect'] ) ) {
	        
	        $stripe_user_id = $client_secret = '';
	        $gateway = ZCWC_Stripe_Connect_Gateway::get_instance();
	        $stripe_sandbox = $gateway->sandbox;
	        if ($stripe_sandbox == 'yes') {
	            $client_id = $gateway->stripe_test_client_id;
	             $client_secret = $gateway->stripe_test_secret_key;
	        } else {
	            $client_id = $gateway->stripe_live_client_id;
	            $client_secret = $gateway->stripe_live_secret_key;
	        }
	        $stripe_data = get_term_meta( WC_Product_Vendors_Utils::get_logged_in_vendor(), 'stripe_data', true );
		    if( isset( $stripe_data['stripe_user_id'] ) ) {
		    	$stripe_user_id = $stripe_data['stripe_user_id'];
		    }

		    if('' != $stripe_user_id ) {
		        $request_body = array(
		            'client_id' => $client_id,
		            'stripe_user_id' => $stripe_user_id,
		            'client_secret' => $client_secret
		        );

		        $req = curl_init('https://connect.stripe.com/oauth/deauthorize');
		        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
		        curl_setopt($req, CURLOPT_POST, true);
		        curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($request_body));
		        curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false);
		        curl_setopt($req, CURLOPT_SSL_VERIFYHOST, 2);
		        curl_setopt($req, CURLOPT_VERBOSE, true);
		        
		        $respcode = curl_getinfo($req, CURLINFO_HTTP_CODE);
		        $resp = json_decode(curl_exec($req), true);

		        if (isset($resp['error'])) {
		            $error_warning = $resp['error'].':'.$resp['error_description'];
		        } else {

		        	$this->add_stripe_connect( array(
		        		'token_type' 				=> '',
						'stripe_publishable_key' 	=> '',
						'livemode'					=> '',
						'stripe_user_id'			=> '',
						'refresh_token'				=> '',
						'access_token'				=> ''
					) );

		            $success_message = esc_html__('Account revoked Successfully', 'woocommerce-stripe-connect');
		        }
		        curl_close($req);
		    }

	    } 

	    if ('' !== $error_warning && !defined('DOING_AJAX')) {
	    	$zcwc_stripe_connect_notices['error'] = $error_warning;
	    } elseif ('' !== $success_message && !defined('DOING_AJAX')) {
	    	$zcwc_stripe_connect_notices['success'] = $success_message;
	    }
	}

	/**
	 * Add Stripe info.
	 *
	 * @param array $resp api response
	 *
	 * @return void
	 */
	public function add_stripe_connect( $resp ) {

	    $received_stripe_data = array(
			'stripe_token_type' 		=> $resp['token_type'],
			'stripe_publishable_key' 	=> $resp['stripe_publishable_key'],
			'stripe_livemode'			=> $resp['livemode'],
			'stripe_user_id'			=> $resp['stripe_user_id'],
			'stripe_refresh_token'		=> $resp['refresh_token'],
			'stripe_access_token'		=> $resp['access_token']
		);
		
		update_term_meta( get_current_user_id(), 'stripe_data', $received_stripe_data );

		return true;

	}

	/**
	 * WooCommerce fallback notice.
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return string
	 */
	public function woocommerce_missing_notice() {
		echo '<div class="error"><p>' . sprintf( esc_html__( 'WooCommerce Stripe Connect requires WooCommerce to be installed and active. You can download %s here.', 'woocommerce-stripe-connect' ), '<a href="https://woocommerce.com/?aff=10217&cid=8995456" target="_blank">WooCommerce</a>' ) . '</p></div>';
	}

	/**
	 * WooCommerce fallback notice.
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @return string
	 */
	public function woocommerce_product_vendors_notice() {
		echo '<div class="error"><p>' . sprintf( esc_html__( 'WooCommerce Stripe Connect requires WooCommerce Product Vendors to be installed and active. You can download %s here.', 'woocommerce-stripe-connect' ), '<a href="https://woocommerce.com/products/product-vendors/?aff=10217&cid=8995456" target="_blank">WooCommerce Product Vendors</a>' ) . '</p></div>';
	}

	/**
	 * PHP version fallback notice.
	 *
	 * @access public
	 * @since 2.1.5
	 * @version 2.1.5
	 * @return string
	 */
	public function php_version_notice() {
		echo '<div class="error"><p>' . wp_kses( sprintf( esc_html__( 'WooCommerce Stripe Connect requires PHP 5.5 and above. <a href="%s">How to update your PHP version</a>', 'woocommerce' ), 'https://docs.woocommerce.com/document/how-to-update-your-php-version/' ), array(
			'a' => array(
				'href'  => array(),
				'title' => array(),
			),
		) ) . '</p></div>';
	}
	 
	/**
	 * Meta box display callback.
	 *
	 * @param WP_Order $order Current order object.
	 */
	public function admin_order_display_stripe_transfers( $order ) {
	    
		if( 'zcwc_stripe_connect' == $order->get_payment_method() ) {
			if( current_user_can( 'edit_shop_orders' ) ) {
 
		    	$transfers = maybe_unserialize( get_post_meta( $order->get_id(), '_stripe_transfers', true ) );

		    	if( !empty( $transfers ) ) {

		    		echo '<h4>Stripe Transfers</h4>';
		    		echo '<ul>';
		    		foreach( $transfers as $transfer ) {
		    			echo '<li><a href="'.esc_url( $transfer['url'] ).'" target="_blank">'.esc_html( $transfer['id'] ).'</a></li>';
		    		} 
		    		echo '</ul>';
		    	}
		    }
		}
	}

	/**
	 * Handle commission during checkout.
	 *
	 * @param object $order WC_Order Current order object.
	 * @return bool.
	 */
	public function handle_commission( $order ) {


		if( 'zcwc_stripe_connect' != $order->get_payment_method() ) {
			return;
		}

		if( ! in_array( $order->get_status() , array('completed', 'processing') ) ) {
			return;
		} 

		$gateway = ZCWC_Stripe_Connect_Gateway::get_instance();

		// Dont trasnfer money right away if manual trasnfers are enabled.
		if( $gateway->manual_transfer_enabled() ) {
			return;
		}

		$gateway->set_current_order_id( $order->get_id() );

		$commission 	= new WC_Product_Vendors_Commission( new WC_Product_Vendors_Stripe_Connect_MassPay );
		$commissions 	= $commission->get_commission_by_order_id( $order->get_id() , 'unpaid' );

		$commission_ids = array();
		foreach( $commissions as $c ) {
			$commission_ids[ $c->id ] = absint( $c->id );
		}
		
		//this will allow all commissions to be transferred separately.
		add_filter( 'wcpv_combine_total_commission_payout_per_vendor', '__return_false' );
		
		if ( ! empty( $commission_ids ) ) {
			try {
				$commission->pay( $commission_ids );

			} catch ( Exception $e ) {
				if( 'yes' === $gateway->debug ) {
					$gateway->log->add( $gateway->id, $e->getMessage() );
				}
			}
		}

		return true;
	}

	/**
	 * Add Stripe Transfer action to commission bulk actions. 
	 *
	 * @param array $actions Array containing bulk actions.
	 * @return array $actions.
	 */
	public function add_commission_bulk_actions( $actions ) {
		$actions['transfer_with_stripe'] = __('Transfer with Stripe Connect', 'woocommerce-stripe-connect');
		return $actions;
	}

	/**
	 * Process transfer with Stripe from bulk actions. 
	 *
	 * @return bool
	 */
	public function process_bulk_action() {

		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-commissions' ) ) {
			return;
		}

		if ( empty( $_REQUEST['ids'] ) ) {
			return;
		}

		// handle pay bulk action
		if ( 'transfer_with_stripe' === $_REQUEST['action'] ) {

			$commission = new WC_Product_Vendors_Commission( new WC_Product_Vendors_Stripe_Connect_MassPay );

			$ids = array_map( 'absint', $_REQUEST['ids'] );

			//this will allow all commissions to be transferred separately.
			add_filter( 'wcpv_combine_total_commission_payout_per_vendor', '__return_false' );
			
			try {
				$commission->pay( $ids );
			} catch ( Exception $e ) {
				if( class_exists('WC_Product_Vendors_Logger') ) {
					WC_Product_Vendors_Logger::log( $e->getMessage() );
				}
			}
		}

		return true;
	}


	/**
	 * Save the pass shipping field for the product general tab
	 *
	 * @access public
	 * @since 1.0.0
	 * @version 1.0.0
	 * @param int $post_id
	 * @return bool
	 */
	public function save_product_pass_shipping_tax_field_general( $post_id ) {
		if ( current_user_can( 'manage_vendors' ) ) {
			if ( empty( $post_id ) ) {
				return;
			}

			if ( ! empty( $_POST['_wcpv_product_default_pass_shipping_tax'] ) ) {
				update_post_meta( $post_id, '_wcpv_product_default_pass_shipping_tax', 'yes' );

			} else {

				update_post_meta( $post_id, '_wcpv_product_default_pass_shipping_tax', 'no' );
			}
		}

		return true;
	}

	/**
	 * Handles successful PaymentIntent authentications.
	 *
	 * @since 4.2.0
	 */
	public function verify_intent() {
		global $woocommerce;

		$gateway = $this->get_gateway();

		try {
			$order = $gateway->get_order_from_request();
		} catch ( Exception $e ) {
			/* translators: Error message text */
			$message = sprintf( __( 'Payment verification error: %s', 'woocommerce-stripe-connect' ), $e->getMessage() );
			wc_add_notice( esc_html( $message ), 'error' );

			$redirect_url = $woocommerce->cart->is_empty() ? get_permalink( wc_get_page_id( 'shop' ) ) : wc_get_checkout_url();
		}

		try {
			
			$gateway->verify_intent_after_checkout( $order );

			if ( ! isset( $_GET['is_ajax'] ) ) {
				$redirect_url = isset( $_GET['redirect_to'] ) // wpcs: csrf ok.
					? esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) // wpcs: csrf ok.
					: $gateway->get_return_url( $order );

				wp_safe_redirect( $redirect_url );
			}

			exit;
		} catch ( Exception $e ) {
			$gateway->handle_error( $e, $gateway->get_return_url( $order ) );
		}
	}

	/**
	 * Returns an instantiated gateway.
	 *
	 * @since 4.2.0
	 * @return WC_Gateway_Stripe
	 */
	protected function get_gateway() {
		if ( ! isset( $this->gateway ) ) {
			if ( class_exists( 'WC_Subscriptions_Order' ) && function_exists( 'wcs_create_renewal_order' ) ) {
				$class_name = 'ZCWC_Stripe_Connect_Subs_Gateway';
			} else {
				$class_name = 'ZCWC_Stripe_Connect_Gateway';
			}

			$this->gateway = new $class_name();
		}

		return $this->gateway;
	}

	/**
	 * Adds the failed SCA auth email to WooCommerce.
	 *
	 * @param WC_Email[] $email_classes All existing emails.
	 * @return WC_Email[]
	 */
	public function add_emails( $email_classes ) {
		require_once ZCWC_STRIPE_CONNECT_PATH . '/includes/emails/class-wc-stripe-connect-email-failed-authentication.php';
		require_once ZCWC_STRIPE_CONNECT_PATH . '/includes/emails/class-wc-stripe-connect-email-failed-renewal-authentication.php';
		require_once ZCWC_STRIPE_CONNECT_PATH . '/includes/emails/class-wc-stripe-connect-email-failed-authentication-retry.php';

		// Add all emails, generated by the gateway.
		$email_classes['WC_Stripe_Connect_Email_Failed_Renewal_Authentication']  = new WC_Stripe_Connect_Email_Failed_Renewal_Authentication( $email_classes );
		$email_classes['WC_Stripe_Connect_Email_Failed_Authentication_Retry'] = new WC_Stripe_Connect_Email_Failed_Authentication_Retry( $email_classes );

		return $email_classes;
	}
}

ZCWC_Stripe_Connect::instance();