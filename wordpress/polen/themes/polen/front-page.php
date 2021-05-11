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

	<?php polen_banner_scrollable(polen_get_new_talents(6), "Destaque", polen_get_all_new_talents_url() ); ?>

	<?php polen_front_get_tutorial(); ?>

	<?php polen_banner_scrollable(polen_get_talents(12), "Todos talentos", polen_get_all_talents_url() );
	?>

</main><!-- #main -->

<?php
get_footer();
