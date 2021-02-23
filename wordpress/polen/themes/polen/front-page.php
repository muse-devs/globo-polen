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
				<?php for ($i = 0; $i < 4; $i++) : ?>
					<div class="col-md-3">
						<div class="polen-card category">
							<a href="#" class="link">Categoria a</a>
						</div>
					</div>
				<?php endfor; ?>
			</div>
		</div>
	</section>

	<section class="row my-5 py-4 all-artists">
		<div class="col-md-12">
			<header class="row mb-4">
				<div class="col">
					<h2>Todos os Artistas</h2>
				</div>
				<div class="col d-flex justify-content-end align-items-center"><a href="#">Ver mais</a></div>
			</header>
		</div>
		<div class="col-md-12">
			<div class="row d-flex justify-content-between flex-wrap">
				<?php for ($i = 0; $i < 10; $i++) : ?>
					<?php polen_get_card("#opa", "https://picsum.photos/220/276", "100", "Nome do Artista", "Categoria", "#cat", "small"); ?>
				<?php endfor; ?>
			</div>
			<div class="row mt-5">
				<div class="col-md-12 text-center">
					<button type="button" class="btn btn-primary btn-lg">Ver mais</button>
				</div>
			</div>
		</div>
	</section>

</main><!-- #main -->

<?php
get_footer();
