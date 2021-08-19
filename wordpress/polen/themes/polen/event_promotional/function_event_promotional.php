<?php

function event_promotional_url_home()
{
    return site_url( Promotional_Event_Rewrite::BASE_URL . '/de-porta-em-porta' );
}

function event_promotional_url_code_validation()
{
    return event_promotional_url_home() . '/validar-codigo';
}

function event_promotional_url_order()
{
    return event_promotional_url_home() . '/pedido';
}

function event_promotional_url_success()
{
    return event_promotional_url_home() . '/confirmado';
}

function event_promotional_is_home()
{
    if( $GLOBALS[ Promotional_Event_Rewrite::QUERY_VARS_EVENT_PROMOTIONAL_IS_HOME ] == '1' ) {
        return true;
    }
    return false;
}

function event_get_magalu_url()
{
	return "https://www.magazineluiza.com.br/livro-de-porta-em-porta-luciano-huck-com-brinde/p/231238100/li/adml/";
}
