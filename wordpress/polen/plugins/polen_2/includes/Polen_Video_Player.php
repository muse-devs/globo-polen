<?php

namespace Polen\Includes;

use \Polen\Includes\Polen_Update_Fields;
class Polen_Video_Player
{
    public function __construct( bool $static = false )
    {
        if( $static ) {
            add_action( 'init',             array( $this, 'rewrites' ) );
            add_filter( 'query_vars',       array( $this, 'query_vars' ) );
            add_action( 'template_include', array( $this, 'template_include' ) );

            add_action( 'wp_ajax_nopriv_draw-player-modal', array( $this, 'draw_player_modal' ) );
            add_action( 'wp_ajax_draw-player-modal',        array( $this, 'draw_player_modal' ) );
        }
    }
    
    
    public function rewrites()
    {
        add_rewrite_rule( 'v/([a-z0-9-]+)[/]?$', 'index.php?video_hash=$matches[1]', 'top' );
    }
    
    public function query_vars( $query_vars )
    {
        $query_vars[] = 'video_hash';
        return $query_vars;
    }
    
    public function template_include( $template )
    {
        if ( get_query_var( 'video_hash' ) == false || get_query_var( 'video_hash' ) == '' ) {
            return $template;
        }
        $video_hash = get_query_var( 'video_hash' );        
        return get_template_directory() . '/video.php';
    }


    /**
     * Funcao handler do /wp-admin/admin-ajax.php
     * onde é recebido via GET a hash e é devolvido um 
     * HTML para apresentar o player do modal
     */
    public function draw_player_modal()
    {
        $video_hash = filter_input( INPUT_GET, 'hash' );
        if( empty( $video_hash ) ) {
            polen_player_video_modal_ajax_invalid_hash();
            wp_die();
        }

        $video_info = Polen_Video_Info::get_by_hash( $video_hash );
        if(  empty( $video_info ) ) {
            polen_player_video_modal_ajax_invalid_hash();
            wp_die();
        }

        $Talent_Fields = new Polen_Update_Fields();
        $talent = $Talent_Fields->get_vendor_data( $video_info->talent_id );
        $user_id = get_current_user_id();
        polen_get_video_player( $talent, $video_info, $user_id );
        wp_die();
    }
}
