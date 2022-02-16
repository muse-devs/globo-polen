<?php
namespace Polen\Api;

use Exception;
use Polen\Admin\Polen_Forms;
use Polen\Includes\Polen_Form_DB;
use Polen\Includes\Polen_Zapier;
use Polen\Includes\Sendgrid\Polen_Sendgrid_Emails;
use Polen\Includes\Sendgrid\Polen_Sendgrid_Redux;
use WP_REST_Request;
use WP_REST_Server;

class Api_Contact
{

    public $base;
    public function __construct()
    {
        $this->base = 'polen/v1';
    }
    public function register_route()
    {
        /**
         * Criacao de um endpoint para envio de email de ajuda Galo
         */
        register_rest_route( $this->base, '/contact', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'handler_email_help'),
                'permission_callback' => '__return_true',
            ),
            'schema' => array()
        ) );
        
        #CRIANDO ENDOPINT PARA NONCE DO FORMULARIO DE EMAIL DO 
        register_rest_route( $this->base, '/contact', array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array($this, 'create_nonce'),
                'permission_callback' => '__return_true',
            ),
            'schema' => array()
        ) );


        register_rest_route( $this->base, '/b2b', array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'handler_b2b_contact'),
                'permission_callback' => '__return_true',
            ),
            'schema' => array()
        ) );
    }


    public function create_nonce( WP_REST_Request $request )
    {
        $ip     = $_SERVER[ 'REMOTE_ADDR' ];
        $client = $_SERVER[ 'HTTP_USER_AGENT' ];
        return api_response( Api_Util_Security::create_nonce($ip . $client), 200 );
    }




    public function handler_email_help( WP_REST_Request $request )
    {
        try {
            $ip     = $_SERVER[ 'REMOTE_ADDR' ];
            $client = $_SERVER[ 'HTTP_USER_AGENT' ];
            $nonce  = $request->get_param( 'security' );
            if( !Api_Util_Security::verify_nonce($ip . $client, $nonce) ) {
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


    public function handler_b2b_contact(WP_REST_Request $request)
    {
        global $Polen_Plugin_Settings;

        $ip     = $_SERVER[ 'REMOTE_ADDR' ];
        $client = $_SERVER[ 'HTTP_USER_AGENT' ];
        $nonce  = $request->get_param( 'security' );

        $form_id = filter_var($request->get_param( 'form_id' ), FILTER_SANITIZE_NUMBER_INT);
        $name    = filter_var($request->get_param( 'name' ), FILTER_SANITIZE_STRING);
        $email   = filter_var($request->get_param( 'email' ), FILTER_SANITIZE_EMAIL);
        $company = filter_var($request->get_param( 'company' ), FILTER_SANITIZE_SPECIAL_CHARS);
        $phone   = filter_var($request->get_param( 'phone' ), FILTER_SANITIZE_SPECIAL_CHARS);
        $slug    = filter_var($request->get_param( 'slug-product' ), FILTER_SANITIZE_SPECIAL_CHARS);
        $terms   = '1';
        $body = compact('form_id', 'name', 'email', 'company', 'phone');
        try {
            if(!Api_Util_Security::verify_nonce($ip . $client, $nonce)) {
                throw new Exception('Erro na segurança', 403);
            }

            $this->validate_inputs_b2b($body);

            $form_db = new Polen_Form_DB();
            $form_db->insert($body);
            
            $body['product'] = $slug;
            $form_service    = new Polen_Forms();
            $form_service->mailSend($body);

            $url_zapier_b2b_hotspot = $Polen_Plugin_Settings['polen_url_zapier_b2b_hotspot'];
            $zapier = new Polen_Zapier();
            $zapier->send($url_zapier_b2b_hotspot, $body);
            
            return api_response(true, 201);
        } catch(Exception $e) {
            return api_response($e->getMessage(), $e->getCode());
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


    /**
     * 
     */
    protected function validate_inputs_b2b(array $body)
    {
        if(!filter_var($body['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Email inválido', 401);
        }
        $required_fields = $this->required_fields_b2b();
        foreach($required_fields as $item) {
            if(empty(trim($body[$item]))) {
                throw new Exception('Todos os campos são obrigatórios');
            }
        }
    }


    /**
     * Retorna todos os campos do formulário que são obrigatórios
     */
    private function required_fields_b2b(): array
    {
        return [
            'name',
            'email',
            'company',
            'phone',
            'form_id',
        ];
    }

}
