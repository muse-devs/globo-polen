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
}