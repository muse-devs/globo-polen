<?php

/**
 * Template name: Página Inicial
 */

get_header();
?>

<main id="primary" class="site-main">
  <h1 class="d-none">Presenteie e supreenda com Vídeos Personalizados.</h1>

  <?php polen_front_get_categories_buttons(); ?>

  <?php polen_front_get_banner_video(); ?>

  <?php //polen_front_get_banner_with_carousel();
  ?>

  <?php polen_banner_scrollable(polen_get_new_talents(6), "Destaque", polen_get_all_new_talents_url()); ?>

	<div class="row">
		<div class="col-12">
			<div id="product-carousel" class="owl-carousel owl-theme">
        <!-- <div class="item">
					<?php //polen_get_lacta_banner(site_url('/lacta')); ?>
				</div> -->
        <div class="item">
					<?php polen_get_natal_banner(site_url('tag/natal-social/')); ?>
				</div>
			</div>
		</div>
	</div>

  <?php polen_front_get_tutorial(); ?>

  <?php
    $videos = ["3626", "3492", "3806", "3554", "2930", "3898", "3168"];
    polen_front_get_videos(polen_get_home_stories($videos));
  ?>

  <?php polen_front_get_news(polen_get_talents(12), "Todos os Ídolos", polen_get_all_talents_url());
  ?>

  <?php polen_get_media_news(); ?>

  <?php //polen_front_get_suggestion_box();
  ?>

</main><!-- #main -->

<?php
get_footer();
