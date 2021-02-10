<?php

function update_user_date($customer_id, $new_customer_data, $password_generated){
	wp_update_user(['ID' => $customer_id, 'display_name' => $_POST['name']]);
	update_user_meta($customer_id, 'first_name', $_POST['name']);
	update_user_meta($customer_id, '_phone', $_POST['phone']);
}
add_filter('woocommerce_created_customer', 'update_user_date', 10, 3);

function add_fields_sign_in(){ ?>
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
add_action('woocommerce_register_form_start', 'add_fields_sign_in', 10, 0);