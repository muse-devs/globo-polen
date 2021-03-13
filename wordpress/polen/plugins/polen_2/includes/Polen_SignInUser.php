<?php

namespace Polen\Includes;

class Polen_SignInUser
{
    public function __construct() {
        add_action( 'wp_logout', array( $this, 'polen_logout_redirect' ) );
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


    function update_user_date($customer_id, $new_customer_data, $password_generated){
        wp_update_user(['ID' => $customer_id, 'display_name' => $_POST['name']]);
        update_user_meta($customer_id, 'first_name', $_POST['name']);
        update_user_meta($customer_id, '_phone', $_POST['phone']);
    }

    public function polen_logout_redirect() {
            wp_redirect( home_url() );
            exit();
    }
}