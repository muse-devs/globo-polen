<?php
$tribute_hash = get_query_var( 'tribute_hash' );
$invite_hash  = get_query_var( 'invite_hash' );
echo "Send Video: {$tribute_hash} {$invite_hash}";