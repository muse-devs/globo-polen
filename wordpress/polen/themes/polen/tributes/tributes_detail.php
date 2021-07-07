<?php

use Polen\Tributes\Tributes_Invites_Model;

global $tribute;

$invites = Tributes_Invites_Model::get_all_by_tribute_id( $tribute->ID );

echo $tribute->hash . '<br>';

foreach( $invites as $invite ) {
    echo $invite->name_inviter . '<br>';
}