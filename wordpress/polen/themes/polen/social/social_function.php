<?php

use Polen\Social\Social;
use Polen\Social\Social_Category;
use Polen\Social\Social_Product;

define( 'CATEGORY_SLUG_CRIESP', 'criesp' );

/**
 * Verifica se está dentro do App de Social ou seja /social/crianca-esperanca
 * @return bool
 */
function social_is_in_social_app()
{
    return Social::is_social_app();
}


/**
 * Pega o object da Categoria Social
 * @param string
 * @return Social_Category
 */
function social_get_category_base( $category_slug = CATEGORY_SLUG_CRIESP )
{
    return Social_Category::get_category_by_slug( $category_slug );
}


/**
 * Pega todos os produtos de uma Categoria Social
 * @param Social_Category
 * @return array
 */
function social_get_products_by_category_slug( $category )
{
    $products = Social_Product::get_all_products_by_category( $category );
    return $products;
}


/**
 * Saber se um produto é social no caso agora criança esperanca
 * @param WC_Product
 * @param Social_Category
 * @return bool
 */
function social_product_is_social( $producty, $category )
{
    return Social_Product::product_is_social( $producty, $category );
}
