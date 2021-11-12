<?php
namespace Polen\Includes;

use WC_Customer;
use WP_Error;

/**
 * Verifica se existe um usuario logado na hora do checkout
 * se nao existir verifica se existe uma conta com o email do checkout
 * se nao existir cria um usuário
 */
class Polen_Checkout_Create_User
{
    const META_KEY_CREATED_BY = 'created_by';
    const META_KEY_PHONE = 'billing_phone';

    public function __construct( $static = false )
    {
        if( $static ) {
            add_filter( 'woocommerce_checkout_customer_id', [ $this, 'treat_user_data' ], 10, 1 );
        }
    }

    public function treat_user_data( $user_id )
    {
        $cart_content = WC()->cart->get_cart_contents();
        $item_cart = array_pop( $cart_content );
        if( 0 === $user_id || empty( $user_id ) ) {
            if( empty( $item_cart ) || !isset( $item_cart['email_to_video'] ) ) {
                return new WP_Error(234, __LINE__);
            }
            $user_email = $item_cart['email_to_video'];
            $user = get_user_by( 'email', $user_email );
            if( is_wp_error( $user ) ) {
                return new WP_Error(234, __LINE__);
            }
            if( empty( $user ) ) {
                $user_password = wp_generate_password( 5, false ) . random_int( 0, 99 );
                $id_registered = wc_create_new_customer( $user_email, $user_email, $user_password );
                $user = get_user_by( 'ID', $id_registered );
                add_user_meta( $user->ID, self::META_KEY_CREATED_BY, 'checkout', true );
            } else {
                $customer = new WC_Customer( $user->ID );
                $customer->set_display_name( $item_cart['offered_by'] );
                $customer->save();
                $phone = get_user_meta( $user_id, self::META_KEY_PHONE, true );
                if( empty( $phone ) ) {
                    add_user_meta( $user_id, self::META_KEY_PHONE, $phone, true );
                }
            }
            $user_id = $user->ID;
        } else {
            $customer = new WC_Customer( $user_id );
            $customer->set_display_name( $item_cart['offered_by'] );
            $customer->save();
            $phone = get_user_meta( $user_id, self::META_KEY_PHONE, true );
            if( empty( $phone ) ) {
                add_user_meta( $user_id, self::META_KEY_PHONE, $phone, true );
            }
        }
        return $user_id;
    }
}
