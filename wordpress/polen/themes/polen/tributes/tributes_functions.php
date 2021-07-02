<?php

use Polen\Tributes\{ Tributes, Tributes_Occasions_Model, Tributes_Questions_Model };

if( !defined( 'ABSPATH' ) ) {
    echo 'Silence is Golden';
    die;
}

//Funcoes responsaveis pelos emails
include_once TEMPLATE_DIR . '/tributes/tributes_functions_emails.php';

function is_tribute_app() {
    return Tributes::is_tributes_app();
}

function is_tribute_home() {
    return Tributes::is_tributes_home();
}

function is_tribute_create() {
    return Tributes::is_tributes_create();
}

/**
 * Pega a URL da pagina que faz os convites
 * 
 * @param string $tribute_hash
 * @return string URL completa
 */
function tribute_get_url_invites( $tribute_hash ) {
    return site_url( "tributes/{$tribute_hash}" );
}

/**
 * Pega todas as questões cadastradas
 * @return array stdClass $obj->question
 */
function tribute_get_questions() {
    return Tributes_Questions_Model::get_all();
}

/**
 * Pega todas as ocasiões cadastradas
 * @return array stdClass $obj->occasion
 */
function tribute_get_occasions() {
    return Tributes_Occasions_Model::get_all();
}
