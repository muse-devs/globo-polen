<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Polen
 */

get_header();
?>

<main id="primary" class="site-main">

	<?php polen_front_get_banner();
	?>

	<?php polen_front_get_news(polen_get_new_talents());
	?>

	<?php polen_front_get_categories(get_categories_home());
	?>

	<?php polen_front_get_artists(polen_get_talents(), "Todos os Artistas");
	?>

	<?php polen_front_get_tutorial(); ?>

</main><!-- #main -->

<?php
get_footer();
