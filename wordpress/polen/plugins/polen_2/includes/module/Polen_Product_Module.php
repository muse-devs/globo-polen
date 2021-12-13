<?php
namespace Polen\Includes\Module;

use Polen\Admin\Polen_Admin_Event_Promotional_Event_Fields as Event_Promotional;

class Polen_Product_Module
{

    const TAXONOMY_SLUG_CAMPAIGN = 'campaigns';
    
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
        $campaign_taxonomies = wp_get_post_terms( $product->get_id(), self::TAXONOMY_SLUG_CAMPAIGN );
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
        $campaign_taxonomies = wp_get_post_terms( $product->get_id(), self::TAXONOMY_SLUG_CAMPAIGN );
        return $campaign_taxonomies[ 0 ]->slug;
    }

    public function get_campaign_name()
    {
        if( !$this->get_is_campaign() ) {
            return '';
        }
        $product = $this->object;
        $campaign_taxonomies = wp_get_post_terms( $product->get_id(), self::TAXONOMY_SLUG_CAMPAIGN );
        return $campaign_taxonomies[ 0 ]->name;
    }


    /**
     * Get All orders IDs by product ID
     * *<Important>COLOCAR O wc- no inicio do STATUS</Important>
     *
     * @param  integer  $product_id (required)
     * @param  array    $order_status (optional) Default is 'wc-completed' precisa colocar o wc- no inicio
     *
     * @return array
     */
    public static function get_orders_ids_by_product_id( int $product_id, $order_status = array( 'wc-completed' ) ){
        global $wpdb;
        $results = $wpdb->get_col("
            SELECT order_items.order_id
            FROM {$wpdb->prefix}woocommerce_order_items as order_items
            LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
            LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
            WHERE posts.post_type = 'shop_order'
            AND posts.post_status IN ( '" . implode( "','", $order_status ) . "' )
            AND order_items.order_item_type = 'line_item'
            AND order_item_meta.meta_key = '_product_id'
            AND order_item_meta.meta_value = '$product_id';
        ");
        return $results;
    }


    /**
     * Pega o nome da primeira Categoria do produto
     */
    public function get_category_name()
    {
        $categories_ids = $this->object->get_category_ids();
        $category_id = $categories_ids[ 0 ];
        $category = get_term_by( 'id', $category_id, 'product_cat' );
        if( empty( $category ) ) {
            return '';
        }
        return $category->name;
    }
}
