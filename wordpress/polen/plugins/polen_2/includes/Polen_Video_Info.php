<?php

/**
 * Class para CRUD dos Videos criados pelas Orders no Vimeo
 */
namespace Polen\Includes;

use Polen\Includes\Db\Polen_DB;

class Polen_Video_Info extends Polen_DB
{
    
    public $ID;
    public $order_id;
    public $talent_id;
    public $is_public;
    public $vimeo_id;
    public $vimeo_thumbnail;
    public $vimeo_process_complete;
    public $vimeo_url_download;
    public $vimeo_link;
    
    function __construct( int $id = null )
    {
        parent::__construct();
        if( !empty( $id ) ) {
            $object = $this->get_by_id( $id );
            if( !empty( $object ) ) {
                $this->ID = intval( $object->ID );
                $this->order_id = intval( $object->order_id );
                $this->talent_id = intval( $object->talent_id );
                $this->vimeo_id = $object->vimeo_id;
                $this->is_public = intval( $object->is_public );
                $this->vimeo_thumbnail = $object->vimeo_thumbnail;
                $this->vimeo_process_complete = $object->vimeo_process_complete;
                $this->vimeo_url_download = $object->vimeo_url_download;
                $this->vimeo_link = $object->vimeo_link;
                $this->valid = true;
            }
        }
    }
    
    
    /**
     * Retorna do nome da Table para os SQLs
     * @return string
     */
    public function table_name()
    {
        return $this->wpdb->prefix . 'video_info';
    }
    
    
    /**
     * Gera os dados para insert
     * @return array
     */
    public function get_data_insert()
    {
        $return = array(
            'order_id' => $this->order_id,
            'talent_id' => $this->talent_id,
            'is_public' => $this->is_public,
            'vimeo_id' => $this->vimeo_id,
            'vimeo_thumbnail' => $this->vimeo_thumbnail,
            'vimeo_process_complete' => $this->vimeo_process_complete,
            'vimeo_url_download' => $this->vimeo_url_download,
            'vimeo_link' => $this->vimeo_link
        );
        return $return;
    }
    
    
    /**
     * Gera os dados para update
     * @return array
     */
    public function get_data_update()
    {
        $return = array(
            'order_id' => $this->order_id,
            'talent_id' => $this->talent_id,
            'is_public' => $this->is_public,
            'vimeo_id' => $this->vimeo_id,
            'vimeo_thumbnail' => $this->vimeo_thumbnail,
            'vimeo_process_complete' => $this->vimeo_process_complete,
            'vimeo_url_download' => $this->vimeo_url_download,
            'vimeo_link' => $this->vimeo_link,
        );
        return $return;
    }
    
    
    /**
     * Insere os dados que estão na instancia
     * @return int
     */
    public function insert()
    {
        $this->ID = parent::insert();
        $this->valid = true;
        return $this->ID;
    }
    
    
    /**
     * Faz update com os dados que estao na instancia
     * @param array $where
     * @return type
     */
    public function update( array $where = null )
    {
        $where = array( 'ID' => $this->ID );
        return parent::update( $where );
    }
    
    
    /**
     * Deleta um item Passado pelo WHERE ou deleta a instancia do banco
     * @param array $where [ ID => 10 ]
     * @return int || false
     */
    public function delete( array $where = null ) {
        if( $this->valid && empty( $where ) ) {
            $where = array( 'ID' => $this->ID );
        } else if ( !$this->valid && empty( $where ) ) {
            return false;
        }
        return parent::delete( $where );
    }
    
    
    static public function select_by_talent_id( int $talent_id )
    {
        $self_obj = new self();
        return self::create_instance_many( $self_obj->get_results( 'talent_id', $talent_id ) );
    }
    
    /**
     * Cria um objeto apartir de um array, geralmente vindo do BD
     * ou seja transforma um resultado de DB para um Objecto
     * 
     * @param array $data
     * @return Polen_Video_Info
     */
    static public function create_instance_one( $data )
    {
        $object = new self();
        $object->is_public = $data->is_public;
        $object->ID = $data->ID;
        $object->order_id = $data->order_id;
        $object->talent_id = $data->talent_id;
        $object->vimeo_id = $data->vimeo_id;
        $object->vimeo_process_complete = $data->vimeo_process_complete;
        $object->vimeo_thumbnail = $data->vimeo_thumbnail;
        $object->vimeo_url_download = $data->vimeo_url_download;
        $object->vimeo_link = $data->vimeo_link;
        return $object;
    }
    
    /**
     * Cria um array de objectos do Polen_Video_Info
     * 
     * @param array $data
     * @return array
     */
    static public function create_instance_many( $data )
    {
        $many_objects = array();
        foreach ( $data as $item ) {
            $many_objects[] = self::create_instance_one( $item );
        }
        return $many_objects;
    }
}
