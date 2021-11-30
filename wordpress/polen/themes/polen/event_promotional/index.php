<?php

/**
 * Template name: Página Inicial Vídeo Autógrafo
 */

use Polen\Includes\Polen_Talent;
use Polen\Includes\Polen_Update_Fields;

$inputs = new Material_Inputs();

$product = $GLOBALS[ Promotional_Event_Rewrite::GLOBAL_KEY_PRODUCT_OBJECT ];

if( 'instock' == $product->get_stock_status() ) {
	$has_stock = true;
} else {
	$has_stock = false;
}

$image_data = polen_get_thumbnail($product->get_id());

get_header();
?>

<main id="primary" class="site-main">

  <!-- Perfil -->
	<div class="row">
		<div class="col-12 col-md-6 m-md-auto mt-3 d-flex flex-wrap justify-content-center lacta-profile">
      <figure class="image">
        <img loading="lazy" src="<?php echo $image_data["image"] ?>" alt="<?php echo $product->get_title(); ?>">
      </figure>
      <h2><?php echo $product->get_title(); ?></h2>
		</div>
	</div>

  <!-- Botão de adicionar ao carrinho -->
	<div class="row mt-4 talent-page-footer">
		<div class="col-12 col-md-6 m-md-auto pb-3 event-lacta">
			<?php if($has_stock) : ?>
        <div class="btn-buy-b2b">
          <a href="<?php echo event_promotional_url_code_validation( $product ); ?>">
            <div class="mdc-button mdc-button--raised mdc-ripple-upgraded">
              Resgatar meu vídeo
            </div>
          </a>
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
        <h5>Sobre o vídeo de <?php echo $product->get_title(); ?></h5>
        <p><?php echo $product->get_description(); ?></p>
      </div>
		</div>
	</div>

  <!-- Instruções -->
  <div class="row mt-4">
		<div class="col-12 col-md-6 m-md-auto event-lacta">
      <?php get_lacta_insctruction($product); ?>
		</div>
	</div>

  <!-- Banner -->
  <div class="row my-4">
    <div class="col-sm-12 mb-4">
      <div class="lacta-wrapper">
        <div class="lacta-carousel">
          <img src="<?php echo TEMPLATE_URI . '/assets/img/lacta/banner-1.jpg'; ?>" alt="Banner Lacta" style="width: 100%">
          <a href="https://www.lacta.com.br/" target="_blank" class="lacta-banner-link"></a>
          <img src="<?php echo TEMPLATE_URI . '/assets/img/lacta/banner-1.jpg'; ?>" alt="Banner Lacta" style="width: 100%">
          <a href="https://www.lacta.com.br/" target="_blank" class="lacta-banner-link"></a>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 m-md-auto event-lacta">
      <a href="https://www.lacta.com.br/" target="_blank">
        <div class="mdc-button mdc-button--outlined mdc-ripple-upgraded" style="--mdc-ripple-fg-size:294px; --mdc-ripple-fg-scale:1.71077; --mdc-ripple-fg-translate-start:74.375px, -113.195px; --mdc-ripple-fg-translate-end:98px, -120px;">
          Compre agora
        </div>
      </a>
    </div>
  </div>

</main><!-- #main -->

<?php
get_footer();
