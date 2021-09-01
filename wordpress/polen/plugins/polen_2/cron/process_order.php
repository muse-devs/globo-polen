<?php
// Se a execução não for pelo CLI gera Exception
if( strpos( php_sapi_name(), 'cli' ) === false ) {
    echo 'Silence is Golden';
    die;
}

include_once dirname( __FILE__ ) . '/init.php';

$oq = new WC_Order_Query([
    'return' => 'ids',
    'limit' => 1,
    'paginate' => true,
    // 'social' => '1',
    'status' => ['completed', 'payment-approved','talent-accepted'],
    'meta_key' => 'campaing',
    'meta_value' => 'criesp',
]);

var_dump( $oq->get_orders() );

// var_dump($wpdb->last_query);
