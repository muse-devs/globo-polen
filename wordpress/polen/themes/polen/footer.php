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
	<?php if ( (!is_page('cart') || !is_cart()) && 
			 (!is_page('checkout') || !is_checkout()) ) : ?>
		<div class="row pb-5">
			<div class="col-12">
				<div class="row justify-content-md-between">
					<div id="signin-newsletter" class="col-md-6 mt-4 order-md-2">
						<h5 class="title">Junte-se à nossa lista</h5>
						<p class="description">Seja o primeiro a saber sobre as estrelas mais recentes e as melhores ofertas no Muse</p>
						<div class="row">
							<div class="col-md-8 mb-2 mb-md-0">
								<input type="email" name="signin_newsletter" placeholder="Entre com o seu e-mail" class="form-control form-control-lg" />
							</div>
							<?php
								$newsletter_signin_nonce = wp_create_nonce('news-signin');
							?>
							<div class="col-md-4 mt-2 mt-md-0 d-md-flex align-items-md-center" >
								<button class="signin-newsletter-button btn btn-outline-light btn-lg btn-block" code="<?php echo $newsletter_signin_nonce;?>">Enviar</button>
							</div>
							<div class="col-md-8 mb-2 mb-md-0 small signin-response"></div>
						</div>
					</div>

					<div class="col-md-4 mt-4 order-md-1">
						<h5 class="title"><?= get_bloginfo('name'); ?></h5>
							<?php
								$menu = wp_nav_menu(
									array(
										'menu'              => 'menu-1',
										'theme_location'    => 'primary',
										'depth'             => 0,
										'menu_class' 		=> 'footer-menu',
										'container_class' 	=> ' ',
										'items_wrap' 		=> '<ul class="%2$s">%3$s</ul>',
										'container' 		=> ' ',
										'echo'				=> false,
									)
								);
								if( !is_account_page() ) {
									echo $menu;
								} else { ?>
									<ul class="footer-menu">
										<li><a href="<?php echo home_url().'/shop'; ?>">Todos os talentos</a></li>
										<li><a href="/">Como funciona</a></li>
										<li><a href="/politica-de-privacidade">Política de privacidade</a></li>
										<li><a href="/politica-de-privacidade">Termos e condições</a></li>
									</ul>
								<?php }
							?>
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
		<div class="col-md-12 pt-3 text-center">2021 @Polen</div>
	</div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #Container -->

<?php wp_footer(); ?>

</body>

</html>

<?php if( defined('DEV_ENV') && DEV_ENV ) : ?>
<!--
<?php print_r( get_included_files() ); ?>
-->
<?php endif; ?>
