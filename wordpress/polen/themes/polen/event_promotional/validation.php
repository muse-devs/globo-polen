<?php

/**
 * Template name: Página Inicial Vídeo Autógrafo
 */
$product = $GLOBALS[ Promotional_Event_Rewrite::GLOBAL_KEY_PRODUCT_OBJECT ];
get_header();
?>

<main id="primary" class="site-main">
	<div class="row">
		<div class="col-12 col-md-8 m-md-auto">
			<?php
			va_get_banner_book( $product, true );
			va_magalu_box_cart( $product );
			va_coupon( $product );
			?>
		</div>
	</div>
</main><!-- #main -->

<?php
get_footer();
