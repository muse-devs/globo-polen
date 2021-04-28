<?php

namespace Polen\Includes;

if( ! defined( 'ABSPATH') ) {
    die( 'Silence is golden.' );
}

if ( ! class_exists( 'WC_Email' ) ) {
	return;
}

class Polen_WC_Talent_Rejected extends \WC_Email {

    public function __construct() {
        $this->id          = 'wc_talent_rejected';
		$this->title       = __( 'O talento rejeitou', 'polen' );
		$this->description = __( 'E-mail que será enviado ao usuário quando o talento rejeitar o pedido.', 'polen' );
		$this->customer_email = true;
		$this->heading     = __( 'O talento rejeitou', 'polen' );

		$this->subject     = sprintf( _x( '[%s] O talento rejeitou', 'E-mail que será enviado ao usuário quando o talento rejeitar o pedido.', 'polen' ), '{blogname}' );
    
		$this->template_html  = 'emails/Polen_WC_Talent_Rejected.php';
		$this->template_plain = 'emails/plain/Polen_WC_Talent_Rejected.php';
		$this->template_base  = TEMPLATEPATH . 'woocommerce/';
    
		add_action( 'woocommerce_order_status_on-hold_to_talent-rejected_notification', array( $this, 'trigger' ) );
		add_action( 'woocommerce_order_status_payment-approved_to_talent-rejected_notification', array( $this, 'trigger' ) );

		parent::__construct();
    }
    
}