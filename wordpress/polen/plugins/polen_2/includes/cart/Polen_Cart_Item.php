<?php

namespace Polen\Includes\Cart;

class Polen_Cart_Item
{
    
    /**
     * Contate com valor para o video que é pedido para outra pessoa
     */
    const VIDEO_FOR_OTHER_ONE = 'other_one';
    
    /**
     * Contato com valor que repesenta o video para a propria pessoa
     */
    const VIDEO_FOR_TO_MY_SELF= 'to_myself';
    
    /**
     * Item que está o carrinho padrao do WC
     * @var \WC_Order_Item_Product
     */
    public $item;
    
    public function __construct( \WC_Order_Item_Product $item )
    {
        $this->item = $item;
    }
    
    
    /**
     * Retorna o nome da pessoa que está oferencendo o video
     * @return string
     */
    public function get_offered_by()
    {
        return $this->item->get_meta( 'offered_by' );
    }
    
    
    /**
     * Retorna o nome da pessoa para quem o video foi feito
     * @return string
     */
    public function get_name_to_video()
    {
        return $this->item->get_meta( 'name_to_video' );
    }
    
    
    /**
     * Retorna o email de acompanhamento do pedido
     * @return string
     */
    public function get_email_to_video()
    {
        return $this->item->get_meta( 'email_to_video' );
    }
    
    
    /**
     * Retorna um dos 2 valores other_one | to_myself
     * other_one - para outra pessoa
     * to_myself - para a pessoa mesmo que pediu o video
     * @return string
     */
    public function get_video_to()
    {
        return $this->item->get_meta( 'video_to' );
    }
    
    
    /**
     * Nome da ocorrencia do video
     * @return string
     */
    public function get_video_category()
    {
        return $this->item->get_meta( 'video_category' );
    }
    
    
    /**
     * Retorna as instruções escritas pelo comprador
     * @return string
     */
    public function get_instructions_to_video()
    {
        return $this->item->get_meta( 'instructions_to_video' );
    }
}
