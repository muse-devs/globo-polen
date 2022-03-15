<?php

namespace Polen\Admin;

use Polen\Api\Api_Checkout;
use Polen\Includes\Cart\Polen_Cart_Item_Factory;
use Polen\Includes\Debug;
use Polen\Includes\Polen_Utils;
use WC_Order;
use WP_Error;

class Polen_Admin_Order_B2B
{
    public function __construct(bool $static = false)
    {
        if($static) {
            add_action('woocommerce_new_order', [$this, 'new_order_handler'], 10, 3);
            add_action('add_meta_boxes', [$this, 'add_meta_box_handler']);
        }
    }

    /**
     * 
     */
    public function add_meta_box_handler()
    {
        global $current_screen;
        if( $current_screen 
            && ! is_null( $current_screen ) 
            && isset( $current_screen->id )
            && $current_screen->id == 'shop_order' 
            // && isset( $_REQUEST['action'] ) 
            // && $_REQUEST['action'] == 'edit'
        )
        {
            add_meta_box('Polen_order_b2b_fields', 'Campos B2B', [$this, 'add_other_fields_for'], 'shop_order', 'normal', 'default');
        }
    }


    /**
     * 
     */
    public function add_other_fields_for()
    {
        $file = plugin_dir_path(__FILE__) . 'partials/metaboxes/metabox-order-b2b-fields.php';
        if(file_exists($file)) {
            require_once $file;
        }
    }


    /**
     * 
     */
    public function new_order_handler($order_id, WC_Order $order = null)
    {
        $screen = get_current_screen();
        if( is_admin() && $screen->base == 'post' && $screen->post_type == 'shop_order' && current_user_can('manage_options') ) {
            if(empty($order)) {
                $order = wc_get_order($order_id);
            }
            $cart_item = Polen_Cart_Item_Factory::polen_cart_item_from_order($order);
            if(empty($cart_item)) {
                return new WP_Error('no_order_item', 'Compra sem itens, não é possível ');
            }
            $item_order = $cart_item->get_item_order();
            if(empty($item_order)) {
                return new WP_Error('no_order_item', 'Compra sem itens, não é possível(2)');
            }

            $company_name          = sanitize_text_field($_POST['company_name']);
            $corporate_name        = sanitize_text_field($_POST['corporate_name']);
            $cnpj                  = sanitize_text_field($_POST['cnpj']);
            $video_to              = sanitize_text_field($_POST['company_name']);
            $email_to_video        = sanitize_email($order->get_billing_email());
            $instructions_to_video = Polen_Utils::sanitize_xss_br_escape($_POST['instructions_to_video']);
            $video_category        = sanitize_text_field($_POST['video_category']);
            $licence_in_days       = sanitize_text_field($_POST['licence_in_days']);
            
            update_post_meta($order_id, 'b2b', '1');
            update_post_meta($order_id, Api_Checkout::ORDER_METAKEY, 'b2b');
            update_post_meta($order_id, '_billing_cnpj', $cnpj);
            update_post_meta($order_id, '_billing_corporate_name', $corporate_name);
            update_post_meta($order_id, '_billing_company', $company_name);

            wc_add_order_item_meta($item_order->get_id(), 'company_name', $company_name, true);
            wc_add_order_item_meta($item_order->get_id(), 'video_to', $video_to, true);
            wc_add_order_item_meta($item_order->get_id(), 'email_to_video', $email_to_video, true);
            wc_add_order_item_meta($item_order->get_id(), 'instructions_to_video', $instructions_to_video, true);
            wc_add_order_item_meta($item_order->get_id(), 'video_category', $video_category, true);
            wc_add_order_item_meta($item_order->get_id(), 'licence_in_days', $licence_in_days, true);
        }
    }
}
