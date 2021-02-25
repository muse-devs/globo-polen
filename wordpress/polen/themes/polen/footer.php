<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Polen
 */

?>

<footer id="colophon" class="mt-5 site-footer">
	<div class="row footer-menu">
		<div class="col-md-6">
			<div class="row">
				<div class="col-md-4">
					<h5 class="title">Polen</h5>
					<ul class="footer-menu">
						<li>
							<a href="#asd">Como funciona</a>
						</li>
						<li>
							<a href="#asd">Política de privacidade</a>
						</li>
					</ul>
				</div>
				<div class="col-md-8">
					<h5 class="title">Categorias</h5>
					<ul class="footer-menu">
						<li>
							<a href="#asd">Categoria A</a>
						</li>
						<li>
							<a href="#asd">Categoria B</a>
						</li>
						<li>
							<a href="#asd">Categoria C</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<h5 class="title">Junte-se à nossa lista</h5>
			<p>Seja o primeiro a saber sobre as estrelas mais recentes e as melhores ofertas no Polen</p>
		</div>
	</div>
	<div class="row mt-5 pt-5 site-info">
		<div class="col-md-4"><?php the_custom_logo(); ?></div>
		<div class="col-md-4 text-center">2021 @ Polen</div>
		<div class="col-md-4 text-right">social</div>
	</div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #Container -->

<?php wp_footer(); ?>

</body>

</html>
