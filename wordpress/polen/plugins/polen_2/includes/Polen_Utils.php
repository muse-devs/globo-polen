<?php
namespace Polen\Includes;

class Polen_Utils
{
    /**
     * Sanitize as intradas de input contra XSS
     * @param string
     * @return string
     */
    public static function sanitize_xss_br_escape( $txt )
    {
        $string_escaped = htmlspecialchars( $txt );
        return nl2br( $string_escaped );
    }


    /**
     * Retona um pattern para o WPDB::Prepare por um ARRAY
     * Ex: INPUT  [100, 101, 102]
     *     OUTPUT %s, %s, %s
     * 
     * @param array
     * @return string
     */
    static public function pattern_array( array $var = [] )
    {
        return implode( ', ', array_fill( 0, count( $var ), '%s' ) );
    }


    /**
     * Escapa um SQL com entradas em ARRAY
     * Devolvendo o SQL Preparado
     * 
     * @param string
     * @param array
     * @return string
     */
    public static function esc_arr( $sql, $args )
    {
        global $wpdb;
        $query = call_user_func_array( array( $wpdb, 'prepare' ), array_merge( array( $sql ), $args ) );
        return $query;
    }
}
