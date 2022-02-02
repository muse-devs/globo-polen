<?php
namespace Polen\Api\Talent;

use Exception;
use Polen\Api\Api_Util_Security;
use Polen\Includes\Talent\Polen_Talent_Controller;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Server;

class Api_Talent_Order extends WP_REST_Controller
{
    /**
     * Controler de check permission
     */
    protected $controller_access;

    /**
     * Esquema
     */
    protected $schema = [
        '$schema'              => 'http://json-schema.org/draft-04/schema#',
        'title'                => 'Talent Dashboard',
        'type'                 => 'Object',
        'properties'           => array(
            'id' => array(
                'description'  => 'Unique identifier for the object.',
                'type'         => 'integer',
                'context'      => array( 'view', 'edit', 'embed' ),
                'readonly'     => true,
            ),
            'content' => array(
                'description'  => 'The content for the object.',
                'type'         => 'string',
            ),
        ),
    ];

    /**
     * Metodo construtor
     */
    public function __construct()
    {
        $this->namespace = 'polen/v1';
        $this->rest_base = 'talent';
    }


    /**
     * Registro das Rotas
     */
    public function register_routes()
    {
        //Rota para aceitar ou não o pedido de video
        register_rest_route( $this->namespace, $this->rest_base . '/orders/(?P<id>[\d]+)/acceptance', [
            [
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => [ $this, 'accept_reject_order' ],
                'permission_callback' => [ Api_Talent_Check_Permission::class, 'check_permission' ],
                'args' => []
            ]
        ] );

        //Rota para pegar um nonde válido
        register_rest_route( $this->namespace, $this->rest_base . '/orders/nonce', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [ $this, 'create_nonce' ],
                'permission_callback' => [ Api_Talent_Check_Permission::class, 'check_permission' ],
                'args' => []
            ]
        ] );
    }


    /**
     * Handler da rota para um talento aceitar ou negar um pedido
     */
    public function accept_reject_order( WP_REST_Request $request )
    {
        $order_id           = $request['id'];
        $security           = $request->get_param('security');
        $option             = $request->get_param('option');
        $reason_reject      = $request->get_param('reason_reject') ?? '';
        $description_reject = $request->get_param('description_reject') ?? '';
        $user_id            = get_current_user_id();
        $ip                 = $_SERVER['REMOTE_ADDR'];
        $client             = $_SERVER['HTTP_USER_AGENT'];

        try {
            if(!isset($security) || !Api_Util_Security::verify_nonce($ip . $client . $user_id, $security)) {
                throw new Exception('Problema na seguraça tente novamente', 403);
            }

            $talent_controller = new Polen_Talent_Controller();
            $result = $talent_controller->talent_accept_or_reject_api(
                $option,
                $order_id,
                $reason_reject,
                $description_reject
            );
            return api_response($result, 200);
        } catch (Exception $e) {
            return api_response($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Handler para a criacao de um nonce
     */
    public function create_nonce(WP_REST_Request $request)
    {
        $ip     = $_SERVER[ 'REMOTE_ADDR' ];
        $client = $_SERVER[ 'HTTP_USER_AGENT' ];
        $user_id = get_current_user_id();
        return api_response( Api_Util_Security::create_nonce($ip . $client . $user_id), 200 );
    }
}