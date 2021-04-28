<?php

use Polen\Includes\Polen_Update_Fields;

class Cubo9_Braspag {

    /**
     * Dados de acesso PRODUÇÃO
     */
	const CIELO_COMMERCE_API       = "https://api.cieloecommerce.cielo.com.br/";
	const CIELO_COMMERCE_API_QUERY = "https://apiquery.cieloecommerce.cielo.com.br/";
	const BRASPAG_SPLIT_API        = "https://split.braspag.com.br/";
	const BRASPAG_OAUTH2           = "https://auth.braspag.com.br/";

    /**
     * Dados de acesso SANDBOX
     */
	const SANDBOX_CIELO_COMMERCE_API       = "https://apisandbox.cieloecommerce.cielo.com.br/";
	const SANDBOX_CIELO_COMMERCE_API_QUERY = "https://apiquerysandbox.cieloecommerce.cielo.com.br/";
	const SANDBOX_BRASPAG_SPLIT_API        = "https://splitsandbox.braspag.com.br/";
    const SANDBOX_BRASPAG_OAUTH2           = "https://authsandbox.braspag.com.br/";

    /**
     * Variáveis
     */
    private $cart_items              = array();
    private $merchant_defined_fields = array();
    
    public function __construct( WC_Order $order, $session_id ) {
        if( $order instanceof WC_Order ) {
            $this->SESSION_ID = $session_id;
            $WC_Cubo9_BraspagReduxSettings = get_option( 'WC_Cubo9_BraspagReduxSettings');

            $this->order            = $order;
            $this->order_id         = $order->get_id();
            $this->braspag_settings = $WC_Cubo9_BraspagReduxSettings;
            $this->softdescriptor   = substr( $WC_Cubo9_BraspagReduxSettings['braspag_softdescriptor'], 0, 13 );
            $this->set_items();

            if( intval( $WC_Cubo9_BraspagReduxSettings['enable_braspag_sandbox'] ) == intval( 1 ) ) {
                $this->MERCHANT_ID                  = $WC_Cubo9_BraspagReduxSettings['sandbox_master_subordinate_merchant_id'];
                $this->MERCHANT_KEY                 = $WC_Cubo9_BraspagReduxSettings['sandbox_master_client_secret'];
                $this->URL_CIELO_COMMERCE_API       = self::SANDBOX_CIELO_COMMERCE_API;
                $this->URL_CIELO_COMMERCE_API_QUERY = self::SANDBOX_CIELO_COMMERCE_API_QUERY;
                $this->URL_BRASPAG_SPLIT_API        = self::SANDBOX_BRASPAG_SPLIT_API;
                $this->URL_BRASPAG_OAUTH2           = self::SANDBOX_BRASPAG_OAUTH2;
                $this->SANDBOX_NAME_SUFIX           = ' ACCEPT';
            } else {
                $this->MERCHANT_ID                  = $WC_Cubo9_BraspagReduxSettings['master_subordinate_merchant_id'];
                $this->MERCHANT_KEY                 = $WC_Cubo9_BraspagReduxSettings['master_client_secret'];
                $this->URL_CIELO_COMMERCE_API       = self::CIELO_COMMERCE_API;
                $this->URL_CIELO_COMMERCE_API_QUERY = self::CIELO_COMMERCE_API_QUERY;
                $this->URL_BRASPAG_SPLIT_API        = self::BRASPAG_SPLIT_API;
                $this->URL_BRASPAG_OAUTH2           = self::BRASPAG_OAUTH2;
                $this->SANDBOX_NAME_SUFIX           = '';
            }
        }
    }

    /**
     * Autenticação Braspag
     */
    private function auth() {
        $base64_encode = base64_encode( $this->MERCHANT_ID . ':' . $this->MERCHANT_KEY );
		$headers = array(
			'Authorization' => 'Basic ' . $base64_encode,
			'Content-Type' => 'application/x-www-form-urlencoded'
        );
        
        $response = wp_remote_post( 
            $this->URL_BRASPAG_OAUTH2 . '/oauth2/token', 
            array( 
                'headers' => $headers,
                'method' => 'POST',
                'timeout' => 1000,
                'body' => array( 
                    'grant_type' => 'client_credentials'
                ),
            )
        );

        if( ! is_wp_error( $response ) ) {
            if(  wp_remote_retrieve_response_code( $response ) === 200 ) {
                $response_body = json_decode( $response["body"] );
                $this->BRASPAG_TOKEN = $response_body->access_token;
                $response = array(
                    'status'  => 'success',
                    'message' => 'Autenticado com sucesso.',
                );
            } else {
                $response = array(
                    'status'  => 'error',
                    'message' => 'Autenticação usuário falhou. (C9-BP001)',
                );
            }
        } else {
            $response = array(
                'status'  => 'error',
                'message' => $response->get_error_message() . ' (C9-BP002)',
            );
        }

        return $response;
    }

