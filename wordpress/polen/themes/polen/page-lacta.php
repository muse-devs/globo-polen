<?php

/** Template Name: Página - Lacta */

$talents = polen_get_talents_by_campaingn('lacta');

get_header();
?>

<main id="primary" class="site-main">
  <div class="row">
    <header class="lacta-top-banner col-12 d-flex align-items-center">
      <div class="content mt-2">
        <h1 class="title">Um pedacinho de emoção para quem você ama.</h1>
        <p class="typo typo-text mt-2">Neste Natal, a Lacta vai aproximar ídolos e fãs com vídeos personalizados emocionantes!</p>
      </div>
    </header>
  </div>
  <div class="row mt-4">
    <div class="col-12">
      <?php
      //TODO -- função para trazer esses dados
      $videos = ["461", "421", "422"];
      polen_front_get_videos(polen_get_home_stories($videos), "Últimos vídeos gravados");
      ?>
    </div>
  </div>
  <?php
  while (have_posts()) :
    the_post();
    get_template_part('template-parts/content', 'page');
  endwhile; // End of the loop.
  ?>

</main><!-- #main -->

<?php
get_footer();
