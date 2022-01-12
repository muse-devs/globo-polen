<?php

namespace Polen\Includes;

use Polen\Includes\Cart\Polen_Cart_Item_Factory;
use Polen\Includes\Module\Polen_Order_Module;

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
		$this->title_ep       = __( ' aceitou fazer seu vídeo-autógrafo', 'polen' );
		$this->description = __( 'E-mail que será enviado ao usuário quando o talento aceitar o pedido.', 'polen' );
		$this->customer_email = true;
		$this->heading     = __( 'O talento aceitou', 'polen' );
		// $this->heading_ep     = __( '%s aceitou', 'polen' );

		$this->subject     = sprintf( _x( '[%s] O talento aceitou', 'E-mail que será enviado ao usuário quando o talento aceitar o pedido.', 'polen' ), '{blogname}' );
		// $this->subject_ep  = 'Lacta - Pedido de vídeo aceito' ;
    
		$this->template_html  = 'emails/Polen_WC_Talent_Accepted.php';
		$this->template_plain = 'emails/plain/Polen_WC_Talent_Accepted.php';

		// $this->template_ep_html  = 'emails/video-autografo/%s/Polen_WC_Talent_Accepted.php';
		$this->campaign_template_html = 'emails/campaign/%s/Polen_WC_Talent_Accepted.php';
		$this->template_base  = TEMPLATEPATH . '/woocommerce/';
    
		add_action( 'woocommerce_order_status_changed', array( $this, 'trigger' ), 10, 1 );

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

            /**
             * Não disparar email caso flag no_send_email estiver marcada
             */

			// if( !( defined('DOING_AJAX') ) ) {
			// 	if (is_admin() === true && get_post_meta($order_id, 'send_email', true) != 1) {
			// 		return;
			// 	}
			// }
			
			$cart_item = Polen_Cart_Item_Factory::polen_cart_item_from_order( $this->object );
			$this->product = $cart_item->get_product();

			// $order_is_ep = event_promotional_order_is_event_promotional( $this->object );
			// if( $order_is_ep ) {
			// 	$this->send( $this->get_recipient(), $this->get_subject_ep(), $this->get_content_ep_html(), $this->get_headers(), $this->get_attachments() );
			// } else {
			// 	$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
			// }
			$order_is_campaing = Polen_Campaign::get_is_order_campaing( $this->object );
			if( $order_is_campaing) {
				$this->send( $this->get_recipient(), $this->get_subject_campaing(), $this->get_content_campaign_html(), $this->get_headers(), $this->get_attachments() );
			} else {
				$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
			}
		}
	}

	public function get_subject_campaing()
	{
		return 'O talento aceitou fazer seu vídeo';
	}

	// public function get_subject_ep()
	// {
	// 	$author = $this->product->get_meta( '_promotional_event_author', true );
	// 	return $this->subject_ep;
	// }

	// public function get_heading_ep()
	// {
	// 	$author = $this->product->get_meta( '_promotional_event_author', true );
	// 	return sprintf( $this->heading_ep, $author );
	// }

	// public function get_content_ep_html() {
	// 	$polen_order = new Polen_Order_Module( $this->object );
	// 	$file_templete = sprintf( $this->template_ep_html, $polen_order->get_campaign_slug() );
	// 	return wc_get_template_html( $file_templete, array(
	// 		'order'         => $this->object,
	// 		'email_heading' => $this->get_heading_ep(),
	// 		'sent_to_admin' => true,
	// 		'plain_text'    => false,
	// 		'email'			=> $this
	// 	), '', $this->template_base );
	// }

	public function get_content_campaign_html() {
		$slug_campaign = Polen_Campaign::get_order_campaing_slug( $this->object );
		$file_templete = sprintf( $this->campaign_template_html, $slug_campaign );
		return wc_get_template_html( $file_templete, array(
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