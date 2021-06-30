<?php

namespace Polen\Tributes;

class Tributes_Controller
{
    
    public function create_tribute()
    {
        $data_input = [];
        $data_input[ 'name_honored' ] = filter_input( INPUT_POST, 'name_honored' );
        $data_input[ 'slug' ] = filter_input( INPUT_POST, 'slug' );
        $data_input[ 'hash' ] = Tributes_Model::create_hash();
        $data_input[ 'deadline' ] = filter_input( INPUT_POST, 'slug' );
        $data_input[ 'occasion' ] = filter_input( INPUT_POST, 'slug' );;
        $data_input[ 'creator_email' ] = filter_input( INPUT_POST, 'slug' );
        $data_input[ 'welcome_message' ] = filter_input( INPUT_POST, 'slug' );
        $data_input[ 'question' ] = filter_input( INPUT_POST, 'slug' );

        try {
            $new_id = Tributes_Model::insert( $data_input );
            $new_tribute = Tributes_Model::get_by_id( $new_id );
            wp_send_json_success( $new_tribute, 201 );
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
        $tribute = Tributes_Model::get_by_slug( $slug );
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

}