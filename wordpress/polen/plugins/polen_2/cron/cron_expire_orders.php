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

global $wpdb, $Polen_Plugin_Settings, $WC_Cubo9_BraspagReduxSettings;

$current_date = new DateTime( 'now', new DateTimeZone( wp_timezone_string() ) );
$date_minus_one_hour = new DateInterval( 'P' . $WC_Cubo9_BraspagReduxSettings['order_expires'] . 'D' );
$current_date_string = $current_date->format( 'Y-m-d H:i:s' );
$current_date->sub( $date_minus_one_hour );
$date_string_expires = $current_date->format( 'Y-m-d H:i:s' );

$sql = "SELECT `ID` FROM `" . $wpdb->posts . "` WHERE `post_type`='shop_order' AND `post_status`='wc-payment-approved' AND `post_date` <= '" . $date_string_expires . "'";
$res = $wpdb->get_results( $sql );
if( $res && ! is_null( $res ) && ! is_wp_error( $res ) && is_array( $res ) && count( $res ) > 0 ) {
    foreach( $res as $k => $row ) {
        $order_id = $row->ID;
        $order = wc_get_order( $order_id );
        $Cubo9_Braspag = new Cubo9_Braspag( $order, false );
        $return = $Cubo9_Braspag->void();

        if( isset( $return['ProviderReturnMessage'] ) && $return['ProviderReturnMessage'] == 'Operation Successful' ) {
            if( $order->get_status() != 'talent-rejected' ) {
                $order->update_status( 'talent-rejected', 'order_note' );
                echo '#' . $order_id . ': Cancelado e estornado.'; 
            }
        } else {
            echo '#' . $order_id . ': ' . $return['Message'] . "\n";
            if( $return['Message'] == 'Transaction not available to refund' ) {
                $order->update_status( 'talent-rejected', 'order_note' );
                echo '#' . $order_id . ': Marcado como cancelado e estornado.' . "\n"; 
            }
        }
    }
} else {
    echo "Nenhum pedido a ser expirado.\n";
}

echo "\n";