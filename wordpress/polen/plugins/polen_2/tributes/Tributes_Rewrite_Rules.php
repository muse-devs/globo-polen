<?php

namespace Polen\Tributes;

use Polen\Includes\Debug;
use Polen\Includes\Polen_Messages;

class Tributes_Rewrite_Rules
{
    const BASE_PATH = 'tributes';

    const TRIBUTES_OPERATION_EMAIL_READED  = 'email_readed';
    const TRIBUTES_OPERATION_EMAIL_CLICKED = 'email_clicked';
    const TRIBUTES_OPERATION_SEND_VIDEO    = 'send_video';
    const TRIBUTES_OPERATION_INVITES       = 'invites';
    const TRIBUTES_OPERATION_CREATE        = 'create';
    const TRIBUTES_OPERATION_HOME          = 'home';
    const TRIBUTES_OPERATION_MY_TRIBUTES   = 'my_tributes';
    const TRIBUTES_OPERATION_DETAILS       = 'details';

    const TRIBUTES_OPERATIONS = array(
        self::TRIBUTES_OPERATION_EMAIL_READED,
        self::TRIBUTES_OPERATION_EMAIL_CLICKED,
        self::TRIBUTES_OPERATION_SEND_VIDEO,
        self::TRIBUTES_OPERATION_INVITES,
        self::TRIBUTES_OPERATION_CREATE,
        self::TRIBUTES_OPERATION_HOME,
        self::TRIBUTES_OPERATION_MY_TRIBUTES,
        self::TRIBUTES_OPERATION_DETAILS,
    );

    const TRIBUTES_QUERY_VAR_TRUBITES_APP                             = 'tributes_app';
    const TRIBUTES_QUERY_VAR_TRIBUTES_OPERAION                        = 'tribute_operation';
    const TRIBUTES_QUERY_VAR_TRIBUTES_HASH                            = 'tributes_hash';
    const TRIBUTES_QUERY_VAR_TRIBUTES_INVITE_HASH                     = 'tributes_invite_hash';
    const TRIBUTES_QUERY_VAR_TRIBUTES_SET_EMAIL_READED_HASH           = 'tributes_set_email_readed_hash';
    const TRIBUTES_QUERY_VAR_TRIBUTES_OPERAION_SET_EMAIL_CLICKED_HASH = 'tributes_set_email_clicked_hash';
    const TRIBUTES_QUERY_VAR_TRIBUTES_OPERAION_SEND_VIDEO             = 'tributes_send_video';
    const TRIBUTES_QUERY_VAR_TRIBUTES_MY_TRIBUTES                     = 'tributes_my_tributes';
    const TRIBUTES_QUERY_VAR_TRIBUTES_DETAILS                         = 'tributes_details';
    

