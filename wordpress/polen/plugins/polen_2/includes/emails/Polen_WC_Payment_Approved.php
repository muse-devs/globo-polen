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

class Polen_WC_Payment_Approved extends \WC_Email {

	/**
	 * Email do talento
	 * String
	 */
	public $recipient_talent;

	/**
	 * Assunto do Email do talento
	 * String
	 */
	public $subject_talent;

	private $social_template_html;
	private $social_template_plain;

	/**
	 * Assunto do Email do talento
	 * String
	 */
	public $subject_social;

    public function __construct() {
        $this->id          = 'wc_payment_approved';
		$this->title       = __( 'Pagamento Aprovado', 'polen' );
		$this->description = __( 'E-mail que será enviado ao usuário quando o pagamento do pedido é aprovado.', 'polen' );
		$this->customer_email = true;
		$this->heading     = __( 'Pagamento Aprovado', 'polen' );
		$this->heading_ep     = 'Pedido de vídeo recebido';
		$this->heading_talent_social = __( 'Doação recebida', 'polen' );

		$this->subject     = sprintf( _x( '[%s] Pagamento Aprovado', 'E-mail que será enviado ao usuário quando o pagamento do pedido é aprovado', 'polen' ), '{blogname}' );
    
		$this->talent_template_html  = 'emails/Polen_WC_Payment_Approved_Talent.php';
		$this->talent_social_template_html  = 'emails/Polen_WC_Payment_Approved_Talent_social.php';
		$this->talent_template_plain = 'emails/plain/Polen_WC_Payment_Approved_Talent.php';
		$this->social_template_html  = 'emails/Polen_WC_Payment_Approved_social.php';
		$this->social_template_plain = 'emails/plain/Polen_WC_Payment_Approved_social.php';
		$this->social_template_plain = 'emails/plain/Polen_WC_Payment_Approved_social.php';
		$this->template_html  = 'emails/Polen_WC_Payment_Approved.php';
		$this->template_plain = 'emails/plain/Polen_WC_Payment_Approved.php';
		$this->template_base  = TEMPLATEPATH . '/woocommerce/';

		$this->ep_template_html  = 'emails/video-autografo/%s/Polen_WC_Payment_Approved.php';
		$this->campaign_template_html  = 'emails/campaign/%s/Polen_WC_Payment_Approved.php';

		$this->subject_talent = 'Você está a um passo de receber mais R$!';
		$this->subject_talent_social = 'Recebemos mais uma doação para o Criança Esperança!';
		$this->subject_social = 'Obrigado por ajudar o Criança Esperança.';
		$this->subject_ep = 'Lacta - Pedido de vídeo recebido';
    
		add_action( 'woocommerce_order_status_changed', array( $this, 'trigger' ) );

		parent::__construct();
    }

