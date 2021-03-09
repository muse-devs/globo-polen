<?php

/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart');

use Polen\Includes\Polen_Occasion_List;

$occasion_list = new Polen_Occasion_List();

?>

<span class="step">Passo 1/2</span>

<form class="woocommerce-cart-form mt-5" action="<?php echo esc_url(wc_get_checkout_url()); ?>" method="post">
	<?php do_action('woocommerce_before_cart_table'); ?>


	<?php do_action('woocommerce_before_cart_contents'); ?>
	<?php
	foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
		$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
		$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

		if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) :
			$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
	?>
			<div class="row py-5 px-3 cart-item">
				<div class="col-md-5 d-flex justify-content-start align-items-center">
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
				<div class="col-md-3 mt-3">
					<div class="row">
						<div class="col skill-title">Responde em</div>
						<div class="col skill-title">Reviews</div>
					</div>
					<div class="row">
						<div class="col"><?php polen_icon_clock(); ?> 1h</div>
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
	<?php endforeach; ?>

	<div class="row mt-1 py-5 px-5 cart-other">
		<?php do_action('woocommerce_cart_contents'); ?>
		<div class="col-md-12 px-5">
			<div class="row">
				<div class="col-md-12 mb-4">
					<span class="form-title">Esse vídeo é para:</span>
				</div>
				<div class="col-md-12 mb-2">
					<?php
					$video_to = isset($cart_item['video_to']) ? $cart_item['video_to'] : '';
					$checked_other_one = '';
					if ($video_to == 'other_one') {
						$checked_other_one = 'checked';
					}
					$checked_to_myself = '';
					if ($video_to == 'to_myself') {
						$checked_to_myself = 'checked';
					}

					printf(
						'<input type="radio" class="%s" id="cart_video_to_%s" data-cart-id="%s" name="video_to" value="other_one" %s > Outra pessoa
						<input type="radio" class="%s ml-4" id="cart_video_to_%s" data-cart-id="%s" name="video_to" value="to_myself" %s > Para mim',
						'polen-cart-item-data',
						$cart_item_key,
						$cart_item_key,
						$checked_other_one,
						'polen-cart-item-data',
						$cart_item_key,
						$cart_item_key,
						$checked_to_myself
					);
					?>
				</div>
			</div>

			<div class="row mt-3">
				<div class="col-md-6">
					<?php
					$offered_by = isset($cart_item['offered_by']) ? $cart_item['offered_by'] : '';
					printf(
						'<input type="text" placeholder="Vídeo oferecido por" class="%s form-control form-control-lg" id="cart_offered_by_%s" data-cart-id="%s" name="offered_by" value="%s" />',
						'polen-cart-item-data',
						$cart_item_key,
						$cart_item_key,
						$offered_by,
					);
					?>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<?php
					$name_to_video = isset($cart_item['name_to_video']) ? $cart_item['name_to_video'] : '';
					printf(
						'<input type="text" placeholder="Para quem é o vídeo?" class="%s form-control form-control-lg" id="cart_name_to_video_%s" data-cart-id="%s" name="name_to_video" value="%s" />',
						'polen-cart-item-data',
						$cart_item_key,
						$cart_item_key,
						$name_to_video,
					);
					?>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<?php
					$email_to_video = isset($cart_item['email_to_video']) ? $cart_item['email_to_video'] : '';
					printf(
						'<input type="text" placeholder="E-mail para receber updates" class="%s form-control form-control-lg" id="cart_email_to_video_%s" data-cart-id="%s" name="email_to_video" value="%s" />',
						'polen-cart-item-data',
						$cart_item_key,
						$cart_item_key,
						$email_to_video,
					);
					?>
				</div>
			</div>

			<div class="row mt-3">
				<div class="col-md-12 mb-4">
					<span class="form-title">Qual ocasião do vídeo?</span>
				</div>
				<div class="col-md-6">
					<?php
					$email_to_video = isset($cart_item['email_to_video']) ? $cart_item['email_to_video'] : '';
					printf(
						'<select class="%s form-control form-control-lg" id="cart_video_category_%s" data-cart-id="%s" name="video_category"/>',
						'polen-cart-item-data',
						$cart_item_key,
						$cart_item_key
					);
					echo "<option value=''>Categoria</option>";
					$arr_occasion = $occasion_list->get_occasion();
					foreach ($arr_occasion as $occasion) :
						echo "<option value='" . $occasion->type . "'>" . $occasion->type . "</option>";

					endforeach;
					?>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 mb-4 mt-3">
					<span class="form-title">Instruções para o vídeo</span>
				</div>
				<div class="col-md-6">
					<?php
					$instructions_to_video = isset($cart_item['instructions_to_video']) ? $cart_item['instructions_to_video'] : '';
					printf(
						'<textarea 	name="instructions_to_video" placeholder="Instruções" 
										class="%s form-control form-control-lg" id="cart_instructions_to_video_%s" 
										data-cart-id="%s">%s</textarea>',
						'polen-cart-item-data',
						$cart_item_key,
						$cart_item_key,
						$instructions_to_video,
					);
					?>
				</div>
			</div>
			<div class="row my-3">
				<div class="col-md-12">
					<?php
					$allow_video_on_page = isset($cart_item['allow_video_on_page']) ? $cart_item['allow_video_on_page'] : 'on';
					$checked_allow = '';
					if ($allow_video_on_page == 'on') {
						$checked_allow = 'checked';
					}

					printf(
						'<input type="checkbox" name="allow_video_on_page" class="%s form-control form-control-lg" id="cart_allow_video_on_page_%s" 
										data-cart-id="%s" %s> Permitir que o vídeo seja postado no perfil do artista',
						'polen-cart-item-data',
						$cart_item_key,
						$cart_item_key,
						$checked_allow,
					);
					?>
				</div>
			</div>
			<div class="row actions">
				<div class="col-md-6 mb-4 mt-3">
					<button type="submit" class="btn btn-primary btn-lg btn-block py-4" name="" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Avançar', 'woocommerce'); ?></button>

					<?php //do_action( 'woocommerce_cart_actions' ); 
					?>

					<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
				</div>
			</div>
		</div>
	</div>
	<?php do_action('woocommerce_after_cart_contents'); ?>
	<?php do_action('woocommerce_after_cart_table'); ?>
</form>

<?php do_action('woocommerce_before_cart_collaterals'); ?>

<div class="cart-collaterals">
	<?php
	/**
	 * Cart collaterals hook.
	 *
	 * @hooked woocommerce_cross_sell_display
	 * @hooked woocommerce_cart_totals - 10
	 */
	do_action('woocommerce_cart_collaterals');
	?>
</div>

<?php do_action('woocommerce_after_cart'); ?>