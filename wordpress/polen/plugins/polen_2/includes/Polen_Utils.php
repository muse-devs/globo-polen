<?php
namespace Polen\Includes;

class Polen_Utils
{
    public static function sanitize_xss_br_escape( $txt )
    {
        $string_escaped = htmlspecialchars( $txt );
        return nl2br( $string_escaped );
    }
}