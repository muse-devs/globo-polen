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

	<section class="row my-5 py-4 top-banner">
		<div class="col-md-4">
			<div class="polen-card big">
				<figure class="image">a</figure>
			</div>
		</div>
		<div class="col-md-4">
			<div class="polen-card big">
				<figure class="image">a</figure>
			</div>
		</div>
		<div class="col-md-4">
			<div class="polen-card big">
				<figure class="image">a</figure>
			</div>
		</div>
	</section>

	<section class="row my-5 py-4 news">
		<div class="col-md-12">
			<header class="row mb-4">
				<div class="col">
					<h2>Novidades</h2>
				</div>
				<div class="col d-flex justify-content-end align-items-center"><a href="#">Ver todos</a></div>
			</header>
		</div>
		<div class="col-md-12">
			<div class="row">
				<?php for ($i = 0; $i < 4; $i++) : ?>
					<?php polen_get_card("#opa", "https://picsum.photos/255/350", "100", "Nome do Artista", "Categoria", "#cat"); ?>
				<?php endfor; ?>
			</div>
		</div>
	</section>

	<section class="row my-5 py-4 categories">
		<div class="col-md-12">
			<header class="row mb-4">
				<div class="col">
					<h2>Categorias</h2>
				</div>
				<div class="col d-flex justify-content-end align-items-center"><a href="#">Ver todos</a></div>
			</header>
		</div>
		<div class="col md-12">
			<div class="row">
				<div class="col-md-3">
					<div class="polen-card category">
						<figure class="image">Categoria a</figure>
					</div>
				</div>
				<div class="col-md-3">
					<div class="polen-card category">
						<figure class="image">Categoria a</figure>
					</div>
				</div>
				<div class="col-md-3">
					<div class="polen-card category">
						<figure class="image">Categoria a</figure>
					</div>
				</div>
				<div class="col-md-3">
					<div class="polen-card category">
						<figure class="image">Categoria a</figure>
					</div>
				</div>
			</div>
		</div>
	</section>

</main><!-- #main -->

<?php
get_footer();
