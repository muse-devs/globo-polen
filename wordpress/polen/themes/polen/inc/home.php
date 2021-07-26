<?php

/**
 * Template name: Página Inicial
 */

get_header();
?>

<main id="primary" class="site-main">

	<?php polen_front_get_banner_with_carousel();
	?>

	<?php polen_banner_scrollable(polen_get_new_talents(6), "Destaque", polen_get_all_new_talents_url()); ?>

	<?php polen_front_get_tutorial(); ?>

	<?php polen_front_get_news(polen_get_talents(12), "Todos os talentos", polen_get_all_talents_url());
	?>

	<?php //polen_front_get_suggestion_box(); ?>

</main><!-- #main -->

<?php
get_footer();
