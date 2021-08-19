<?php

/**
 * Template name: Página Inicial Vídeo Autógrafo
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="row">
		<div class="col-12 col-md-8 m-md-auto">
			<?php
			va_get_banner_book(true);
			va_magalu_box_cart();
			va_coupon();
			?>
		</div>
	</div>
</main><!-- #main -->

<?php
get_footer();
