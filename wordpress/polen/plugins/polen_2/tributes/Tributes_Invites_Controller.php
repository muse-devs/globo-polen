<?php
namespace Polen\Tributes;

use Polen\Includes\Cart\Polen_Cart_Item_Factory;
use Polen\Includes\Debug;
use Polen\Includes\Vimeo\Polen_Vimeo_Response;
use Polen\Includes\Vimeo\Polen_Vimeo_Vimeo_Options;
use Vimeo\Exceptions\ExceptionInterface;
use Vimeo\Exceptions\VimeoRequestException;
use Vimeo\Vimeo;

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
                $name  = $names[ $i ];
                if( !empty( $email ) ) {
                    $invite               = $this->create_a_invites( $name, $email, $tribute->ID );
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
        $new_id      = Tributes_Invites_Model::insert( $data_input );
        $new_tribute = Tributes_Invites_Model::get_by_id( $new_id );
        return $new_tribute;
    }


    /**
     * 
     */
    public function get_all_invite_by_tribute_id()
    {
        $tribute_id = filter_input( INPUT_POST, 'tribute_id' );
        $nonce      = filter_input( INPUT_POST, 'security' );
        $tributes   = Tributes_Invites_Model::get_all_by_tribute_id( $tribute_id );
        wp_send_json_success( $tributes, 200 );
        wp_die();
    }


    /**
     * Handler para o admin-ajax onde é executado quando o convidado ao tributo,
     * seleciona um video e
     * envia, antes do envio é criado no Vimeo um Slot para receber o Video com o 
     * mesmo tamanho em bytes
     * 
     * @global type $Polen_Plugin_Settings
     * @throws VimeoRequestException
     */
    public function make_video_slot_vimeo()
    {
        global $Polen_Plugin_Settings;

        $client_id = $Polen_Plugin_Settings['polen_vimeo_client_id'];
        $client_secret = $Polen_Plugin_Settings['polen_vimeo_client_secret'];
        $token = $Polen_Plugin_Settings['polen_vimeo_access_token'];
        
        $lib = new Vimeo( $client_id, $client_secret, $token );
        
        // $order_id = filter_input( INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT );
        // $file_size = filter_input( INPUT_POST, 'file_size', FILTER_SANITIZE_NUMBER_INT );
        // $name_to_video = filter_input( INPUT_POST, 'name_to_video' );
        //TODO:
        $invite_hash = filter_input( INPUT_POST, 'invite_hash' );
        $invite_id   = filter_input( INPUT_POST, 'invite_id', FILTER_SANITIZE_NUMBER_INT );
        $file_size   = filter_input( INPUT_POST, 'file_size', FILTER_SANITIZE_NUMBER_INT );
        $name_to_video = '';
        try {
            $args = Polen_Vimeo_Vimeo_Options::get_option_insert_video( $file_size, $name_to_video );
            $vimeo_response = $lib->request( '/me/videos', $args, 'POST' );
            
            $response = new Polen_Vimeo_Response( $vimeo_response );
            
            if( $response->is_error() ) {
                throw new VimeoRequestException( $response->get_developer_message(), 500 );
            }
            
            // $order = wc_get_order( $order_id );
            // $cart_item = Polen_Cart_Item_Factory::polen_cart_item_from_order( $order );
            
            // $video_info = $this->mount_video_info( $order, $cart_item, $response);
            // $video_info->insert();
            //TODO: Update o invite
            
            wp_send_json_success( $response->response, 200 );
        } catch ( ExceptionInterface $e ) {
            wp_send_json_error( $e->getMessage(), $e->getCode() );
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage(), $e->getCode() );
        }
        wp_die();
    }
}
