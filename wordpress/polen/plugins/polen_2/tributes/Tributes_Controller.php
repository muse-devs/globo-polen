<?php

namespace Polen\Tributes;

use Exception;
use Polen\Includes\Vimeo\Polen_Vimeo_Create_Folder;
use Polen\Includes\Vimeo\Polen_Vimeo_Factory;

class Tributes_Controller
{
    
    public function create_tribute()
    {
        $data_input = [];
        $data_input[ 'name_honored' ]    = filter_input( INPUT_POST, 'name_honored', FILTER_SANITIZE_SPECIAL_CHARS );
        $data_input[ 'slug' ]            = filter_input( INPUT_POST, 'slug', FILTER_SANITIZE_SPECIAL_CHARS );
        $data_input[ 'hash' ]            = Tributes_Model::create_hash();
        $data_input[ 'deadline' ]        = $this->treat_deadline_date( filter_input( INPUT_POST, 'deadline' ) );
        $data_input[ 'occasion' ]        = filter_input( INPUT_POST, 'occasion', FILTER_SANITIZE_SPECIAL_CHARS );;
        $data_input[ 'creator_name' ]    = filter_input( INPUT_POST, 'creator_name', FILTER_SANITIZE_SPECIAL_CHARS );
        $data_input[ 'creator_email' ]   = filter_input( INPUT_POST, 'creator_email', FILTER_VALIDATE_EMAIL );
        $data_input[ 'welcome_message' ] = filter_input( INPUT_POST, 'welcome_message', FILTER_SANITIZE_SPECIAL_CHARS );
        
        if( !$this->validate_slug_not_empty( $data_input[ 'slug' ] ) ) {
            wp_send_json_error( 'Endereço não pode ser em branco', 401 );
            wp_die();
        }
        
        if( !$this->validate_slug_not_empty( $data_input[ 'welcome_message' ] ) ) {
            wp_send_json_error( 'Instruções não pode ser em branco', 401 );
            wp_die();
        }
        if( !$this->validate_slug_not_empty( $data_input[ 'creator_email' ] ) ) {
            wp_send_json_error( 'Seu email não pode ser em branco', 401 );
            wp_die();
        }
        try {
            $new_id = Tributes_Model::insert( $data_input );
            $new_tribute = Tributes_Model::get_by_id( $new_id );

            $folder_name = "{$new_id}_{$data_input[ 'slug' ]}";
            $create_folder_tribute = $this->create_folder_to_new_tribute( $folder_name );

            $data_update_tribute_folder = array(
                'ID' => $new_id,
                'vimeo_folder_uri' => $create_folder_tribute,
            );
            Tributes_Model::update( $data_update_tribute_folder );

            $return_ajax = array(
                'hash' => $new_tribute->hash,
                'url_redirect' => tribute_get_url_invites( $new_tribute->hash ),
            );

            $invite_model = new Tributes_Invites_Controller();
            $invite = $invite_model->create_a_invites( $data_input[ 'creator_name' ], $data_input[ 'creator_email' ], $new_id );
            $email_invite_content = \tributes_email_create_content_invite( $invite->hash );
            tributes_send_email( $email_invite_content, $invite->name_inviter, $invite->email_inviter );

            wp_send_json_success( $return_ajax, 201 );
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage(), $e->getCode() );
        }
        wp_die();
    }

    /**
     *
     */
    public function check_slug_exists()
    {
        $slug    = filter_input( INPUT_POST, 'slug' );

        if( !$this->validate_slug_not_empty( $slug ) ) {
            wp_send_json_error( 'Porfavor escolha um endereço', 401 );
            wp_die();
        }
        $tribute = Tributes_Model::get_by_slug( $slug );
        if( !empty( $tribute ) ) {
            wp_send_json_error( 'Esse endereço já existe, tente outro', 403 );
            wp_die();
        }
        wp_send_json_success( 'Endereço disponível', 200 );
        wp_die();       
    }


    /**
     *
     */
    public function check_hash_exists( $hash )
    {
        $slug    = filter_input( INPUT_POST, 'slug' );
        $tribute = Tributes_Model::get_by_hash( $slug );
        if( !empty( $tribute ) ) {
            wp_send_json_error( 'Slug já existe', 403 );
            wp_die();
        }
        wp_send_json_success( 'Slug livre', 200 );
        wp_die();   
    }


    /**
     * 
     */
    private function treat_deadline_date( $deadline )
    {
        $date_time = \DateTime::createFromFormat( 'd/m/Y', $deadline );
        if( $date_time === false ) {
            wp_send_json_error( 'Data inválida', 401 );
            wp_die();
        }
        return $date_time->format( 'Y-m-d' );
    }
    

    /**
     * 
     */
    private function validate_slug_not_empty( $slug )
    {
        return ( empty( trim( $slug ) ) ) ? false : true;
    }


    /**
     * Criar uma pasta para os videos de um novo tributo
     * @param \Vimeo\Vimeo $vimeo_api
     * @param string $folder_name
     * @return string uri_da_folder
     */
    private function create_folder_to_new_tribute( $folder_name )
    {
        $vimeo_api = Polen_Vimeo_Factory::create_vimeo_colab_instance_with_redux();
        $result_viemo_create_folder = Polen_Vimeo_Create_Folder::create_folder( $vimeo_api, $folder_name );
        if( $result_viemo_create_folder->is_error() ) {
            throw new Exception( $result_viemo_create_folder->get_error(), 403 );
        }
        return $result_viemo_create_folder->get_folder_uri();
    }

}