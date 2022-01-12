<?php
namespace Polen\Api;

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
        }
    }

}