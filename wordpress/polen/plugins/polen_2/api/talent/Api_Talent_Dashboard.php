<?php
namespace Polen\Api\Talent;

use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Server;

class Api_Talent_Dashboard extends WP_REST_Controller
{
    protected $controller_access;

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

    public function __construct()
    {
        $this->namespace = 'polen/v1';
        $this->rest_base = 'talent';
    }


    public function register_routes()
    {
        register_rest_route( $this->namespace, $this->rest_base . '/dashboard', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [ $this, 'dashboard' ],
                'permission_callback' => [ Api_Talent_Check_Permission::class, 'check_permission' ],
                'args' => []
            ]
        ] );
    }


    public function dashboard( WP_REST_Request $request )
    {
        return api_response( 'AEEEEEEEEEEEEEEEE' );
    }

}
