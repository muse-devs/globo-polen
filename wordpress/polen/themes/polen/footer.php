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

<footer id="colophon" class="site-footer pb-4">
	<?php if ( (!is_page('cart') || !is_cart()) &&
			 (!is_page('checkout') || !is_checkout()) && !polen_is_landingpage()) : ?>
		<div class="row">
			<div class="col-12">
				<div class="row justify-content-md-between">

					<?php
						if(is_front_page()) {
							polen_form_signin_newsletter();
						}
					?>

					<div class="mt-4 <?php echo is_front_page() ? "col-md-4" : "col-md-8" ?> order-md-1">
						<h5 class="title typo typo-title typo-small"><?= get_bloginfo('name'); ?></h5>
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
	<div class="row mt-5 copyright">
		<div class="col-md-12 pb-4 text-center social">
			<a href="https://www.facebook.com/Polen-107879504782470/" target="_blank" rel="noreferrer"><?php Icon_Class::polen_icon_social("facebook"); ?></a>
			<a href="https://www.instagram.com/polen.me" target="_blank" rel="noreferrer"><?php Icon_Class::polen_icon_social("instagram"); ?></a>
			<a href="https://vm.tiktok.com/ZMeKtWr1H/" target="_blank" rel="noreferrer"><?php Icon_Class::polen_icon_social("tiktok"); ?></a>
			<a href="https://twitter.com/polen_me" target="_blank" rel="noreferrer"><?php Icon_Class::polen_icon_social("twitter"); ?></a>
		</div>
		<div class="col-md-12 pt-3 text-center">2021 @<?= get_bloginfo('name'); ?></div>
	</div><!-- .site-info -->
</footer><!-- #colophon -->

<?php include_once TEMPLATE_DIR . '/inc/custom-footer.php'; ?>

</div><!-- #Container -->

<?php wp_footer(); ?>
<?php do_action( 'polen_messages_service_error' ); ?>
<?php do_action( 'polen_messages_service_success' ); ?>
<?php Polen\Includes\Polen_Messages::clear_messages(); ?>
<?php include_once TEMPLATE_DIR . '/inc/analitics_footer.php'; ?>
</body>

</html>

<?php if( defined('DEV_ENV') && DEV_ENV ) : ?>
<!--
<?php print_r( get_included_files() ); ?>
-->
<?php endif; ?>
