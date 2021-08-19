<?php

/**
 * Template name: Página Inicial Vídeo Autógrafo
 */

get_header();
$coupon = "";
?>

<main id="primary" class="site-main">

	<div class="row">
		<div class="col-12 col-md-8 m-md-auto">
			<?php
			va_get_banner_book(true);
			va_cart_form($coupon);
			?>
		</div>
	</div>

</main><!-- #main -->

<?php
get_footer();
