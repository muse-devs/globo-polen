<?php

namespace Polen\Includes;

if( ! defined( 'ABSPATH') ) {
    die( 'Silence is golden.' );
}

if ( ! class_exists( 'WC_Email' ) ) {
	return;
}

class Polen_WC_Payment_In_Revision extends \WC_Email {
    
}