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
        add_rewrite_rule( 'tributes/([^/]*)/?', 'index.php?tributes_root=1&tribute_hash=$matches[1]', 'top' );
    }

    /**
     * 
     */
    public function query_vars( $query_vars )
    {
        $query_vars[] = 'tributes_root';
        $query_vars[] = 'tribute_hash';
        return $query_vars;
    }


    /**
     * Template Include Filter
     */
    public function template_include( $template )
    {
        $tribute_hash = get_query_var( 'tribute_hash' );
        if ( get_query_var( 'tributes_root' ) != '1' ) {
            return $template;
        }

        //se for /tribute/tribute-hash
        if( strlen( $tribute_hash ) === 32 ) {
            
            $GLOBALS['tribute_hash']  = $tribute_hash;
            return get_template_directory() . '/tributes/invites.php';

        } else if ( strlen( $tribute_hash ) > 0 && strlen( $tribute_hash ) < 32 ) {
            return $this->set_404();
        }
        $GLOBALS['tributes_root'] = true;
        return get_template_directory() . '/tributes/index.php';

    }


    /**
     * Set 404 para Tributos não encontrados
     */
    public function set_404()
    {
        global $wp_query;
        $wp_query->set_404();
        status_header( 404 );
        return get_template_directory() . '/tributes/404.php';
    }
}