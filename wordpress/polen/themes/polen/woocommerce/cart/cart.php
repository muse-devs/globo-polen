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
use Polen\Includes\Polen_Update_Fields;

$occasion_list = new Polen_Occasion_List();
$Talent_Fields = new Polen_Update_Fields();
?>

<!-- <div class="row mt-2">
	<div class="col-12">
		<div class="progress" style="height: 7px;">
			<div class="progress-bar bg-primary" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
	</div>
</div> -->
<div class="row">
	<div class="col-12 col-md-6 order-md-2 mt-md-4">
		<?php
		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
			$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
			$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
			$talent_id = get_post_field('post_author', $product_id);
			$thumbnail = wp_get_attachment_image_src($_product->get_image_id(), 'thumbnail')[0];
			$talent = get_user_by('id', $talent_id);

			$talent_data = $Talent_Fields->get_vendor_data($talent_id);

			$talent_cart_detail = array(
				"has_details" => false,
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
	</div>
	<form class="woocommerce-cart-form col-12 col-md-6 order-md-1" action="<?php echo esc_url(wc_get_checkout_url()); ?>" method="post">
		<?php do_action('woocommerce_before_cart_table'); ?>


		<?php do_action('woocommerce_before_cart_contents'); ?>
		<?php
		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
			$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
			$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
			$talent_id = get_post_field('post_author', $product_id);
			$talent_data = $Talent_Fields->get_vendor_data($talent_id);

			if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) :
				$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
		?>
			<?php endif; ?>
		<?php endforeach; ?>

		<div class="row mt-3 py-2 cart-other">
			<?php do_action('woocommerce_cart_contents'); ?>
			<div class="col-12 col-md-12">
				<div class="row d-none">
					<div class="col-md-12 mb-3">
						<span class="form-title">Esse vídeo é para:</span>
					</div>
					<div class="col-md-12 mb-3">
						<?php
						$video_to = isset($cart_item['video_to']) ? $cart_item['video_to'] : '';
						$checked_other_one = '';
						// if ($video_to == 'other_one' || empty( $video_to )) {
						// 	$checked_other_one = 'checked';
						// }
						// $checked_to_myself = '';
						// if ($video_to == 'to_myself') {
						// 	$checked_to_myself = 'checked';
						// }
						$checked_to_myself = 'checked';
						?>
						<label for="cart_video_to_<?php echo $cart_item_key; ?>_other">
							<input type="radio" class="polen-cart-item-data cart-video-to" id="cart_video_to_<?php echo $cart_item_key; ?>_other" data-cart-id="<?php echo $cart_item_key; ?>" name="video_to" value="other_one" <?php echo $checked_other_one; ?> /><span class="ml-2">Outra pessoa</span>
						</label>
						<label for="cart_video_to_<?php echo $cart_item_key; ?>_me">
							<input type="radio" class="polen-cart-item-data cart-video-to ml-4" id="cart_video_to_<?php echo $cart_item_key; ?>_me" data-cart-id="<?php echo $cart_item_key; ?>" name="video_to" value="to_myself" <?php echo $checked_to_myself; ?> /><span class="ml-2">Para mim</span>
						</label>
					</div>
				</div>

				<div class="row video-to-info mb-3 d-none">
					<div class="col-12 col-md-12">
						<?php
						$offered_by = isset($cart_item['offered_by']) ? $cart_item['offered_by'] : '';
						?>
						<label for="<?php echo 'cart_offered_by_' . $cart_item_key; ?>">Vídeo oferecido por</label>
						<?php
						printf(
							'<input type="text" placeholder="Vídeo oferecido por" class="%s form-control form-control-lg" id="cart_offered_by_%s" data-cart-id="%s" name="offered_by" value="%s" required="required" />',
							'polen-cart-item-data',
							$cart_item_key,
							$cart_item_key,
							$offered_by,
						);
						?>
					</div>
				</div>

				<div class="row">
					<div class="col-12 col-md-12">
						<?php
						$name_to_video = isset($cart_item['name_to_video']) ? $cart_item['name_to_video'] : '';
						?>
						<label for="<?php echo 'cart_name_to_video_' . $cart_item_key; ?>">Nome</label>
						<?php
						printf(
							'<input type="text" placeholder="Para quem é esse vídeo-polen" class="%s form-control form-control-lg" id="cart_name_to_video_%s" data-cart-id="%s" name="name_to_video" value="%s" required="required"/>',
							'polen-cart-item-data',
							$cart_item_key,
							$cart_item_key,
							$name_to_video,
						);
						?>
					</div>
				</div>

				<?php
				if (is_user_logged_in()) {
					$current_user = wp_get_current_user();
					$email_to_video = $current_user->user_email;
					printf(
						'<input type="hidden" class="%s" id="cart_email_to_video_%s" data-cart-id="%s" name="email_to_video" value="%s" required="required" />',
						'polen-cart-item-data',
						$cart_item_key,
						$cart_item_key,
						$email_to_video,
					);
				?>
				<?php } else { ?>
					<div class="row mt-3">
						<div class="col-12 col-md-12">
							<?php
							$email_to_video = isset($cart_item['email_to_video']) ? $cart_item['email_to_video'] : '';
							?>
							<label for="<?php echo 'cart_email_to_video_' . $cart_item_key; ?>">e-mail</label>
							<?php
							printf(
								'<input type="email" placeholder="e-mail para atualizações do seu pedido" class="%s form-control form-control-lg" id="cart_email_to_video_%s" data-cart-id="%s" name="email_to_video" value="%s" required="required"  />',
								'polen-cart-item-data',
								$cart_item_key,
								$cart_item_key,
								$email_to_video,
							);
							?>
						</div>
					</div>
				<?php } ?>

				<div class="row mt-4">
					<div class="col-12 col-md-12">
						<label for="cart_video_category_<?php echo $cart_item_key; ?>">Qual ocasião do vídeo?</label>
					</div>
					<div class="col-md-12">
						<?php
						$video_category = isset( $cart_item['video_category' ] ) ? $cart_item[ 'video_category' ] : '';
						printf(
							'<select class="%s form-control form-control-lg custom-select select-ocasion" id="cart_video_category_%s" data-cart-id="%s" name="video_category" required="required"/>',
							'polen-cart-item-data',
							$cart_item_key,
							$cart_item_key
						);
						echo "<option value=''>Categoria</option>";
						$arr_occasion = $occasion_list->get_occasion(null, 'type', 'ASC', 1, 0, 'DISTINCT type');
						foreach ($arr_occasion as $occasion) :
							$selected = ( $occasion->type == $video_category ) ? 'selected ' : null;
							echo "<option value='" . $occasion->type . "' {$selected}>" . $occasion->type . "</option>";

						endforeach;
						?>
						</select>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-12 col-md-12 mb-3">
						<span class="form-title">Instruções para o vídeo</span>
					</div>
					<div class="col-md-12">
						<?php
						$instructions_to_video = isset($cart_item['instructions_to_video']) ? $cart_item['instructions_to_video'] : '';
						printf(
							"<textarea 	name=\"instructions_to_video\" placeholder=\"Escreva aqui o que você gostaria que {$_product->get_title()} falasse nesse vídeo\"
										class=\"%s form-control form-control-lg\" id=\"cart_instructions_to_video_%s\"
										data-cart-id=\"%s\" required=\"required\">%s</textarea>",
							'polen-cart-item-data',
							$cart_item_key,
							$cart_item_key,
							$instructions_to_video,
						);
						?>
					</div>
				</div>
				<!-- <div class="row pb-2">
					<div class="col-12 d-flex align-items-center reload-sugestions">
						<?php Icon_Class::polen_icon_reload("reload"); ?><a href="javascript:void(0)" class="link-alt video-instruction-refresh ml-2">Outra sugestão de instrução</a>
					</div>
				</div> -->
				<div class="row mt-4">
					<div class="col-12 col-md-12">
						<?php
						$allow_video_on_page = isset($cart_item['allow_video_on_page']) ? $cart_item['allow_video_on_page'] : 'on';
						$checked_allow = '';
						if ($allow_video_on_page == 'on') {
							$checked_allow = 'checked';
						}

						?>
						<label for="cart_allow_video_on_page_<?php echo $cart_item_key; ?>" class="d-flex">
							<?php
							printf(
								'<input type="checkbox" name="allow_video_on_page" class="%s form-control form-control-lg" id="cart_allow_video_on_page_%s"
											data-cart-id="%s" %s>',
								'polen-cart-item-data',
								$cart_item_key,
								$cart_item_key,
								$checked_allow,
							);
							?>
							<span class="ml-2">Permitir que o vídeo seja postado no perfil do artista</span>
						</label>
					</div>
				</div>
				<div class="row actions">
					<div class="col-12 col-md-12 mb-4 mt-3">
						<button type="submit" class="btn btn-primary btn-lg btn-block" name="" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Avançar', 'woocommerce'); ?></button>

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
</div>

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
