<?php

include_once dirname( __FILE__ ) . '/init.php';

$args = [
//     'fields' => 'ids',
    'paginate' => true,
    'limit' => '100',
    'order_by' => 'id',
    'order' => 'DESC',
//     'page' => 1,
//     'post_type' => wc_get_order_types(),
    'post_status' => array_keys( wc_get_order_statuses() ),
//     // 'post_status' => ['wc-payment-approved','wc-talent-accepted'],
//     // 'deadline' => '2021-08-13',
//     'meta_query' => [
//         [ 'key' => '_deadline', 'value' => '1629470548', 'compare' => 'IN' ],
//     ],
];

// $oq = new WP_Query( $args );
// $oq = new WC_Order_Query( $args );
// $oq = new Polen_Order_Data_Store_CPT( $args );


// var_dump( $oq->query_vars);//die;
// $orders = $oq->get_orders();
$orders = wc_get_orders( $args );

for( $i = 1; $i <= $orders->max_num_pages; $i++ ) :
    // $orders = $oq->get_posts();
    // global $wpdb;
    // var_dump( $wpdb->last_query );
    // var_dump('FOUND_ROWS: '.$wpdb->get_var('SELECT FOUND_ROWS();'));

    // $ds = new WC_Data_Store_WP();
    // var_dump( $ds-> );




    // Debug::def( $oq->get_query_vars() );die;
    // var_dump($oq->get_query_vars());
    // $ods = new WC_Order_Data_Store_CPT();
    // var_dump( $ods->parse_date_for_wp_query( '<YYYY-MM-DD','_deadline', ['value' => '2021-08-13'] ) );
    // var_dump( WC_Data_Store::load( 'order' )->get_current_class_name() );
    // var_dump( $oq->parse_date_for_wp_query() );


    // die;
    // var_dump( $oq->parse_date_for_wp_query() );die;
    // var_dump( $orders );
    // die;
    var_dump($orders);
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
        // $order->add_meta_data( '_polen_deadline', $created_at->getTimestamp(), true );
        // $order->save();
    }

    $args = [
            'paginate' => true,
            'per_page' => '100',
            'order_by' => 'id',
            'order' => 'DESC',
            'post_status' => array_keys( wc_get_order_statuses() ),
    ];
endfor;
// var_dump( $wpdb->last_query );

// var_dump( $orders );
// WC_Emails::instance();
