<?php
namespace Polen\Includes\Module;

use Polen\Admin\Polen_Admin_Event_Promotional_Event_Fields as Promotional_Event;
use Polen\Admin\Polen_Admin_Event_Promotional_Event_Fields;
use Polen\Admin\Polen_Admin_Order_Custom_Fields;
use WC_Order_Query;

class Polen_Order_Module
{
    public $object;

    public function __construct( $order )
    {
        if( empty( $order ) ) {
            return null;
        }

        $this->object = $order;
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


    /**
     * 
     * Retorna Orders_ids por alguma campanha e por um status especifico
     */
    public static function get_orders_ids_by_campaign_and_status( string $campaign_name, string $order_status )
    {
        $orders_query = new WC_Order_Query([
            'return' => 'ids',
            'limit' => 3,
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

// $order->add_meta_data( Polen_Admin_Event_Promotional_Event_Fields::FIELD_NAME_IS, 'yes', true);
// $order->add_meta_data( Polen_Admin_Event_Promotional_Event_Fields::FIELD_NAME_SLUG_CAMPAIGN, $polen_product->get_campaign_slug(), true);