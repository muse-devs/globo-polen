<?php

namespace Polen\Includes\Order_Review;

use Polen\Includes\Talent\{Polen_Talent_Router, Polen_Talent_Controller_Base};

class Polen_Order_Review_Router extends Polen_Talent_Router
{
    public function __construct( Polen_Talent_Controller_Base $controller )
    {
        $this->controller = $controller;
    }

    public function init_routes()
    {
        $this->add_route( 'create_order_review', 'create_order_review', true );
    }
}
