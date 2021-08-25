<?php

/**
 * Template name: Página Inicial Vídeo Autógrafo
 */

use Polen\Includes\Polen_Talent;
use Polen\Includes\Polen_Update_Fields;

get_header();
?>

<main id="primary" class="site-main">

	<div class="row">
		<div class="col-12 col-md-8 m-md-auto">
			<?php
			va_get_banner_book();
			va_ctas(event_promotional_url_code_validation(), event_get_magalu_url());
			va_what_is();
			va_get_book_infos();

			$Talent_Fields = new Polen_Update_Fields();
			$product_id = 409;
			$polen_talent_class = new Polen_Talent();
			$talent_stdClass = $polen_talent_class->get_talent_from_product( $product_id );
			$Talent_Fields = $Talent_Fields->get_vendor_data( $talent_stdClass->ID );
			// va_front_get_talent_videos($Talent_Fields, $product_id);
			?>
		</div>
	</div>

</main><!-- #main -->

<?php
get_footer();
