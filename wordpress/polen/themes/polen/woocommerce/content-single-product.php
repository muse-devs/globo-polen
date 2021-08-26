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

$bg_image = wp_get_attachment_image_src($Talent_Fields->cover_image_id, "large")[0];

$donate = get_post_meta(get_the_ID(), '_is_charity', true);
$donate_name = get_post_meta(get_the_ID(), '_charity_name', true);
$donate_image =  get_post_meta(get_the_ID(), '_url_charity_logo', true);
$donate_text = stripslashes(get_post_meta(get_the_ID(), '_description_charity', true));
$social = social_product_is_social($product, social_get_category_base());

$stock = $product->get_stock_quantity();

?>

<script>
	// params
	jQuery(document).ready(function() {
		if(document.querySelector("#stories")) {
			renderStories(<?php echo polen_get_videos_by_talent($Talent_Fields, true); ?>, <?php echo json_encode(get_the_title()); ?>, <?php echo json_encode(wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'polen-thumb-lg')[0]); ?>, <?php echo $social || 'null'; ?>, <?php echo $stock || 'null'; ?>)
		}
	});
</script>

<?php if ($bg_image) : ?>
	<figure class="image-bg">
		<img src="<?php echo $bg_image; ?>" alt="<?php echo $Talent_Fields->nome; ?>">
	</figure>
<?php endif; ?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

	<!-- Tags -->
	<div class="row">
		<div class="col-12<?php echo $social ? ' col-md-6 m-md-auto' : ''; ?> d-flex align-items-center">
			<?php $social && polen_front_get_talent_stories(); ?>
			<div>
				<h1 class="talent-name" title="<?= get_the_title(); ?>"><?= get_the_title(); ?></h1>
				<?php if($social) : ?>
					<h5 class="talent-count-videos text-truncate">
						<?php
						$videosCount = $stock;
						if ($videosCount === 1) {
							echo $videosCount . " vídeo disponível";
						} else if ($videosCount === 0) {
							echo "Nenhum vídeo disponível";
						} else {
							echo $videosCount . " vídeos disponíveis";
						}
						?>
					</h5>
				<?php endif; ?>
			</div>
			<?php polen_get_share_button(); ?>
		</div>
		<div class="col-12 mt-3">
			<?php $social || polen_front_get_talent_videos($Talent_Fields); ?>
		</div>
	</div>

	<div class="row mt-3 mb-1 talent-page-footer">
		<div class="col-12 col-md-6 m-md-auto pb-3">
			<?php if(!$social || $stock > 0) : ?>
				<?php echo woocommerce_template_single_add_to_cart(); ?>
			<?php else: ?>
				<a href="<?php echo social_get_criesp_url(); ?>" class="btn btn-success btn-lg btn-block btn-get-video">
					<span class="mr-2"><?php Icon_Class::polen_icon_criesp(); ?></span>
					Escolher outro artista
				</a>
			<?php endif; ?>
		</div>
	</div>

	<div class="row">
		<div class="col-12 col-md-6 m-md-auto d-flex">
			<!-- Se for doação -->
			<?php if ($donate && !$social) : ?>
				<div class="row">
					<div class="col-md-12 mb-1">
						<?php polen_donate_badge("100% DO CACHÊ DOADO PARA " . strtoupper($donate_name), false); ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($social) : ?>
				<div class="row">
					<div class="col-md-12 mb-1">
						<?php polen_donate_badge("100% DO VALOR DOADO PARA O CRIANÇA ESPERANÇA", false, true); ?>
					</div>
				</div>
			<?php endif; ?>
			<!-- /------------ -->
		</div>
	</div>

	<!-- Card dos Reviews -->
	<?php $social || polen_card_talent_reviews_order($post, $Talent_Fields); ?>

	<?php
		if (!$social) {
	?>
		<div class="row mt-4">
			<div class="col-md-12">
				<?php if (count($terms) > 0) : ?>
					<?php foreach ($terms as $k => $term) : ?>
						<a href="<?= get_tag_link($term); ?>" class="tag-link mb-2"><?= $term->name; ?></a>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	<?php
		} else {
			criesp_get_send_video_date();
		}
	?>

	<!-- Bio -->
	<div class="row mt-4">
		<div class="col-12 col-md-6 m-md-auto d-flex">
			<p><?= $product->get_description(); ?></p>
		</div>
	</div>

	<!-- Share -->
	<?php polen_get_share_icons(); ?>

	<!-- Doação -->
	<?php $donate && !$social ?
		polen_front_get_donation_box($donate_image, $donate_text) :
		null;

	$social && criesp_get_donation_box();
	?>

	<!-- Como funciona? -->
	<?php $social || polen_front_get_tutorial(); ?>

	<!-- Produtos Relacionados -->
	<?php //polen_box_related_product_by_product_id(get_the_ID());
	?>

</div>

<?php do_action('woocommerce_after_single_product'); ?>
