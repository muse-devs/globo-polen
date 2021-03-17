<?php

namespace Polen\Admin;

/**
 * Description of Polen_Admin_DisableTalenAccess
 *
 * @author rodolfoneto
 */
class Polen_Admin_RedirectTalentAccess
{
    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'redirect_talent' ) );
    }
    
    public function redirect_talent()
    {
        $user = wp_get_current_user();
        
        $roles = $user->roles;
        if( array_search( 'user_talent', $roles ) !== false ) {
            $path = 'my-account';
            wp_redirect( site_url( $path ) );
        }
    }

}
