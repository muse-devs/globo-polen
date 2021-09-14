<?php

include_once dirname( __FILE__ ) . '/init.php';

$args = [
    'return' => 'ids',
    // 'paginate' => true,
    'order_by' => 'id',
    'order' => 'ASC',
    'meta_key' => 'campaign',
    'meta_value' => 'rebeldes-tem-asas',
    'page' => 1,
];

global $wpdb;

$oq = new WC_Order_Query( $args );

$orders = $oq->get_orders();

var_dump( $wpdb->last_query );

// var_dump( $orders );
