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
	<?php polen_get_header_objects(); ?>
	<?php wp_head(); ?>
	<?php include_once TEMPLATE_DIR . '/inc/analitics_header.php'; ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<?php include_once TEMPLATE_DIR . '/inc/analitics_init_body.php'; ?>
	<div id="page" class="container-fluid tributes">
		<header id="masthead" class="header border-bottom mb-4">
			<div class="container">
				<div class="row pt-3 pb-4">
					<div class="col-6 logo-tribute-wrap<?php echo is_tribute_home() ? '' : ' text-center'; ?>">
						<a href="<?= tribute_get_url_base_url(); ?>" class="custom-logo-link" rel="home" aria-current="page">
							<img width="37" height="37" src="<?php echo TEMPLATE_URI; ?>/tributes/assets/img/logo-icon.svg" class="custom-logo light" alt="Polen" />
							<span class="logo-text ml-2">Colab</span>
						</a>
					</div>
					<div class="col-6 text-right">
						<p class="pt-4"><a href="#acompanheseupedido">acompanhe seu pedido</a></p>
					</div>
				</div>
			</div>
		</header><!-- #masthead -->
