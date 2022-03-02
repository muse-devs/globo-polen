<?php
namespace Polen\Api\Talent;

use Polen\Includes\Module\Polen_User_Module;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Server;

class Api_Talent_My_Account extends WP_REST_Controller
{

    /**
     * Metodo construtor
     */
    public function __construct()
    {
        $this->namespace = 'polen/v1';
        $this->rest_base = 'talents';
    }

    /**
     * Registro das Rotas
     */
    public function register_routes()
    {
        register_rest_route($this->namespace, $this->rest_base . '/myaccount', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'myaccount'],
                'permission_callback' => [ Api_Talent_Check_Permission::class, 'check_permission' ],
                'args' => []
            ]
        ] );

        register_rest_route($this->namespace, $this->rest_base . '/password', [
            [
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => [$this, 'update_password'],
                'permission_callback' => [Api_Talent_Check_Permission::class, 'check_permission'],
                'args' => []
            ]
        ] );

        register_rest_route($this->namespace, $this->rest_base . '/user', [
            [
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => [$this, 'update_user'],
                'permission_callback' => [Api_Talent_Check_Permission::class, 'check_permission'],
                'args' => []
            ]
        ] );
    }

    /**
     * Listar informações do usuario
     */
    public function myaccount(): \WP_REST_Response
    {
        $products_id  = Api_Talent_Utils::get_globals_product_id();
        $user_module = Polen_User_Module::create_from_product_id($products_id[0]);

        $talent_object = $user_module->get_info_talent();
        foreach ($talent_object as $talent) {
            $data['user_id'] = $talent->user_id;
            $data['name'] = $user_module->get_display_name();
            $data['birthday'] = $talent->nascimento;
            $data['fantasy_name'] = $talent->nome_fantasia;
            $data['phone'] = $talent->celular;
            $data['telephone'] = $talent->telefone;
            $data['whatsapp'] = $talent->whatsapp;
        }

        return api_response($data);
    }

    /**
     * Atualizar Senha do usuario
     *
     * @param WP_REST_Request $request
     * @return \WP_REST_Response
     */
    public function update_password(\WP_REST_Request $request): \WP_REST_Response
    {
        $products_id  = Api_Talent_Utils::get_globals_product_id();
        $user_module = Polen_User_Module::create_from_product_id($products_id[0]);

        $current = $request->get_param('current_pass');
        $new = $request->get_param('new_pass');

        try {
            $user_module->update_pass($current, $new);
            return api_response('Senha Atualizada', 200);
        } catch (\Exception $e) {
            return api_response($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Atualizar Senha do usuario
     *
     * @param WP_REST_Request $request
     * @return \WP_REST_Response
     */
    public function update_user(\WP_REST_Request $request): \WP_REST_Response
    {
        $products_id  = Api_Talent_Utils::get_globals_product_id();

        $user_module = Polen_User_Module::create_from_product_id($products_id[0]);
        $data = $request->get_params();

        try {
            $user_module->update_user($data);
            return api_response('Dados atualizados', 200);
        } catch (\Exception $e) {
            return api_response($e->getMessage(), $e->getCode());
        }
    }
}
