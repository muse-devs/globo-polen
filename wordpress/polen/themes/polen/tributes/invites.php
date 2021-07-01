<?php

use Polen\Tributes\Tributes_Model;

$tribute_hash = get_query_var( 'tribute_hash' );
$tribute = Tributes_Model::get_by_hash( $tribute_hash );

if( empty( $tribute ) ) {
    echo 'SET 404';
    exit;
}

echo "Tribute: {$tribute_hash}<br />";
echo '<pre>';
var_dump( $tribute );
echo '</pre>';