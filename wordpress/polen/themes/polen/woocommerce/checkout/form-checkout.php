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

if (!defined('ABSPATH')) {
	exit;
}
do_action('polen_before_cart');
do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
	echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
	return;
}

use Polen\Includes\Polen_Update_Fields;

$Talent_Fields = new Polen_Update_Fields();
?>

<div class="row mt-2">
	<div class="col-12">
		<div class="progress" style="height: 7px;">
			<div class="progress-bar bg-primary" role="progressbar" style="width: 95%;" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
	</div>
</div>

<?php
foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
    $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
    $talent_id = get_post_field('post_author', $product_id);
    $thumbnail = wp_get_attachment_image_src($_product->get_image_id(), 'thumbnail')[0];
    $talent = get_user_by('id', $talent_id);

    $update_fields = new Polen_Update_Fields();
    $talent_data = $update_fields->get_vendor_data( $talent_id );

    $talent_cart_detail = array(
        "has_details" => true,
        "avatar" => $thumbnail,
        "name" => $_product->get_title(),
        "career" => $talent_data->profissao,
        "price" => $_product->get_price_html(),
        "from" => "",
        "to" => "",
        "category" => "",
        "mail" => "",
        "description" => ""
    );
}
polen_get_talent_card( $talent_cart_detail ); ?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-8">
			<?php
				if( is_user_logged_in() && get_current_user_id() > 0 ){ ?>
					<!-- <h3>Você está logado como:</h3> -->
			<?php   $user_id = get_current_user_id();
					$user_data = get_userdata( $user_id );
					// echo $user_data->display_name;
				}
			?>
			<!-- Cabeçalho do Artista -->
			<?php
			/*
			foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
				$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
				$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
				$talent_id = get_post_field( 'post_author', $product_id );
				$talent_data = $Talent_Fields->get_vendor_data( $talent_id );

				if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) :
					$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
			?>
					<div class="row py-4 px-3 cart-item">
						<div class="col-md-7 d-flex justify-content-start align-items-center">
							<figure class="thumbnail mr-4">
								<?php
								$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

								if (!$product_permalink) {
									echo $thumbnail; // PHPCS: XSS ok.
								} else {
									printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
								}
								?>
							</figure>
							<div class="cart-item-product">
								<div class="product-name text-truncate" data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
									<?php
									if (!$product_permalink) {
										echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
									} else {
										echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
									}

									do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

									// Meta data.
									echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.

									// Backorder notification.
									if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
										echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
									}
									?>
									<div class="product-price" data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
										<?php
										echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-5 mt-3">
							<div class="row">
								<div class="col skill-title">Responde em</div>
								<div class="col skill-title">Reviews</div>
							</div>
							<div class="row">
								<div class="col"><?php polen_icon_clock(); ?> <?php echo $talent_data->tempo_resposta; ?>h</div>
								<div class="col"><?php polen_icon_star(true); ?> 5.0</div>
							</div>
						</div>
						<div class="product-quantity" data-title="<?php esc_attr_e('Quantity', 'woocommerce'); ?>">
							<?php
							$product_quantity = sprintf('<input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
							echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
							?>
						</div>
					</div>
				<?php endif; ?>
			<?php endforeach; */
			?>

			<?php if ($checkout->get_checkout_fields()) : ?>

				<?php do_action('woocommerce_checkout_before_customer_details'); ?>

				<div class="row" id="customer_details">
					<div class="col-12">
						<?php do_action('woocommerce_checkout_billing'); ?>
					</div>

					<div class="col-12">
						<?php do_action('woocommerce_checkout_shipping'); ?>
					</div>
				</div>

				<?php do_action('woocommerce_checkout_after_customer_details'); ?>

			<?php endif; ?>

			<?php woocommerce_checkout_payment();?>

		</div>
		<div class="col-md-4" style="display: none;">

			<?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

			<h3 id="order_review_heading" class="title-alt"><?php esc_html_e('Resumo da compra', 'polen'); ?></h3>

			<?php do_action('woocommerce_checkout_before_order_review'); ?>

			<?php
			/* pagamento está na mesma action do review
			<div id="order_review" class="woocommerce-checkout-review-order">
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
			</div>
				*/
			woocommerce_order_review();
			?>
			<?php do_action('woocommerce_checkout_after_order_review'); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-8">
			<?php
			//woocommerce_checkout_payment();
			?>
		</div>
	</div>
</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