    public function get_splitpayments( $installment, $brand_slug ) {
        $splitpayments = array();
        $seller_info   = array();

        $WC_Cubo9_Braspag_Helper = new WC_Cubo9_Braspag_Helper();
        $installment_rate = $WC_Cubo9_Braspag_Helper->get_installment_rates_by_brand( $installment, $brand_slug );

        $fee_gateway                = (int) $this->braspag_settings['fee_gateway'];
        $fee_antifraude             = (int) $this->braspag_settings['fee_antifraude'];
        $pass_rates                 = (bool) $this->braspag_settings['pass_rates'];
        $default_mdr                = (float) $this->braspag_settings['default_mdr'];
        $default_fee                = (int) $this->braspag_settings['default_fee'];

        $mdr = (float) $default_mdr + $installment_rate;

        if( $pass_rates ) {
            $fee = (int) $fee_gateway + $fee_antifraude + $default_fee;
        } else {
            $fee = $default_fee;
            if( $default_mdr == (float) 0 ) {
                $fee = (int) $fee_gateway + $fee_antifraude + $default_fee;
            }
        }

        // Como pegar na tabela talents?

        if( is_array( $this->order_sellers  ) && count( $this->order_sellers  ) > 0 ) {
            foreach( $this->order_sellers as $k => $seller_data ) {
                $Polen_Update_Fields = new Polen_Update_Fields();
                $row_seller = $Polen_Update_Fields->get_vendor_data( $seller_data[ 'id' ] );
                $splitpayments[] = array(
                    'subordinatemerchantid' => $row_seller->subordinate_merchant_id,
                    'amount'                => $seller_data['amount'],
                    'fares'                 => array(
                        'mdr' => $mdr,
                        'fee' => $fee,
                    ),
                );
            }
        }
        

        $taxes_and_shipping_split = $this->set_taxes_and_shipping_split();
        if( $taxes_and_shipping_split && is_array( $taxes_and_shipping_split ) && isset( $taxes_and_shipping_split['subordinatemerchantid'] ) ) {
            $splitpayments[] = $taxes_and_shipping_split;
            $seller_info[1] = array(
                'id'                    => 1,
                'amount'                => $taxes_and_shipping_split['amount'],
                'subordinatemerchantid' => $taxes_and_shipping_split['subordinatemerchantid'],
            );
        }

        return $splitpayments;
    }

