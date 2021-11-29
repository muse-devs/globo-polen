<?php
namespace Polen\Includes\Module;

use Polen\Admin\Polen_Admin_Event_Promotional_Event_Fields as Event_Promotional;

class Polen_Product_Module
{

    const TAXONOMY_SLUG_CAMPAING = 'campaigns';
    
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
        $campaing_taxonomies = wp_get_post_terms( $product->get_id(), self::TAXONOMY_SLUG_CAMPAING );
        if( empty( $campaing_taxonomies ) || is_wp_error( $campaing_taxonomies ) ) {
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
        $campaing_taxonomies = wp_get_post_terms( $product->get_id(), self::TAXONOMY_SLUG_CAMPAING );
        return $campaing_taxonomies[ 0 ]->slug;
    }
}
