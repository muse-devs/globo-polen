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

  <?php wp_enqueue_script('polen-help'); ?>

	<main id="primary" class="site-main">
    <section id="faq" class="my-4">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <h2 class="text-center title">Perguntas Frequentes</h2>
          </div>
          <?php
            $faq = get_the_content();
            $faq = apply_filters( 'the_content', get_the_content() );
            $faq = str_replace('</p>', '', $faq);
            $faq = explode('<p>', $faq);
            //print_r($faq);
          ?>
          <div class="col-md-10 col-sm-12 my-3 mx-auto">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
              <?php foreach ($faq as $key => $item) : ?>
                <?php
                  if ($key % 2 != 0) {
                ?>
                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading-<?php echo $key; ?>">
                      <h4 class="panel-title">
                        <a class="panel-button" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?php echo $key; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $key; ?>">
                          <?php echo $item; ?>
                        </a>
                      </h4>
                    </div>
                    <div id="collapse-<?php echo $key; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-<?php echo $key; ?>">
                      <div class="panel-body">
                        <?php echo $faq[$key + 1]; ?>
                      </div>
                    </div>
                  </div>
                <?php
                  }
                ?>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="col-sm-12 mt-5">
            <h2 class="text-center title">Ainda possui dúvidas?</h2>
          </div>
          <div class="col-sm-12 mt-1">
            <p class="text-center subtitle">Entre em contato conosco 😀</p>
          </div>
        </div>
      </div>
    </section>
    <section id="bus-form-wrapper" class="row mb-5 bus-form">
      <div class="col-12 col-md-8 m-md-auto">
        <form id="help-form" v-on:submit.prevent="handleSubmit" method="POST">
          <input type="hidden" id="url-success" value="<?php echo enterprise_url_success(); ?>" />
          <input type="hidden" name="action" value="submit_form" />
          <input type="hidden" name="form_id" value="2" />
          <input type="hidden" name="terms" value="1" />
          <label class="pol-input-group mb-3" aria-required="true">
            <span class="label">Nome Completo</span>
            <input type="text" class="input" name="name" placeholder="Seu nome" required />
          </label>
          <label class="pol-input-group mb-3" aria-required="true">
            <span class="label">e-mail</span>
            <input type="email" name="email" class="input" placeholder="Seu melhor e-mail" required />
          </label>
          <label class="pol-input-group mb-3" aria-required="true">
            <span class="label">Telefone de contato</span>
            <input type="text" name="phone" v-model="phone" v-on:keyup="handleChange" class="input" placeholder="(DDD) XXXXX-XXXX" maxlength="15" required />
          </label>
          <label class="pol-input-group mb-3" aria-required="true">
            <span class="label">Mensagem</span>
            <textarea name="message" placeholder="Descreva o que gostaria de saber" rows="6" required></textarea>
          </label>
          <input type="submit" class="btn btn-primary btn-lg btn-block mt-4" value="Enviar" />
        </form>
      </div>
    </section>
	</main><!-- #main -->

<?php
get_footer();
