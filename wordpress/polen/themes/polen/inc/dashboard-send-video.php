<?php /* Template Name: Envio de Vídeo */
wp_enqueue_script('polen-upload-video', TEMPLATE_URI . '/assets/js/' . $min . 'upload-video.js', array("jquery"), _S_VERSION, true);
do_action('polen_before_upload_video');

$order_id = filter_input(INPUT_GET, 'order_id');
$order = wc_get_order($order_id);
$polen_order = \Polen\Includes\Cart\Polen_Cart_Item_Factory::polen_cart_item_from_order($order);
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
				<!--<button class="send-video btn btn-primary btn-lg btn-block">Escolher Vídeo</button>-->


				<form id="form-video-upload" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label for="file-data">Example file input</label>
						<input type="file" class="form-control-file" id="file-video" name="file_data" accept="video/*" capture="user">
					</div>
					<button type="submit" class="send-video btn btn-primary btn-lg btn-block">Enviar</button>
				</form>
				<div id="progress"></div>


			</div>
		</div>
		<div class="row" style="display: none;">
			<div class="col-md-4">
				<div class="row">
					<div class="col-12">
						<h4>Mensagem para:</h4>
						<p class="p"><?= $polen_order->get_name_to_video(); ?></p>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<h4>Ocasião:</h4>
						<p class="p"><?= $polen_order->get_video_category(); ?></p>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<h4>Instruções:</h4>
				<p class="p"><?= $polen_order->get_instructions_to_video(); ?></p>
			</div>
		</div>
	</article>
</main><!-- #main -->

<?php
get_footer();
