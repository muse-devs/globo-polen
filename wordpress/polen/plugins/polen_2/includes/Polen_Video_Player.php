<?php

namespace Polen\Includes;

class Polen_Video_Player
{
    public function __construct( bool $static = false )
    {
        if( $static ) {
            add_action( 'init',             array( $this, 'rewrites' ) );
            add_filter( 'query_vars',       array( $this, 'query_vars' ) );
            add_action( 'template_include', array( $this, 'template_include' ) );
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
}
