<?php
// Se a execução não for pelo CLI gera Exception

use Polen\Includes\Cart\Polen_Cart_Item_Factory;

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
    // 'status' => ['cancelled', 'refunded','failed','payment-in-revision','payment-rejected','talent-rejected','order-expired'],
    'status' => ['completed', 'payment-approved','talent-accepted'],
    'meta_key' => 'campaing',
    'meta_value' => 'criesp',
]);

$result = $oq->get_orders();
$orders = $result->orders;

echo "Nome;Pedido;Data;Status; \r\n";
foreach( $orders as $order ) {
    $cart_item = Polen_Cart_Item_Factory::polen_cart_item_from_order( $order );
    $product = $cart_item->get_product();
    $name = $product->get_title();
    $id = $order->get_id();
    $date = $order->get_date_created()->date('d/m/Y');
    $status = $order->get_status();
    echo "{$name};{$id};{$date};{$status}; \r\n";
}

// var_dump($wpdb->last_query);
