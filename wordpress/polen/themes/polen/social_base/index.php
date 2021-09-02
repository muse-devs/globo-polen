<?php

use Polen\Social_Base\Social_Base_Product;
use Polen\Social_Base\Social_Base_Rewrite;

$social_slug = $GLOBALS[ Social_Base_Rewrite::QUERY_VARS_SOCIAL_SLUG ];

$products = Social_Base_Product::get_all_products_by_slug_campaing( $social_slug );

var_dump( $products );

echo $social_slug;
