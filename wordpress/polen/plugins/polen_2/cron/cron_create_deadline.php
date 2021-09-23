<?php

use Polen\Includes\Polen_Order;

include_once dirname( __FILE__ ) . '/init.php';

// $order = wc_get_order( 370 );
// $interval = Polen_Order::get_deadline_interval_order_by_social_event( $order );
// $var_dump( Polen_Order::get_deadline_timestamp_by_social_event( $order, $interval ) );
// // echo get_class($order);
// // var_dump(Polen_Order::get_order_ids_by_deadline( ['payment-approved', 'talent-accepted'], "1632171169"));
// die;

$args = [
    // 'return' => 'ids',
    'paginate' => true,
    'limit' => '10',
    'order_by' => 'id',
    'order' => 'DESC',
    'page' => 1,
    'post_status' => array_keys( wc_get_order_statuses() ),
];

$orders = wc_get_orders( $args );
$max_page = $orders->max_num_pages;
for( $i = 1; $i <= $max_page; $i++ ) :

    if( $i > 1 ) {
        $args['page'] = $i;
        $orders = wc_get_orders( $args );
    }

    foreach( $orders->orders as $order ) {
        if( social_order_is_social( $order ) ) {
            $interval_time = new DateInterval( 'P15D' );
            $type = ' S ';
        } elseif ( event_promotional_order_is_event_promotional( $order ) ) {
            $interval_time = new DateInterval( 'P30D' );
            $type = ' E ';
        } else {
            $interval_time = new DateInterval( 'P7D' );
            $type = ' B ';
        }

        echo " {$i} ";
        $created_at = $order->get_date_created();
        echo $order->get_id();
        echo '  |  ';
        echo $created_at->format('d/m/Y H:i:s');
        echo ' | ';
        echo $created_at->add( $interval_time )->format('d/m/Y H:i:s');
        echo ' | ';
        echo $created_at->getTimestamp();
        echo $type;
        echo "\n";
        $order->add_meta_data( Polen_Order::META_KEY_DEADLINE, $created_at->getTimestamp(), true );
        $order->save();
    }
die('OK--KO');
endfor;
// var_dump( $wpdb->last_query );

// var_dump( $orders );
// WC_Emails::instance();
