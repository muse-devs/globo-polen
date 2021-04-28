<?php

namespace Polen\Includes;

if( ! defined( 'ABSPATH') ) {
    die( 'Silence is golden.' );
}

if ( ! class_exists( 'WC_Email' ) ) {
	return;
}

class Polen_WC_Payment_Rejected extends \WC_Email {

    public function __construct() {
        $this->id          = 'wc_payment_rejected';
		$this->title       = __( 'Pagamento Rejeitado', 'polen' );
		$this->description = __( 'E-mail que será enviado para o usuário quando o pagamento for rejeitado.', 'polen' );
		$this->customer_email = true;
		$this->heading     = __( 'Pagamento Rejeitado', 'polen' );

		$this->subject     = sprintf( _x( '[%s] Pagamento Rejeitado', 'E-mail que será enviado para o usuário quando o pagamento for rejeitado.', 'polen' ), '{blogname}' );
    
		$this->template_html  = 'emails/Polen_WC_Payment_Rejected.php';
		$this->template_plain = 'emails/plain/Polen_WC_Payment_Rejected.php';
		$this->template_base  = TEMPLATEPATH . 'woocommerce/';
    
		add_action( 'woocommerce_order_status_pending_to_payment-rejected_notification', array( $this, 'trigger' ) );
		add_action( 'woocommerce_order_status_payment-in-revision_to_payment-rejected_notification', array( $this, 'trigger' ) );

		parent::__construct();
    }
    
}