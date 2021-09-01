<?php

class Promotional_Event_Product
{
    public $product;

    public function __construct( \WC_Product $product )
    {
        $this->product = $product;
    }

    public function get_url_image_book()
    {
        $attachment_id = $this->product->get_image_id();
        $src = wp_get_attachment_image_src( $attachment_id, 'polen-thumb-lg' );
        if( empty( $src ) ) {
            return false;
        }
        return $src[ 0 ];
    }
}
