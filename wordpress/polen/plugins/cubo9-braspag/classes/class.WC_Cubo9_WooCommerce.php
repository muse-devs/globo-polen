<?php

class WC_Cubo9_WooCommerce {

    public function __construct( $static = false ) {
        if( $static ) {
            add_action( 'init', array( $this, 'endpoints' ) );
            add_filter( 'query_vars', array( $this, 'query_vars' ), 0, 1 );
            add_filter( 'woocommerce_account_menu_items', array( $this, 'my_account_tabs' ) );
            add_action( 'woocommerce_account_payment-options_endpoint', array( $this, 'my_account_credit_cards_content' ) );
            add_action( 'wp_ajax_braspag-default', array( $this, 'make_default_payment') );
            add_action( 'wp_ajax_braspag-remove', array( $this, 'remove_payment') );
        }
    }

    function endpoints() {
        add_rewrite_endpoint( 'payment-options', EP_PAGES );
    }
    
    public function query_vars( $query_vars ) {
        $query_vars[] = 'payment-options';
        return $query_vars;
    }

    public function my_account_tabs( $tabs ) {
        $tabs['payment-options'] = __( 'Opções de Pagamento', 'cubonove' );
        return $tabs;
    }

    public function my_account_credit_cards_content() {
        require_once PLUGIN_CUBO9_BRASPAG_DIR . 'assets/php/my-account/payment-options.php';
    }

    public function make_default_payment() {
        $return = array(
            'success' => 0,
        );
        if( is_user_logged_in() && isset( $_POST['id'] ) && strlen( $_POST['id'] ) == 32 ) {
            $braspag_card_saved_data = get_user_meta( get_current_user_id(), 'braspag_card_saved_data' );
            if( ! is_null( $braspag_card_saved_data ) && ! empty( $braspag_card_saved_data ) && is_array( $braspag_card_saved_data ) && count( $braspag_card_saved_data ) > 0 ) {
                $braspag_default_payment = get_user_meta( get_current_user_id(), 'braspag_default_payment', true );
                delete_user_meta( get_current_user_id(), 'braspag_default_payment' );
                foreach( $braspag_card_saved_data as $k => $cards ) {
                    foreach( $cards as $p => $data ) {
                        $prefix = md5( $p );
                        if( ( ! $braspag_default_payment || is_null( $braspag_default_payment ) || empty( $braspag_default_payment ) ) && $prefix == $_POST['id'] && $braspag_default_payment == $_POST['id'] ) {
                            delete_user_meta( get_current_user_id(), 'braspag_default_payment' );
                        } else if( ( ! $braspag_default_payment || is_null( $braspag_default_payment ) || empty( $braspag_default_payment ) ) && $prefix == $_POST['id'] ) {
                            update_user_meta( get_current_user_id(), 'braspag_default_payment', $prefix );
                            $return = array(
                                'success' => 1,
                            );
                        }
                    }
                }
            } else {
                
            }
        }
        echo wp_json_encode( $return );
        die;
    }

    public function remove_payment() {
        $return = array(
            'success' => 0,
        );
        if( is_user_logged_in() && isset( $_POST['id'] ) && strlen( $_POST['id'] ) == 32 ) {
            $braspag_card_saved_data = get_user_meta( get_current_user_id(), 'braspag_card_saved_data' );
            $braspag_default_payment = get_user_meta( get_current_user_id(), 'braspag_default_payment', true );
            if( ! is_null( $braspag_card_saved_data ) && ! empty( $braspag_card_saved_data ) && is_array( $braspag_card_saved_data ) && count( $braspag_card_saved_data ) > 0 ) {
                foreach( $braspag_card_saved_data as $k => $cards ) {
                    foreach( $cards as $p => $data ) {
                        $prefix = md5( $p );
                        if( $prefix == $_POST['id'] ) {
                            unset( $cards[ $p ] );
                            if( $braspag_default_payment && ! is_null( $braspag_default_payment ) && ! empty( $braspag_default_payment ) && $prefix == $braspag_default_payment ) {
                                delete_user_meta( get_current_user_id(), 'braspag_default_payment' );
                            }
                        }
                    }
                }
                update_user_meta( get_current_user_id(), 'braspag_card_saved_data', $cards );
                $return = array(
                    'success' => 1,
                );
            }
        }
        echo wp_json_encode( $return );
        die;
    }
}

new WC_Cubo9_WooCommerce( true );