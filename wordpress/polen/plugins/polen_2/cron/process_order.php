<?php
// Se a execução não for pelo CLI gera Exception
if( strpos( php_sapi_name(), 'cli' ) === false ) {
    echo 'Silence is Golden';
    die;
}

include_once dirname( __FILE__ ) . '/init.php';

$oq = new WC_Order_Query([
    // 'return' => 'ids',
    'limit' => 1000,
    'paginate' => true,
    // 'social' => '1',
    'status' => ['completed', 'payment-approved','talent-accepted'],
    'meta_key' => 'campaing',
    'meta_value' => 'criesp',
]);

$result = $oq->get_orders();
$orders = $result->orders;

foreach( $orders as $order ) {
    $id = $order->get_id();
    $date = $order->get_date_created('d/m/Y');
    $status = $order->get_status();
    echo "{$id};{$date};{$status}; \r\n";
}

// var_dump($wpdb->last_query);
