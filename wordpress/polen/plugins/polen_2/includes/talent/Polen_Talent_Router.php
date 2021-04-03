<?php

namespace Polen\Includes\Talent;

class Polen_Talent_Router
{

    private $controller;
    const PREFIX_AJAX_ACTION = 'wp_ajax_';
    const PREFIX_AJAX_ACTION_NO_AUTH = 'wp_ajax_nopriv_';

    public function __construct( Polen_Talent_Controller_Base $controller )
    {
        $this->controller = $controller;
    }

    public function init_routes()
    {
     //   $this->add_route( 'login', 'login', false );
//        add_action( 'wp_ajax_nopriv_login', array( $this->controller, 'login' ) );
//        add_action( '', array( $this->controller ) );
        $this->add_route( 'talent_acceptance', 'talent_acceptance', false );
        $this->add_route( 'talent_acceptance', 'talent_acceptance', true );
//        $this->add_route( 'create_video_slot_vimeo', 'make_video_slot_vimeo', false );
        $this->add_route( 'create_video_slot_vimeo', 'make_video_slot_vimeo', true );
    }
    
    public function add_route( string $action, string $handler, $authenticade = true )
    {
        $prefix = self::PREFIX_AJAX_ACTION_NO_AUTH;
        if( true === $authenticade ) {
            $prefix = self::PREFIX_AJAX_ACTION;
        }
        add_action( $prefix . $action, array( $this->controller, $handler ) );
    }
}