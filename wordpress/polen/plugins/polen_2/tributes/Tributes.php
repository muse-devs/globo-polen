<?php

namespace Polen\Tributes;

class Tributes
{
    public function __construct( bool $static = false )
    {
        new Tributes_Rewrite_Rules( $static );
        new Tributes_API_Router( $static );
    }
}