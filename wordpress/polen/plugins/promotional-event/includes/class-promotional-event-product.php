<?php

class Promotional_Event_Product
{
    public $product;

    public function __construct( \WC_Product $product )
    {
        $this->product = $product;
    }
}
