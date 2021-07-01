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
	<script>
		var polenObj = {
			base_url: '<?= site_url(); ?>',
			developer: <?php echo DEVELOPER ? 1 : 0; ?>,
			COOKIES: <?php echo json_encode(POL_COOKIES); ?>
		};
		if (!polenObj.developer) {
			console = {
				debug: function() {},
				error: function() {},
				info: function() {},
				log: function() {},
				warn: function() {},
			};
		}
	</script>
	<?php wp_head(); ?>
	<?php include_once TEMPLATE_DIR . '/inc/analitics_header.php'; ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<?php include_once TEMPLATE_DIR . '/inc/analitics_init_body.php'; ?>
	<div id="page" class="container-fluid site">
		<header id="masthead" class="header border-bottom">
			<div class="container">
				<div class="row pt-3 pb-4">
					<div class="col-6 col-sm-6 logo-tribute-wrap">
						<?php polen_the_theme_logos(); ?>
					</div>
				</div>
			</div>
		</header><!-- #masthead -->
