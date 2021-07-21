<?php

namespace Polen\Admin;

use Polen\Includes\Cart\Polen_Cart_Item_Factory;
use Polen\Includes\Polen_Cart;

class Polen_Admin_Order_Custom_Fields
{

    const NONCE_ACTION = 'polen_edit_order_custom_fields';

    public function __construct( $static = false )
    {
        add_action( 'wp_ajax_polen_edit_order_custom_fields', [ $this, 'edit_order_custom_fields' ] );
        add_action( 'wp_ajax_nopriv_polen_edit_order_custom_fields', [ $this, 'edit_order_custom_fields' ] );
    }

    public function edit_order_custom_fields()
    {
        $field = filter_input( INPUT_POST, 'field' );
        $this->validate_field_is_valid( $field );
        $new_value = filter_input( INPUT_POST, 'value' );
        
        $nonce = filter_input( INPUT_POST, 'security' );
        $this->validate_nonce( $nonce, self::NONCE_ACTION );
        
        $order_id = filter_input( INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT );
        $order = wc_get_order( $order_id );
        $this->validate_order_valid( $order );

        $item_cart = Polen_Cart_Item_Factory::polen_cart_item_from_order( $order );
        $item_order = $item_cart->get_item_order();
        $item_order->update_meta_data( $field, $new_value, true );
        $item_order->save();
        wp_send_json_success( 'editado com sucesso', 200 );
    }


    /**
     * Retona um error no WP_SEND_JSON_ERROR
     */
    public function return_error( $msg, $error_no = 500 )
    {
        wp_send_json_error( $msg, $error_no );
        wp_die();
    }


    /**
     * Validacao nonce Correto
     */
    public function validate_nonce( $nonce, $action )
    {
        if( !wp_verify_nonce( $nonce, $action ) ) {
            $this->return_error( 'Validação nonce inválida', 403 );
        }
    }


    /**
     * Validacao Order existe
     */
    public function validate_order_valid( $order )
    {
        if( empty( $order ) ) {
            $this->return_error( 'Order inválida', 404 );
        }
    }


    /**
     * Validar se o fields a ser editado existe nos custom Fields
     */
    public function validate_field_is_valid( $field )
    {
        if( !in_array( $field, Polen_Cart::ALLOWED_ITEM ) ) {
            $this->return_error( 'field é inválido', 403 );
        }
    }
}