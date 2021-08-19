<?php

function event_promotional_url_home()
{
    return site_url( Promotional_Event_Rewrite::BASE_URL . '/de-porta-em-porta' );
}

function event_promotional_is_home()
{
    if( $GLOBALS[ Promotional_Event_Rewrite::QUERY_VARS_EVENT_PROMOTIONAL_IS_HOME ] == '1' ) {
        return true;
    }
    return false;
}