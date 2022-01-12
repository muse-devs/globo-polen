<?php
namespace Polen\Includes\Module;

use Polen\Admin\Polen_Admin_Event_Promotional_Event_Fields;
use Polen\Api\Api_Checkout;
use Polen\Includes\Cart\Polen_Cart_Item_Factory;
use WC_Order_Query;

class Polen_Order_Module
{
    const VIDEO_TO_TO_MYSELF = 'to_myself';
    const VIDEO_TO_OTHER_ONE = 'other_one';

    public $object;
    public $cart_item;

    public function __construct( $order )
    {
        if( empty( $order ) ) {
            return null;
        }

        $this->object = $order;

        $this->cart_item = Polen_Cart_Item_Factory::polen_cart_item_from_order( $this->object );
    }


    public function get_is_campaign()
    {
        $order = $this->object;
        $campaign_slug = $order->get_meta( Api_Checkout::ORDER_METAKEY, true );

        if( empty( $campaign_slug ) ) {
            return false;
        }
        return true;
    }

    public function get_campaign_slug()
    {
        $order = $this->object;
        $campaign_slug = $order->get_meta( Api_Checkout::ORDER_METAKEY, true );

        if( empty( $campaign_slug ) ) {
            return '';
        }
        return $campaign_slug;
    }



    public function get_video_to()
    {
        return $this->cart_item->get_video_to();
    }

    public function get_name_to_video()
    {
        if( self::VIDEO_TO_OTHER_ONE === $this->get_video_to() || $this->get_is_campaign() ) {
            return $this->cart_item->get_name_to_video();
        }
        return $this->cart_item->get_offered_by();
    }

    public function get_offered_by()
    {
        return $this->cart_item->get_offered_by();
    }





    /**
     * 
     * Retorna Orders_ids por alguma campanha e por um status especifico
     */
    public static function get_orders_ids_by_campaign_and_status( string $campaign_name, string $order_status )
    {
        $orders_query = new WC_Order_Query([
            'return' => 'ids',
            'limit' => 10,
            'paginate' => true,
            'status' => [ $order_status ],
            'meta_key' => Polen_Admin_Event_Promotional_Event_Fields::FIELD_NAME_SLUG_CAMPAIGN,
            'meta_value' => $campaign_name,
            'orderby' => 'rand'
        ]);
        
        $result = $orders_query->get_orders();
        return $result->orders;
    }


    /**
     * Retorna a Oriem do Pedido se foi Polen ou se for
     * 
     * @return string
     */
    public function get_origin_to_list_orders_talent()
    {
        if( $this->get_is_campaign() ) {
            return $this->get_campaign_slug();
        }

        return 'Polen';
    }


    /**
     * Cria uma linha na lista de Orders do Talento com a origem de onde veio o pedido,
     * se da Polen ou de algum WhiteLabel
     * 
     * @return HTML
     */
    public function get_html_origin_to_list_orders_talent()
    {
        return <<<HTML
            <div class="col-6 col-md-6">
                <p class="p">Origem do Pedido</p>
                <p class="value small">{$this->get_origin_to_list_orders_talent()}</p>
            </div>
        HTML;
    }
}
