<?php /* Template Name: Envio de Vídeo */ ?>

<?php get_header(); ?>

<main id="primary" class="site-main">
    <header class="entry-header">
        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
    </header>
    <article>
        <div class="row">
            <div class="col-12">
                <div class="box-video">
                    <div class="content-upload">
                        <div class="text">Enviando video</div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="buttons">
                            <button class="send-video"><?php polen_icon_upload(); ?>Enviar Vídeo</button>
                            <button class="cancel-send">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-12">
                        <h4>Mensagem para:</h4>
                        <p class="p">Raul</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h4>Ocasião:</h4>
                        <p class="p">Aniversário, Comemoração</p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <h4>Instruções:</h4>
                <p class="p">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean egestas eros eget nulla porta efficitur. Etiam id risus ut ipsum efficitur dignissim et id dui. Donec congue id libero vitae feugiat. Nam eget nibh nibh. Nunc hendrerit faucibus leo.</p>
            </div>
        </div>
    </article>
</main><!-- #main -->

<?php
get_footer();
