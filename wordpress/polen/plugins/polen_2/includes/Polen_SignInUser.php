<?php

namespace Polen\Includes;

class Polen_SignInUser
{
    public function __construct() {
        add_action( 'wp_logout', array( $this, 'polen_logout_redirect' ) );
        add_action( 'user_register', array( $this, 'register_check_user_logged_out_orders'), 999, 1 );
    }        

    public function add_fields_sign_in()
    { ?>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="reg_name"><?php esc_html_e( 'Name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="name" id="reg_name" autocomplete="name" value="<?php echo ( ! empty( $_POST['name'] ) ) ? esc_attr( wp_unslash( $_POST['name'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
        </p>
    
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="reg_phone"><?php esc_html_e( 'Phone', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="phone" id="reg_phone" autocomplete="phone" value="<?php echo ( ! empty( $_POST['phone'] ) ) ? esc_attr( wp_unslash( $_POST['phone'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
        </p>
    <?php
    }

    public function update_user_date($customer_id, $new_customer_data, $password_generated){
        wp_update_user(['ID' => $customer_id, 'display_name' => $_POST['name']]);
        update_user_meta($customer_id, 'first_name', $_POST['name']);
        update_user_meta($customer_id, '_phone', $_POST['phone']);
    }

    public function polen_logout_redirect() {
            wp_redirect( home_url() );
            exit();
    }

    public function register_check_user_logged_out_orders( $user_id ) {
        global $wpdb;
        $user = get_user_by( 'id', $user_id );
        if( $user && ! is_null( $user ) && ! empty( $user ) && isset( $user->user_email ) ) {
            $sql_orders = "SELECT `post_id` AS `order_id` FROM `" . $wpdb->postmeta . "` WHERE `post_id` IN ( SELECT `post_id` FROM `" . $wpdb->postmeta . "` WHERE `meta_key`='_billing_email' AND `meta_value`='" . $user->user_email . "' ) AND `meta_key`='_customer_user' AND `meta_value`='0'";
            $res_orders = $wpdb->get_results( $sql_orders );
            if( $res_orders && ! is_null( $res_orders ) && ! empty( $res_orders ) && is_array( $res_orders ) && count( $res_orders ) > 0 ) {
                foreach( $res_orders as $k => $order ) {
                    update_post_meta( $order->order_id, '_customer_user', $user->ID );
                }
            }
        }
    }
}