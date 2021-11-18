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
         * @param s Filtrar por string (opcional.)
         * @param paged Exibir a página atual (opcional)
         * @param perPage Número de post por página (opcional)
         * @param category Filtrar por categoria (opcional)
         */
        register_rest_route('/v3', '/talents', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$controller, 'talents'],
            'args' => [
                's',
                'perPage',
                'paged',
                'category,',
            ],
            'validate_callback' => '__return_null',
        ));
    }
}
