<?php
namespace Polen\Includes\Emails;

use Polen\Includes\Cart\Polen_Cart_Item_Factory;
use Polen\Includes\Module\Polen_Order_Module;
use Polen\Includes\Polen_Video_Info;
use Polen\Social_Base\Social_Base_Order;

class Polen_WC_Completed_Order extends \WC_Email_Customer_Completed_Order
{
    public function __construct() {
        $this->id             = 'customer_completed_order';
        $this->customer_email = true;
        $this->title          = __( 'Completed order', 'woocommerce' );
        $this->description    = __( 'Order complete emails are sent to customers when their orders are marked completed and usually indicate that their orders have been shipped.', 'woocommerce' );
        $this->template_html  = 'emails/customer-completed-order.php';
        $this->template_plain = 'emails/plain/customer-completed-order.php';
        
        $this->template_ep_html          = 'emails/video-autografo/%s/customer-completed-order.php';
        $this->template_social_base_html = 'emails/social-base/%s/customer-completed-order.php';

        $this->placeholders   = array(
            '{order_date}'   => '',
            '{order_number}' => '',
        );
        $this->template_base  = TEMPLATEPATH . '/woocommerce/';
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

        /**
         * Não disparar email caso flag no_send_email estiver marcada
         */


        if ( $order_id && ! is_a( $order, 'WC_Order' ) ) {
            $order = wc_get_order( $order_id );
        }

        if ( is_a( $order, 'WC_Order' ) ) {
            $this->object                         = $order;
            $this->recipient                      = $this->object->get_billing_email();
            $this->placeholders['{order_date}']   = wc_format_datetime( $this->object->get_date_created() );
            $this->placeholders['{order_number}'] = $this->object->get_order_number();
        } else {
            return;
        }

        $cart_item = Polen_Cart_Item_Factory::polen_cart_item_from_order( $this->object );
        $this->product = $cart_item->get_product();

        $is_event_promotional = event_promotional_order_is_event_promotional( $order );
        $is_social_base = order_is_social_base( $order );
        if ( $this->is_enabled() && $this->get_recipient() ) {
            if( $is_event_promotional ) {
                $this->send( $this->get_recipient(), $this->get_subject_ep(), $this->get_content_ep(), $this->get_headers(), $this->get_attachments() );
            } elseif( $is_social_base ) {
                $this->send( $this->get_recipient(), $this->get_subject_social_base(), $this->get_content_social_base(), $this->get_headers(), $this->get_attachments() );
            } else {
                $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
            }
        }

        $this->restore_locale();
    }

   public function get_subject_ep() {
       return 'Seu vídeo chegou!';
   }

   public function get_subject_social_base() {
       return 'Sua compra na Reserva veio com um 🎁!';
   }

   public function get_content_ep()
   {
        $polen_order = new Polen_Order_Module( $this->object );
        $file_templete = sprintf( $this->template_ep_html, $polen_order->get_campaing_slug() );
        return wc_get_template_html(
            $file_templete,
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

   public function get_content_social_base()
   {

        $video_info = Polen_Video_Info::get_by_order_id( $this->object->get_id() );
        $video_url = site_url( 'v/' . $video_info->hash );
        $social_campaing_name = $this->object->get_meta( Social_Base_Order::ORDER_META_KEY_CAMPAING, true );
        return wc_get_template_html(
            sprintf( $this->template_social_base_html, $social_campaing_name ),
            array(
                'order'              => $this->object,
                'video_url'          => $video_url,
                'email_heading'      => $this->get_heading(),
                'additional_content' => $this->get_additional_content(),
                'sent_to_admin'      => false,
                'plain_text'         => false,
                'email'              => $this,
            )
        );
   }
}