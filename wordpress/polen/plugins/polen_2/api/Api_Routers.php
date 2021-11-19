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
        register_rest_route('/v3', '/talents', array(
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
            'validate_callback' => '__return_null',
        ));

        /**
         * ROTA: Descrição do talento
         * @param slug slug do talento (required)
         */
        register_rest_route('/v3', '/talent', array(
            'methods' => 'GET',
            'args' => array(
                'slug',
                ),
            'callback' => [$controller, 'talent'],
            'validate_callback' => '__return_null',
        ));
    }
}
