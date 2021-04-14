<?php

namespace Polen\Includes\Db;

class Polen_DB
{
    
    private $wpdb;
    public $table_name;
    public $valid = false;
    
    function __construct()
    {
        global $wpdb;
        
        $this->wpdb = $wpdb;
        $this->table_name = $this->table_name();
    }
    
    public function __get( $param ) {
        if( $param == 'wpdb') {
            return $this->wpdb;
        }
    }
    
    
    /*
     * Retuna o nome da Tabela
     * Esse metodo precisa ser sobrescrito
     * 
     */
    public function table_name()
    {
        throw new Exception('Esse metodo tem que ser sobreecrito', 500);
    }
    
    public function get_data_insert()
    {
        throw new Exception('Esse metodo tem que ser sobreecrito', 500);
    }
    
    public function get_data_update()
    {
        throw new Exception('Esse metodo tem que ser sobreecrito', 500);
    }
    
    public function insert()
    {
        $this->wpdb->insert(
                $this->table_name,
                $this->get_data_insert()
            );
        if( empty( $this->wpdb->last_error ) ) {
            return $this->wpdb->insert_id;
        } else {
            throw new \Exception( $this->wpdb->last_error, 500 );
        }
    }
    
    
    /**
     * Fazer Update de um registro no tabela
     * @param array $where ['ID' => 1]
     */
    public function update( array $where )
    {
        $this->wpdb->update(
                $this->table_name,
                $this->get_data_update(),
                $where
            );
        if( empty( $this->wpdb->last_error ) ) {
            return $this->wpdb->insert_id;
        } else {
            throw new \Exception( $this->wpdb->last_error, 500 );
        }
    }
    
    public function delete( array $where )
    {
        $this->wpdb->delete(
                $this->table_name,
                $where
            );
        if( empty( $this->wpdb->last_error ) ) {
            return $this->wpdb->insert_id;
        } else {
            throw new \Exception( $this->wpdb->last_error, 500 );
        }
    }
    
    
    /**
     * Retona uma linha do banco de dados, buscando pelo ID
     * @param int $id
     * @return stdClass
     */
    public function get_by_id( int $id )
    {
        return self::create_instance_one( $this->get( 'ID', $id, '%d' ) );
    }
    
    /**
     * 
     * @param type $field
     * @param type $value
     * @param type $format
     * @return type
     */
    public function get( $field, $value, $format = "%s" )
    {
        return self::create_instance_one( $this->wpdb->get_row(
            $this->wpdb->prepare( "SELECT * FROM {$this->table_name} WHERE {$field} = {$format};", $value )
        ) );
    }
    
    
    public function get_results( $field, $value, $format = '%s' )
    {
        return self::create_instance_many( $this->wpdb->get_results(
                $this->wpdb->prepare(
                        "SELECT * FROM {$this->table_name} WHERE {$field} = {$format};", $value
                    )
            ) );
    }
    
    static public function create_instance_one( $data )
    {
        return $data;
    }
    
    static public function create_instance_many( $data )
    {
        return $data;
    }
    
    
}
