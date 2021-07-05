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
        $friends_list = $_POST[ 'friends' ];
        
        if ( empty( $tribute ) || $tribute->ID !== $tribute_id ) {
            wp_send_json_error( 'Tributo Inválido', 401 );
            wp_die();
        }

        if( empty( $friends_list ) ) {
            wp_send_json_error( 'lista de convites inválida', 401 );
            wp_die();
        }

        $emails = $_POST[ 'friends' ][ 'email' ];
        $names = $_POST[ 'friends' ][ 'name' ];
        try {
            for( $i = 0; $i < count( $emails ); $i++ ) {
                $email = filter_var( $emails[ $i ], FILTER_VALIDATE_EMAIL );
                $name = $names[ $i ];
                if( !empty( $email ) ) {
                    $invite = $this->create_a_invites( $name, $email, $tribute->ID );
                    $email_invite_content = \tributes_email_create_content_invite( $invite->hash );
                    tributes_send_email( $email_invite_content, $invite->name_inviter, $invite->email_inviter );
                }
            }
            wp_send_json_success( 'Convites criados com sucesso', 201 );
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage(), $e->getCode() );
        }
        wp_die();
    }


    /**
     * 
     */
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
