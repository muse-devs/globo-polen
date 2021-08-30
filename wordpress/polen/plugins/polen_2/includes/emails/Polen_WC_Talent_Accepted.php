<?php

namespace Polen\Includes;

if( ! defined( 'ABSPATH') ) {
    die( 'Silence is golden.' );
}

if ( ! class_exists( 'WC_Email' ) ) {
	return;
}

class Polen_WC_Talent_Accepted extends \WC_Email {

    public function __construct() {
        $this->id          = 'wc_talent_accepted';
		$this->title       = __( 'O talento aceitou', 'polen' );
		$this->title_ep       = __( 'Luciano aceitou fazer seu vídeo-autógrafo', 'polen' );
		$this->description = __( 'E-mail que será enviado ao usuário quando o talento aceitar o pedido.', 'polen' );
		$this->customer_email = true;
		$this->heading     = __( 'O talento aceitou', 'polen' );
		$this->heading     = __( 'Luciano aceitou', 'polen' );

		$this->subject     = sprintf( _x( '[%s] O talento aceitou', 'E-mail que será enviado ao usuário quando o talento aceitar o pedido.', 'polen' ), '{blogname}' );
		$this->subject_ep  = 'Luciano aceitou seu pedido e logo fará seu vídeo-autógrafo' ;
    
		$this->template_html  = 'emails/Polen_WC_Talent_Accepted.php';
		$this->template_plain = 'emails/plain/Polen_WC_Talent_Accepted.php';

		$this->template_ep_html  = 'emails/video-autografo/Polen_WC_Talent_Accepted.php';

		$this->template_base  = TEMPLATEPATH . 'woocommerce/';
    
		add_action( 'woocommerce_order_status_changed', array( $this, 'trigger' ) );

		parent::__construct();
    }

	public function trigger( $order_id ) {
		$this->object = wc_get_order( $order_id );
		if( $this->object->get_status() === Polen_WooCommerce::ORDER_STATUS_TALENT_ACCEPTED ) {
			if ( version_compare( '3.0.0', WC()->version, '>' ) ) {
				$order_email = $this->object->billing_email;
			} else {
				$order_email = $this->object->get_billing_email();
			}

			$this->recipient = $order_email;

			if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
				return;
			}
			$order_is_ep = event_promotional_order_is_event_promotional( $this->object );
			if( $order_is_ep ) {
				$this->send( $this->get_recipient(), $this->subject_ep, $this->get_content_ep_html(), $this->get_headers(), $this->get_attachments() );
			} else {
				$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
			}
		}
	}

	public function get_content_ep_html() {
		return wc_get_template_html( $this->template_ep_html, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => true,
			'plain_text'    => false,
			'email'			=> $this
		), '', $this->template_base );
	}

    public function get_content_html() {
		return wc_get_template_html( $this->template_html, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => true,
			'plain_text'    => false,
			'email'			=> $this
		), '', $this->template_base );
	}

	public function get_content_plain() {
		return wc_get_template_html( $this->template_plain, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => true,
			'plain_text'    => true,
			'email'			=> $this
		), '', $this->template_base );
	}
    
}