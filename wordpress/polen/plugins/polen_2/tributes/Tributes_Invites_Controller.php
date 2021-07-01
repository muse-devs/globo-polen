<?php
namespace Polen\Tributes;

use Polen\Includes\Debug;

class Tributes_Invites_Controller
{
    public function create_tribute_invites()
    {
        $tribute_hash = filter_input( INPUT_POST, 'tribute_hash' );
        $tribute      = Tributes_Model::get_by_hash( $tribute_hash );
        $tribute_id   = filter_input( INPUT_POST, 'tribute_id' );
        
        if ( empty( $tribute ) || $tribute->ID !== $tribute_id ) {
            wp_send_json_error( 'Tributo Inválido', 401 );
            wp_die();
        }

        $data_input = [];
        $data_input[ 'tribute_id' ]    = $tribute_id;
        $data_input[ 'name_inviter' ]  = filter_input( INPUT_POST, 'name_inviter' );
        $data_input[ 'email_inviter' ] = filter_input( INPUT_POST, 'email_inviter', FILTER_VALIDATE_EMAIL );
        $data_input[ 'hash' ]          = Tributes_Invites_Model::create_hash( $tribute_id );

        try {
            $new_id = Tributes_Invites_Model::insert( $data_input );
            $new_tribute = Tributes_Invites_Model::get_by_id( $new_id );
            wp_send_json_success( $new_tribute, 201 );
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage(), $e->getCode() );
        }
        wp_die();
    }
}