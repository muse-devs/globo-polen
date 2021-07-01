<?php

namespace Polen\Tributes;

class Tributes_Rewrite_Rules
{
    const BASE_PATH = 'tributes';

    const TRIBUTES_OPERATION_EMAIL_READED  = 'email_readed';
    const TRIBUTES_OPERATION_EMAIL_CLICKED = 'email_clicked';
    const TRIBUTES_OPERATION_SEND_VIDEO    = 'send_video';
    const TRIBUTES_OPERATION_INVITES       = 'invites';
    const TRIBUTES_OPERATION_CREATE        = 'create';

    const TRIBUTES_OPERATIONS = array(
        self::TRIBUTES_OPERATION_EMAIL_READED,
        self::TRIBUTES_OPERATION_EMAIL_CLICKED,
        self::TRIBUTES_OPERATION_SEND_VIDEO,
        self::TRIBUTES_OPERATION_INVITES,
        self::TRIBUTES_OPERATION_CREATE,
    );
    
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
        add_rewrite_rule( self::BASE_PATH . '/([^/]*)/?/set-email-readed',  'index.php?tributes_app=1&tribute_operation='.self::TRIBUTES_OPERATION_EMAIL_READED.'&tributes_set_email_readed_hash=$matches[1]', 'top' );
        add_rewrite_rule( self::BASE_PATH . '/([^/]*)/?/set-email-clicked', 'index.php?tributes_app=1&tribute_operation='.self::TRIBUTES_OPERATION_EMAIL_CLICKED.'&tributes_set_email_clicked_hash=$matches[1]', 'top' );
        add_rewrite_rule( self::BASE_PATH . '/([^/]*)/?/invite/([^/]*)/?',  'index.php?tributes_app=1&tribute_operation='.self::TRIBUTES_OPERATION_SEND_VIDEO.'&tributes_hash=$matches[1]&tributes_invite_hash=$matches[2]&tributes_send_video=1', 'top' );
        add_rewrite_rule( self::BASE_PATH . '/([^/]*)/?',                   'index.php?tributes_app=1&tribute_operation='.self::TRIBUTES_OPERATION_INVITES.'&tributes_hash=$matches[1]', 'top' );
        add_rewrite_rule( self::BASE_PATH . '[/]?$',                        'index.php?tributes_app=1&tribute_operation='.self::TRIBUTES_OPERATION_CREATE, 'top' );
    }


    /**
     * 
     */
    public function query_vars( $query_vars )
    {
        $query_vars[] = 'tributes_app';
        $query_vars[] = 'tributes_hash';
        $query_vars[] = 'tributes_invite_hash';
        $query_vars[] = 'tributes_set_email_readed_hash';
        $query_vars[] = 'tributes_set_email_clicked_hash';
        $query_vars[] = 'tributes_send_video';
        $query_vars[] = 'tribute_operation';
        return $query_vars;
    }


    /**
     * Template Include Filter
     */
    public function template_include( $template )
    {
        $tributes_app           = get_query_var( 'tributes_app' );
        $tribute_operation      = get_query_var( 'tribute_operation' );
        $tribute_hash           = get_query_var( 'tributes_hash' );
        $invites_hash           = get_query_var( 'tributes_invite_hash' );
        $set_email_readed_hash  = get_query_var( 'tributes_set_email_readed_hash' );
        $set_email_clicked_hash = get_query_var( 'tributes_set_email_clicked_hash' );
        $tribute_send_video     = get_query_var( 'tributes_send_video' );

        // echo'<pre>';var_dump([
        //     'tributes_app' => $tributes_app,
        //     'tribute_hash' => $tribute_hash,
        //     'invites_hash' => $invites_hash,
        //     'set_email_readed_hash' => $set_email_readed_hash,
        //     'set_email_clicked_hash' => $set_email_clicked_hash,
        //     'tribute_send_video' => $tribute_send_video,
        //     'tribute_operation' => $tribute_operation,
        // ]);die;
        
        if ( $tributes_app != '1' || !in_array( $tribute_operation, self::TRIBUTES_OPERATIONS ) ) {
            return $template;
        }

        $GLOBALS['tributes_app'] = true;
        
        if( $tribute_operation == self::TRIBUTES_OPERATION_CREATE ) {
            return get_template_directory() . '/tributes/index.php';
        }

        if( $tribute_operation == self::TRIBUTES_OPERATION_SEND_VIDEO ) {
            return get_template_directory() . '/tributes/send_video.php';
        }

        if( $tribute_operation == self::TRIBUTES_OPERATION_INVITES ) {
            return get_template_directory() . '/tributes/invites.php';
        }

        if( $tribute_operation == self::TRIBUTES_OPERATION_EMAIL_READED ) {
            $this->set_email_readed( $set_email_readed_hash );
        }

        if( $tribute_operation == self::TRIBUTES_OPERATION_EMAIL_CLICKED ) {
            return $this->set_email_clicked( $set_email_clicked_hash );
        }
    }


    /**
     * Apresenta um png transparente para o setar um email como lido
     * quando aberto no leitor de email
     */
    public function set_email_readed( $invite_hash )
    {
        $invite = Tributes_Invites_Model::get_by_hash( $invite_hash );
        if( !empty( $invite ) || $invite->email_opened != '1' ) {
            Tributes_Invites_Model::set_invite_email_opened( $invite->ID );
        }

        header('Content-Type: image/png');
        echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=');
        exit;
    }


    /**
     * Apresenta um png transparente para o email
     */
    public function set_email_clicked( $invite_hash )
    {
        $invite = Tributes_Invites_Model::get_by_hash( $invite_hash );

        if( empty( $invite ) ) {
            return $this->set_404();
        }
        if( $invite->email_clicked != '1' ) {
            Tributes_Invites_Model::set_invite_email_clicked( $invite->ID );
        }
        $tribute = Tributes_Model::get_by_id( $invite->tribute_id );
        $retalive_url = "tributes/{$tribute->hash}/invite/{$invite->hash}";
        return wp_safe_redirect( site_url( $retalive_url ) );
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