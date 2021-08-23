<?php
namespace Polen\Social;

class Social_Order
{
    const ORDER_META_KEY_SOCIAL = 'social';
    const ORDER_META_KEY_CAMPAING = 'campaing';

    static function is_social( $order )
    {
        if( $order->get_meta( self::ORDER_META_KEY_SOCIAL, true ) == '1' ) {
            return true;
        }
        return false;
    }

    static function is_campaing( $order )
    {
        if( $order->get_meta( self::ORDER_META_KEY_CAMPAING, true ) == 'de-porta-em-porta' ) {
            return true;
        }
        return false;
    }
}