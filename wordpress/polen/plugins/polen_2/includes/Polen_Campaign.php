<?php

namespace Polen\Includes;

use Polen\Api\Api_Checkout;

class Polen_Campaign
{

    const KEY_CAMPAIGN = 'campaigns';
    const LABEL_CAMPAIGN = 'Campanhas';
    const SLUG_CAMPAIGN = 'campanha';

    public function __construct( bool $static = false )
    {
        if( $static ) {
            add_action('init', [ $this, 'create_taxonomy_campaigns' ]);
        }
    }


    /**
     * Registrar taxonomia de campanha em produtos
     */
    public function create_taxonomy_campaigns()
    {
        register_taxonomy(
            self::KEY_CAMPAIGN,
            'product',
            array(
                'label' => self::LABEL_CAMPAIGN,
                'rewrite' => array( 'slug' => self::SLUG_CAMPAIGN ),
                'hierarchical' => true,
            )
        );
    }


    /**
     * 
     * @param WC_Order
     * @return bool
     */
    public static function get_is_order_campaing( $order )
    {
        $meta_key = $order->get_meta( Api_Checkout::ORDER_METAKEY, true );
        if( !empty( $meta_key ) ) {
            return true;
        }
        return false;
    }


    /**
     * 
     * @param WC_Order
     * @return string
     */
    public static function get_order_campaing_slug( $order )
    {
        $meta_key = $order->get_meta( Api_Checkout::ORDER_METAKEY, true );
        return $meta_key;
    }
}