    public function trigger( $order_id ) {
		$this->object = wc_get_order( $order_id );
		if( $this->object->has_status( 'payment-approved') ) {
			if ( version_compare( '3.0.0', WC()->version, '>' ) ) {
				$order_email = $this->object->billing_email;
			} else {
				$order_email = $this->object->get_billing_email();
			}

			$this->recipient = $order_email;

			if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
				return;
			}
			$cart_item = Polen_Cart_Item_Factory::polen_cart_item_from_order( $this->object );
			$this->product = $cart_item->get_product();

            /**
             * Não disparar email caso flag no_send_email estiver marcada
             */
			if( !( defined('DOING_AJAX') ) ) {
				if (is_admin() === true && get_post_meta($order_id, 'send_email', true) != 1) {
					return;
				}
			}

			// $order_is_social = social_order_is_social( $this->object );
			// $order_is_ep = event_promotional_order_is_event_promotional( $this->object );
			$order_is_campaing = Polen_Campaign::get_is_order_campaing( $this->object );
			// if( $order_is_social ) {
			// 	$this->send( $this->get_recipient(), $this->get_subject_social(), $this->get_content_social(), $this->get_headers(), $this->get_attachments() );
			// } elseif( $order_is_ep ) {
			// 	$this->send( $this->get_recipient(), $this->get_subject_ep(), $this->get_content_ep(), $this->get_headers(), $this->get_attachments() );
			if( $order_is_campaing ) {
				$this->send( $this->get_recipient(), $this->get_subject_campaign(), $this->get_content_campaign(), $this->get_headers(), $this->get_attachments() );
			} else {
				$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
			}

			/**
			 * Envio de e-mail para o Talento
			 */
			foreach ( $this->object->get_items() as $item_id => $item ) {
				$product_id = $item->get_product_id();
			}
			$Polen_Talent = new Polen_Talent();
			$talent = $Polen_Talent->get_talent_from_product( $product_id );
			$this->recipient_talent = $talent->email;
			
			// if( ! $order_is_social ) {
				$this->send( $this->get_recipient_talent(), $this->get_subject_talent(), $this->get_content_talent(), $this->get_headers(), $this->get_attachments() );
			// } else {
			// 	$this->send( $this->get_recipient_talent(), $this->get_subject_talent_social(), $this->get_content_talent_social(), $this->get_headers(), $this->get_attachments() );
			// }
		}
	}

	public function get_recipient_talent()
	{
		return $this->recipient_talent;
	}

	public function get_subject_talent()
	{
		return $this->subject_talent;
	}

	// public function get_subject_talent_social()
	// {
	// 	return $this->subject_talent_social;
	// }

	// public function get_subject_social()
	// {
	// 	return $this->subject_social;
	// }

	// public function get_subject_ep()
	// {
	// 	return $this->subject_ep;
	// }

	public function get_subject_campaign()
	{
		return 'Pagamento Aprovado';
	}

	public function get_content_talent() {
		$this->sending = true;

		if ( 'plain' === $this->get_email_type() ) {
			$email_content = wordwrap( preg_replace( $this->plain_search, $this->plain_replace, wp_strip_all_tags( $this->get_content_talent_plain() ) ), 70 );
		} else {
			$email_content = $this->get_content_talent_html();
		}

		return $email_content;
	}

	// public function get_content_talent_social() {
	// 	$this->sending = true;

	// 	if ( 'plain' === $this->get_email_type() ) {
	// 		$email_content = wordwrap( preg_replace( $this->plain_search, $this->plain_replace, wp_strip_all_tags( $this->get_content_talent_plain() ) ), 70 );
	// 	} else {
	// 		$email_content = $this->get_content_talent_social_html();
	// 	}

	// 	return $email_content;
	// }

	// public function get_content_social() {
	// 	$this->sending = true;

	// 	if ( 'plain' === $this->get_email_type() ) {
	// 		$email_content = wordwrap( preg_replace( $this->plain_search, $this->plain_replace, wp_strip_all_tags( $this->get_content_talent_plain() ) ), 70 );
	// 	} else {
	// 		$email_content = $this->get_content_social_html();
	// 	}

	// 	return $email_content;
	// }

	// public function get_content_ep() {
	// 	$this->sending = true;
	// 	$email_content = $this->get_content_ep_html();
	// 	return $email_content;
	// }

	public function get_content_campaign()
	{
		$email_content = $this->get_content_campaign_html();
		return $email_content;
	}

    public function get_content_talent_html() {
		return wc_get_template_html( $this->talent_template_html, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => true,
			'plain_text'    => false,
			'email'			=> $this
		), '', $this->template_base );
	}

	// public function get_content_talent_social_html() {
	// 	return wc_get_template_html( $this->talent_social_template_html, array(
	// 		'order'         => $this->object,
	// 		'email_heading' => $this->get_heading_social(),
	// 		'sent_to_admin' => true,
	// 		'plain_text'    => false,
	// 		'email'			=> $this
	// 	), '', $this->template_base );
	// }

	// public function get_content_social_html() {
	// 	return wc_get_template_html( $this->social_template_html, array(
	// 		'order'         => $this->object,
	// 		'email_heading' => $this->get_heading(),
	// 		'sent_to_admin' => true,
	// 		'plain_text'    => false,
	// 		'email'			=> $this
	// 	), '', $this->template_base );
	// }

	// public function get_content_ep_html() {
	// 	$polen_order = new Polen_Order_Module( $this->object );
	// 	$file_templete = sprintf( $this->ep_template_html, $polen_order->get_campaign_slug() );
	// 	return wc_get_template_html( $file_templete, array(
	// 		'order'         => $this->object,
	// 		'email_heading' => $this->get_heading(),
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

	public function get_content_talent_plain() {
		return wc_get_template_html( $this->talent_template_plain, array(
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => true,
			'plain_text'    => true,
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

	/**
	 * Get email heading.
	 *
	 * @return string
	 */
	public function get_heading_social() {
		return apply_filters( 'woocommerce_email_heading_' . $this->id, $this->format_string( $this->get_option( 'heading', $this->get_default_heading_social() ) ), $this->object, $this );
	}

	/**
	 * Get email heading.
	 *
	 * @since  3.1.0
	 * @return string
	 */
	public function get_default_heading_social() {
		return $this->heading_talent_social;
	}
    
}
