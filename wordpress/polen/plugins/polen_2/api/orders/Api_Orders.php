<?php
namespace Polen\Api\Orders;

use Exception;
use Polen\Api\Talent\Api_Talent_Check_Permission;
use Polen\Includes\Polen_Order;
use Polen\Includes\Polen_Talent;
use Polen\Includes\Talent\Polen_Talent_Controller;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Server;

class Api_Orders extends WP_REST_Controller
{
    protected $controller_access;

    public function __construct()
    {
        $this->namespace = 'polen/v1';
        $this->rest_base = 'talents';

        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes()
    {
        register_rest_route( $this->namespace, $this->rest_base . '/orders', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'get_orders'],
                'permission_callback' => [Api_Talent_Check_Permission::class, 'check_permission'],
                'args' => []
            ]
        ] );



        register_rest_route( $this->namespace, $this->rest_base . '/orders/(?P<order_id>[\d]+)', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'get_order'],
                'permission_callback' => [Api_Talent_Check_Permission::class, 'check_permission'],
                'args' => [
                    'order_id' => [
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric($param);
                        }
                    ],
                ]
            ]
        ] );
    }


    /**
     * Retornar pedidos por ID do cliente
     *
     * @param WP_REST_Request $request
     * @return \WP_REST_Response
     */
    public function get_orders(WP_REST_Request $request): \WP_REST_Response
    {
        $talent_id = get_current_user_id();
        $customer_orders = [];

        $polen_talent = new Polen_Talent();
        $customer_orders = $polen_talent->get_talent_orders_v2($talent_id);

        return api_response($customer_orders);
    }


    /**
     * Retornar detalhes de uma unica order
     *
     * @param WP_REST_Request $request
     * @return \WP_REST_Response
     * @throws Exception
     */
    public function get_order(WP_REST_Request $request): \WP_REST_Response
    {
        $talent_id = get_current_user_id();
        $order_id = $request['order_id'];
        try {
            $talent_controller = new Polen_Talent_Controller();
            $checked = $talent_controller->check_product_and_order( $talent_id, $order_id );
            if(!$checked) {
                throw new Exception('Erro na relação ídolo/pedido', 403);
            }
            $talent_service = new Polen_Talent();
            $order_info = $talent_service->get_order_info_v2($order_id);
            if(empty($order_info)) {
                throw new Exception('Pedido não encontrado', 403);
            }
            
            return api_response($order_info);
        } catch(Exception $e) {
            return api_response($e->getMessage(), $e->getCode());
        }
    }

}
