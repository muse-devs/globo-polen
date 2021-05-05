<?php

namespace Polen\Includes;

class Polen_Order_Review
{
    const COMMENT_TYPE = 'order_review';
    
    private $comment_id;
    private $user_id;
    private $comment_agent;
    private $comment_content;
    private $comment_date;
    private $comment_karma;
    private $comment_type;
    private $comment_meta;
    private $comment_approved;
    
    /**
     * É o order_id
     * @var int
     */
    private $comment_post_ID;
    
    /**
     * Vai ser salvo no metadata
     * @var int
     */
    private $talent_id;
    
    public function set_comment_id( $comment_id ): void
    {
        $this->comment_id = $comment_id;
    }

    public function set_user_id( $user_id ): void
    {
        $this->user_id = $user_id;
    }

    public function set_comment_agent( $comment_agent ): void
    {
        $this->comment_agent = $comment_agent;
    }
    
    public function set_comment_content( $comment_content ): void
    {
        $this->comment_content = $comment_content;
    }

    public function set_comment_date( $comment_date ): void
    {
        $this->comment_date = $comment_date;
    }

    public function set_comment_karma( $comment_karma ): void
    {
        $this->comment_karma = $comment_karma;
    }

    public function set_comment_post_ID( $comment_post_ID ): void
    {
        $this->comment_post_ID = $comment_post_ID;
    }
    
    public function set_order_id( $comment_post_ID ): void
    {
        $this->set_comment_post_ID( $comment_post_ID );
    }

    public function set_comment_type( $comment_type ): void
    {
        $this->comment_type = $comment_type;
    }

    public function set_comment_meta( $comment_meta ): void
    {
        $this->comment_meta = $comment_meta;
    }

    public function set_comment_approved( $comment_approved ): void
    {
        $this->comment_approved = $comment_approved;
    }

    
    /**
     * 
     * @param type $talent_id
     * @return void
     */
    public function set_talent_id( $talent_id ): void
    {
        $this->talent_id = $talent_id;
    }
    
    
    /**
     * Prepara o talent_id para entrar no Meta que é um array
     * @return type
     */
    private function prepare_metadata_insert_db()
    {
        return array(
            'talent_id' => $this->talent_id
        );
    }
    
    
    /**
     * Validacao antes do insert
     */
    protected function validate_comment()
    {
        $this->unique_comment();
        $this->validate_order_is_complete();
    }
    
    
    /**
     * Validação para ver se já existe o comentário baseado no user_id e 
     * comment_post_ID que é o order_id
     * 
     * @throws \Exception
     */
    protected function unique_comment()
    {
        if( !empty( self::get_comment_by_user_id_order_id( $this->user_id, $this->comment_post_ID ) ) ) {
            throw new \Exception( 'this comment already exist', 500 );
        }
    }
    
    
    /**
     * Valida se a order está complta para poder criar um review
     * @throws \Exception
     */
    protected function validate_order_is_complete()
    {
        $order = wc_get_order( $this->comment_post_ID );
        if( $order->get_status() !== Polen_Order::SLUG_ORDER_COMPLETE ) {
            throw new \Exception( 'the order isnt completed', 500 );
        }
    }
    
    
    /**
     * Pegar um comentário pelo user_id e order_id
     * necessário para saber se o comentátio é unico
     * 
     * @param int $user_id
     * @param int $order_id
     * @return array [WP_Comment]
     */
    static public function get_comment_by_user_id_order_id( $user_id, $order_id )
    {
        return get_comments( array(
            'user_id' => $user_id,
            'post_id' => $order_id,
            'type'    => self::COMMENT_TYPE,
        ) );
    }
    
    public function save()
    {
        $commentdata = $this->prepare_data_db();
        
        $this->validate_comment();
        
        $comment_id = wp_insert_comment( $commentdata );
        if( $comment_id === false ) {
            return false;
        }
        
        $this->comment_id = $comment_id;
        return $this;
    }
    
    /**
     * Prepara a estrutura em array para o insert
     * @return array
     */
    public function prepare_data_db()
    {
        return array(
            'comment_agent'         => $this->talent_id,
            'comment_approved'      => $this->comment_approved,
            'comment_author'        => '',
            'comment_author_email'  => '',
            'comment_author_IP'     => '',
            'comment_author_url'    => '',
            'comment_content'       => $this->comment_content,
            'comment_karma'         => $this->comment_karma,
            'comment_parent'        => '',
            'comment_post_ID'       => $this->comment_post_ID,
            'comment_type'          => self::COMMENT_TYPE,
            'comment_meta'          => $this->prepare_metadata_insert_db(),
            'user_id'               => $this->user_id
        );
    }


}
