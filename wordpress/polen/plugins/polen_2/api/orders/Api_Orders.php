<?php
namespace Polen\Api\Orders;

use Polen\Api\Talent\Api_Talent_Check_Permission;
use Polen\Includes\Polen_Order;
use Polen\Includes\Polen_Talent;
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
        register_rest_route( $this->namespace, $this->rest_base . '/orders/(?P<id>[\d]+)', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'get_orders'],
                'permission_callback' => [Api_Talent_Check_Permission::class, 'check_permission'],
                'args' => [
                    'id' => [
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
        $talent_id = $request['id'];
        $customer_orders = [];

        $polen_talent = new Polen_Talent();
        $customer_orders = $polen_talent->get_talent_orders_v2($talent_id);

        // foreach ($talent_orders as $talent_order) {
        //     $order = wc_get_order($talent_order['order_id']);
        //     $customer_orders[] = [
        //         'order_id' => $talent_order['order_id'],
        //         'from' => $talent_order['name'],
        //         'to' => $talent_order['from'],
        //         'category' => $talent_order['category'],
        //         'total' => $talent_order['total_value'],
        //         'total_raw' => $talent_order['total_raw'],
        //         'deadline' => date('Y-m-d', Polen_Order::get_deadline_timestamp_by_order($order)),
        //         'origin' => $talent_order['origin'],
        //         'instructions' => $talent_order['instructions'],
        //     ];
        // }

        return api_response($customer_orders);
    }

}
