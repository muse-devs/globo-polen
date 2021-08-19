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
			va_get_banner_book();
			va_ctas(event_promotional_url_code_validation(), "https://www.magazineluiza.com.br/livro-de-porta-em-porta-luciano-huck-com-brinde/p/231238100/li/adml/");
			va_what_is();
			va_get_book_infos();

			use Polen\Includes\Polen_Update_Fields;

			$Talent_Fields = new Polen_Update_Fields();
			$Talent_Fields = $Talent_Fields->get_vendor_data(15);
			va_front_get_talent_videos($Talent_Fields);
			?>
		</div>
	</div>

</main><!-- #main -->

<?php
get_footer();
