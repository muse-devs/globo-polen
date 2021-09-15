<?php

include_once dirname( __FILE__ ) . '/init.php';

$args = [
    // 'return' => 'ids',
    // 'paginate' => true,
    'limit' => '20',
    'order_by' => 'id',
    'order' => 'ASC',
    'page' => 9,
];

$oq = new WC_Order_Query( $args );

$orders = $oq->get_orders();

foreach( $orders as $order ) {
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

    

    echo $order->get_id();
    echo '  |  ';
    echo $order->get_date_created()->format('d/m/Y H:i:s');
    echo ' | ';
    echo $order->get_date_created()->add( $interval_time )->format('d/m/Y H:i:s');
    echo $type;
    echo "\n";
}

// var_dump( $wpdb->last_query );

// var_dump( $orders );
