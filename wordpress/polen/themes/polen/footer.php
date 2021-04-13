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

<footer id="colophon" class="pt-4 mt-5 site-footer">
	<?php if ((!is_page('cart') || !is_cart()) && (!is_page('checkout') || !is_checkout())) : ?>
		<div class="row pb-5">
			<div class="col-12">
				<div class="row justify-content-md-between">
					<div class="col-md-6 mt-4 order-md-2">
						<h5 class="title">Junte-se à nossa lista</h5>
						<p class="description">Seja o primeiro a saber sobre as estrelas mais recentes e as melhores ofertas no Muse</p>
						<div class="row">
							<div class="col-md-8 mb-2">
								<input type="text" placeholder="Entre com o seu e-mail" class="form-control form-control-lg" />
							</div>
							<div class="col-md-4 mt-2"><button class="btn btn-outline-light btn-lg btn-block">Enviar</button></div>
						</div>
					</div>
					<div class="col-md-4 mt-4 order-md-1">
						<h5 class="title">Muse</h5>
						<ul class="footer-menu">
							<li>
								<a href="#asd">Todos os talentos</a>
							</li>
							<li>
								<a href="#asd">Como funciona</a>
							</li>
							<li>
								<a href="#asd">Política de privacidade</a>
							</li>
							<li>
								<a href="#asd">Termos e condições</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div class="row mt-5 pb-5 copyright">
		<div class="col-md-12 pb-4 text-center social">
			<a href="#facebook"><?php Icon_Class::polen_icon_social("facebook"); ?></a>
			<a href="#instagram"><?php Icon_Class::polen_icon_social("instagram"); ?></a>
			<a href="#linkedin"><?php Icon_Class::polen_icon_social("linkedin"); ?></a>
			<a href="#twitter"><?php Icon_Class::polen_icon_social("twitter"); ?></a>
		</div>
		<div class="col-md-12 pt-3 text-center">2021 @Muse</div>
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
