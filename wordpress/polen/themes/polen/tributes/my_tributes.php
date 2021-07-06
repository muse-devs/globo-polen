<?php

use Polen\Tributes\Tributes_Invites_Model;

global $my_tributes;

foreach( $my_tributes as $tribute ) {
    list( $sent, $not_sent ) = Tributes_Invites_Model::get_videos_sent_and_not( $tribute->ID );
    $total_success = ( $sent / ( $sent + $not_sent ) ) * 100;
    $date_limit = date('d/m/Y', strtotime( $tribute->deadline ) );
    echo $tribute->name_honored . '<br>';
}

?>