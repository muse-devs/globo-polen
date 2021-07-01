<?php
namespace Polen\Tributes;

class Tributes_Model
{

    const TABLE_NAME = 'tributes';

    const ERROR_SLUG_UNIQUE = 'tribute_slug_uniq';
    const ERROR_HASH_UNIQUE = 'tribute_hash_unique';

    /**
     * 
     */
     public static function table_name()
     {
         global $wpdb;
         return $wpdb->base_prefix . self::TABLE_NAME;
     }


    /**
     *
     */
    public static function insert( $data )
    {
        global $wpdb;
        $table_name = self::table_name();
        $result_insert = $wpdb->insert(
            $table_name,
            $data
        );
        if( $result_insert === false ) {
            throw new \Exception( $wpdb->last_error, 401 );
        }
        return $wpdb->insert_id;
    }


    /**
     *
     */
     public static function get_by_id( $id )
     {
         global $wpdb;
         $table_name = self::table_name();
         $result = $wpdb->get_row(
             $wpdb->prepare( "SELECT * FROM `{$table_name}` WHERE `ID` = %s", $id )
         );
         return $result;
     }


    /**
     *
     */
    public static function get_by_hash( $hash )
    {
        global $wpdb;
        $table_name = self::table_name();
        $result = $wpdb->get_row(
            $wpdb->prepare( "SELECT * FROM `{$table_name}` WHERE `hash` = %s", $hash )
        );
        return $result;
    }


    /**
     *
     */
    public static function get_by_slug( $slug )
    {
        global $wpdb;
        $table_name = self::table_name();
        $result = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM `{$table_name}` WHERE `slug` = %s", $slug )
        );
        return $result;
    }


    /**
     * 
     */
    public static function create_hash()
    {
        return md5( rand( 0, 10000 ) . date( 'Y-m-d H:i:s' ) );
    }

}