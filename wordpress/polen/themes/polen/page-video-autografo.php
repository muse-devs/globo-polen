<?php

/**
 * Template name: Página Inicial Vídeo Autógrafo
 */

get_header();
?>

<main id="primary" class="site-main">

	<?php
	va_get_banner_book();
	va_ctas();
	va_what_is();
	va_get_book_infos();

	use Polen\Includes\Polen_Update_Fields;

	$Talent_Fields = new Polen_Update_Fields();
	$Talent_Fields = $Talent_Fields->get_vendor_data(15);
	va_front_get_talent_videos($Talent_Fields);
	?>

</main><!-- #main -->

<?php
get_footer();
