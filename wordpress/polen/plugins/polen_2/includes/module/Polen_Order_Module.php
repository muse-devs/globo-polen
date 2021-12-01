<?php
namespace Polen\Includes\Module;

use Polen\Admin\Polen_Admin_Event_Promotional_Event_Fields as Promotional_Event;

use Polen\Admin\Polen_Admin_Order_Custom_Fields;

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
}