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

			$product = $GLOBALS[ Promotional_Event_Rewrite::GLOBAL_KEY_PRODUCT_OBJECT ];
			$product_id = $product->get_id();

			va_get_banner_book( $product );
			$url_to_buy = $product->get_meta( '_promotional_event_link_buy', true );
			va_ctas(event_promotional_url_code_validation( $product ), $url_to_buy );
			va_what_is( $product );
			va_get_book_infos( $product, 4.2 );

			$Polen_Talent = new Polen_Talent();
			$talent = $Polen_Talent->get_talent_from_product( $product_id );
			$Talent_Fields = new Polen_Update_Fields();
			$Talent_Fields = $Talent_Fields->get_vendor_data( $talent->ID );
			va_front_get_talent_videos( $Talent_Fields, $product_id );
			?>
		</div>
	</div>

</main><!-- #main -->

<?php
get_footer();
