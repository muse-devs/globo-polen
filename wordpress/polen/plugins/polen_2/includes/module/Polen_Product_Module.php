<?php
namespace Polen\Includes\Module;

use Polen\Admin\Polen_Admin_Event_Promotional_Event_Fields as Event_Promotional;

class Polen_Product_Module
{

    const TAXONOMY_SLUG_campaign = 'campaigns';
    
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

    public function get_is_campaign()
    {
        $product = $this->object;
        $campaign_taxonomies = wp_get_post_terms( $product->get_id(), self::TAXONOMY_SLUG_campaign );
        if( empty( $campaign_taxonomies ) || is_wp_error( $campaign_taxonomies ) ) {
            return false;
        }
        return true;
    }

    public function get_campaign_slug()
    {
        if( !$this->get_is_campaign() ) {
            return '';
        }
        $product = $this->object;
        $campaign_taxonomies = wp_get_post_terms( $product->get_id(), self::TAXONOMY_SLUG_campaign );
        return $campaign_taxonomies[ 0 ]->slug;
    }

    public function get_campaign_name()
    {
        if( !$this->get_is_campaign() ) {
            return '';
        }
        $product = $this->object;
        $campaign_taxonomies = wp_get_post_terms( $product->get_id(), self::TAXONOMY_SLUG_campaign );
        return $campaign_taxonomies[ 0 ]->name;
    }
}
