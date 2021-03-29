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
$terms = wp_get_object_terms(get_the_ID(), 'product_cat');
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

	<!-- Cabeçalho do Artista -->
	<header class="talent-page-header">
		<div class="row mt-5 d-flex justify-content-between align-items-center">
			<div class="col-md-5">
				<div class="row">
					<div class="col-md-12 d-flex justify-content-between align-items-center">
						<h1 class="talent-name text-truncate" title="<?= get_the_title(); ?>"><?= get_the_title(); ?></h1>
						<div class="talent-share ml-4">
							<button id="share-button" class="share-button"><?php polen_icon_share(); ?></button>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<?php if (count($terms) > 0) : ?>
							<?php foreach ($terms as $k => $term) : ?>
								<a href="<?= get_tag_link($term); ?>" class="tag-link mb-2"><?= $term->name; ?></a>
							<?php endforeach; ?>
					</div>
				<?php endif; ?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="row">
					<div class="col-md-6 text-center">
						<span class="skill-title">Responde em</span>
					</div>
					<div class="col-md-6 text-center">
						<span class="skill-title">Reviews</span>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 text-center">
						<?php polen_icon_clock(); ?>
						<span class="skill-value"><?= $Talent_Fields->tempo_resposta; ?>h</span>
					</div>
					<div class="col-md-6 text-center">
						<?php polen_icon_star(true); ?>
						<span class="skill-value"><?= "5.0"; ?></span>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<?php echo woocommerce_template_single_add_to_cart(); ?>
				<!--button class="btn btn-primary btn-lg btn-block btn-get-video">Pedir vídeo R$ 200</button-->
			</div>
		</div>
		<div class="row">
			<div class="col-md-12"><span class="price"><?php echo wc_price( $product->get_price() );?></span></div>
		</div>
	</header>

	<!-- Vídeos -->
	<?php polen_front_get_talent_videos(); ?>

	<!-- Tags -->
	<div class="row mt-5">
		<div class="col-md-12">
			<h4>Tags</h4>
			<div>
				<?php 
				$talent_tags = get_the_terms( get_the_ID(), 'product_tag' ); 
				foreach( $talent_tags as $tag ):
					echo $tag->name;
				endforeach;	
				?>
			</div>
		</div>
	</div>

	<!-- Como funciona? -->
	<?php polen_front_get_tutorial(); ?>

	<!-- Artistas Relacionados -->
	<div class="row">
		<div class="col m-5 text-center">
			<h1>Talentos relacionados</h1>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<?php
			$terms_ids = array();
			if (count($terms) > 0) {
				foreach ($terms as $k => $term) {
					$terms_ids[] = $term->term_id;
				}
			}
			if (count($terms_ids) > 0) : ?>
				<?php $others = get_objects_in_term($terms_ids, 'product_cat'); ?>
				<?php if (count($others)) : ?>
					<div class="row d-flex flex-wrap">
						<?php foreach ($others as $k => $id) :
							$product = wc_get_product($id);
						?>
							<?php
							polen_front_get_card(array(
								"talent_url" => "",
								"image" => wp_get_attachment_url($product->get_image_id()),
								"name" => $product->get_title(),
								"price" => $product->get_regular_price(),
								"category_url" => "",
								"category" => ""
							), "small");
							?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>

</div>

<?php do_action('woocommerce_after_single_product'); ?>
