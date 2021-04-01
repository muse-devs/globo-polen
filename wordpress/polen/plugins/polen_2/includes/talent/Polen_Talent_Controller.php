<?php

namespace Polen\Includes\Talent;

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
}