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
global $Polen_Plugin_Settings;
global $post;

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
use Polen\Social_Base\Social_Base_Product;

$Talent_Fields = new Polen_Update_Fields();
$Talent_Fields = $Talent_Fields->get_vendor_data($post->post_author);
$terms = wp_get_object_terms(get_the_ID(), 'product_tag');

$bg_image = wp_get_attachment_image_src($Talent_Fields->cover_image_id, "large")[0];
$image_data = polen_get_thumbnail(get_the_ID());

$donate = get_post_meta(get_the_ID(), '_is_charity', true);
$donate_name = get_post_meta(get_the_ID(), '_charity_name', true);
$donate_image =  get_post_meta(get_the_ID(), '_url_charity_logo', true);
$donate_text = stripslashes(get_post_meta(get_the_ID(), '_description_charity', true));
// $social = social_product_is_social($product, social_get_category_base()); //Antigo CRIESP

$histories_enabled = $Polen_Plugin_Settings['polen_histories_on'];
$social = Social_Base_Product::product_is_social_base( $product );
$inputs = new Material_Inputs();

// outofstock
// instock
if( 'instock' == $product->get_stock_status() ) {
	$has_stock = true;
} else {
	$has_stock = false;
}
// $stock = $product->get_stock_status();

?>

<?php if ($bg_image) : ?>
	<figure class="image-bg">
		<img src="<?php echo $bg_image; ?>" alt="<?php echo $Talent_Fields->nome; ?>">
	</figure>
<?php endif; ?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

	<!-- Perfil -->
	<div class="row">
		<div class="col-12 col-md-6 m-md-auto mt-3 d-flex flex-wrap justify-content-center lacta-profile">
      <figure class="image">
        <img loading="lazy" src="<?php echo $image_data["image"] ?>" alt="<?php echo get_the_title(); ?>">
      </figure>
      <h2><?php echo get_the_title(); ?></h2>
		</div>
	</div>

  <!-- Botão de adicionar ao carrinho -->
	<div class="row mt-4 mb-1 talent-page-footer">
		<div class="col-12 col-md-6 m-md-auto pb-3 event-lacta">
			<?php if($has_stock) : ?>
        <div class="btn-buy-b2b">
          <?php $inputs->material_button_link("btn-b2b", "Resgatar meu vídeo", enterprise_url_home() . "#bus-form-wrapper", false, "", array(), $donate ? "donate" : ""); ?>
        </div>
			<?php else: ?>
        <div class="lacta-btn-disable mb-3">
          <div class="mdc-button mdc-button--raised mdc-ripple-upgraded">
            Esgotado
          </div>
        </div>
        <?php $inputs->material_button_link_outlined("todos", "Escolher outro artista", home_url( "shop" ), false, "", array(), $donate ? "donate" : ""); ?>
			<?php endif; ?>
		</div>
	</div>

	<!-- Bio -->
	<div class="row mt-4">
		<div class="col-12 col-md-6 m-md-auto d-flex">
      <div class="lacta-bio">
        <h5>Sobre o vídeo de <?php echo get_the_title(); ?></h5>
        <p><?php echo $product->get_description(); ?></p>
      </div>
		</div>
	</div>

  <!-- Instruções -->
  <div class="row mt-4">
		<div class="col-12 col-md-6 m-md-auto d-flex">
      <?php get_lacta_insctruction($product); ?>
		</div>
	</div>

  <!-- Banner -->
  <?php polen_get_lacta_banner_2("/lacta"); ?>

</div>

<?php do_action('woocommerce_after_single_product'); ?>
