<?php

/**
 * Template name: Página Inicial
 */

get_header();
?>

<main id="primary" class="site-main">

	<?php polen_front_get_banner_with_carousel();
	?>

	<?php va_get_book_infos(); ?>

	<?php polen_banner_scrollable(social_get_products_by_category_slug(social_get_category_base()), "Criança Esperança", social_get_criesp_url(), "Aqui seu Vídeo Polen vira uma doação", true); ?>

	<?php va_get_home_banner(social_get_criesp_url()); ?>

	<?php polen_front_get_tutorial(); ?>

	<?php polen_front_get_news(polen_get_talents(12), "Todos os talentos", polen_get_all_talents_url());
	?>

	<?php //polen_front_get_suggestion_box(); ?>

</main><!-- #main -->

<?php
get_footer();
