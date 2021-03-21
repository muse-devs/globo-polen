<?php

namespace Polen\Includes;

class Polen_Cart
{
    
    public function __construct( $static = false ) {
        if( $static ) {
            add_action( 'wp_ajax_polen_update_cart_item', array( $this, 'polen_update_cart_item' ) );
            add_action( 'wp_ajax_polen_update_cart_item', array( $this, 'polen_update_cart_item' ) );
            add_action( 'wp_ajax_nopriv_polen_update_cart_item', array( $this, 'polen_update_cart_item' ) );
            add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'polen_cart_line_item' ), 10, 4 );
            add_action( 'polen_before_cart', array( $this, 'polen_save_cart' ), 10 );
            add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'empty_before_add_to_cart' ), 20 );
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

    public function polen_save_cart(){
		wc_nocache_headers();
        
		$nonce_value = wc_get_var( $_REQUEST['woocommerce-cart-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.
        if ( wp_verify_nonce( $nonce_value, 'woocommerce-cart' ) ) {    
			$cart_updated = false;
			$cart_totals  = isset( $_POST['cart'] ) ? wp_unslash( $_POST['cart'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			if ( ! WC()->cart->is_empty() && is_array( $cart_totals ) ) {
				foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
                    $cart = WC()->cart->cart_contents;
                    $cart_id = $cart_item_key;
            
                    $cart_item = $cart[$cart_id];

                    $allowed_item = [ 'offered_by', 'video_to', 'name_to_video', 'email_to_video', 'video_category', 'instructions_to_video', 'allow_video_on_page' ];
                    foreach( $allowed_item as $p_item ):
                        if( isset( $_POST[$p_item] ) ){
                            $item_name = $p_item;
                            $item_data = $_POST[$p_item];
                            $cart_item[$item_name] = $item_data;
                        }    
                    endforeach;
            
                    WC()->cart->cart_contents[$cart_id] = $cart_item;
                    WC()->cart->set_session();

                    $cart_updated = true;
				}
			}
			$cart_updated = apply_filters( 'woocommerce_update_cart_action_cart_updated', $cart_updated );
            WC()->cart->calculate_totals();
		}
    }

    public function empty_before_add_to_cart( $passed ) {
        if( !WC()->cart->is_empty() ){
            WC()->cart->empty_cart();
        }    
        return $passed;
    }

}
