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

/**
 * Pega o path do email de envio do convite
 * @return string
 */
function tributes_get_path_email_send_invite() {
    return tributes_get_email_path() . 'invite_send_video.php';
}

function tributes_get_email_path() {
    return TEMPLATE_DIR . '/tributes/emails/';
}

/**
 * Cria o link para o Botao Envie Seu Video no email do invite
 */
function tribute_create_link_email_send_video( $invite_hash ) {
    return site_url( "tributes/{$invite_hash}/set-email-clicked" );
}

/**
 * Cria o link da imagem de 1px 1px que seta o email como aberto
 */
function tribute_create_link_set_email_opened( $invite_hash ) {
    return site_url( "tributes/{$invite_hash}/set-email-readed" ) . '/';
}
