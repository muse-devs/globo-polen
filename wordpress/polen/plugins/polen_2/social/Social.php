<?php
namespace Polen\Social;

class Social
{
    public function __construct( $static = false )
    {
        if( $static ) {
            new Social_Rewrite( $static );
        }
    }


    static public function is_social_app()
    {
        $social_app = $GLOBALS[ Social_Rewrite::QUERY_VARS_SOCIAL_APP ];
        if( $social_app === '1' ) {
            return true;
        }
        return false;
    }
}