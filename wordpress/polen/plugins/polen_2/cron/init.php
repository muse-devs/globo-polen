<?php

//Se a execução não for pelo CLI gera Exception
if( strpos(php_sapi_name(), 'cli' ) === false ) {
    echo 'no CLI';
    die;
}

global $wp, $wpdb, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header, $Polen_Plugin_Settings;

include_once 'polen/plugins/polen_2/autoload.php';
include_once 'polen/plugins/polen_2/vendor/autoload.php';
