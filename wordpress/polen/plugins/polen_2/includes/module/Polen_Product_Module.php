<?php
namespace Polen\Includes\Module;

use Polen\Admin\Polen_Admin_Event_Promotional_Event_Fields as Event_Promotional;

class Polen_Product_Module
{
    public $object;

    /**
     * 
     * @param WC_Product
     */
    public function __construct( $product )
    {
        if( empty( $product ) ) {
            return null;
        }
        $this->object = $product;
    }

    public function get_is_campaing()
    {
        $product = $this->object;
        $is_campaing = $product->get_meta( Event_Promotional::FIELD_NAME_IS, true );
        if( 'yes' !== $is_campaing ) {
            return false;
        }
        return true;
    }

    public function get_campaing_slug()
    {
        if( !$this->get_is_campaing() ) {
            return '';
        }
        $product = $this->object;
        $campaing_slug = $product->get_meta( Event_Promotional::FIELD_NAME_SLUG_CAMPAING, true );
        return $campaing_slug;
    }
}
