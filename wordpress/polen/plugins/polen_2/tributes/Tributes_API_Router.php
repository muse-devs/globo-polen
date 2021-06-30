<?php
namespace Polen\Tributes;

class Tributes_API_Router extends \WP_REST_Controller
{

    public function __construct( bool $static = false )
    {
        if( $static ) {
            $this->create_routes_create_tributes();
        }
    }

    public function create_routes_create_tributes()
    {
        $controller = new Tributes_Controller();
        add_action( 'create_routes_create_tributes', [ $controller, 'create_tribute' ] );
        add_action( 'wp_ajax_nopriv_create_tribute', [ $controller, 'create_tribute' ] );

        add_action( 'wp_ajax_nopriv_check_slug_exists', [ $controller, 'check_slug_exists' ] );
        add_action( 'wp_ajax_nopriv_check_hash_exists', [ $controller, 'check_hash_exists' ] );
    }
}
