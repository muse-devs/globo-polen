<?php

namespace Polen\Tributes;

class Tributes_Rewrite_Rules
{
    public function __construct( bool $static = false )
    {
        if( $static ) {
            //ROUTES /lp/SLUG-ARTISTA
            add_action( 'init',             array( $this, 'rewrites' ) );
            add_filter( 'query_vars',       array( $this, 'query_vars' ) );
            add_action( 'template_include', array( $this, 'template_include' ) );
        }
    }

    /**
     * Rewrite Rules lp/sku-talent
     */
    public function rewrites()
    {
        add_rewrite_rule( 'tributes[/]?$', 'index.php?tributes_root=1', 'top' );
    }

    /**
     * 
     */
    public function query_vars( $query_vars )
    {
        $query_vars[] = 'tributes_root';
        return $query_vars;
    }


    /**
     * Template Include Filter
     */
    public function template_include( $template )
    {
        if ( get_query_var( 'tributes_root' ) != '1' ) {
            return $template;
        }
        $GLOBALS['tributes_root'] = true;
        return get_template_directory() . '/tributes/index.php';

    }
}