<?php
namespace Polen\Tributes;

class Tributes_API_Router extends \WP_REST_Controller
{
    protected $namespace = 'tributes/v1';
    protected $rest_base = 'products';
    protected $post_type = 'product';

    public function __construct( bool $static = false )
    {
        if( $static ) {
            add_action( 'rest_api_init', [ $this, 'register_routes'] );
        }
    }


    public function register_routes()
    {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            array(
                array(
                    'methods' => \WP_REST_Server::READABLE,
                    'callback' => array( $this, 'get_items' ),
                    'permission_callback' => '__RETURN_TRUE',
                    'args' => $this->get_collection_params(),
                ),
            ),
            false,
        );
    }

    public function get_items( $request )
    {
        return new \WP_REST_Response( 'haehehaehae', 200 );
    }
}