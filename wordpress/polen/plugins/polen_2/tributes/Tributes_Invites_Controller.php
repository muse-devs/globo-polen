<?php
namespace Polen\Tributes;

use Polen\Includes\Debug;

class Tributes_Invites_Controller
{

    /**
     * Inseri na base um Invite inicial
     */
    public function create_tribute_invites()
    {
        $tribute_hash = filter_input( INPUT_POST, 'tribute_hash' );
        $tribute      = Tributes_Model::get_by_hash( $tribute_hash );
        $tribute_id   = filter_input( INPUT_POST, 'tribute_id' );
        $friends_list = filter_input( INPUT_POST, 'friends' );
        Debug::def($_POST, $friends_list);
        if ( empty( $tribute ) || $tribute->ID !== $tribute_id ) {
            wp_send_json_error( 'Tributo Inválido', 401 );
            wp_die();
        }

        if( empty($friends_list) ) {
            wp_send_json_error( 'lista de convites inválida', 401 );
            wp_die();
        }

        // $data_input = [];
        // $data_input[ 'name_inviter' ]  = filter_input( INPUT_POST, 'name_inviter' );
        // $data_input[ 'email_inviter' ] = filter_input( INPUT_POST, 'email_inviter', FILTER_VALIDATE_EMAIL );
        // $data_input[ 'tribute_id' ]    = $tribute_id;
        // $data_input[ 'hash' ]          = Tributes_Invites_Model::create_hash( $tribute_id );

        try {
            // $new_id = Tributes_Invites_Model::insert( $data_input );
            // $new_tribute = Tributes_Invites_Model::get_by_id( $new_id );
            // wp_send_json_success( $new_tribute, 201 );
            foreach( $friends_list as $friend ) {
                $this->create_a_invites( $friend[ 'name' ], $friend[ 'email' ], $tribute->ID );
            }
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage(), $e->getCode() );
        }
        wp_die();
    }


    public function create_a_invites( $name_inviter, $email_inviter, $tribute_id )
    {
        $data_input = [];
        $data_input[ 'name_inviter' ]  = $name_inviter;
        $data_input[ 'email_inviter' ] = filter_var( $email_inviter, FILTER_VALIDATE_EMAIL );
        $data_input[ 'tribute_id' ]    = $tribute_id;
        $data_input[ 'hash' ]          = Tributes_Invites_Model::create_hash( $tribute_id );
        $new_id = Tributes_Invites_Model::insert( $data_input );
        $new_tribute = Tributes_Invites_Model::get_by_id( $new_id );
        return $new_tribute;
    }
}
