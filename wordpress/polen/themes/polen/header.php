<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Polen
 */

use Polen\Includes\Polen_Talent;

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover, user-scalable=no">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-title" content="<?php bloginfo('name'); ?>">
	<meta name="apple-touch-fullscreen" content="yes">
	<!-- <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"> -->
	<meta name="theme-color" content="#000000">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<script>
		var polenObj = {
			base_url: '<?= site_url(); ?>',
			developer: <?php echo DEVELOPER ? 1 : 0; ?>,
			COOKIES: <?php echo json_encode(POL_COOKIES); ?>
		};
		if (!polenObj.developer) {
			console = {
				debug: function () {},
				error: function () {},
				info: function () {},
				log: function () {},
				warn: function () {},
			};
		}
	</script>
	<?php wp_head(); ?>
    <?php include_once TEMPLATE_DIR . '/inc/analitics_header.php'; ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
    <?php include_once TEMPLATE_DIR . '/inc/analitics_init_body.php'; ?>
	<div id="page" class="container site">
		<header id="masthead" class="row pt-3 pb-4<?php echo is_front_page() ? " header-home" : ""; ?>">
			<div class="col-6 col-sm-6 d-flex align-items-center">
				<?php polen_the_theme_logos(); ?>
			</div>
			<?php if(!polen_is_landingpage()) : ?>
			<div class="col-6 col-sm-6 d-flex justify-content-end align-items-center">
				<?php //get_search_form();
				?>
				<div class="ml-2">
					<div class="dropdown">
						<?php
						if (is_user_logged_in()) {
							$user_name = wp_get_current_user();
						?>
							<a class="dropbtn">
								<div class="menu-user-data">
									<div class="user-avatar d-flex flex-wrap align-items-center justify-content-center">
										<?php echo polen_get_avatar( get_current_user_id(), "polen-square-crop-lg" ); ?>
									</div>
									<span class="text"><?php Icon_Class::polen_icon_chevron_down(); ?></span>
								</div>
							</a>
							<div class="dropdown-content background text">
								<div class="row mb-4 d-md-none">
									<div class="col-12">
										<div class="user-avatar d-flex flex-wrap align-items-center justify-content-center mb-1">
											<?php echo polen_get_avatar( get_current_user_id(), "polen-square-crop-lg" ); ?>
										</div>
										<p class="user-name"><?php echo $user_name->display_name; ?></p>
									</div>
									<a class="menu-close"><?php Icon_Class::polen_icon_close(); ?></a>
								</div>
								<div class="row">
									<div class="col-12">
									<?php if( Polen_Talent::static_is_user_talent( wp_get_current_user() ) ) : ?>
										<a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>">Dashboard</a>
									<?php endif; ?>
										<a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>">Meus pedidos</a>
										<?php /* <a href="<?php echo esc_url(wc_get_account_endpoint_url('payment-options')); ?>">Pagamentos</a> */ ?>
									<?php if( !Polen_Talent::static_is_user_talent( wp_get_current_user() ) ) : ?>
										<a href="<?php echo esc_url(wc_customer_edit_account_url()); ?>">Meus dados</a>
									<?php endif; ?>
										<a href="<?php echo esc_url(wp_logout_url()); ?>">Sair</a>
									</div>
								</div>
							</div>
						<?php
						} else { ?>
							<a class="btn btn-outline-light" href="<?php echo polen_get_login_url(); ?>">
								Login
							</a>
						<?php
						}
						?>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</header><!-- #masthead -->
