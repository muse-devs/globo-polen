<?php

namespace Polen\Includes;

use \Polen\Admin\Polen_Admin;

class Polen_Checkout
{

    private $table_talent;

    public function __construct( $static = false ) {
        if( $static ) {
            //add_action( 'woocommerce_edit_account_form_start', array( $this, 'add_cpf_to_form' ) );
            //add_action( 'woocommerce_edit_account_form_start', array( $this, 'add_phone_to_form' ) );
            //add_filter( 'woocommerce_save_account_details', array( $this, 'save_account_details' ) );
            //add_action( 'woocommerce_before_checkout_billing_form', array( $this, 'add_cpf_and_phone_to_checkout') );
            //add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'save_order_meta_from_checkout' ) );
            add_filter( 'woocommerce_checkout_fields', array( $this, 'remove_woocommerce_fields' ) );
            add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
            add_filter( 'the_title',  array( $this, 'remove_thankyou_title' ), 20, 2 );
        }
    }

    public function remove_woocommerce_fields( $fields ) {
        $removed_keys = array(
            'billing_email',
            'billing_first_name',
            'billing_last_name',
            'billing_company',
            'billing_phone',
            'billing_address_1',
            'billing_address_2',
            'billing_city',
            'billing_postcode',
            'billing_country',
            'billing_state',
        );

        foreach( $removed_keys as $key ) {
            unset( $fields['billing'][$key] );
        }
        
        return $fields;
    }

 
    /**
     * Add CPF to user account form
     */
    public function add_cpf_to_form() {
        $user = wp_get_current_user();

        if( is_account_page() ) {
            ?>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="billing_cpf">
                    <?php _e( 'CPF', 'cubo9-marketplace' ); ?> <span class="required">*</span></label>
                    <input type="text" class="woocommerce-Input input-text" name="billing_cpf" id="billing_cpf" value="<?php echo esc_attr( $user->billing_cpf ); ?>" />
                <div class="error-message"></div>
            </p>
        <?php
        } else {
            if( ! empty( $user->billing_cpf ) ) {
            ?>
                <input 	type="hidden" class="woocommerce-Input input-text" name="billing_cpf" id="billing_cpf" value="<?php echo esc_attr( $user->billing_cpf ); ?>" />
                <div class="error-message"></div>
            <?php
            } else {
                ?>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="billing_cpf"><?php _e( 'CPF', 'woocommerce' ); ?> <span class="required">*</span></label>
                    <input type="text" class="woocommerce-Input input-text" name="billing_cpf" id="billing_cpf" value="<?php echo esc_attr( $user->billing_cpf ); ?>" />
                    <div class="error-message"></div>
                </p>
            <?php
            }
        }   
    }

    /**
     * Adicionar o campo de CPF no Checkout para caso o usuário não possua.
     */
    public function add_cpf_and_phone_to_checkout( $checkout ) {
        /*
        $billing_cpf = get_user_meta( get_current_user_id(), 'billing_cpf', true );
        if( ! $billing_cpf || is_null( $billing_cpf ) || empty( $billing_cpf ) || strlen( $billing_cpf ) != 14 ) {
            $args = array(
                "type"        => "text",
                "required"    => true,
                "class"       => array( "form-row-wide", "input-cpf" ),
                "label"       => "CPF",
                "label_class" => array( 'title-on-checkout-notes' ),
                "placeholder" => "Informe seu CPF",
                "maxlength"   => 14,
            );
            woocommerce_form_field( 'billing_cpf', $args, $checkout->get_value( 'billing_cpf' ) );
        }
        */
        $billing_phone = get_user_meta( get_current_user_id(), 'billing_phone', true );
        if( ! $billing_phone || is_null( $billing_phone ) || empty( $billing_phone ) || strlen( $billing_phone ) != 14 ) {
            $args = array(
                "type"        => "text",
                "required"    => true,
                "class"       => array( "form-row-wide", "input-cpf" ),
                "label"       => "Telefone",
                "label_class" => array( 'title-on-checkout-notes' ),
                "placeholder" => "Informe seu Telefone",
                "maxlength"   => 14,
            );
            woocommerce_form_field( 'billing_phone', $args, $checkout->get_value( 'billing_phone' ) );
        }

    }

    /**
     * Salvar o campo de CPF do usuário no Checkout para caso o usuário não possua.
     */
    public function save_order_meta_from_checkout( $order_id ) {
        $billing_cpf = get_user_meta( $_customer_user, 'billing_cpf', true );
        if( ( ! $billing_cpf || is_null( $billing_cpf ) || empty( $billing_cpf ) || strlen( $billing_cpf ) != 14 )
            && ( isset( $_POST['billing_cpf'] ) && ! empty( trim( $_POST['billing_cpf'] ) ) && strlen( trim( $_POST['billing_cpf'] ) ) == '14' ) 
        ) {
            $_customer_user = get_post_meta( $order_id, '_customer_user', true );
            update_user_meta( $_customer_user, 'billing_cpf', trim( $_POST['billing_cpf'] ) );
            update_post_meta( $order_id, 'billing_cpf', trim( $_POST['billing_cpf'] ) );
        } else if( $billing_cpf && ! is_null( $billing_cpf ) && ! empty( $billing_cpf ) && strlen( $billing_cpf ) == 14 ) {
            update_post_meta( $order_id, 'billing_cpf', $billing_cpf );
        }
    }

    public function add_phone_to_form() {
        $user = wp_get_current_user();

        if( is_account_page() ) {
            ?>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="billing_phone"><?php _e( 'Celular', 'woocommerce' ); ?> <span class="required">*</span></label>
                <input type="text" class="woocommerce-Input input-text" name="billing_phone" id="billing_phone" value="<?php echo esc_attr( $user->billing_phone ); ?>" />
                <div class="error-message"></div>
            </p>
        <?php
        } else {
            if( ! empty( $user->billing_phone ) ) {
            ?>
                <input 	type="hidden" class="woocommerce-Input input-text" name="billing_phone" id="billing_phone" value="<?php echo esc_attr( $user->billing_phone ); ?>" />
                <div class="error-message"></div>
            <?php
            } else {
                ?>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="billing_phone"><?php _e( 'Celular', 'woocommerce' ); ?> <span class="required">*</span></label>
                    <input type="text" class="woocommerce-Input input-text" name="billing_phone" id="billing_phone" value="<?php echo esc_attr( $user->billing_phone ); ?>" />
                    <div class="error-message"></div>
                </p>
            <?php
            }
        }   
    }

    public function save_account_details( $user_id ) {
        //update_user_meta( $user_id, 'billing_cpf', sanitize_text_field( $_POST['billing_cpf'] ) );
        update_user_meta( $user_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
    }

    public function remove_thankyou_title( $title, $id ) {
        if ( ( is_order_received_page() && get_the_ID() === $id ) || is_account_page() ) {
            $title = '';
        }
        return $title;
    }
}
