<?php
namespace Polen\Includes\Module;

use Polen\Admin\Polen_Admin_Event_Promotional_Event_Fields as Promotional_Event;
use Polen\Admin\Polen_Admin_Event_Promotional_Event_Fields;
use Polen\Admin\Polen_Admin_Order_Custom_Fields;
use Polen\Includes\Cart\Polen_Cart_Item;
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
        $is_campaign = $order->get_meta( Promotional_Event::FIELD_NAME_IS, true );

        if( 'yes' !== $is_campaign ) {
            return false;
        }
        return true;
    }

    public function get_campaign_slug()
    {
        $order = $this->object;
        $is_campaign = $order->get_meta( Promotional_Event::FIELD_NAME_IS, true );

        if( 'yes' !== $is_campaign ) {
            return '';
        }
        $campaign_slug = $order->get_meta( Promotional_Event::FIELD_NAME_SLUG_CAMPAIGN );
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
}
