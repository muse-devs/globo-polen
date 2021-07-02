<?php

use Polen\Tributes\{ Tributes_Invites_Model, Tributes_Model };

if( !defined( 'ABSPATH' ) ) {
    echo 'Silence is Golden';
    die;
}

/**
 * Pega o path geral de todos os emails
 */
function tributes_get_email_path() {
    return TEMPLATE_DIR . '/tributes/emails/';
}

/**
 * Pega o path do email de envio do convite
 * @return string
 */
function tributes_get_path_email_send_invite() {
    return tributes_get_email_path() . 'invite_send_video.php';
}

/**
 * Pega o path do email de envio do convite
 * @return string
 */
function tributes_get_path_email_sended_complete() {
    return tributes_get_email_path() . 'invites_sended.php';
}

/**
 * Cria o link para o Botao Envie Seu Video no email do invite
 */
function tributes_create_link_email_send_video( $invite_hash ) {
    return site_url( "tributes/{$invite_hash}/set-email-clicked" );
}

/**
 * Cria o link da imagem de 1px 1px que seta o email como aberto
 */
function tributes_create_link_set_email_opened( $invite_hash ) {
    return site_url( "tributes/{$invite_hash}/set-email-readed" ) . '/';
}

function tributes_send_email( $email_content) {
    $headers[] = 'From: Polen No-reply <polen@example.net>';
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    $to = "Rodolfo Neto <rodolfoneto@gmail.com>";
    var_dump(wp_mail($to, 'Convite para Tributo a', $email_content, $headers));
}

/**
 * Cria o conteudo do email de convite
 * @param string
 * @return string
 */
function tributes_email_create_content_invite( $invite_hash ) {
    $path_email = tributes_get_path_email_send_invite();
    $email_content = file_get_contents( $path_email );

    $invites = Tributes_Invites_Model::get_by_hash( $invite_hash );
    $tribute = Tributes_Model::get_by_id( $invites->tribute_id );

    $date = date('d \d\e F \d\e o', strtotime($tribute->deadline));
    $content_formatted = sprintf(
        $email_content,
        $invites->name_inviter,
        $tribute->creator_name,
        $tribute->name_honored,
        $date,
        $tribute->creator_name,
        $tribute->welcome_message,
        $tribute->question,
        tributes_create_link_email_send_video( $invites->hash ),
        tributes_create_link_set_email_opened( $invites->hash ),
    );
    return $content_formatted;
}