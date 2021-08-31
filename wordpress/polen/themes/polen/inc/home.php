<?php

/**
 * Template name: Página Inicial
 */

get_header();
?>

<main id="primary" class="site-main">
	<h1 class="d-none">Presenteie e supreenda com Vídeos Personalizados.</h1>

	<?php polen_front_get_banner_with_carousel();
	?>

	<?php polen_banner_scrollable(polen_get_new_talents(6), "Destaque", polen_get_all_new_talents_url()); ?>

	<div class="row">
		<div class="col-12">
			<div id="product-carousel" class="owl-carousel owl-theme">
				<div class="item">
					<?php mc_get_home_banner(event_promotional_url_home()); ?>
				</div>
				<div class="item">
					<?php va_get_home_banner_de_porta_em_porta(event_promotional_url_home()); ?>
				</div>
				<div class="item">
					<?php
					//TODO link da landpage
					va_get_home_banner(
						"Rebeldes têm asas",
						"Agora você pode comprar o livro e ter um autógrafo em vídeo do Rony Meisler.",
						"#link",
						array(
							"mobile" => TEMPLATE_URI . '/assets/img/video-autografo/rta-banner-home-mobile.jpeg',
							"desktop" => TEMPLATE_URI . '/assets/img/video-autografo/rta-banner-home-desktop.jpeg',
						)
					);
					?>
				</div>
			</div>
		</div>
	</div>

	<?php polen_front_get_tutorial(); ?>

	<?php polen_front_get_news(polen_get_talents(12), "Todos os talentos", polen_get_all_talents_url());
	?>

	<?php //polen_front_get_suggestion_box();
	?>

</main><!-- #main -->

<?php
get_footer();
