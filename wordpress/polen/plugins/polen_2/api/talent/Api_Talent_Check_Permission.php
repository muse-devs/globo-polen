<?php
namespace Polen\Api\Talent;

use Polen\Includes\Debug;
use Polen\Includes\Polen_Talent;
use WP_REST_Request;

abstract class Api_Talent_Check_Permission
{
    public static function check_permission( WP_REST_Request $request )
    {
        if( !is_user_logged_in() ) {
            return false;
        }

        $user_id = get_current_user_id();
        if( empty( $user_id ) ) {
            return false;
        }

        $user = get_user_by( 'ID', $user_id );
        if( is_wp_error( $user ) ) {
            return false;
        }

        return Polen_Talent::static_is_user_talent( $user );
    }
}