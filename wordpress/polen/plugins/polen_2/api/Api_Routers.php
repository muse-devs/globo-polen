<?php
namespace Polen\Api;

use WP_REST_Server;

class Api_Routers{

    public function __construct()
    {
        add_action('rest_api_init', [ $this, 'init_routers' ]);
    }

    function init_routers()
    {
        $controller = new Api_Controller();
        /**
         * ROTA: Listar Talentos
         *
         * @param s Filtrar por string (opcional)
         * @param paged Exibir a página atual (opcional)
         * @param per_page Número de post por página (opcional)
         * @param campaign Filtrar pela a  campanha - ID (opcional)
         * @param orderby ordernar resultados de posts (opcional)
         *      Values [
         *           Ordenar por popularidade = popularity
         *           Ordenar por media de classificação = rating
         *           Ordenar do mais antigo para o mais novo = date-asc
         *           Ordenar do mais novo para o mais antigo = date-desc
         *           Ordenar menor preço para maior: price-asc
         *           Ordenar maior preço para menor: price-desc
         *      ]
         * @param campaign_category Filtrar por categoria - slug (opcional)
         */
        register_rest_route('v3', '/talents', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$controller, 'talents'],
            'args' => [
                's',
                'per_page',
                'paged',
                'orderby',
                'campaign',
                'campaign_category,',
            ],
            'permission_callback' => '__return_true',
        ));

        /**
         * ROTA: Descrição do talento
         *
         * @param slug slug do talento (required)
         * @param campaign slug da campanha do talento (required)
         */
        register_rest_route('v3', '/talent', array(
            'methods' => WP_REST_Server::READABLE,
            'args' => array(
                'slug',
                'campaign',
            ),
            'callback' => [$controller, 'talent'],
            'permission_callback' => '__return_true',
        ));

        /**
         * ROTA: metodo de pagamento
         *
         * @param name Nome cliente (required)
         * @param cpf CPF do cliente (required)
         * @param phone Telefone do cliente (required)
         * @param email Email do cliente (required)
         * @param product_id ID do produto que será comprado (required)
         * @param coupon Cupom que será utilizado na compra (opcional)
         */
        register_rest_route('v3', '/payment', array(
            'methods' => WP_REST_Server::CREATABLE,
            'args' => array(
                'name',
                'cpf',
                'phone',
                'email',
                'product_id',
                'coupon',
            ),
            'callback' => [$controller, 'payment'],
            'permission_callback' => '__return_true',
        ));

        /**
         * ROTA: Verificar se existe stock
         *
         * @param product_id ID do produto que será comprado (required)
         */
        register_rest_route('v3', '/cart', array(
            'methods' => WP_REST_Server::READABLE,
            'args' => array(
                'product_id',
            ),
            'callback' => [$controller, 'cart'],
            'permission_callback' => '__return_true',
        ));
    }
}
