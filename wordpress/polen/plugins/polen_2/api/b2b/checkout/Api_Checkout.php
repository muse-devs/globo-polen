<?php
namespace Polen\Api\b2b\Checkout;

use Exception;
use Polen\Api\Api_Util_Security;
use Polen\Includes\Module\Products\Polen_B2B_Orders;
use Polen\Includes\Polen_Create_Customer;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Server;

class Api_Checkout extends WP_REST_Controller
{
    protected $controller_access;

    public function __construct()
    {
        $this->namespace = 'polen/v1';
        $this->rest_base = 'b2b';

        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes()
    {
        register_rest_route($this->namespace, $this->rest_base . '/checkout/(?P<order_id>[\d]+)/(?P<key_order>[^/]*)', [
            [
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => [$this, 'form_checkout'],
                'permission_callback' => [],
                'args' => []
            ],
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'checkout_info_step_one'],
                'permission_callback' => [],
                'args' => []
            ]
        ] );

    }

    /**
     * Rota checkout
     *
     * @param WP_REST_Request $request
     * @return \WP_REST_Response
     * @throws Exception
     */
    public function form_checkout(WP_REST_Request $request): \WP_REST_Response
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $client = $_SERVER['HTTP_USER_AGENT'];
        $nonce = $request->get_param('security');

        try {
            if(!Api_Util_Security::verify_nonce($ip . $client, $nonce)) {
                throw new Exception('Erro na segurança', 403);
            }

            $required_fields = $this->required_fields();
            $fields_checkout = $request->get_params();

            foreach ($required_fields as $key => $field) {
                if (!isset($fields_checkout[$key]) && !empty($field)) {
                    $errors[] = "O campo {$field} é obrigatório";
                }
                $data[$key] = sanitize_text_field($fields_checkout[$key]);
            }

            if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email inválido', 403);
            }

            /*
            TODO: Os passos posteriores só serão executados caso o pagamento for aprovado
            TODO: implementar pagamento
            */

            $create_user = new Polen_Create_Customer();
            $user = $create_user->create_new_user($data);

            $b2b_order = new Polen_B2B_Orders($request['order_id'], $request['key_order']);
            $b2b_order->update_order($data);

            return api_response([
                'status' => 'Pagamento aprovado',
                'new_account' => $user['new_account'],
            ]);

        } catch(Exception $e) {
            return api_response($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Exibir informações básicas
     *
     * @throws Exception
     */
    public function checkout_info_step_one(WP_REST_Request $request): \WP_REST_Response
    {
        try {
            $b2b_order = new Polen_B2B_Orders($request['order_id'], $request['key_order']);

            return api_response($b2b_order->get_order_info_step_one());
        } catch(Exception $e) {
            return api_response($e->getMessage(), $e->getCode());
        }

    }

    /**
     * Retorna todos os campos do formulário que são obrigatórios
     */
    private function required_fields(): array
    {
        return [
            'name' => 'Nome do representante',
            'company' => 'Nome empresa',
            'address_1' => 'Endereço',
            'address_2' => 'Complemento',
            'city' => 'Cidade',
            'postcode' => 'CEP',
            'neighborhood' => 'Bairro',
            'country' => 'País',
            'state' => 'Estado',
            'email' => 'Email',
            'phone' => 'Celular',
            'cnpj' => 'CNPJ',
            'corporate_name' => 'Razão Social',
            'method_payment' => 'Método de pagamento',
        ];
    }

}
