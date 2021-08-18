<?php

class Promotional_Event_Rewrite
{

    const BASE_URL = 'produtos';

    const QUERY_VARS_EVENT_PROMOTIONAL_APP     = 'event_promotinal_app';
    const QUERY_VARS_EVENT_PROMOTIONAL_IS_HOME = 'event_promotinal_is_home';
    const QUERY_VARS_EVENT_PROMOTIONAL_VALIDATION = 'event_promotinal_validation';

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        add_action( 'init',             array( $this, 'rewrites' ) );
        add_filter( 'query_vars',       array( $this, 'query_vars' ), 10, 1 );
        add_action( 'template_include', array( $this, 'template_include' ) );
    }



    /**
     * 
     */
    public function rewrites()
    {
        add_rewrite_rule( self::BASE_URL . '/de-porta-em-porta/validar-codigo', 'index.php?'.self::QUERY_VARS_EVENT_PROMOTIONAL_APP.'=1&'.self::QUERY_VARS_EVENT_PROMOTIONAL_VALIDATION.'=1', 'top' );
        add_rewrite_rule( self::BASE_URL . '/de-porta-em-porta', 'index.php?'.self::QUERY_VARS_EVENT_PROMOTIONAL_APP.'=1&'.self::QUERY_VARS_EVENT_PROMOTIONAL_IS_HOME.'=1', 'top' );
    }


    /**
     * 
     */
    public function query_vars( $query_vars )
    {
        $query_vars[] = self::QUERY_VARS_EVENT_PROMOTIONAL_APP;
        $query_vars[] = self::QUERY_VARS_EVENT_PROMOTIONAL_IS_HOME;
        $query_vars[] = self::QUERY_VARS_EVENT_PROMOTIONAL_VALIDATION;
        return $query_vars;
    }


    /**
     * 
     */
    public function template_include( $template )
    {
        $app = get_query_var( self::QUERY_VARS_EVENT_PROMOTIONAL_APP );
        if( empty( $app ) || $app !== '1' ) {
            return $template;
        }

        $GLOBALS[ self::QUERY_VARS_EVENT_PROMOTIONAL_APP ]     = '1';
        
        if( $this->is_home() ) {
            $GLOBALS[ self::QUERY_VARS_EVENT_PROMOTIONAL_IS_HOME ] = '1';
            return get_template_directory() . '/event_promotional/index.php';
        }

        if( $this->is_page_validation() ) {
            $GLOBALS[ self::QUERY_VARS_EVENT_PROMOTIONAL_VALIDATION ] = '1';
            return get_template_directory() . '/event_promotional/validation.php';
        }
    }



    /**
     * 
     */
    private function is_home()
    {
        $is_home = get_query_var( self::QUERY_VARS_EVENT_PROMOTIONAL_IS_HOME );
        if( !empty( $is_home ) || $is_home == '1' ) {
            return true;
        }
        return false;
    }


    /**
     * 
     */
    private function is_page_validation()
    {
        $page = get_query_var( self::QUERY_VARS_EVENT_PROMOTIONAL_VALIDATION );
        if( !empty( $page ) || $page == '1' ) {
            return true;
        }
        return false;
    }

}
