<?php
namespace Polen\Api;

use Polen\Api\Orders\Api_Orders;
use Polen\Api\Talent\Api_Talent_Dashboard;

class Api {

    public function __construct( bool $static = false )
    {
        if( $static ) {
            new Api_Routers( true );
            add_action( 'rest_api_init', array( $this, 'rest_api_includes' ) ); // add to construct class
        }
    }

    // create this method
    public function rest_api_includes() {
        if ( empty( WC()->cart ) ) {
            WC()->frontend_includes();
            wc_load_cart();

            #Área do Talento Logado
            $talent_dashboard = new Api_Talent_Dashboard();
            $talent_dashboard->register_routes();

            #Área de pedidos
            $talent_dashboard = new Api_Orders();
            $talent_dashboard->register_routes();
        }
    }

}