<?php /* Template Name: Envio de Vídeo */ 
do_action( 'polen_before_upload_video' );
?>

<?php get_header(); ?>

<main id="primary" class="site-main mt-4">
	<header class="entry-header">
		<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
		<button class="btn btn-outline-light btn-lg btn-block mt-4">Instruções</button>
	</header>
	<article>
		<div class="row my-4">
			<div class="col-12">
				<div class="py-5 text-center box-video">
					<div id="content-info" class="content-info show">
						<figure class="image">
							<img src="<?php echo TEMPLATE_URI ?>/assets/img/upload-info.png" alt="Gravar vídeo agora">
						</figure>
						<p class="info">Gravar Vídeo agora</p>
					</div>
					<div id="content-upload" class="content-upload">
						<div class="spinner-border text-secondary" role="status">
							<span class="sr-only">Loading...</span>
						</div>
						<p class="my-4"><strong id="progress-value">Enviando vídeo 75%</strong></p>
						<button class="btn btn-outline-light btn-lg">Cancelar</button>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<button class="send-video btn btn-primary btn-lg btn-block">Escolher Vídeo</button>
                                <form>
                                    <input type="file" class="form-control-file" id="file_data" name="file_data" aria-describedby="videoHelp" accept="video/*"/>
                                    <button type="submit">Enviar</button>
                                </form>
			</div>
		</div>
		<div class="row" style="display: none;">
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
