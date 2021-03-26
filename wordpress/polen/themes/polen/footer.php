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

<footer id="colophon" class="pt-5 site-footer">
	<?php if ((!is_page('cart') || !is_cart()) && (!is_page('checkout') || !is_checkout())) : ?>
		<div class="row pb-5">
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-4 my-2 text-center text-md-left">
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
					<div class="col-md-8 my-2 text-center text-md-left">
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
			<div class="col-md-6 mt-4 text-center text-md-left">
				<h5 class="title">Junte-se à nossa lista</h5>
				<p class="description">Seja o primeiro a saber sobre as estrelas mais recentes e as melhores ofertas no Polen</p>
				<div class="row">
					<div class="col-md-8 mb-2">
						<input type="text" placeholder="Entre com o seu e-mail" class="form-control form-control-lg" />
					</div>
					<div class="col-md-4"><button class="btn btn-primary btn-lg btn-block">Enviar</button></div>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div class="row mt-5 copyright">
		<div class="col-md-4 py-3 py-md-5 text-center text-md-left"><?php the_custom_logo(); ?></div>
		<div class="col-md-4 py-1 py-md-5 text-center">2021 @ Polen</div>
		<div class="col-md-4 py-1 py-md-5 text-center text-md-right">social</div>
	</div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #Container -->

<?php wp_footer(); ?>

</body>

</html>

<?php if (defined('DEV_ENV') && DEV_ENV) { ?>
	<!--
<?php print_r(get_included_files()); ?>
-->
<?php } ?>