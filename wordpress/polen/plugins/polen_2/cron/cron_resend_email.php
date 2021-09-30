<?php
include_once dirname( __FILE__ ) . '/init.php';

use Polen\Includes\Emails\Polen_WC_Completed_Order;


$orders = wc_get_orders( [
    // 'includes' => [ 457,456,429 ],
    // 'limit' => 300,
    // 'date_completed' => '2021-09-15...2021-09-17'
    'limit' =>70,
    'paged' => 1,
    'date_completed' => '2021-09-15...2021-09-17'
] );
WC_Emails::instance();
foreach( $orders as $order ) {
    // echo $order->get_id();
    if( "completed" == $order->get_status() ) {
        echo $order->get_billing_email() . "\n";
        $mail = new Polen_WC_Completed_Order();
        $mail->trigger( $order->get_id(), $order );
    }
}
