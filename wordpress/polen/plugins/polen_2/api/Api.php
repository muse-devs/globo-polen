<?php
namespace Polen\Api;

class Api{

    public function __construct()
    {
        new Api_Routers();
        add_action('init', [$this, 'create_taxonomy_campaigns']);
    }

    /**
     * Registrar taxonomia de campanha em produtos
     */
    function create_taxonomy_campaigns()
    {
        register_taxonomy(
            'campaigns',
            'product',
            array(
                'label' => 'Campanhas',
                'rewrite' => array('slug' => 'campanha'),
                'hierarchical' => true,
            )
        );
    }
}