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

<div class="row mt-2">
	<div class="col-12">
		<div class="progress" style="height: 7px;">
			<div class="progress-bar bg-primary" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
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

    $talent_data = $Talent_Fields->get_vendor_data( $talent_id );

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

<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_checkout_url()); ?>" method="post">
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

	<div class="row mt-1 py-2 cart-other">
		<?php do_action('woocommerce_cart_contents'); ?>
		<div class="col-12 col-md-12">
			<div class="row">
				<div class="col-md-12 mb-4">
					<span class="form-title">Esse vídeo é para:</span>
				</div>
				<div class="col-md-12 mb-4">
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
						'<input type="radio" checked="true" class="%s cart-video-to" id="cart_video_to_%s" data-cart-id="%s" name="video_to" value="other_one" %s > Outra pessoa
						<input type="radio" class="%s ml-4 cart-video-to" id="cart_video_to_%s" data-cart-id="%s" name="video_to" value="to_myself" %s > Para mim',
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

			<div class="row video-to-info">
				<div class="col-12 col-md-6">
					<?php
					$offered_by = isset($cart_item['offered_by']) ? $cart_item['offered_by'] : '';
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
				<div class="col-12 col-md-6">
					<?php
					$name_to_video = isset($cart_item['name_to_video']) ? $cart_item['name_to_video'] : '';
					printf(
						'<input type="text" placeholder="Para quem é o vídeo?" class="%s form-control form-control-lg" id="cart_name_to_video_%s" data-cart-id="%s" name="name_to_video" value="%s" required="required"/>',
						'polen-cart-item-data',
						$cart_item_key,
						$cart_item_key,
						$name_to_video,
					);
					?>
				</div>
			</div>

			<?php
			if ( is_user_logged_in() ) {
				$current_user = wp_get_current_user();
				$email_to_video = $current_user->user_email;
				printf(
					'<input type="hidden" placeholder="E-mail para receber updates" class="%s form-control form-control-lg" id="cart_email_to_video_%s" data-cart-id="%s" name="email_to_video" value="%s" required="required" />',
					'polen-cart-item-data',
					$cart_item_key,
					$cart_item_key,
					$email_to_video,
				);
			?>
			<?php } else { ?>
			<div class="row">
				<div class="col-12 col-md-6">
					<?php
					$email_to_video = isset($cart_item['email_to_video']) ? $cart_item['email_to_video'] : '';
					printf(
						'<input type="text" placeholder="E-mail para receber updates" class="%s form-control form-control-lg" id="cart_email_to_video_%s" data-cart-id="%s" name="email_to_video" value="%s" required="required" />',
						'polen-cart-item-data',
						$cart_item_key,
						$cart_item_key,
						$email_to_video,
					);
					?>
				</div>
			</div>
			<?php } ?>

			<div class="row mt-3">
				<div class="col-12 col-md-12 mb-4">
					<span class="form-title">Qual ocasião do vídeo?</span>
				</div>
				<div class="col-md-6">
					<?php
					$email_to_video = isset($cart_item['email_to_video']) ? $cart_item['email_to_video'] : '';
					printf(
						'<select class="%s form-control form-control-lg" id="cart_video_category_%s" data-cart-id="%s" name="video_category" required="required"/>',
						'polen-cart-item-data',
						$cart_item_key,
						$cart_item_key
					);
					echo "<option value=''>Categoria</option>";
					$arr_occasion = $occasion_list->get_occasion(null, 'type', 'ASC', 1, 0, 'DISTINCT type');
					foreach ($arr_occasion as $occasion) :
						echo "<option value='" . $occasion->type . "'>" . $occasion->type . "</option>";

					endforeach;
					?>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-12 col-md-12 mb-4 mt-3">
					<span class="form-title">Instruções para o vídeo</span>
				</div>
				<div class="col-md-6">
					<?php
					$instructions_to_video = isset($cart_item['instructions_to_video']) ? $cart_item['instructions_to_video'] : '';
					printf(
						'<textarea 	name="instructions_to_video" placeholder="Instruções"
										class="%s form-control form-control-lg" id="cart_instructions_to_video_%s"
										data-cart-id="%s" required="required">%s</textarea>',
						'polen-cart-item-data',
						$cart_item_key,
						$cart_item_key,
						$instructions_to_video,
					);
					?>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<a href="javascript:void(0)" class="link-alt"><?php Icon_Class::polen_icon_reload(); ?> Outras mensagens</a>
				</div>
			</div>
			<div class="row my-3">
				<div class="col-12 col-md-12">
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
				<div class="col-12 col-md-6 mb-4 mt-3">
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
