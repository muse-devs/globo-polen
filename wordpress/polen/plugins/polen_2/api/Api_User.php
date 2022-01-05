<?php
namespace Polen\Api;

use Exception;
use Polen\Includes\Emails\Polen_WC_Customer_New_Account;
use Polen\Includes\Polen_Campaign;
use Polen\Includes\Polen_SignInUser_Strong_Password;
use WP_Error;

class Api_User
{

    public function sign_on( $request )
    {
        $email           = $request->get_param( 'email' );
        $password        = $request->get_param( 'password' );
        $terms_coditions = $request->get_param( 'terms_conditions' );
        $user_name       = $request->get_param( 'user_name' );
        $campaing        = $request->get_param( 'campaign' );

        if( empty( $terms_coditions ) ) {
            return api_response( [ 'message' => 'Aceite os termos e condições do site' ], 403 );
        }

        if( !$this->check_security_password( $password ) ) {
            $strong_password = new Polen_SignInUser_Strong_Password();
            return api_response( [ 'message' => $strong_password->get_default_message_error() ], 403 );
        }

        if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
            return api_response( [ 'message' => 'Email inválido' ], 403 );
        }

        try {
            $this->create_user_custumer( $email, $user_name, $password, [ 'campaign' => $campaing ] );
        } catch ( Exception $e ) {
            return api_response( $e->getMessage(), $e->getCode() );
        }

        return api_response( [ 'message' => 'Usuário cadastrado com sucesso' ], 201 );
    }


    public function check_permission_create_item( \WP_REST_Request $request )
    {
        return true;
    }

    public function check_security_password( $password )
    {
        $strong_password = new Polen_SignInUser_Strong_Password();
        return $strong_password->verify_strong_password( $password );
    }


    /**
     * 
     * @param string
     * @param string
     * @param string
     * @param array
     * @return int
     */
    public function create_user_custumer( $email, $user_name, $password, $metas = [], $is_checkout = false )
    {
        $args = [ 'display_name' => $user_name ];

        //Seguindo Padrao do Woocommerce com as ACTIONs
        $errors = new WP_Error();

        do_action( 'woocommerce_register_post', $email, $email, $errors );
        
        $errors = apply_filters( 'woocommerce_registration_errors', $errors, $email, $email );
		if ( $errors->get_error_code() ) {
			throw new Exception( $errors->get_error_messages(), $errors->get_error_code() );
		}
		
        $new_customer_data = apply_filters(
			'woocommerce_new_customer_data',
			array_merge(
				$args,
				array(
					'user_login' => $email,
					'user_pass'  => $password,
					'user_email' => $email,
					'role'       => 'customer',
				)
			)
		);

        $customer_id = wp_insert_user( $new_customer_data );

        if( is_wp_error( $customer_id ) ) {
            throw new Exception( implode( $customer_id->get_error_messages() ), 403 );
        }

        if( isset( $metas[ 'campaign' ] ) ) {
            Polen_Campaign::set_user_campaign( $customer_id, $metas['campaign'] );
            unset( $metas['campaign'] );
        }

        if( !empty( $metas ) ) {
            foreach( $metas as $key => $value ) {
                update_user_meta( $customer_id, $key, $value );
            }
        }

        if( !$is_checkout ) {
            $password = '';
        }

        do_action( 'woocommerce_created_customer', $customer_id, [ 'user_pass' => $password ], $is_checkout );

        return $customer_id;
    }

    /**
     * Recuperar dados do usuario
     *
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response
     */
    public function my_account(\WP_REST_Request $request): \WP_REST_Response
    {
        $email = $request->get_param('email');
        if(empty($email)) {
            return api_response(['message' => 'Email Obrigatório'], 403);
        }

        $user = get_user_by('email', $email);
        if(empty($user)) {
            return api_response(['message' => 'Não existe nenhum usuario com esse email'], 403);
        }

        $response = [
            'ID' => $user->data->ID,
            'name' => $user->data->display_name,
            'phone' => get_user_meta($user->data->ID,'billing_phone',true),
            'email' => $user->data->user_email,
            'display_name' => $user->data->display_name,
            'date_registered' => $user->data->user_registered
        ];

        return api_response($response, 200);
    }

}
