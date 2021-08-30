<?php

// Se a execução não for pelo CLI gera Exception
if( strpos( php_sapi_name(), 'cli' ) === false ) {
    echo 'Silence is Golden';
    die;
}

function findWordpressBasePath() {
	$dir = dirname( __FILE__ );
	do {
		if( file_exists( $dir . '/wp-config.php' ) ) {
			return $dir;
		}
	} while( $dir = realpath( "$dir/.." ) );
	return null;
}

define( 'BASE_PATH', findWordpressBasePath() . "/" );
define( 'WP_USE_THEMES', false ) ;

echo "\n\n";
wp_set_current_user( 1 );
global $wpdb, $Polen_Plugin_Settings, $WC_Cubo9_BraspagReduxSettings;

$current_date = new DateTime( 'now', new DateTimeZone( wp_timezone_string() ) );
$date_minus_one_hour = new DateInterval( 'P' . $Polen_Plugin_Settings['order_expires'] . 'D' );
$current_date_string = $current_date->format( 'Y-m-d H:i:s' );
$current_date->sub( $date_minus_one_hour );
$date_string_expires = $current_date->format( 'Y-m-d H:i:s' );

$sql = "SELECT `ID` FROM `" . $wpdb->posts . "` WHERE `post_type`='shop_order' AND `post_status` IN ( 'wc-payment-approved', 'wc-talent-accepted' ) AND `post_date` <= '" . $date_string_expires . "'";
$res = $wpdb->get_results( $sql );
if( $res && ! is_null( $res ) && ! is_wp_error( $res ) && is_array( $res ) && count( $res ) > 0 ) {
    foreach( $res as $k => $row ) {
        $order_id = $row->ID;
        $order = wc_get_order( $order_id );
        // $Cubo9_Braspag = new Cubo9_Braspag( $order, false );
        // $return = $Cubo9_Braspag->void();

        // if( isset( $return['ProviderReturnMessage'] ) && $return['ProviderReturnMessage'] == 'Operation Successful' ) {
            // if( $order->get_status() != 'talent-rejected' ) {
            //     $order->update_status( 'order-expired', 'order_note' );
            //     echo '#' . $order_id . ': Cancelado e estornado.' . "\n";
            // }
        // } else {
        //     echo '#' . $order_id . ': ' . $return['Message'] . "\n";
        //     if( $return['Message'] == 'Transaction not available to refund' ) {

            if( social_order_is_social( $order ) ) {
                $interval_time = new DateInterval( 'P15D' );
                $cd = new DateTime( 'now', new DateTimeZone( wp_timezone_string() ) );
                $diff = $order->get_date_created()->add( $interval_time )->diff( $cd );
                if( $diff->invert == 0) {
                    $order->update_status( 'order-expired', 'order_note' );
                    echo '#' . $order_id . ': Marcado como expirado extorno manual CRIESP.' . "\n"; 
                }
            } elseif( event_promotional_order_is_event_promotional( $order ) ) {
                $interval_time = new DateInterval( 'P30D' );
                $cd = new DateTime( 'now', new DateTimeZone( wp_timezone_string() ) );
                $diff = $order->get_date_created()->add( $interval_time )->diff( $cd );
                if( $diff->invert == 0) {
                    $order->update_status( 'order-expired', 'order_note', true );
                    echo '#' . $order_id . ': Marcado como expirado extorno manual Video-Autografo.' . "\n"; 
                }
            } else {
                $order->update_status( 'order-expired', 'order_note' );
                echo '#' . $order_id . ': Marcado como expirado extorno manual.' . "\n"; 
            }
        //     }
        // }
    }
} else {
    echo "Nenhum pedido a ser expirado.\n";
}

echo "\n";