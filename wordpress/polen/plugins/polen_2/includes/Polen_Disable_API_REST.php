<?php

namespace Polen\Includes;

class Polen_Disable_API_REST
{

    public function __construct( bool $static = false )
    {
        if( $static ) {
            add_filter( 'rest_authentication_errors', [ $this, 'disable_rest_api' ] );
        }
    }

    public function disable_rest_api( $result ) {
        global $request;
        if ( true === $result || is_wp_error( $result ) ) {
            return $result;
        }
        if ( ! is_user_logged_in() ) {
            return new \WP_Error('rest_not_logged_in', 'Silence is Golden', [ 'status' => '401' ] );
        }
        return $result;
    }
}