    public function pay() {
        $auth = $this->auth();
        if( $auth['status'] == 'success' && ! is_null( $this->BRASPAG_TOKEN ) ) {
            /**
             * Dados da compra
             */
            $order       = $this->order;
            $order_id    = $order->get_id();
            $amount      = number_format( $order->get_total(), 2, '', '' );
            $user        = $order->get_user();
            $billing_cpf = ( isset( $_REQUEST['billing_cpf'] ) ) ? $_REQUEST['billing_cpf'] : '';
            $billing_cpf = ( isset( $user->ID ) ) ? get_user_meta( $user->ID, 'billing_cpf', true ) : $billing_cpf;
            $billing_phone = ( isset( $_REQUEST['billing_phone'] ) ) ? $_REQUEST['billing_phone'] : '';
            $billing_phone = ( isset( $user->ID ) ) ? get_user_meta( $user->ID, 'billing_phone', true ) : $billing_phone;

            /**
             * Metadados da compra
             */
            $order_data                             = $order->get_data();
            
            $order_data['billing']['number']        = get_post_meta( $order_id, '_billing_number', true );
            $order_data['billing']['complement']    = get_post_meta( $order_id, '_billing_complement', true );
            $order_data['billing']['neighborhood']  = get_post_meta( $order_id, '_billing_neighborhood', true );
            $order_data['billing']['phone']         = $billing_phone;
            $order_data['billing']['cpf']           = $billing_cpf;

            if( isset( $order_data['shipping'] ) ) {
                $order_data['shipping']['number']       = get_post_meta( $order_id, '_shipping_number', true );
                $order_data['shipping']['complement']   = get_post_meta( $order_id, '_shipping_complement', true );
                $order_data['shipping']['neighborhood'] = get_post_meta( $order_id, '_shipping_neighborhood', true );
                $order_data['shipping']['phone']        = $billing_phone;
            }

            /**
             * Dados do usuário comprador.
             */
            if( isset( $user->display_name ) ) {
                $user_display_name = substr( $user->display_name . $this->SANDBOX_NAME_SUFIX, 0, 61 );
            } else {
                $user_display_name = '' . $this->SANDBOX_NAME_SUFIX;
            }

            $document_type     = 'CPF';
            $document_number   = substr( preg_replace( '/[^0-9]/', '', $billing_cpf ), 0, 18 );
            $user_phone        = substr( preg_replace( '/[^0-9]/', '', $order_data['billing']['phone'] ), 0, 15 );
            $user_mobile_phone = substr( preg_replace( '/[^0-9]/', '', $order_data['billing']['phone'] ), 0, 15 );

            /**
             * E-mail do comprador
             */
            if( isset( $order_data['billing']['email'] ) && ! empty( $order_data['billing']['email'] ) ) {
                $billing_email = $order_data['billing']['email'];
            } elseif ( is_user_logged_in() ) {
                $current_user = wp_get_current_user();
                $billing_email = $current_user->user_email;
            } else {
                $items = WC()->cart->get_cart();
                $key = array_key_first( $items );
                $billing_email = $items[ $key ][ 'email_to_video' ];
            }

            /**
             * Dados do cartão de Crédito ou Débito.
             */
            if( isset( $_REQUEST['braspag_use_saved_card'] ) && ! empty( $_REQUEST['braspag_use_saved_card'] ) && (int) $_REQUEST['braspag_use_saved_card'] === (int) 1 ) {
                $brasapag_creditcard_saved = substr( $_REQUEST['brasapag_creditcard_saved'], 0, 6 );
                $braspag_card_saved_data = get_user_meta( get_current_user_id(), 'braspag_card_saved_data', true );
                $card_info = $braspag_card_saved_data[ $brasapag_creditcard_saved ];
                $CreditCardData = array(
                    'CardToken'    => $card_info['token'],
                    'Brand'        => $card_info['brand'],
                    // 'SecurityCode' => preg_replace( '/[^0-9]/', '', strip_tags( $_REQUEST['brasapag_creditcard_saved_cvv'] ) ),
                );
                $creditcard_number = $card_info['card_number'];
                $creditcard_holder = $card_info['holder'];
                $creditcard_brand  = $card_info['brand'];
            } else {
                $creditcard_cvv        = substr( preg_replace( '/[^0-9]/', '', $_REQUEST['braspag_creditcardCvv'] ), 0, 4 );
                $creditcard_brand      = substr( $_REQUEST['braspag_creditcardBrand'], 0, 10 );
                $creditcard_expiration = substr( str_replace( ' ', '', trim( $_REQUEST['braspag_creditcardValidity'] ) ), 0, 7 );
                $creditcard_number     = substr( preg_replace( '/[^0-9]/', '', $_REQUEST['braspag_creditcardNumber'] ), 0 , 19 );
                $creditcard_holder     = substr( trim( strtoupper( $_REQUEST['braspag_creditcardName'] ) ), 0, 50 );
                if( isset ( $_REQUEST['braspag_creditcardCpf'] ) ) {
                    $creditcard_holder_cpf = preg_replace( '/[^0-9]/', '', $_REQUEST['braspag_creditcardCpf'] );
                } else {
                    $creditcard_holder_cpf = '';
                }
                $creditcard_save       = ( isset( $_REQUEST['braspag_saveCreditCard'] ) && (bool) $_REQUEST['braspag_saveCreditCard'] === true ) ? true : false;

                $CreditCardData = array(
                    'CardNumber'     => $creditcard_number,     // Número do cartão de crédito (apenas dígitos)
                    'Holder'         => $creditcard_holder . $this->SANDBOX_NAME_SUFIX, // Nome impresso no cartão de crédito
                    'ExpirationDate' => $creditcard_expiration, // Data de expiração no format MM/YYYY
                    'SecurityCode'   => $creditcard_cvv,        // Código de segurança do cartão
                    'Brand'          => $creditcard_brand,      // Bandeira do Cartão ( Visa / Master / Amex / Elo / Aura / JCB / Diners / Discover )
                    'SaveCard'       => $creditcard_save,       // Se deve salvar o cartão de crédito (True/False)
                );
            }

            /**
             * Dados da forma de pagamento
             */
            $installments = ( isset( $_REQUEST['braspag_creditcardInstallments'] ) && intval( $_REQUEST['braspag_creditcardInstallments'] ) == (int) $_REQUEST['braspag_creditcardInstallments'] && (int) $_REQUEST['braspag_creditcardInstallments'] >= 1 && (int) $_REQUEST['braspag_creditcardInstallments'] <= 12 ) ? (int) $_REQUEST['braspag_creditcardInstallments'] : 1;

            /**
             * Seta o MerchantDefinedFields
             */
            $merchant_defined_fields = array();
            $card_prefix             = substr( $creditcard_number, 0, 6 );
            $card_sufix              = substr( $creditcard_number, -4 );

            $merchant_defined_fields['installments']       = $installments;
            $merchant_defined_fields['credit_card_prefix'] = $card_prefix;
            $merchant_defined_fields['credit_card_sufix']  = $card_sufix;
            $merchant_defined_fields['credit_card_name']   = $creditcard_holder;

            $this->set_merchant_defined_fields( $merchant_defined_fields );

            $request = array();
            $request['MerchantOrderId'] = $order_id;
            $request['Customer']        = array(
                'Name'            => $user_display_name,
                'IdentityType'    => $document_type,
                'Identity'        => $document_number,
                'Email'           => substr( $billing_email, 0, 60 ),
                'Phone'           => $user_phone,
                'Mobile'          => $user_mobile_phone,
                'DeliveryAddress' => array(
                    'ZipCode'    => $order_data['billing']['postcode'],
                    'Street'     => $order_data['billing']['address_1'],
                    'Number'     => $order_data['billing']['number'],
                    'Complement' => $order_data['billing']['complement'],
                    'District'   => $order_data['billing']['neighborhood'],
                    'City'       => $order_data['billing']['city'],
                    'State'      => $order_data['billing']['state'],
                    'Country'    => 'BR',
                ),
                'BillingAddress'  => array(
                    'ZipCode'    => $order_data['billing']['postcode'],
                    'Street'     => $order_data['billing']['address_1'],
                    'Number'     => $order_data['billing']['number'],
                    'Complement' => $order_data['billing']['complement'],
                    'District'   => $order_data['billing']['neighborhood'],
                    'City'       => $order_data['billing']['city'],
                    'State'      => $order_data['billing']['state'],
                    'Country'    => 'BR',
                ),
            );

            $request['Payment']         = array(
                'Type'             => 'SplittedCreditCard',  // SplittedCreditCard ou SplittedDebitCard.
                'Amount'           => $amount,               // Valor total da transação em centavos (Ex: R$ 1.901,20, informar 190120).
                'Capture'          => true,                  // SE True, a transação é efetivada, se False, ela é apenas autorizada.
                'Installments'     => $installments,         // Quantidade de parcelas (De 1 a 12);
                'SoftDescriptor'   => $this->softdescriptor, // Descrição que aparecerá na Fatura
                'CreditCard'       => $CreditCardData,
                'FraudAnalysis'    => array(
                    'Provider'         => 'Cybersource', // Possíveis valores: Cybersource (Máximo de 12 caracteres).
                    'TotalOrderAmount' => $amount,       // Valor total do pedido em centavos, podendo ser diferente do valor da transação	Ex: Valor do pedido sem a taxa de entrega.
                    'Sequence'         => 'analysefirst',
                    'SequenceCriteria' => 'onsuccess',
                    'CaptureOnLowRisk' => false, 
                    'VoidOnHighRisk'   => false,
                    'Browser'          => array(
                        'IpAddress'          => $_SERVER['REMOTE_ADDR'], // Endereço IP do Comprador
                        'BrowserFingerPrint' => $this->SESSION_ID,       // Impressão digital do dispositivo do usuário, deve ser o mesmo SESSION ID do JavaScript incluído na página.
                    ),
                    'Shipping'         => array(
                        'Addressee' => $user_display_name, // Nome e sobrenome do usuário comprador
                    ),
                    'Cart'             => array(
                        'isgift'          => ( get_post_meta( $order_id, '_c9_is_gift', true ) == strval( '1' ) ) ? true : false,
                        'returnsaccepted' => true,
                        'items'           => $this->get_cart_items(),
                    ),
                    'MerchantDefinedFields' => $this->get_merchant_defined_fields(),
                ),
                'splitpayments'        => $this->get_splitpayments( $installments, $creditcard_brand ),
            );

            $request_array = $request;
            $request_array['Payment']['CreditCard'] = array();
            $request_json = json_encode( $request_array );

            add_post_meta( $order_id, 'braspag_request_array', $request_array );
            add_post_meta( $order_id, 'braspag_request_json', $request_json );

            $headers = array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->BRASPAG_TOKEN,
            );

            $body = json_encode( $request );

            $response = wp_remote_post( $this->URL_CIELO_COMMERCE_API . '1/sales', array(
                    'method'  => 'POST',
                    'timeout' => 1000,
                    'headers' => $headers,
                    'body'    => $body
                )
            );

            add_post_meta( $order_id, 'braspag_response', $response );

            if( is_null( $response ) || is_wp_error( $response ) ) {
                // Provavel falta de comunicação entre os servidores (Timeout?)
                $message = 'Não foi possível processar a requisição, tente novamente dentro de alguns instantes. (C9-WCGW-002)';
                $return = array(
                    'result' => 'error',
                    'message' => $message,
                );
            } else if( ( ! is_null( $response ) && ! is_wp_error( $response ) ) || wp_remote_retrieve_response_code( $response ) === 200 ) {
                $response_code = wp_remote_retrieve_response_code( $response );
                $response_body = wp_remote_retrieve_body( $response );
                $response_body_json = json_decode( $response_body );

                add_post_meta( $this->order_id, 'braspag_response_code', $response_code );
                add_post_meta( $this->order_id, 'braspag_response_body', $response_body );
                add_post_meta( $this->order_id, 'braspag_response_body_json', $response_body_json );

                if( isset( $response_body_json->Payment->Status ) ) {
                    add_post_meta( $this->order_id, 'braspag_payment_status', $response_body_json->Payment->Status );
                }

                if( isset( $response_body_json->Payment->FraudAnalysis->Status ) ) {
                    add_post_meta( $this->order_id, 'braspag_payment_fraud_analysis_status', $response_body_json->Payment->FraudAnalysis->Status );
                }

                if( isset( $response_body_json->Payment->FraudAnalysis->StatusDescription ) ) {
                    add_post_meta( $this->order_id, 'braspag_payment_fraud_analysis_status_description', $response_body_json->Payment->FraudAnalysis->StatusDescription );
                }

                if( 
                    intval( strval( $response[ 'response' ][ 'code' ] ) ) === intval( '201' ) 
                    && strtoupper( $response[ 'response' ][ 'message' ] ) == strtoupper( "Created" ) 
                    && intval( $response_body_json->Payment->Status ) == intval( '2' ) 
                    && (int) $response_body_json->Payment->FraudAnalysis->Status === (int) 1 
                ) {
                    /**
                     * Verifica se deve salvar o cartão de crédito e valida se o cartão utilizado já foi salvo anteriormente.
                     */
                    $save_card = $response_body_json->Payment->CreditCard->SaveCard;
					if( $save_card ) {
                        $braspag_card_saved_data = get_user_meta( get_current_user_id(), 'braspag_card_saved_data', true );

                        $card_number     = $response_body_json->Payment->CreditCard->CardNumber;
                        $holder          = $response_body_json->Payment->CreditCard->Holder;
                        $expiration_date = $response_body_json->Payment->CreditCard->ExpirationDate;
                        $card_token      = $response_body_json->Payment->CreditCard->CardToken;
                        $brand           = $response_body_json->Payment->CreditCard->Brand;
                        $card_prefix     = substr( $card_number, 0, 6 );
                        $card_sufix      = substr( $card_number, -4 );
                        $card_label      = strtoupper( $brand ) . ' - ' . $card_sufix;

                        if( is_array( $braspag_card_saved_data ) && count( $braspag_card_saved_data ) > 0 ) {
                            if( ! isset( $braspag_card_saved_data[ $card_prefix ] ) ) {
                                $braspag_card_saved_data[ $card_prefix ] = array(
                                    'card_number'     => $card_number,
                                    'prefix'          => $card_prefix,
                                    'sufix'           => $card_sufix,
                                    'token'           => $card_token,
                                    'brand'           => $brand,
                                    'expiration_date' => $expiration_date,
                                    'holder'          => $holder,
                                    'holder_cpf'      => $creditcard_holder_cpf,
                                    'card_label'      => $card_label,
                                );
                                update_user_meta( get_current_user_id(), 'braspag_card_saved_data', $braspag_card_saved_data );
                            }
                        } else {
                            $card_saved_data[ $card_prefix ] = array(
                                'card_number'     => $card_number,
                                'prefix'          => $card_prefix,
                                'sufix'           => $card_sufix,
                                'token'           => $card_token,
                                'brand'           => $brand,
                                'expiration_date' => $expiration_date,
                                'holder'          => $holder,
                                'holder_cpf'      => $creditcard_holder_cpf,
                                'card_label'      => $card_label,
                            );
                            add_user_meta( get_current_user_id(), 'braspag_card_saved_data', $card_saved_data );
                        }
                    }
                    
                    // Dados do pagamento
                    $WC_Cubo9_Braspag_Helper = new WC_Cubo9_Braspag_Helper;
                    $installment_value = $WC_Cubo9_Braspag_Helper->calculate_installments( $order->get_total() );
                    $installment_rate = $WC_Cubo9_Braspag_Helper->get_installment_rates_by_brand( $installments, $creditcard_brand );

                    $order->update_meta_data( '_transaction_id', $response_body_json->Payment->PaymentId );
                    
                    $order->update_meta_data( 'braspag_order_amount', $amount );
                    $order->update_meta_data( 'braspag_order_transaction_id', $response_body_json->Payment->PaymentId );
                    $order->update_meta_data( 'braspag_order_nsu', $response_body_json->Payment->ProofOfSale );
					$order->update_meta_data( 'braspag_order_tid', $response_body_json->Payment->Tid );
                    $order->update_meta_data( 'braspag_order_authorizationCode', $response_body_json->Payment->AuthorizationCode );
                    $order->update_meta_data( 'braspag_order_links', $response_body_json->Payment->Links );
                    $order->update_meta_data( 'braspag_order_brand', $creditcard_brand );
                    $order->update_meta_data( 'braspag_order_installments', $installments );
                    $order->update_meta_data( 'braspag_order_installment_value', $installment_value[ $installments ] );
                    $order->update_meta_data( 'braspag_order_brand_mdr', $installment_rate );

                    $order->update_meta_data( 'creditcard_brand', $creditcard_brand );
                    $order->update_meta_data( 'creditcard_brand_mdr', $installment_rate );
                    $order->update_meta_data( 'creditcard_installments', $installments );
                    $order->update_meta_data( 'creditcard_installment_value', str_replace( ',', '.', str_replace( '.', '', $installment_value[ $installments ] ) ) );

                    // Adiciona a nota com o dia/hora em que foi autorizado na 
                    $date_time_format = get_option('date_format') . ' \à\s ' . get_option('time_format');
                    $order->add_order_note( __('Pagamento aprovado pela <strong>Braspag</strong> em ' . date_i18n( $date_time_format , strtotime( $response_body_json->Payment->CapturedDate ) ) . "." , 'cubonove') );

                    // Marca como Aguardando ('on-hold')
                    $order->update_status( 'payment-approved' );

                    // Muda o status para processando
                    // $order->payment_complete();

                    // Atualiza o estoque
                    wc_reduce_stock_levels( $order_id );

                    // Salva as alterações
                    $order->save();

                    // Remove do carrinho
                    global $woocommerce;
                    $woocommerce->cart->empty_cart();

                    $return = array(
                        'result'   => 'success',
                        'message'  => 'Sucesso!',
                        'redirect' => $order->get_checkout_order_received_url(),
                    );
                } elseif( isset( $response_body_json->Payment->FraudAnalysis->Status ) && (int) $response_body_json->Payment->FraudAnalysis->Status === (int) 3 && strtoupper( $response_body_json->Payment->FraudAnalysis->StatusDescription ) == strtoupper('REVIEW') ) {
                    $order->update_status( 'payment-in-revision' );
                    return array(
                        'type' => 'review',
                        'message' => 'Seu pedido está aguardando confirmação de pagamento.',
                        'body' => $response["body"],
                        'response' => $response["response"]
                    );
                } else {
                    if( is_array( $response_body_json ) && isset( $response_body_json[0]->Code ) && ! isset( $response_body_json[1]->Code ) ) {
                        $message = $response_body_json[0]->Message . " (C9-GW-" . $response_body_json[0]->Code . " #" . $this->order_id . ")";
                    } elseif( is_array( $response_body_json ) && isset( $response_body_json[0]->Code ) && isset( $response_body_json[1]->Code ) ) {
                        $message = $response_body_json[1]->Message . " (C9-GW-" . $response_body_json[1]->Code . " #" . $this->order_id . ")";
                    } elseif( is_object( $response_body_json ) && isset( $response_body_json->Payment->FraudAnalysis->Status ) ) {
                        $message = $response_body_json->Payment->FraudAnalysis->StatusDescription . " (C9-GW-" . $response_body_json->Payment->FraudAnalysis->Status . " #" . $order_id . ")";
                    } else {
                        $message = 'Não foi possível processar o seu pagamento. (C9-WCGW-007)';
                    }
                    
                    $return = array(
                        'result' => 'error',
                        'message' => $message,
                    );
                }
            } else {
                $response_code = wp_remote_retrieve_response_code( $response );
                $response_body = wp_remote_retrieve_body( $response );
                $response_body_json = json_decode( $response_body );

                add_post_meta( $this->order_id, 'braspag_response_code', $response_code );
                add_post_meta( $this->order_id, 'braspag_response_body', $response_body );
                add_post_meta( $this->order_id, 'braspag_response_body_json', $response_body_json );

                switch( $response_code ) {
                    case "400":
                        // Erro na Request processada no servidor Braspag
                        $message = 'Ocorreu um erro ao tentar processar sua requisição, ferifique os dados e tente novamente dentro de alguns instantes. (C9-WCGW-003)';
                        break;
                    case "404":
                        // Endpoint não encontrado no servidor Braspag
                        $message = 'Ocorreu erro de comunicação com o servidor. Tente novamente mais tarde. (C9-WCGW-004)';
                        break;
                    case "500":
                        // Erro interno no servidor Braspag
                        $message = 'Ocorreu erro de comunicação com o servidor. Tente novamente mais tarde. (C9-WCGW-005)';
                        break;
                    default:
                        $message = 'Ocorreu erro de comunicação com o servidor. Tente novamente mais tarde. (C9-WCGW-006)';
                        break;
                }

                $return = array(
                    'result' => 'error',
                    'message' => $message,
                );
            }
        } else {
            $message = 'Ocorreu erro interno. Tente novamente dentro de alguns instantes. (C9-WCGW-001)';
            $return = array(
                'result' => 'error',
                'message' => $message,
            );
        }

