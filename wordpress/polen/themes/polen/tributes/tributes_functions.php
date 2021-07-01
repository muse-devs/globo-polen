<?php

use Polen\Tributes\Tributes;

if( !defined( 'ABSPATH' ) ) {
    echo 'Silence is Golden';
    die;
}

function is_tribute_app() {
    return Tributes::is_tributes_app();
}

function is_tribute_home() {
    return Tributes::is_tributes_home();
}

function is_tribute_create() {
    return Tributes::is_tributes_create();
}
