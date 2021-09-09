<?php
/**
 * Checkout terms and conditions area.
 *
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( apply_filters( 'woocommerce_checkout_show_terms', true ) && function_exists( 'wc_terms_and_conditions_checkbox_enabled' ) ) {
	do_action( 'woocommerce_checkout_before_terms_and_conditions' );

	?>

    <div class="woocommerce-terms-and-conditions-wrapper">
		<?php
		/**
		 * Terms and conditions hook used to inject content.
		 *
		 * @since 3.4.0.
		 * @hooked wc_checkout_privacy_policy_text() Shows custom privacy policy text. Priority 20.
		 * @hooked wc_terms_and_conditions_page_content() Shows t&c page content. Priority 30.
		 */
		do_action( 'woocommerce_checkout_terms_and_conditions' );
		?>

        <div class="order-terms">
            <div class="order-terms__checkbox">
                <input type="checkbox" name="terms" required="required" id="terms">
                <label for="terms">Aceito os <a href="#">termos de uso</a></label>
            </div>
            <div class="order-terms__checkbox">
                <input type="checkbox" name="info" id="info" required="required">
                <label for="info">Aceito receber informações da Polen</label>
            </div>
        </div>

        <div class="order-info" id="pix-payment-custom" style="display: none;">
            <p>Copie o código Pix na próxima etapa e faça o pagamento na instituição financeira de sua escolha. O código tem validade de 1 dia.</p>
        </div>

        <div class="order-info" id="bolet-payment-custom" style="display: none;">
            <p><strong>Curso será disponibilizado após o pagamento</strong></p>
            <p>O prazo para pagamento do boleto é <?php echo date('Y-m-d', strtotime('+1 days', current_time('timestamp'))); ?></p>
        </div>

	</div>
	<?php

	do_action( 'woocommerce_checkout_after_terms_and_conditions' );
}
