<?php

use Polen\Tributes\{ Tributes, Tributes_Occasions_Model, Tributes_Questions_Model, Tributes_Rewrite_Rules};

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



/****************************************
 ******************** URLs **************
 ****************************************/

 /**
 * Pega a URL da pagina que faz os convites
 * 
 * @param string $tribute_hash
 * @return string URL completa
 */
function tribute_get_url_base_url() {
    return site_url( Tributes_Rewrite_Rules::BASE_PATH );
}


 /**
 * Pega a URL da pagina de detalhes do tributo
 * 
 * @param string $tribute_hash
 * @return string URL completa
 */
function tribute_get_url_tribute_detail( $tribute_hash ) {
    return site_url( Tributes_Rewrite_Rules::BASE_PATH . "/{$tribute_hash}/detalhes" );
}

/**
 * Pega a URL da pagina que faz os convites
 * 
 * @param string $tribute_hash
 * @return string URL completa
 */
function tribute_get_url_invites( $tribute_hash ) {
    return site_url( Tributes_Rewrite_Rules::BASE_PATH . "/{$tribute_hash}" );
}


/**
 * Pega a URL da pagina de sucesso depois que envia o video
 * 
 * @param string $tribute_hash
 * @param string $invite_hash
 * @return string URL completa
 */
function tribute_get_url_send_video_success( $tribute_hash, $invite_hash ) {
    return site_url( Tributes_Rewrite_Rules::BASE_PATH . "/{$tribute_hash}/invite/{$invite_hash}/sucesso" );
}



//*****************************************/



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
