/* global zcwc_stripe_connect_params */

jQuery( function( $ ) {
	'use strict';

	try {
		var stripe = Stripe( zcwc_stripe_connect_params.key );
	} catch( error ) {
		console.log( error );
		return;
	}

	if( 'yes' == zcwc_stripe_connect_params.is_change_payment_page ) {
		$( document.body ).trigger( 'updated_checkout' );
	}

	var stripe_elements_options = Object.keys( zcwc_stripe_connect_params.elements_options ).length ? zcwc_stripe_connect_params.elements_options : {},
		elements = stripe.elements( stripe_elements_options ),
		stripe_card,
		stripe_exp,
		stripe_cvc;

	/**
	 * Object to handle Stripe elements payment form.
	 */
	var zc_wc_stripe_connect_form = {

		/**
		 * Unmounts all Stripe elements when the checkout page is being updated.
		 */
		unmountElements: function() {
			stripe_card.unmount( '#zcwc_stripe_connect-card-number' );
			stripe_exp.unmount( '#zcwc_stripe_connect-card-expiry' );
			stripe_cvc.unmount( '#zcwc_stripe_connect-card-cvc' );
		},

		/**
		 * Mounts all elements to their DOM nodes on initial loads and updates.
		 */
		mountElements: function() {
			if ( ! $( '#zcwc_stripe_connect-card-number' ).length ) {
				return;
			}

			stripe_card.mount( '#zcwc_stripe_connect-card-number' );
			stripe_exp.mount( '#zcwc_stripe_connect-card-expiry' );
			stripe_cvc.mount( '#zcwc_stripe_connect-card-cvc' );
		},

		/**
		 * Creates all Stripe elements that will be used to enter cards or IBANs.
		 */
		createElements: function() {
			var elementStyles = {
				base: {
					iconColor: '#666EE8',
					color: '#31325F',
					fontSize: '15px',
					'::placeholder': {
				  		color: '#CFD7E0',
					}
				}
			};

			var elementClasses = {
				base: 'input-text',
				focus: 'focused',
				empty: 'empty',
				invalid: 'invalid',
			};

			elementStyles  = zcwc_stripe_connect_params.elements_styling ? zcwc_stripe_connect_params.elements_styling : elementStyles;
			elementClasses = zcwc_stripe_connect_params.elements_classes ? zcwc_stripe_connect_params.elements_classes : elementClasses;

			stripe_card = elements.create( 'cardNumber', { style: elementStyles, classes: elementClasses } );
			stripe_exp  = elements.create( 'cardExpiry', { style: elementStyles, classes: elementClasses } );
			stripe_cvc  = elements.create( 'cardCvc', { style: elementStyles, classes: elementClasses } );

			stripe_card.addEventListener( 'change', function( event ) {
				zc_wc_stripe_connect_form.onCCFormChange();

				if ( event.error ) {
					$( document.body ).trigger( 'stripeConnectError', event );
				}
			} );

			stripe_exp.addEventListener( 'change', function( event ) {
				zc_wc_stripe_connect_form.onCCFormChange();

				if ( event.error ) {
					$( document.body ).trigger( 'stripeConnectError', event );
				}
			} );

			stripe_cvc.addEventListener( 'change', function( event ) {
				zc_wc_stripe_connect_form.onCCFormChange();

				if ( event.error ) {
					$( document.body ).trigger( 'stripeConnectError', event );
				}
			} );

			/**
			 * Only in checkout page we need to delay the mounting of the
			 * card as some AJAX process needs to happen before we do.
			 */
			if ( 'yes' === zcwc_stripe_connect_params.is_checkout ) {
				$( document.body ).on( 'updated_checkout', function() {
					// Don't mount elements a second time.
					if ( stripe_card ) {
						zc_wc_stripe_connect_form.unmountElements();
					}

					zc_wc_stripe_connect_form.mountElements();

				} );
			} else if ( $( 'form#add_payment_method' ).length || $( 'form#order_review' ).length ) {
				zc_wc_stripe_connect_form.mountElements();
			}
		},

		/**
		 * Initialize event handlers and UI state.
		 */
		init: function() {
			// Initialize tokenization script if on change payment method page and pay for order page.
			if ( 'yes' === zcwc_stripe_connect_params.is_change_payment_page || 'yes' === zcwc_stripe_connect_params.is_pay_for_order_page ) {
				// $( document.body ).trigger( 'wc-credit-card-form-init' );
				// console.log('form-triggered.');
			}

			// checkout page
			if ( $( 'form.woocommerce-checkout' ).length ) {
				this.form = $( 'form.woocommerce-checkout' );
			}

			$( 'form.woocommerce-checkout' )
				.on( 'checkout_place_order_zcwc_stripe_connect',
					this.onSubmit
				);

			// pay order page
			if ( $( 'form#order_review' ).length ) {
				this.form = $( 'form#order_review' );
			}

			$( 'form#order_review, form#add_payment_method' )
				.on(
					'submit',
					this.onSubmit
				);

			// add payment method page
			if ( $( 'form#add_payment_method' ).length ) {
				this.form = $( 'form#add_payment_method' );
			}

			$( 'form.woocommerce-checkout' )
				.on(
					'change',
					this.reset
				);

			$( document.body )
				.on(
					'stripeConnectError',
					this.onError
				)
				.on(
					'checkout_error',
					this.reset
				);

			zc_wc_stripe_connect_form.createElements();

			// Listen for hash changes in order to handle payment intents
			window.addEventListener( 'hashchange', zc_wc_stripe_connect_form.onHashChange );
			zc_wc_stripe_connect_form.maybeConfirmIntent();

		},

		/**
		 * Check to see if Stripe in general is being used for checkout.
		 *
		 * @return {boolean}
		 */
		isStripeChosen: function() {
			return $( '#payment_method_zcwc_stripe_connect' ).is( ':checked' ) 
			|| ( $( '#payment_method_zcwc_stripe_connect' ).is( ':checked' ) && 'new' === $( 'input[name="wc-zcwc_stripe_connect-payment-token"]:checked' ).val() );
		},

		/**
		 * Currently only support saved cards via credit cards and SEPA. No other payment method.
		 *
		 * @return {boolean}
		 */
		isStripeSaveCardChosen: function() {
			return (
				$( '#payment_method_zcwc_stripe_connect' ).is( ':checked' )
				&& $( 'input[name="wc-zcwc_stripe_connect-payment-token"]' ).is( ':checked' )
				&& 'new' !== $( 'input[name="wc-zcwc_stripe_connect-payment-token"]:checked' ).val()
			);
		},

		/**
		 * Check if Stripe credit card is being used used.
		 *
		 * @return {boolean}
		 */
		isStripeCardChosen: function() {
			return $( '#payment_method_zcwc_stripe_connect' ).is( ':checked' );
		},

		/**
		 * Checks if a source ID is present as a hidden input.
		 * Only used when SEPA Direct Debit is chosen.
		 *
		 * @return {boolean}
		 */
		hasSource: function() {
			return 0 < $( 'input.stripe-connect-source' ).length;
		},

		/**
		 * Checks if a source ID is present as a hidden input.
		 * Only used when SEPA Direct Debit is chosen.
		 *
		 * @return {boolean}
		 */
		hasCVCToken: function() {
			return 0 < $( 'input.stripe-connect-cvc-token' ).length;
		},

		/**
		 * Check whether a mobile device is being used.
		 *
		 * @return {boolean}
		 */
		isMobile: function() {
			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test( navigator.userAgent ) ) {
				return true;
			}

			return false;
		},

		/**
		 * Blocks payment forms with an overlay while being submitted.
		 */
		block: function() {
			if ( ! zc_wc_stripe_connect_form.isMobile() ) {
				zc_wc_stripe_connect_form.form.block( {
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				} );
			}
		},

		/**
		 * Removes overlays from payment forms.
		 */
		unblock: function() {
			zc_wc_stripe_connect_form.form && zc_wc_stripe_connect_form.form.unblock();
		},

		/**
		 * Returns the selected payment method HTML element.
		 *
		 * @return {HTMLElement}
		 */
		getSelectedPaymentElement: function() {
			return $( '.payment_methods input[name="payment_method"]:checked' );
		},

		/**
		 * Initiates the creation of a Source object.
		 *
		 */
		createSource: function() {
			// Handle card payments.
			return stripe.createToken( stripe_card, {})
				.then( zc_wc_stripe_connect_form.sourceResponse );
		},

		/**
		 * Initiates the creation of a Source object.
		 *
		 */
		createCVCToken: function() {
			// Handle card payments.
			return stripe.createToken( 'cvc_update', stripe_cvc)
				.then( zc_wc_stripe_connect_form.cvcResponse );
		},

		/**
		 * Handles responses, based on source object.
		 *
		 * @param {Object} response The `stripe.createSource` response.
		 */
		sourceResponse: function( response ) {
			if ( response.error ) {
				return $( document.body ).trigger( 'stripeConnectError', response );
			}

			zc_wc_stripe_connect_form.reset();

			zc_wc_stripe_connect_form.form.append(
				$( '<input type="hidden" />' )
					.addClass( 'stripe-connect-source' )
					.attr( 'name', 'zcwc_stripe_connect_credit_card_hash' )
					.val( response.token.id )
			);

			if ( $( 'form#add_payment_method' ).length ) {
				$( zc_wc_stripe_connect_form.form ).off( 'submit', zc_wc_stripe_connect_form.form.onSubmit );
			}

			zc_wc_stripe_connect_form.form.submit();
		},

		/**
		 * Handles responses, based on source object.
		 *
		 * @param {Object} response The `stripe.createSource` response.
		 */
		cvcResponse: function( response ) {

			if ( response.error ) {
				return $( document.body ).trigger( 'stripeConnectError', response );
			}

			zc_wc_stripe_connect_form.reset();

			zc_wc_stripe_connect_form.form.append(
				$( '<input type="hidden" />' )
					.addClass( 'stripe-connect-cvc-token' )
					.attr( 'name', 'zcwc_stripe_connect_cvc_token' )
					.val( response.token.id )
			);

			console.log('token_id', response.token.id);

			if ( $( 'form#add_payment_method' ).length ) {
				$( zc_wc_stripe_connect_form.form ).off( 'submit', zc_wc_stripe_connect_form.form.onSubmit );
			}

			zc_wc_stripe_connect_form.form.submit();
		},

		/**
		 * Performs payment-related actions when a checkout/payment form is being submitted.
		 *
		 * @return {boolean} An indicator whether the submission should proceed.
		 *                   WooCommerce's checkout.js stops only on `false`, so this needs to be explicit.
		 */
		onSubmit: function() {

			if ( ! zc_wc_stripe_connect_form.isStripeChosen() ) {
				return true;
			}

			if ( zc_wc_stripe_connect_form.hasSource() ) {
				return true;
			}

			// If a source is already in place, submit the form as usual.
			if ( zc_wc_stripe_connect_form.isStripeSaveCardChosen() ) {
				
				if( 'yes' == zcwc_stripe_connect_params.cvc_on_saved ) {

					if ( 'yes' == zcwc_stripe_connect_params.is_change_payment_page ) {
						return true;
					}

					if ( zc_wc_stripe_connect_form.hasCVCToken() ) {
						return true;
					}
					
					//create token for cvc verification
					zc_wc_stripe_connect_form.block();
					zc_wc_stripe_connect_form.createCVCToken();

					return false;

				}
				
				return true;
			}

			zc_wc_stripe_connect_form.block();
			zc_wc_stripe_connect_form.createSource();

			return false;
		},

		/**
		 * If a new credit card is entered, reset sources.
		 */
		onCCFormChange: function() {
			zc_wc_stripe_connect_form.reset();
		},

		/**
		 * Removes all Stripe errors and hidden fields with IDs from the form.
		 */
		reset: function() {
			console.log('reset');
			$( '.wc-stripe-connect-error, .stripe-connect-source, .stripe-connect-cvc-token' ).remove();
		},

		/**
		 * Displays stripe-related errors.
		 *
		 * @param {Event}  e      The jQuery event.
		 * @param {Object} result The result of Stripe call.
		 */
		onError: function( e, result ) {
			var message = result.error.message;
			var selectedMethodElement = zc_wc_stripe_connect_form.getSelectedPaymentElement().closest( 'li' );
			var errorContainer;

			console.log('error message', message ); 

			errorContainer = selectedMethodElement.find( '.stripe-connect-source-errors' );

			console.log('container', errorContainer);

			// Notify users that the email is invalid.
			if ( 'email_invalid' === result.error.code ) {
				message = zcwc_stripe_connect_params.email_invalid;
			} else if (
				/*
				 * Customers do not need to know the specifics of the below type of errors
				 * therefore return a generic localizable error message.
				 */
				'invalid_request_error' === result.error.type ||
				'api_connection_error'  === result.error.type ||
				'api_error'             === result.error.type ||
				'authentication_error'  === result.error.type ||
				'rate_limit_error'      === result.error.type
			) {
				message = zcwc_stripe_connect_params.invalid_request_error;
			}

			if ( 'card_error' === result.error.type && zcwc_stripe_connect_params.hasOwnProperty( result.error.code ) ) {
				message = zcwc_stripe_connect_params[ result.error.code ];
			}

			if ( 'validation_error' === result.error.type && zcwc_stripe_connect_params.hasOwnProperty( result.error.code ) ) {
				message = zcwc_stripe_connect_params[ result.error.code ];
			}

			zc_wc_stripe_connect_form.reset();
			$( '.woocommerce-NoticeGroup-checkout' ).remove();
			
			console.log( result.error.message ); // Leave for troubleshooting.
			
			$( errorContainer ).html( '<ul class="woocommerce_error woocommerce-error wc-stripe-connect-error"><li /></ul>' );
			$( errorContainer ).find( 'li' ).text( message ); // Prevent XSS

			if ( $( '.wc-stripe-connect-error' ).length ) {
				$( 'html, body' ).animate({
					scrollTop: ( $( '.wc-stripe-connect-error' ).offset().top - 250 )
				}, 200 );
			}
			zc_wc_stripe_connect_form.unblock();
			
			$.unblockUI(); // If arriving via Payment Request Button.
		},

		/**
		 * Handles changes in the hash in order to show a modal for PaymentIntent/SetupIntent confirmations.
		 *
		 * Listens for `hashchange` events and checks for a hash in the following format:
		 * #confirm-pi-<intentClientSecret>:<successRedirectURL>
		 *
		 * If such a hash appears, the partials will be used to call `stripe.handleCardPayment`
		 * in order to allow customers to confirm an 3DS/SCA authorization, or stripe.handleCardSetup if
		 * what needs to be confirmed is a SetupIntent.
		 *
		 * Those redirects/hashes are generated in `WC_Gateway_Stripe::process_payment`.
		 */
		onHashChange: function() {
			var partials = window.location.hash.match( /^#?confirm-connect-(pi|si)-([^:]+):(.+)$/ );

			if ( ! partials || 4 > partials.length ) {
				return;
			}

			var type               = partials[1];
			var intentClientSecret = partials[2];
			var redirectURL        = decodeURIComponent( partials[3] );

			// Cleanup the URL
			window.location.hash = '';

			zc_wc_stripe_connect_form.openIntentModal( intentClientSecret, redirectURL, false, 'si' === type );
		},

		maybeConfirmIntent: function() {
			if ( ! $( '#stripe-connect-intent-id' ).length || ! $( '#stripe-connect-intent-return' ).length ) {
				return;
			}

			var intentSecret = $( '#stripe-connect-intent-id' ).val();
			var returnURL    = $( '#stripe-connect-intent-return' ).val();

			zc_wc_stripe_connect_form.openIntentModal( intentSecret, returnURL, true, false );
		},

		/**
		 * Opens the modal for PaymentIntent authorizations.
		 *
		 * @param {string}  intentClientSecret The client secret of the intent.
		 * @param {string}  redirectURL        The URL to ping on fail or redirect to on success.
		 * @param {boolean} alwaysRedirect     If set to true, an immediate redirect will happen no matter the result.
		 *                                     If not, an error will be displayed on failure.
		 * @param {boolean} isSetupIntent      If set to true, ameans that the flow is handling a Setup Intent.
		 *                                     If false, it's a Payment Intent.
		 */
		openIntentModal: function( intentClientSecret, redirectURL, alwaysRedirect, isSetupIntent ) {
			stripe[ isSetupIntent ? 'handleCardSetup' : 'handleCardPayment' ]( intentClientSecret )
				.then( function( response ) {
					if ( response.error ) {
						throw response.error;
					}

					var intent = response[ isSetupIntent ? 'setupIntent' : 'paymentIntent' ];
					if ( 'requires_capture' !== intent.status && 'succeeded' !== intent.status ) {
						zc_wc_stripe_connect_form.unblock();
						return;
					}

					window.location = redirectURL;
				} )
				.catch( function( error ) {
					if ( alwaysRedirect ) {
						return window.location = redirectURL;
					}

					$( document.body ).trigger( 'stripeConnectError', { error: error } );
					
					$( '.stripe-connect-source' ).remove();
					zc_wc_stripe_connect_form.unblock();
					zc_wc_stripe_connect_form.form.removeClass( 'processing' );

					// Report back to the server.
					$.get( redirectURL + '&is_ajax' );

				} );
		}
	};

	zc_wc_stripe_connect_form.init();
} );
