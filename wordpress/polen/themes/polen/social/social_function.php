<?php

use Polen\Social\Social_Category;
use Polen\Social\Social_Product;

define( 'CATEGORY_SLUG_CRIESP', 'criesp' );

function social_get_products_by_category_slug( $category_slug = CATEGORY_SLUG_CRIESP )
{
    $category = Social_Category::get_category_by_slug( $category_slug );
    $products = Social_Product::get_all_products_by_category( $category );
    return $products;
}

function social_product_is_social( $producty, $category )
{
    Social_Product::product_is_social( $producty, $category );
}