    /**
     * 
     */
    public function __construct( bool $static = false )
    {
        if( $static ) {
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
        add_rewrite_rule( self::BASE_PATH . '/([^/]*)/?/detalhes',          'index.php?tributes_app=1&tribute_operation='.self::TRIBUTES_OPERATION_DETAILS.'&tributes_hash=$matches[1]', 'top' );
        add_rewrite_rule( self::BASE_PATH . '/([^/]*)/?/invite/([^/]*)/?',  'index.php?tributes_app=1&tribute_operation='.self::TRIBUTES_OPERATION_SEND_VIDEO.'&tributes_hash=$matches[1]&tributes_invite_hash=$matches[2]&tributes_send_video=1', 'top' );
        add_rewrite_rule( self::BASE_PATH . '/create/?',                    'index.php?tributes_app=1&tribute_operation='.self::TRIBUTES_OPERATION_CREATE, 'top' );
        add_rewrite_rule( self::BASE_PATH . '/meus-tributos/?',             'index.php?tributes_app=1&tribute_operation='.self::TRIBUTES_OPERATION_MY_TRIBUTES, 'top' );
        add_rewrite_rule( self::BASE_PATH . '/([^/]*)/?',                   'index.php?tributes_app=1&tribute_operation='.self::TRIBUTES_OPERATION_INVITES.'&tributes_hash=$matches[1]', 'top' );
        add_rewrite_rule( self::BASE_PATH . '[/]?$',                        'index.php?tributes_app=1&tribute_operation='.self::TRIBUTES_OPERATION_HOME, 'top' );
    }


    /**
     * 
     */
    public function query_vars( $query_vars )
    {
        $query_vars[] = self::TRIBUTES_QUERY_VAR_TRUBITES_APP;
        $query_vars[] = self::TRIBUTES_QUERY_VAR_TRIBUTES_HASH;
        $query_vars[] = self::TRIBUTES_QUERY_VAR_TRIBUTES_INVITE_HASH;
        $query_vars[] = self::TRIBUTES_QUERY_VAR_TRIBUTES_SET_EMAIL_READED_HASH;
        $query_vars[] = self::TRIBUTES_QUERY_VAR_TRIBUTES_OPERAION_SET_EMAIL_CLICKED_HASH;
        $query_vars[] = self::TRIBUTES_QUERY_VAR_TRIBUTES_OPERAION_SEND_VIDEO;
        $query_vars[] = self::TRIBUTES_QUERY_VAR_TRIBUTES_OPERAION;
        $query_vars[] = self::TRIBUTES_QUERY_VAR_TRIBUTES_MY_TRIBUTES;
        $query_vars[] = self::TRIBUTES_QUERY_VAR_TRIBUTES_DETAILS;
        return $query_vars;
    }


    /**
     * Template Include Filter
     */
    public function template_include( $template )
    {
        $tributes_app           = get_query_var( self::TRIBUTES_QUERY_VAR_TRUBITES_APP );
        $tribute_hash           = get_query_var( self::TRIBUTES_QUERY_VAR_TRIBUTES_HASH );
        $invites_hash           = get_query_var( self::TRIBUTES_QUERY_VAR_TRIBUTES_INVITE_HASH );
        $set_email_readed_hash  = get_query_var( self::TRIBUTES_QUERY_VAR_TRIBUTES_SET_EMAIL_READED_HASH );
        $set_email_clicked_hash = get_query_var( self::TRIBUTES_QUERY_VAR_TRIBUTES_OPERAION_SET_EMAIL_CLICKED_HASH );
        $tribute_send_video     = get_query_var( self::TRIBUTES_QUERY_VAR_TRIBUTES_OPERAION_SEND_VIDEO );
        $tribute_operation      = get_query_var( self::TRIBUTES_QUERY_VAR_TRIBUTES_OPERAION );

        if ( $tributes_app != '1' || !in_array( $tribute_operation, self::TRIBUTES_OPERATIONS ) ) {
            return $template;
        }

        $GLOBALS['tributes_app'] = true;
        
        if( $tribute_operation == self::TRIBUTES_OPERATION_HOME ) {
            return get_template_directory() . '/tributes/index.php';
        }

        if( $tribute_operation == self::TRIBUTES_OPERATION_CREATE ) {
            return get_template_directory() . '/tributes/create_tribute.php';
        }

        if( $tribute_operation == self::TRIBUTES_OPERATION_SEND_VIDEO ) {
            return get_template_directory() . '/tributes/send_video.php';
        }

        if( $tribute_operation == self::TRIBUTES_OPERATION_INVITES ) {
            
            $tribute_hash = get_query_var( self::TRIBUTES_QUERY_VAR_TRIBUTES_HASH );
            $tribute = Tributes_Model::get_by_hash($tribute_hash);
            
            if ( empty( $tribute ) ) {
                status_header( 404 );
                nocache_headers();
                return get_template_directory() . '/tributes/404';
            }

            $GLOBALS[ 'tribute' ] = $tribute;
            $GLOBALS[ 'tribute_hash' ] = $tribute_hash;
            return get_template_directory() . '/tributes/invites.php';
        }


        if( $tribute_operation == self::TRIBUTES_OPERATION_MY_TRIBUTES ) {

            $email_creator = filter_input( INPUT_POST, 'email', FILTER_VALIDATE_EMAIL );
            if( empty( $email_creator ) ) {
                // wc_add_notice( 'Email inválido', 'error' );
                Polen_Messages::set_message( 'Email inválido', Polen_Messages::TYPE_ERROR );
                wp_redirect( site_url( self::BASE_PATH ) );
                exit;
            }
            $tribute_list = Tributes_Model::get_all_by_email_creator( $email_creator );
            $GLOBALS['my_tributes'] = $tribute_list;
            return get_template_directory() . '/tributes/my_tributes.php';
        }

        if( $tribute_operation == self::TRIBUTES_OPERATION_DETAILS ) {
            $tribute = Tributes_Model::get_by_hash( $tribute_hash );
            if( empty( $tribute ) ) {
                return $this->set_404();
            }
            $GLOBALS[ 'tribute' ] = $tribute;
            return get_template_directory() . '/tributes/tributes_detail.php';
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
