<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Polen
 */

get_header();
?>

	<main id="primary" class="site-main">
    <section id="media-news" class="my-4">
      <div class="row">
        <div class="col-12">
          <h1>Polen na Mídia</h1>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-10 mb-4">
          <a href="#" target="_blank" rel="noreferrer">
            <article>
              <div class="news-text">
                <h4>Ana Maria</h4>
                <h2>Supla e João Suplicy gravam mensagens para fãs e doam cachê para instituição do Padre Júlio Lancelloti</h2>
                <h5>13/04/2021</h5>
              </div>
              <figure class="news-image" style="background: url('<?php echo TEMPLATE_URI; ?>/assets/img/about-banner.jpg')"></figure>
            </article>
          </a>
        </div>
        <div class="col-md-10 mb-4">
          <a href="#" target="_blank" rel="noreferrer">
            <article>
              <div class="news-text">
                <h4>Ana Maria</h4>
                <h2>Supla e João Suplicy gravam mensagens para fãs e doam cachê para instituição do Padre Júlio Lancelloti</h2>
                <h5>13/04/2021</h5>
              </div>
              <figure class="news-image" style="background: url('<?php echo TEMPLATE_URI; ?>/assets/img/about-banner.jpg')"></figure>
            </article>
          </a>
        </div>
      </div>
    </section>
	</main><!-- #main -->

<?php
get_footer();
