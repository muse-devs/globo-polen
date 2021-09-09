<?php

/**
 * Saber se o dispositivo é mobile
 * @return bool
 */
function polen_is_mobile() {
    $detect = wp_is_mobile();
    return $detect;
}


function polen_get_initials_name_by_user( $user )
{
    $name = $user->first_name . ' ' . $user->last_name;
    if( empty( trim( $name ) ) ) {
        $name = $user->display_name;
    }
    if( empty( trim( $name ) ) ) {
        $name = $user->nickname;
    }
    return polen_get_initials_name( $name );
}

/**
 * Generate initials from a name
 * https://chrisblackwell.me/generate-perfect-initials-using-php/
 *
 * @param string $name
 * @return string
 */
function polen_get_initials_name( $name )
{
    $words = explode( ' ', $name );
    if (count($words) >= 2) {
        return strtoupper( substr( $words[ 0 ], 0, 1 ) . substr( end( $words ), 0, 1 ) );
    }
    return _polen_makeInitialsFromSingleWord( $name );
}

/**
 * Make initials from a word with no spaces
 * https://chrisblackwell.me/generate-perfect-initials-using-php/
 *
 * @param string $name
 * @return string
 */
function _polen_makeInitialsFromSingleWord( $name )
{
    preg_match_all( '#([A-Z]+)#', $name, $capitals );
    if ( count( $capitals[ 1 ] ) >= 2 ) {
        return substr( implode( '', $capitals[ 1 ] ), 0, 2 );
    }
    return strtoupper( substr( $name, 0, 2 ) );
}


function polen_get_protocol()
{
	return (!empty($_SERVER['HTTPS']) &&
				$_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443)
				? "https:"
				: "http:";
}


function polen_is_landingpage()
{
	global $lp_sigin_lead;
	return isset($lp_sigin_lead) && $lp_sigin_lead === true;
}
