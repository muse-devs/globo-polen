<?php

namespace Polen\Includes;

class Polen_Cart
{
    
    public function __construct( $static = false ) {
        if( $static ) {
            add_action( 'wp_ajax_polen_update_cart_item', array( $this, 'polen_update_cart_item' ) );
            add_action( 'wp_ajax_nopriv_polen_update_cart_item', array( $this, 'polen_update_cart_item' ) );
            add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'polen_cart_line_item' ), 10, 4 );
        }
    }

    public function polen_update_cart_item() {
        if( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'woocommerce-cart' ) ) {
            wp_send_json( array( 'nonce_fail' => 1 ) );
            exit;
        }
       
        $cart = WC()->cart->cart_contents;
        $cart_id = $_POST['cart_id'];

        $cart_item = $cart[$cart_id];
        if( isset( $_POST['polen_data_name'] ) && !empty( $_POST['polen_data_name'] ) ){
            $item_name = $_POST['polen_data_name'];
            $item_data = $_POST['polen_data_value'];
            $cart_item[$item_name] = $item_data;
        }

        WC()->cart->cart_contents[$cart_id] = $cart_item;
        WC()->cart->set_session();
        wp_send_json( array( 'success' => 1 ) );
        exit;
    }

    public function polen_cart_line_item( $item, $cart_item_key, $values, $order ) {
        foreach( $item as $cart_item_key=>$cart_item ) {
            if( isset( $cart_item['offered_by'] ) ) {
                $item->add_meta_data( 'offered_by', $cart_item['offered_by'], true );
            }
            if( isset( $cart_item['video_to'] ) ) {
                $item->add_meta_data( 'video_to', $cart_item['video_to'], true );
            }
            if( isset( $cart_item['name_to_video'] ) ) {
                $item->add_meta_data( 'name_to_video', $cart_item['name_to_video'], true );
            }
            if( isset( $cart_item['email_to_video'] ) ) {
                $item->add_meta_data( 'email_to_video', $cart_item['email_to_video'], true );
            }
            if( isset( $cart_item['video_category'] ) ) {
                $item->add_meta_data( 'video_category', $cart_item['video_category'], true );
            }            
            if( isset( $cart_item['instructions_to_video'] ) ) {
                $item->add_meta_data( 'instructions_to_video', $cart_item['instructions_to_video'], true );
            }
            if( isset( $cart_item['allow_video_on_page'] ) ) {
                $item->add_meta_data( 'allow_video_on_page', $cart_item['allow_video_on_page'], true );
            }
        }
    }
}
