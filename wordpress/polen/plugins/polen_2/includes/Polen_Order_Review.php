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
     * Rate que sera um metadata
     */
    private $rate;
    
    /**
     * Para nao ficar indo buscar no banco para várias validacoes
     * @var type 
     */
    private $_order;
    
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

    public function set_talent_id( $talent_id ): void
    {
        $this->talent_id = $talent_id;
    }
    
    public function set_rate( $rate )
    {
        $this->rate = $rate;
    }
    
    
    /**
     * Prepara o talent_id para entrar no Meta que é um array
     * @return type
     */
    private function prepare_metadata_insert_db()
    {
        return array(
            'talent_id' => $this->talent_id,
            'rate'      => $this->rate,
        );
    }
    
    
    /**
     * Validacao antes do insert
     */
    protected function validate_comment()
    {
        $this->validate_unique_comment();
        $this->validate_order_is_complete();
        $this->validate_same_user_by_order();
    }
    
    
    /**
     * Validação para ver se já existe o comentário baseado no user_id e 
     * comment_post_ID que é o order_id
     * 
     * @throws \Exception
     */
    protected function validate_unique_comment()
    {
        if( !empty( self::get_comment_by_user_id_order_id( $this->user_id, $this->comment_post_ID ) ) ) {
            throw new \Exception( 'Esse comentário já existe', 200 );
        }
    }
    
    
    /**
     * Validacao se o usuário e o mesmo da order
     * @param type $param
     */
    protected function validate_same_user_by_order()
    {
        $order = $this->_order;
        if( $this->user_id !== $order->get_user_id() ) {
            throw new \Exception( 'O usuário do pedido não é o mesmo', 200 );
        }
    }
    
    
    /**
     * Valida se a order está complta para poder criar um review
     * @throws \Exception
     */
    protected function validate_order_is_complete()
    {
        $order = $this->_order;
        if( $order->get_status() !== Polen_Order::SLUG_ORDER_COMPLETE ) {
            throw new \Exception( 'O pedido não está completo, não pode criar review antes do talento enviar o video', 200 );
        }
    }
    
    
    /**
     * Pegar um comentário pelo user_id e order_id
     * necessário para saber se o comentátio é unico
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
    
    
    static public function get_number_total_reviews_by_talent_id( int $talent_id )
    {
        return get_comments( array(
            'meta_key' => 'talent_id',
            'meta_value' => $talent_id,
            'count' => true
        ));
    }
    
    /**
     * Devolve o somatorio das notas por talent_id
     * @param int $talent_id
     * @return int
     */
    static public function get_sum_rate_by_talent( int $talent_id )
    {
        $comments = get_comments( array(
            'meta_key' => 'talent_id',
            'meta_value' => $talent_id,
            "include_unapproved" => '1',
        ));
        $total_rate = 0;
        foreach( $comments as $comment ) {
            $value = get_comment_meta( $comment->comment_ID, 'rate', true);
            $total_rate += intval( $value );
        }
        return $total_rate;
    }
    
    
    /**
     * 
     * @return boolean|$this
     */
    public function save()
    {
        $commentdata = $this->prepare_data_db();
        
        //data for validation
        $this->_order = wc_get_order( $this->comment_post_ID );
        $this->validate_comment();
        
        $comment_id = wp_insert_comment( $commentdata );
        if( $comment_id === false ) {
            global $wpdb;
            $msg = empty( $wpdb->last_error ) ? 'error into insert comment' : $wpdb->last_error;
            throw new \Exception( $msg , 500 );
        }
        
        $number_total_reviews = self::get_number_total_reviews_by_talent_id( $this->talent_id );
        $sum_rate_talent = self::get_sum_rate_by_talent( $this->talent_id );
        
        $cart_item = Cart\Polen_Cart_Item_Factory::polen_cart_item_from_order( $this->_order );
        $product = $cart_item->get_product();
        $product->update_meta_data( 'total_review', $number_total_reviews );
        $product->update_meta_data( 'sum_rate', $sum_rate_talent );
        $product->save();
        
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


    /**
     * Pega os reviews pelo talent_id
     * @param int $talent_id
     */
    static public function get_order_reviews_by_talent_id( int $talent_id )
    {
        $query = array(
            'meta_key' => 'talent_id',
            'meta_value' => $talent_id,
            'type' => 'order_review',
            'status' => 'approve',
        );
        $reviews = get_comments( $query );
        return $reviews;
    }


}
