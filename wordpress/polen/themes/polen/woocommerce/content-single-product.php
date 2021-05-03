<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

use Polen\Includes\Polen_Update_Fields;

global $post;
$Talent_Fields = new Polen_Update_Fields();
$Talent_Fields = $Talent_Fields->get_vendor_data($post->post_author);
$terms = wp_get_object_terms(get_the_ID(), 'product_tag');
?>

<figure class="image-bg">
	<img src="<?php echo wp_get_attachment_image_src($Talent_Fields->cover_image_id, "large")[0]; ?>" alt="<?php echo $Talent_Fields->nome; ?>">
</figure>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

	<!-- Vídeos -->
	<?php polen_front_get_talent_videos($Talent_Fields); ?>

	<!-- Tags -->
	<div class="row pb-4">
		<div class="col-md-12">
			<h1 class="talent-name text-truncate mb-3" title="<?= get_the_title(); ?>"><?= get_the_title(); ?></h1>
			<div class="row">
				<div class="col-md-12">
					<?php if (count($terms) > 0) : ?>
						<?php foreach ($terms as $k => $term) : ?>
							<a href="<?= get_tag_link($term); ?>" class="tag-link mb-2"><?= $term->name; ?></a>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="row my-3 pb-2 talent-page-footer">
		<div class="col-12 col-md-6 m-md-auto">
			<?php echo woocommerce_template_single_add_to_cart(); ?>
			<!--button class="btn btn-primary btn-lg btn-block btn-get-video">Pedir vídeo R$ 200</button-->
		</div>
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12 text-center text-md-center">
					<span class="skill-title">Responde em</span>
				</div>
				<div class="col-md-12 text-center text-md-center mt-2">
					<?php Icon_Class::polen_icon_clock(); ?>
					<span class="skill-value"><?= $Talent_Fields->tempo_resposta; ?>h</span>
				</div>
			</div>
		</div>
	</div>

	<!-- Como funciona? -->
	<?php polen_front_get_tutorial(); ?>

	<div class="row">
		<div class="col-12 col-md-12">
			<?php
			$cat_terms = wp_get_object_terms(get_the_ID(), 'product_cat');
			$cat_link = '';
			if (isset($cat_terms[0]) && !empty($cat_terms[0]->term_id)) {
				$cat_link = get_term_link($cat_terms[0]->term_id);
			}
			$terms_ids = array();
			if (count($cat_terms) > 0) {
				foreach ($cat_terms as $k => $term) {
					$terms_ids[] = $term->term_id;
				}
			}
			if (count($terms_ids) > 0) :
				$others = get_objects_in_term($terms_ids, 'product_cat');
				$arr_obj = array();
				$arr_obj[] = get_the_ID();
				shuffle($others);

				if (count($others)) : ?>
					<?php
					$args = array();
					foreach ($others as $k => $id) :
						if (!in_array($id, $arr_obj)) {
							if (count($arr_obj) > 5) {
								exit;
							}
							$product = wc_get_product($id);
							$arr_obj[] = $id;

							$args[] = array(
								"ID" => $id,
								"talent_url" => get_permalink($id),
								"name" => $product->get_title(),
								"price" => $product->get_regular_price(),
								"category_url" => $cat_link,
								"category" => wc_get_product_category_list($id)
							);
						}
					endforeach; ?>
					<?php polen_banner_scrollable($args, "Relacionados", $cat_link); ?>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>

</div>

<?php do_action('woocommerce_after_single_product'); ?>