        return $return;
    }

    /**
     * Itens da compra
     */
    private function set_items() {
        $items = $this->order->get_items();
		if( count( $items ) > 0 ) {
            $sellers     = array();
            $seller_info = array();

			foreach( $items as $k => $item ) {
				$product_id    = $item['product_id'];
				$product       = wc_get_product( $product_id );
				$quantity      = $item['quantity'];
				$unit_price    = number_format( ( $item->get_subtotal() / $quantity ), 2, '.', '' );
				$product_price = str_replace( '.', '', $unit_price );
				$sku           = get_post_meta( $product_id, '_sku', true );
				$this->add_cart_item( $product->get_title(), $quantity, $product_price, $sku );

                $seller_id = get_post_field( 'post_author', $product_id );

                if( ! in_array( $seller_id, $sellers ) ) {
                    $Polen_Update_Fields = new Polen_Update_Fields();
                    $row_seller = $Polen_Update_Fields->get_vendor_data( $seller_id );
                    $sellers[] = $seller_id;
                    $seller_info[ $seller_id ] = array(
                        'id'                    => $seller_id,
                        'amount'                => number_format( $item->get_subtotal(), 2, '', '' ),
                        'subordinatemerchantid' => $row_seller->subordinate_merchant_id,
                    );
                } else {
                    $seller_info[ $seller_id ]['amount'] = ( $seller_info[ $seller_id ]['amount'] +  number_format( $item->get_subtotal(), 2, '', '' ) );
                }
			}

            $this->order_sellers = $seller_info;
		}
    }

    public function add_cart_item( $product_name, $quantity, $unitprice, $sku ) {
        $item = array();

		if( ! is_null( $product_name ) && ! empty( $product_name ) ) {
			$item['name'] = $product_name;
		}

		if( ! is_null( $quantity ) && ! empty( $quantity ) && intval( $quantity ) == intval( strval( $quantity ) ) && (int) $quantity > 0 ) {
			$item['quantity'] = $quantity;
		}

		if( ! is_null( $unitprice ) && ! empty( $unitprice ) && (int) $unitprice > (int) 0 ) {
			$item['unitprice'] = $unitprice;
		}

		if( ! is_null( $sku ) && ! empty( $sku ) ) {
			$item['sku'] = $sku;
		}

		if( count( $item ) > 0 ) {
			$this->cart_items[] = $item;
		}
    }

    public function get_cart_items() {
        return $this->cart_items;
    }

    /**
     * Campos para ajudar o antifraudes (MerchantDefinedFields)
     */
    public function add_merchant_defined_fields( $key, $value ) {
		if( ! empty( trim( $key ) ) && ! empty( trim( $value ) ) ) {
			$this->merchant_defined_fields[] = array( 
				'Id' => $key,
				'Value' => $value,
			);
		}
    }
    
    public function get_merchant_defined_fields() {
        return $this->merchant_defined_fields;
    }

    public function set_merchant_defined_fields( array $args ) {
        $user_id            = get_current_user_id();
        $order_id           = $this->order->get_id();
        $installments       = $args['installments'];
        $credit_card_prefix = $args['credit_card_prefix'];
        $credit_card_sufix  = $args['credit_card_sufix'];
        $credit_card_name   = $args['credit_card_name'];

        /**
		 * Campos para envio de análise antifraudes.
		 */
        $current_user = get_user_by( 'id', $user_id );
        $wordpress_timezone = new DateTimeZone( get_option('timezone_string') );
		if( isset( $current_user->ID ) ) {
			$user_registered = new DateTime( $current_user->user_registered, $wordpress_timezone );
			$current_date = new DateTime( "now", $wordpress_timezone );
			$date_interval = $user_registered->diff( $current_date );

			/**
			 * Login do usuário
			 */ 
			if( isset( $current_user->user_login ) && ! is_null( $current_user->user_login ) && ! empty( $current_user->user_login ) ) {
				$this->add_merchant_defined_fields( '1', $current_user->user_login );
			}

			/**
			 * Quanto tempo em dias o usuário é cadastrado na plataforma
			 */ 
			if( (int) $date_interval->days > (int) 0 ) {
				$this->add_merchant_defined_fields( '2', $date_interval->days );
			}
		}

		/**
		 * Quantidade de parcelas do pedido
		 */ 
		if( (int) $installments > (int) 0 ) {
			$this->add_merchant_defined_fields( '3', $installments );
		}

		/**
		 * Origem do pagamento: Web ou Movel
		 */ 
		$this->add_merchant_defined_fields( '4', 'Web' );

		/**
		 * Merchant ID do(s) Vendedor(es)
		 */ 
		/* $sellers = $this->get_sellers( $order_id );
		$sellers_array = array();
		foreach( $sellers as $k => $seller ) {
			$sellers_array[] = trim( $seller['subordinatemerchantid'] );
		}
		if( count( $sellers_array ) > 0 ) {
			$sellers_string = implode( '/', $sellers_array );
			$this->add_merchant_defined_fields( '7', $sellers_string );
		} */
		
		/**
		 * Identifica se cliente irá retirar o produto na loja
		 */ 
		$this->add_merchant_defined_fields( '9', 'NAO' );

		/**
		 * 4 últimos dígitos do cartão de crédito
		 */
		if( isset( $credit_card_sufix ) && ! is_null( $credit_card_sufix ) && ! empty( $credit_card_sufix ) && (int) strlen( $credit_card_sufix ) == (int) 4 ) {
			$this->add_merchant_defined_fields( '23', $credit_card_sufix );
		}

		/**
		 * Quantidade de dias desde a primeira compra realizada pelo cliente.
		 */
		$order_args = array(
			'limit'       => '1',
			'status'      => 'completed',
			'customer_id' => $user_id,
			'orderby'     => 'date',
    		'order'       => 'ASC',
		);
		$user_orders = wc_get_orders( $order_args );
		if( ! is_null( $user_orders ) && is_array( $user_orders ) && ! empty( $user_orders ) && isset( $user_orders[0]->order_date ) ) {
			$last_order = $user_orders[0]->get_date_created();
			$last_order = new DateTime( $last_order, $wordpress_timezone );
			$current_date = new DateTime( "now", $wordpress_timezone );
			$date_interval = $last_order->diff( $current_date );
			if( (int) $date_interval->days > (int) 0 ) {
				$this->add_merchant_defined_fields( '24', $date_interval->days );
			}
		}

		/**
		 * 6 primeiros dígitos do cartão de crédito
		 */
		if( isset( $credit_card_prefix ) && ! is_null( $credit_card_prefix ) && ! empty( $credit_card_prefix ) && (int) strlen( $credit_card_prefix ) == (int) 6 ) {
			$this->add_merchant_defined_fields( '26', $credit_card_prefix );
		}
		
		/**
		 * Tipo do endereço do usuário: R => Residencial, C => Comercial
		 */
		// $orderUserAddress = $this->getOrderUserAddress( $order_id );
		$addressType = 'R';
		/* if( ! is_null( $orderUserAddress ) && ! empty( $orderUserAddress ) && is_array( $orderUserAddress ) && strtoupper( substr( $orderUserAddress['address_name'], 0, 1 ) ) != strtoupper( 'C' ) ) {
			$addressType = 'C';
		} */
		$this->add_merchant_defined_fields( '27', $addressType );

		/**
		 * Identifica se foi utilizado cartão presente (GiftCard) na compra como forma de pagamento (SIM OU NAO).
		 * Atualmente não temos essa funcionalidade, então por padrão será NAO.
		 */
		$this->add_merchant_defined_fields( '36', 'NAO' );

		/**
		 * Meio de envio do pedido.
		 * Possíveis valores: Sedex, Sedex 10, 1 Dia, 2 Dias, Motoboy, Mesmo Dia
		 */
		$this->add_merchant_defined_fields( '37', 'Motoboy' );

		/**
		 * Identifica se o pedido é um presente e insere o comentário.
		 */
		$_c9_is_gift = get_post_meta( $order_id, '_c9_is_gift', true );
		if( $_c9_is_gift && ! is_null( $_c9_is_gift ) && ! empty( $_c9_is_gift ) && strval( $_c9_is_gift ) == strval( 'yes' ) ) {
			$_c9_is_gift_to = get_post_meta( $order_id, '_c9_is_gift_to', true );
			if( $_c9_is_gift_to && ! is_null( $_c9_is_gift_to ) && ! empty( $_c9_is_gift_to ) ) {
				$this->add_merchant_defined_fields( '40', $_c9_is_gift_to );
			}
		}

		/**
		 * Tipo de documento do comprador (CPF OU CNPJ)
		 */
		$this->add_merchant_defined_fields( '41', 'CPF' );

		/**
		 * Quantidade de compras que o usuário já efetuou na plataforma.
		 */
        $_order_count = wc_get_customer_order_count( $user_id );
		if( $_order_count && ! is_null( $_order_count ) && ! empty( $_order_count ) && (int) $_order_count > (int) 0 ) {
			$this->add_merchant_defined_fields( '44', $_order_count );
		}

		/**
		 * Nome impresso no cartão de crédito.
		 */
		if( isset( $credit_card_name ) && ! is_null( $credit_card_name ) && ! empty( $credit_card_name ) ) {
			$this->add_merchant_defined_fields( '46', $credit_card_name );
		}

		/**
		 * Quantidade de meios de pagamentos utilizados para efetuar a compra. 
		 * Por padrão só permitimos um meio de pagamento, ou seja nossos usuários não podem pagar a compra
		 * utilizando dois ou três cartões de crédito.
		 */
		$this->add_merchant_defined_fields( '48', '1' );

		/**
		 * Categorias do produto.
		 * A lista deverá estar atualizada com a da Braspag. Como por enquanto não temos essa lista,
		 * estou enviando o valor "Outras Categorias Não Especificadas" que é da Braspag.
		 */
		$this->add_merchant_defined_fields( '52', 'Outras Categorias Não Especificadas' );

		/**
		 * Quantidade em dias desde a data da última alteração no cadastro do usuário.
		 */
        /* $last_update = get_user_meta( $user_id, 'last_update', true );
		if( $last_update && ! is_null( $last_update ) && ! empty( $last_update ) ) {
            $last_update = date( "Y-m-d H:i:s", $last_update );
			$user_updated = new DateTime( $last_update, $wordpress_timezone );
			$current_date = new DateTime( "now", $wordpress_timezone );
            $dateInterval = $user_updated->diff( $current_date );
			if( (int) $dateInterval->days > (int) 0 ) {
				$this->add_merchant_defined_fields( '60', $dateInterval->days );
			}
		} else {
			$user_registered = new DateTime( $current_user->user_registered, $wordpress_timezone );
			$current_date = new DateTime( "now", $wordpress_timezone );
            $dateInterval = $user_registered->diff( $current_date );
			if( (int) $dateInterval->days > (int) 0 ) {
				$this->add_merchant_defined_fields( '60', $dateInterval->days );
			}
		} */

		/**
		 * Ramo de atividade do Marketplace
		 */
		$this->add_merchant_defined_fields( '83', 'Varejo' );

		/**
		 * Tipo de integração com a Braspag
		 */
		$this->add_merchant_defined_fields( '84', 'Propria' );
    }

    /**
     * Seta o subordinado que receberá as taxas e o frete.
     */
    public function set_taxes_and_shipping_split() {
        $amount               = 0;
        $total_fee            = 0;
        $order_total_shipping = 0;

        if( ! is_null( $this->order->get_items( 'fee' ) ) && ! empty( $this->order->get_items( 'fee' ) ) ) {
            $total_fees = $this->order->get_items( 'fee' );
            if( ! empty( $total_fees ) && is_array( $total_fees ) && count( $total_fees ) > 0 ) {
                $total_fee = 0;
                foreach( $total_fees as $k => $fee_data ) {
                    $total_fee = ( $total_fee + $fee_data->get_total() );
                }
                $total_fee = str_replace('.', '', number_format( $total_fee, 2, '.', '' ) );
            }
        }
        
        if( ! is_null( $this->order->get_shipping_total() ) && ! empty( $this->order->get_shipping_total() ) ) {
            $total_shipping = $this->order->get_shipping_total();
            if ( ! is_null( $total_shipping ) && ! empty( $total_shipping ) && $total_shipping > 0 ) {
                $order_total_shipping = str_replace('.', '', number_format( $total_shipping, 2, '.', '' ) );
            }
        }

        $amount = ( $total_fee + $order_total_shipping );

        if( $amount > 0 ) {
            $WC_Cubo9_Braspag_Helper = new WC_Cubo9_Braspag_Helper();
            $installment_rate        = $WC_Cubo9_Braspag_Helper->get_installment_rates_by_brand( $installment, $brand_slug );
            $fee_gateway             = (int) $this->braspag_settings['fee_gateway'];
            $fee_antifraude          = (int) $this->braspag_settings['fee_antifraude'];
            $pass_rates              = (bool) $this->braspag_settings['pass_rates'];
            $default_mdr             = (float) $this->braspag_settings['default_mdr'];
            $default_fee             = (int) $this->braspag_settings['default_fee'];

            if( intval( $this->braspag_settings['enable_braspag_sandbox'] ) == intval( 1 ) ) {
                $subordinate_merchant_id = $this->braspag_settings['sandbox_master_subordinate_merchant_id'];
            } else {
                $subordinate_merchant_id = $this->braspag_settings['master_subordinate_merchant_id'];
            }

            $mdr = (float) $default_mdr + $installment_rate;

            if( $pass_rates ) {
                $fee = (int) $fee_gateway + $fee_antifraude + $default_fee;
            } else {
                $fee = $default_fee;
                if( $default_mdr == (float) 0 ) {
                    $fee = (int) $fee_gateway + $fee_antifraude + $default_fee;
                }
            }

            return array(
                'subordinatemerchantid' => $subordinate_merchant_id,
                'amount'                => $amount,
                'fares'                 => array(
                    'mdr' => $mdr,
                    'fee' => $fee,
                ),
            );
        }
    }

    public function get_last_order_meta( $order_id, $meta_key ) {
        global $wpdb;
        $sql = "SELECT `meta_value` FROM `" . $wpdb->postmeta . "` WHERE `meta_key`='" . $meta_key . "' AND `post_id`=" . $order_id . ' ORDER BY `meta_id` DESC LIMIT 0, 1';
        $res = $wpdb->get_results( $sql );
        if( $res && ! is_wp_error( $res ) ) {
            return maybe_unserialize( $res[0]->meta_value );
        }
    }

    public function void( $amount = false, $VoidSplitPayments = array() ) {
        $auth = $this->auth();
        if( $auth['status'] == 'success' && ! is_null( $this->BRASPAG_TOKEN ) ) {
            $order = $this->order;
            if( $order && ! is_null( $order ) && is_a( $order, 'WC_Order' ) ) {
                $order_id = $order->get_id();
                if( ! $amount ) {
                    $braspag_order_transaction_id = $this->get_last_order_meta( $order_id, 'braspag_order_transaction_id' );
                    if( $braspag_order_transaction_id && ! is_null( $braspag_order_transaction_id ) && ! empty( $braspag_order_transaction_id ) ) {
                        $url = $this->URL_CIELO_COMMERCE_API . '1/sales/' . $braspag_order_transaction_id . '/void';
                        $headers = array(
                            'Content-Type'  => 'application/json',
                            'Authorization' => 'Bearer ' . $this->BRASPAG_TOKEN,
                            'Content-Length' => 0,
                        );
            
                        $body = array();
            
                        $response = wp_remote_post( $url, array(
                                'method'  => 'PUT',
                                'timeout' => 1000,
                                'headers' => $headers,
                            )
                        );

                        if( $response['response']['code'] === 200 && strtoupper( $response['response']['message'] ) === strtoupper( 'OK' ) ) {
                            $str_response_body = $response['body'];
                            update_post_meta( $order_id, 'braspag_void_response', $str_response_body );
                            $body = json_decode( $str_response_body );

                            $return = array(
                                'Status'                => $body->Status,
                                'ReasonCode'            => $body->ReasonCode,
                                'ProviderReturnCode'    => $body->ProviderReturnCode,
                                'ProviderReturnMessage' => $body->ProviderReturnMessage,
                                'ReturnCode'            => $body->ReturnCode,
                                'ReturnMessage'         => $body->ReturnMessage,
                            );
                            return $return;
                        } elseif( $response['response']['code'] === 400 && strtoupper( $response['response']['message'] ) === strtoupper( 'Bad Request' ) ) {
                            $str_response_body = $response['body'];
                            $body = json_decode( $str_response_body );
                            if( is_array( $body ) ) {
                                $return = array(
                                    'Code'    => $body[0]->Code,
                                    'Message' => $body[0]->Message,
                                );
                            } else {
                                $return = array(
                                    'Code'    => $body->Code,
                                    'Message' => $body->Message,
                                );
                            }
                            return $return;
                        }
                    }
                } elseif( $amount && (int) $amount > 0 && $VoidSplitPayments && is_array( $VoidSplitPayments ) ) {

                }
            }
        }

        return false;
    }
}