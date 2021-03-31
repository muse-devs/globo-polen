<?php

namespace Polen\Includes\Talent;

use Vimeo\Vimeo;

class Polen_Talent_Controller extends Polen_Talent_Controller_Base
{
    public function login()
    {
        
        $username = sanitize_email( filter_input( INPUT_POST, 'login' ) );
        $password = trim( filter_input( INPUT_POST, 'password' ) );

        $auth_result = wp_authenticate( $username, $password );
        if( is_wp_error( $auth_result ) ) {
            wp_send_json_error( null, 401 );
        }
        
        //TODO: Verificar se a role é talent
        
        $response = array(
            'user_name' => '',
            'user_email' => '',
            'display_name' => ''
        );
        echo wp_send_json( $auth_result );
    }
    
    public function get_total_a_receber()
    {
        
    }
    
    public function make_video_slot_vimeo()
    {
        $client_id = '1306bc73699bfe32ef09370f448c922d62f080d3';
        $client_secret = 'KN1bXutJtv8rYmlxU6Pbo4AhhCl8yhDKd20LHQqWDi0jXxcXGIVsmVHTxkcIVJzsDcrzZ0WNl'
                       . 'y9sP+CGU9gpLZBneKr0VfdpEFL/MSVS7jae0jLAoi/ev/P85gPV4oUS';
        $token = 'ecdf5727a7b96ec6179c5090db5851ba';

        $lib = new Vimeo( $client_id, $client_secret, $token );
//        return 'asdadads';
//        echo $data;
        $data = ( [false, true, true] );
        wp_send_json_success( $data );
        wp_die();
    }
}