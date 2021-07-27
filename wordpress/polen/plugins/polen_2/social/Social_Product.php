<?php
namespace Polen\Social;

defined( 'ABSPATH' ) || die;

class Social_Product
{
    public static function product_is_social( $product, $category )
    {
        if( in_array( $category->term_id, $product->get_categories_ids() ) ) {
            return true;
        }
        return false;
    }

    public static function get_all_products_by_category( $category )
    {
        $args = array(
            'status' => 'publish',
            'category' => $category->slug,
            'orderby' => 'stock_quantity',
            'order' => 'DESC',
        );
        $products = wc_get_products( $args );
        return $products;
    }
}