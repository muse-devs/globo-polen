<?php
namespace Polen\Api;

use Exception;
use Polen\Includes\Sendgrid\Polen_Sendgrid_Emails;
use Polen\Includes\Sendgrid\Polen_Sendgrid_Redux;
use WP_REST_Request;

class Api_Contact
{

    public function create_nonce( WP_REST_Request $request )
    {
        $ip = $_SERVER[ 'REMOTE_ADDR' ];
        $client = $_SERVER[ 'HTTP_USER_AGENT' ];
        $nonce = crypt( $ip . $client, 'st' );
        return api_response( $nonce, 200 );
    }

    public function verify_nonce( $nonce )
    {
        $ip = $_SERVER[ 'REMOTE_ADDR' ];
        $client = $_SERVER[ 'HTTP_USER_AGENT' ];
        $current_nonce = crypt( $ip . $client, 'st' );
        if( $current_nonce === $nonce ) {
            return true;
        }
        return false;
    }



    public function handler_email_help( WP_REST_Request $request )
    {
        try {
            $nonce = $request->get_param( 'security' );
            if( !$this->verify_nonce( $nonce ) ) {
                throw new Exception( 'Erro na segurança', 403 );
            }
            $name    = filter_var( $request->get_param( 'name' ), FILTER_SANITIZE_STRING );
            $email   = filter_var( $request->get_param( 'email' ), FILTER_SANITIZE_EMAIL );
            $phone   = filter_var( $request->get_param( 'phone' ), FILTER_SANITIZE_NUMBER_INT );
            $message = filter_var( $request->get_param( 'message' ), FILTER_SANITIZE_SPECIAL_CHARS );

            $response_sendgrid = $this->send_email( $name, $email, $phone, $message );
            return api_response( $response_sendgrid->body(), $response_sendgrid->statusCode() );
        } catch ( Exception $e ) {
            return api_response( $e->getMessage(), $e->getCode() );
        }
    }



    public function send_email( $name, $email, $phone, $message )
    {
        global $Polen_Plugin_Settings;
        $apikeySendgrid = $Polen_Plugin_Settings[ Polen_Sendgrid_Redux::APIKEY ];
        $send_grid = new Polen_Sendgrid_Emails( $apikeySendgrid );
        $send_grid->set_from(
            $Polen_Plugin_Settings['polen_smtp_from_email'],
            $Polen_Plugin_Settings['polen_smtp_from_name']
        );
        $send_grid->set_to( $Polen_Plugin_Settings[ 'recipient_email_polen_help' ], 'Ajuda Polen' );
        $send_grid->set_template_id( $Polen_Plugin_Settings[ Polen_Sendgrid_Redux::THEME_ID_GALO_HELP ] );
        $send_grid->set_template_data( 'name', $name );
        $send_grid->set_template_data( 'email', $email );
        $send_grid->set_template_data( 'phone', $phone );
        $send_grid->set_template_data( 'message', $message );
        return $send_grid->send_email();
    }

}
