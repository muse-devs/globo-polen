<?php

/**
 * Template name: Página Inicial
 */

get_header();
$inputs = new Material_Inputs();
?>

<main id="primary" class="site-main">
  <h1 class="d-none">Presenteie e supreenda com Vídeos Personalizados.</h1>

  <!-- Categorias -->
  <?php polen_front_get_categories_buttons(); ?>

  <!-- Banner Principal - Vídeo -->
  <?php polen_front_get_banner_video(); ?>

  <!-- Listagem de Talentos - Destaques -->
  <?php polen_talents_by_category(polen_get_new_talents(10), "Destaque"); ?>

  <!-- Banners -->
	<div class="row">
		<div class="col-12">
			<div id="product-carousel" class="owl-carousel owl-theme">
        <div class="item">
					<?php polen_get_galo_banner(site_url('tag/galo-idolos/')); ?>
				</div>
			</div>
		</div>
	</div>

  <!-- Como Funciona -->
  <?php polen_front_get_tutorial(); ?>

  <!-- Listagem de Vídeos em Destaque -->
  <?php
    $videos = ["3492", "3806", "3554", "2930", "3898", "3168"];
    polen_front_get_videos(polen_get_home_stories($videos));
  ?>

  <!-- Listagem de Talentos - Música -->
  <?php polen_talents_by_category(polen_get_talents_by_product_cat("musica", 10), "Música"); ?>

  <!-- Listagem de Talentos - Atrizes e Atores -->
  <?php polen_talents_by_category(polen_get_talents_by_product_cat("atrizes-e-atores", 10), "Atrizes e Atores"); ?>

  <!-- Listagem de Talentos - Apresentadores -->
  <?php polen_talents_by_category(polen_get_talents_by_product_cat("apresentadores", 10), "Apresentadores"); ?>

  <!-- Listagem de Talentos - Esporte -->
  <?php polen_talents_by_category(polen_get_talents_by_product_cat("esporte", 10), "Esporte"); ?>

  <!-- Listagem de Talentos - Influencers -->
  <?php polen_talents_by_category(polen_get_talents_by_product_cat("influencers", 10), "Influencers"); ?>

  <!-- Listagem de Talentos - Comediantes -->
  <?php polen_talents_by_category(polen_get_talents_by_product_cat("comediantes", 10), "Comediantes"); ?>

  <!-- Banners -->
	<div class="row d-flex justify-content-center my-4">
		<div class="col-xs-12 col-sm-6 mb-5">
      <?php $inputs->material_button_link_outlined("todos", "Ver todos ídolos", home_url( "shop" ), false, "", array()); ?>
		</div>
	</div>

  <!-- Polen na Mídia -->
  <?php polen_get_media_news(); ?>

</main><!-- #main -->

<?php
get_footer();
