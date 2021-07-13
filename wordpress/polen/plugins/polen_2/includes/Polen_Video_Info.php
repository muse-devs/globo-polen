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
    public $hash;
    public $vimeo_thumbnail;
    public $vimeo_process_complete;
    public $vimeo_url_download;
    public $vimeo_link;
    public $duration;
    public $first_order;
    public $created_at;
    public $updated_at;
    
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
                $this->hash = $object->hash;
                $this->is_public = intval( $object->is_public );
                $this->vimeo_thumbnail = $object->vimeo_thumbnail;
                $this->vimeo_process_complete = $object->vimeo_process_complete;
                $this->vimeo_url_download = $object->vimeo_url_download;
                $this->vimeo_link = $object->vimeo_link;
                $this->duration = $object->duration;
                $this->first_order = $object->first_order;
                $this->created_at = $object->created_at;
                $this->updated_at = $object->updated_at;
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
        $return = $this->create_array_fields_db();
        return $return;
    }
    
    
    /**
     * Gera os dados para update
     * @return array
     */
    public function get_data_update()
    {
        $return = $this->create_array_fields_db();
        return $return;
    }
    
    
    public function create_array_fields_db()
    {
        $return = [];
        
        $return[ 'order_id' ]               = $this->order_id;
        $return[ 'talent_id' ]              = $this->talent_id;
        $return[ 'is_public' ]              = $this->is_public;
        $return[ 'vimeo_id' ]               = $this->vimeo_id;
        $return[ 'hash' ]                   = $this->hash;
        $return[ 'vimeo_thumbnail' ]        = $this->vimeo_thumbnail;
        $return[ 'vimeo_process_complete' ] = $this->vimeo_process_complete;
        $return[ 'vimeo_url_download' ]     = $this->vimeo_url_download;
        $return[ 'vimeo_link' ]             = $this->vimeo_link;
        $return[ 'first_order' ]            = $this->first_order;
        $return[ 'duration' ]               = $this->duration;
        
        if ( !empty( $this->created_at ) ) {
            $return[ 'created_at' ] = $this->created_at;
        }
        
        if ( !empty( $this->updated_at ) ) {
            $return[ 'updated_at' ] = $this->updated_at;
        }

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
     * Funcao com validacoes e acoes antes do insert
     */
    public function pre_insert()
    {
        $this->hash = $this->get_vimeo_id_only_id();
        $this->created_at = date('Y-m-d H-i-s');
    }
    
    
    /**
     * Faz update com os dados que estao na instancia
     * @param array $where
     * @return type
     */
    public function update( array $_ = null )
    {
        $where = array( 'ID' => $this->ID );
        return parent::update( $where );
    }
    
    /**
     * 
     */
    public function pre_update() {
        parent::pre_update();
        $this->updated_at = date('Y-m-d H:i:s');
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
    
    
    static public function select_by_talent_id( int $talent_id, $limit = 4 )
    {
        $self_obj = new static();
        $fields = array( 'talent_id' => $talent_id, 'vimeo_process_complete' => "1", 'is_public' => '1' );
        $result_raw = $self_obj->get_result_multi_fields( $fields, "4", "ORDER BY first_order DESC, ID DESC" );
        return $result_raw;//self::create_instance_many( $result_raw );
    }
    
    /**
     * Retorna os videos nao processados pelo Vimeo
     * @return array [Polen_Video_Info]
     */
    static public function select_all_videos_incompleted()
    {
        $pvi = new self();
        $results = $pvi->get_results( 'vimeo_process_complete', '0', '%d' );
        return self::create_instance_many( $results );
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
    
    
    /**
     * Pega um item pela order_id
     * @param int $order_id
     * @return Polen_Video_Info
     */
    static public function get_by_order_id( int $order_id )
    {
        $pvi = new self();
        $result = $pvi->get( 'order_id', $order_id, '%d' );
        if( empty( $result ) ) {
            return null;
        }
        return self::create_instance_one( $result );
    }
    
    /**
     * Pegar uma linha pelo Hash
     * 
     * @param string $hash
     * @return Polen_Video_Info
     */
    static public function get_by_hash( string $hash )
    {
        $pvi = new self();
        $result = $pvi->get( 'hash', $hash, '%s' );
        if( empty( $result ) ) {
            return null;
        }
        return self::create_instance_one( $result );
    }
    
    
    /**
     * Metodo para criacao do uma HASH unica
     * @param string $param
     * @return string
     */
    public function get_vimeo_id_only_id()
    {
        if( empty( $this->vimeo_id ) ) {
            throw new \Exception( "Can\'t execute this method whithout a vimeo_id", 500 );
        }
        return str_replace( '/videos/', '', $this->vimeo_id );
    }
    
    
    /**
     * Cria um objeto apartir de um array, geralmente vindo do BD
     * ou seja transforma um resultado de DB para um Objecto
     * 
     * @param stdClass $data
     * @return Polen_Video_Info
     */
    static public function create_instance_one( $data, $valid = true )
    {
        $object = new self();
        $object->ID = $data->ID;
        $object->is_public = $data->is_public;
        $object->order_id = $data->order_id;
        $object->talent_id = $data->talent_id;
        $object->vimeo_id = $data->vimeo_id;
        $object->hash = $data->hash;
        $object->vimeo_process_complete = $data->vimeo_process_complete;
        $object->vimeo_thumbnail = $data->vimeo_thumbnail;
        $object->vimeo_url_download = $data->vimeo_url_download;
        $object->vimeo_link = $data->vimeo_link;
        $object->duration = $data->duration;
        $object->created_at = $data->created_at;
        $object->first_order = $data->first_order;
        $object->updated_at = $data->updated_at;
        $object->valid = $valid;
        return $object;
    }


    /**
     * Verifica se o processamento do vimeo está completo.
     * @return bool
     */
    public function is_vimeo_process_complete()
    {
        if( $this->vimeo_process_complete == "1" ) {
            return true;
        }
        return false;
    }
}
