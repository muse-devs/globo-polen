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
				<div class="col-12 col-md-6 m-md-auto">
					<div class="row">
						<div class="col-6 col-md-6 text-center text-md-center">
							<span class="skill-title">Responde em</span>
						</div>
						<div class="col-6 col-md-6 text-center text-md-center">
							<span class="skill-title">Avaliações (<?php echo get_post_meta( $post->ID, "total_review", true ); ?>)</span>
						</div>
						<div class="col-6 col-md-6 text-center text-md-center mt-2">
							<?php Icon_Class::polen_icon_clock(); ?>
							<span class="skill-value"><?= $Talent_Fields->tempo_resposta; ?>h</span>
						</div>
						<div class="col-6 col-md-6 text-center text-md-center mt-2">
							<?php Icon_Class::polen_icon_star(true); ?>
							<?php
							$total_review = intval( get_post_meta( $post->ID, "total_review", true ) );
							$sum_rate_reviews = intval( get_post_meta( $post->ID, "sum_rate", true ) );
							$avg_rate = $total_review > 0 ? ( $sum_rate_reviews / $total_review ) : 0 ;
							?>
							<span class="skill-value"><?php echo number_format( $avg_rate, 1 ); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Como funciona? -->
	<?php polen_front_get_tutorial(); ?>

	<!-- Produtos Relacionados -->
	<?php polen_box_related_product_by_product_id( get_The_ID() ); ?>

</div>

<?php do_action('woocommerce_after_single_product'); ?>
