<?php
namespace Polen\Includes\v2;

use Polen\Includes\Polen_Order;
use Polen\Includes\Polen_Utils;

class Polen_Order_V2
{


    /**
     * Pegar a quantidade de Orders por Produtos_Id e Deadline
     * 
     * @param array produtos_id
     * @param string deadline format 'Y/m/d'
     * @return int
     */
    static public function get_qty_orders_by_products_id_deadline( $products_id, $date_deadline )
    {
        global $wpdb;

        $product_ids_pattern = implode( ', ', array_fill( 0, count( $products_id ), '%s' ) );
        $timestamp_start = strtotime( $date_deadline . ' 00:00:00' );
        $timestamp_final = strtotime( $date_deadline . ' 23:59:59' );
        $status = [ Polen_Order::SLUG_ORDER_PAYMENT_APPROVED_INSIDE, Polen_Order::SLUG_ORDER_TALENT_ACCEPTED_INSIDE ];
        $status_pattern = implode( ', ', array_fill( 0, count( $status ), '%s' ) );
        $sql = "SELECT
                opl.order_id
            FROM
                wp_wc_order_product_lookup AS opl
            INNER JOIN wp_wc_order_stats AS os ON ( os.order_id = opl.order_id )
            INNER JOIN wp_postmeta as pm ON ( pm.post_id = opl.order_id AND pm.meta_key = '_polen_deadline' )
            WHERE
                opl.product_id IN ( {$product_ids_pattern} )
            AND
                os.status IN ( {$status_pattern} )
            AND
                CAST(pm.meta_value AS SIGNED) >= %s
            AND
                CAST(pm.meta_value AS SIGNED) <= %s
            GROUP BY opl.order_id;";
        $sql_prepared = Polen_Utils::esc_arr( $sql, array_merge( $products_id, $status, [ $timestamp_start, $timestamp_final ] ) );
        $total = $wpdb->get_results( $sql_prepared );
        if( !empty( $wpdb->last_error ) ) {
            return null;
        }
        return count( $total );
    }
    

    /**
     * Pega a quantidade de Orders por Produtos_Ids e Statuses
     * 
     * @param array products_ids
     * @param array statuses
     * 
     * @return int
     */
    static public function get_qty_orders_by_products_id_status( array $products_id, array $status )
    {
        global $wpdb;

        $product_ids_pattern = implode( ', ', array_fill( 0, count( $products_id ), '%s' ) );
        $status_pattern = implode( ', ', array_fill( 0, count( $status ), '%s' ) );

        $sql = "SELECT opl.*
        FROM wp_wc_order_product_lookup AS opl
        INNER JOIN wp_wc_order_stats AS os ON os.order_id = opl.order_id
        INNER JOIN wp_woocommerce_order_items AS oi ON (
                oi.order_item_id = opl.order_item_id 
            AND
                oi.order_item_type = 'line_item' )
        WHERE
            opl.product_id IN ( {$product_ids_pattern} )
        AND
            os.status IN ( {$status_pattern} )";
        
        $sql_prepared = Polen_Utils::esc_arr( $sql, array_merge( $products_id, $status ) );
        $result = $wpdb->get_results( $sql_prepared );
        if( !empty( $wpdb->last_error ) ) {
            return null;
        }
        $total = $result;
        return count( $total );
    }


    /**
     * Pega o total em R$ das Orders por Produtos_Ids e Statuses
     * 
     * @param array products_ids
     * @param array statuses
     * 
     * @return float
     */
    static public function get_total_orders_by_products_id_status( array $products_id, array $status )
    {
        global $wpdb;

        $product_ids_pattern = Polen_Utils::pattern_array( $products_id );
        $status_pattern = Polen_Utils::pattern_array( $status );
        
        $sql = "SELECT
            SUM( opl.product_gross_revenue ) as total
        FROM
            wp_wc_order_product_lookup AS opl
        INNER JOIN wp_wc_order_stats AS os ON ( os.order_id = opl.order_id )
        WHERE
            opl.product_id IN ( $product_ids_pattern )
        AND
            os.status IN ( $status_pattern );";

        $sql_prepared = Polen_Utils::esc_arr( $sql, array_merge( $products_id, $status ) );
        $result = $wpdb->get_var( $sql_prepared );
        if( !empty( $wpdb->last_error ) ) {
            return null;
        }
        return floatval( $result );
    }

    /**
     * Pegar a quantidade de Orders por Produtos_Id e Deadline
     *
     * @param array produtos_id
     * @param string deadline format 'Y/m/d'
     */
    static public function get_orders_by_products_id_deadline($products_id, $status, $order)
    {
        global $wpdb;

        $product_ids_pattern = implode( ', ', array_fill( 0, count( $products_id ), '%s' ) );
        $timestamp_start = strtotime(date('Y-m-d') . ' 00:00:00');
        $timestamp_final = strtotime(date('Y-m-d') . ' 23:59:59');
        $status_pattern = implode( ', ', array_fill( 0, count( $status ), '%s' ) );
        $sql = "SELECT
                opl.order_id
            FROM
                wp_wc_order_product_lookup AS opl
            INNER JOIN wp_wc_order_stats AS os ON ( os.order_id = opl.order_id )
            INNER JOIN wp_postmeta as pm ON ( pm.post_id = opl.order_id AND pm.meta_key = '_polen_deadline' )
            WHERE
                opl.product_id IN ( {$product_ids_pattern} )
            AND
                os.status IN ( {$status_pattern} )
            AND
                CAST(pm.meta_value AS SIGNED) >= %s
            AND
                CAST(pm.meta_value AS SIGNED) <= %s
            GROUP BY opl.order_id
            ORDER BY opl.order_id {$order};";
        $sql_prepared = Polen_Utils::esc_arr($sql, array_merge($products_id, $status, [$timestamp_start, $timestamp_final]));

        if (!empty($wpdb->last_error)) {
            return null;
        }

        return $wpdb->get_results($sql_prepared, ARRAY_A);
    }
}
