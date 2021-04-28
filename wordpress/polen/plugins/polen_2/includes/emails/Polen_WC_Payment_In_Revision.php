<?php

namespace Polen\Includes;

if( ! defined( 'ABSPATH') ) {
    die( 'Silence is golden.' );
}

if ( ! class_exists( 'WC_Email' ) ) {
	return;
}

class Polen_WC_Payment_In_Revision extends \WC_Email {

    public function __construct() {
        $this->id          = 'wc_payment_in_revision';
		$this->title       = __( 'Aguardando confirmação do pagamento', 'polen' );
		$this->description = __( 'E-mail que será enviado ao usuário quando um pedido é está aguardando confirmação de pagamento.', 'polen' );
		$this->customer_email = true;
		$this->heading     = __( 'Aguardando confirmação do pagamento', 'polen' );

		$this->subject     = sprintf( _x( '[%s] Aguardando confirmação do pagamento', 'E-mail que será enviado ao usuário quando um pedido é está aguardando confirmação de pagamento.', 'polen' ), '{blogname}' );
    
		$this->template_html  = 'emails/Polen_WC_Payment_in_Revision.php';
		$this->template_plain = 'emails/plain/Polen_WC_Payment_in_Revision.php';
		$this->template_base  = TEMPLATEPATH . 'woocommerce/';
    
		add_action( 'woocommerce_order_status_pending_to_payment-in-revision_notification', array( $this, 'trigger' ) );

		parent::__construct();
    }
    
}