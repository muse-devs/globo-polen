<?php
namespace Polen\Api;

use Polen\Includes\Polen_SignInUser;
use Polen\Includes\Polen_SignInUser_Strong_Password;
use WP_REST_Response;

class Api_User
{

    public function sign_on( $request )
    {
        $email = $request->get_param( 'email' );
        $password = $request->get_param( 'password' );
        $terms_coditions = $request->get_param( 'terms_conditions' );
        $user_name = $request->get_param( 'user_name' );
        $campaing = $request->get_param( 'campaign' );

        if( empty( $terms_coditions ) ) {
            return api_response( [ 'message' => 'Aceite os termos e condições do site' ], 403 );
        }

        if( !$this->check_security_password( $password ) ) {
            $strong_password = new Polen_SignInUser_Strong_Password();
            return api_response( [ 'message' => $strong_password->get_default_message_error() ], 403 );
        }

        $args = [ 'display_name' => $user_name ];

        $new_user = wc_create_new_customer( $email, $email, $password, $args );

        if( !empty( $campaing ) ) {
            add_user_meta( $new_user, 'campaing', $campaing, true );
        }

        if( is_wp_error( $new_user ) ) {
            return api_response( [ 'message' => $new_user->get_error_message() ], 403 );
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

}
