<?php

/**
 * Template name: Página Inicial Vídeo Autógrafo
 */
$product = $GLOBALS[ Promotional_Event_Rewrite::GLOBAL_KEY_PRODUCT_OBJECT ];
$pep = new Promotional_Event_Product( $product );
$img = $pep->get_url_image_product_with_size( 'polen-thumb-lg' );
get_header();
?>

<main id="primary" class="site-main">
	<div class="row">
		<div class="col-12 col-md-8 m-md-auto">
      <div class="row">
        <div class="col-12 mb-4">
          <?php polen_get_lacta_header_talent($img, $product->get_title()); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-12 mb-4">
          <?php get_lacta_insctruction(); ?>
        </div>
      </div>
			<?php lacta_coupon( $product ); ?>
		</div>
	</div>
</main><!-- #main -->

<?php
get_footer();
