<?php
namespace Polen\Includes\Emails;

class Polen_WC_Completed_Order extends \WC_Email_Customer_Completed_Order
{
    public function __construct() {
        $this->id             = 'customer_completed_order';
        $this->customer_email = true;
        $this->title          = __( 'Completed order', 'woocommerce' );
        $this->description    = __( 'Order complete emails are sent to customers when their orders are marked completed and usually indicate that their orders have been shipped.', 'woocommerce' );
        $this->template_html  = 'emails/customer-completed-order.php';
        $this->template_plain = 'emails/plain/customer-completed-order.php';
        
        $this->template_ep_html = 'emails/video-autografo/customer-completed-order.php';

        $this->placeholders   = array(
            '{order_date}'   => '',
            '{order_number}' => '',
        );

        // Triggers for this email.
        add_action( 'woocommerce_order_status_completed_notification', array( $this, 'trigger' ), 10, 2 );

        // Call parent constructor.
        parent::__construct();
    }

    /**
     * Trigger the sending of this email.
     *
     * @param int            $order_id The order ID.
     * @param WC_Order|false $order Order object.
     */
    public function trigger( $order_id, $order = false ) {
        $this->setup_locale();

        if ( $order_id && ! is_a( $order, 'WC_Order' ) ) {
            $order = wc_get_order( $order_id );
        }

        if ( is_a( $order, 'WC_Order' ) ) {
            $this->object                         = $order;
            $this->recipient                      = $this->object->get_billing_email();
            $this->placeholders['{order_date}']   = wc_format_datetime( $this->object->get_date_created() );
            $this->placeholders['{order_number}'] = $this->object->get_order_number();
        }

        $is_event_promotional = event_promotional_order_is_event_promotional( $order );
        if ( $this->is_enabled() && $this->get_recipient() ) {
            if( $is_event_promotional ) {
                $this->send( $this->get_recipient(), $this->get_subject_ep(), $this->get_content_ep(), $this->get_headers(), $this->get_attachments() );
            } else {
                $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
            }
        }

        $this->restore_locale();
    }

   public function get_subject_ep() {
       return 'Seu Vídeo-Autógrafo está pronto.';
   }

   public function get_content_ep()
   {
        return wc_get_template_html(
            $this->template_ep_html,
            array(
                'order'              => $this->object,
                'email_heading'      => $this->get_heading(),
                'additional_content' => $this->get_additional_content(),
                'sent_to_admin'      => false,
                'plain_text'         => false,
                'email'              => $this,
            )
        );
   }
}