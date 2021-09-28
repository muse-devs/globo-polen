<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
   exit;
}

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
   echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
   return;
}

?>

<div class="row">
    <div class="col-12">
        <?php
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) : ?>
                <div class="course-card">
                    <div class="course-card__header">
                        <div class="course-card__image">
                            <img src="<?php echo get_the_post_thumbnail_url($_product->get_id()); ?>"
                                 alt="<?php echo $_product->get_name(); ?>">
                        </div>
                        <p><?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)) . '&nbsp;'; ?></p>
                    </div>
                    <div class="course-card__price">
                        <p>Você vai pagar</p>
                        <div class="course-card__value">
                            <?php $subtotal = apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                            <?php if (WC()->cart->get_coupons()) : ?>
                                <s>
                                    <?php echo wc_price($_product->get_regular_price()); ?>
                                </s>
                                <?php wc_cart_totals_order_total_html(); ?>
                            <?php else:  ?>
                                <?php echo $subtotal; ?>
                            <?php endif ?>
                        </div>
                    </div>
                </div>

                <?php
            endif;
        endforeach;
        ?>

    </div>
    <div class="col-12">
        <?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>
    </div>
    <div class="col-12">
        <form name="checkout"
            method="post" class="checkout woocommerce-checkout"
            action="<?php echo esc_url( wc_get_checkout_url() ); ?>"
            enctype="multipart/form-data">

            <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

            <h3 id="order_review_heading">Forma de pagamento</h3>

            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

            <div id="order_review" class="woocommerce-checkout-review-order">
                <?php do_action( 'woocommerce_checkout_order_review' ); ?>
            </div>

            <?php if ( $checkout->get_checkout_fields() ) : ?>

                <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                <div class="row">
                    <div class="col-12">
                        <?php do_action( 'woocommerce_checkout_billing' ); ?>
                    </div>
                </div>

                <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

            <?php endif; ?>

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
                <p>O prazo para pagamento do boleto é <?php echo date('d-m-Y', strtotime('+1 days', current_time('timestamp'))); ?></p>
            </div>

            <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

            <button type="submit"
                    class="button alt" 
                    name="woocommerce_checkout_place_order"
                    id="place_order" value="Finalizar compra"
                    data-value="Finalizar compra"
                    style="width:100%">Finalizar compra</button>

        </form>
        <br><br>
    </div>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>