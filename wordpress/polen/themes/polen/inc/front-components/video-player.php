<?php

function polen_get_talent_video_buttons($talent, $video_url, $video_download, $hash = null, $product = null)
{
	$donate = $product ? get_post_meta($product->get_id(), '_is_charity', true) : false;
?>
	<?php if ($product && $product->is_in_stock()) : ?>
		<button onclick="document.querySelector('.single_add_to_cart_button').click()" class="btn btn-primary btn-lg btn-block mb-4">
			<?php if ($donate) : ?>
				<span class="mr-2"><?php Icon_Class::polen_icon_donate(); ?></span>
			<?php endif; ?>
			Pedir vídeo <?php echo $product->get_price(); ?>
		</button>
	<?php endif; ?>
	<?php if (wp_is_mobile()) : ?>
		<button onclick="shareVideo('Compartilhar vídeo de <?php echo $talent->nome; ?>', '<?php echo $video_url; ?>')" class="btn btn-outline-light btn-lg btn-block share-link mb-4"><?php Icon_Class::polen_icon_share(); ?>Compartilhar</button>
	<?php endif; ?>
	<button onclick="copyToClipboard('<?php echo $video_url; ?>')" class="btn btn-outline-light btn-lg btn-block share-link mb-4"><?php Icon_Class::polen_icon_clipboard(); ?>Copiar Link</button>
	<?php if (!empty($video_download)) : ?>
		<?php $video_download_nonce = wp_create_nonce('generate-download-video-url'); ?>
		<a href="#" onclick="downloadClick_handler(event)" data-download="<?= $hash; ?>" data-nonce="<?= $video_download_nonce; ?>" class="btn btn-outline-light btn-lg btn-block share-link mb-4"><?php Icon_Class::polen_icon_download(); ?>Download</a>
	<?php endif; ?>
<?php
}

function polen_video_icons($user_id, $iniciais, $first = false)
{
?>
	<div class="video-icons">
		<figure class="image-cropper color small">
			<?php echo polen_get_avatar($user_id, 'polen-square-crop-lg'); ?>
		</figure>
		<?php if ($first) : ?>
			<figure class="image-cropper small">
				<img src="<?php echo TEMPLATE_URI . "/assets/img/logo-round-orange.svg" ?>" alt="Logo redonda">
			</figure>
		<?php else : ?>
			<div class="text-cropper small"><?php echo $iniciais; ?></div>
		<?php endif; ?>
	</div>
<?php
}

function polen_get_video_player_html($data, $user_id = null)
{
	if (!$data) {
		return;
	}
	wp_enqueue_script("vimeo");

	$video_url = tribute_get_url_base_url() . "/v/" . $data->slug;
?>
	<div class="row video-card">
		<header class="col-md-6 p-0">
			<div id="video-box">
				<div id="polen-video" class="polen-video"></div>
			</div>
			<script>
				jQuery(document).ready(function() {
					var videoPlayer = new Vimeo.Player("polen-video", {
						url: "<?php echo $data->vimeo_link; ?>",
						autoplay: false,
						muted: false,
						loop: false,
						width: document.getElementById("polen-video").offsetWidth,
					});
				})
			</script>
		</header>
		<div class="content col-md-6 mt-4">
			<header class="row content-header">
				<div class="col-9">
					<h4 class="m-0 name">Seu vídeo</h4>
					<h5 class="mt-3 cat">Colab para <?php echo $data->name_honored; ?></h5>
				</div>
			</header>
			<div class="row mt-4 share">
				<div class="col-12">
					<?php polen_get_talent_video_buttons($data, $video_url, $data->vimeo_url_download, $data->hash); ?>
				</div>
			</div>
		</div>
	</div>
<?php
}

/**
 * Cria a tela para assitir video
 * @param stdClass $talent Polen_Update_Fields
 * @param Polen_Video_Info $video
 * @param int $user_id
 * @return html
 */
function polen_get_video_player($talent, $video, $user_id, $product = null)
{
	if (!$talent || !$video) {
		return;
	}
	$user_talent = get_user_by('id', $talent->user_id);
	wp_enqueue_script('vimeo');
	$video_url = home_url() . "/v/" . $video->hash;
	$isRateble = \Polen\Includes\Polen_Order_Review::can_make_review($user_id, $video->order_id);
?>
	<div class="row video-card">
		<header class="col-md-6 p-0">
			<div id="video-box">
				<div id="polen-video" class="polen-video"></div>
			</div>
			<script>
				jQuery(document).ready(function() {
					var videoPlayer = new Vimeo.Player("polen-video", {
						url: "<?php echo $video->vimeo_link; ?>",
						autoplay: false,
						muted: false,
						loop: false,
						width: document.getElementById("polen-video").offsetWidth,
					});
				})
			</script>
		</header>
		<div class="content col-md-6 mt-4">
			<header class="row content-header">
				<div class="col-3">
					<a href="<?php echo $talent->talent_url; ?>" class="no-underline">
						<span class="image-cropper">
							<?php echo polen_get_avatar($talent->user_id, "polen-square-crop-lg"); ?>
						</span>
					</a>
				</div>
				<div class="col-9">
					<h4 class="m-0"><a href="<?php echo $talent->talent_url; ?>" class="name"><?php echo $user_talent->display_name; ?></a></h4>
					<h5 class="m-0"><a href="<?= polen_get_url_category_by_order_id($video->order_id); ?>" class="d-block my-2 cat"><?php echo $talent->profissao; ?></a></h5>
					<a href="<?php echo $video_url; ?>" class="url"><?php echo $video_url; ?></a>
				</div>
			</header>
			<div class="row mt-4 share">
				<div class="col-12">
					<?php if ($user_id !== 0 && $isRateble) : ?>
						<a href="/my-account/create-review/<?= $video->order_id; ?>" class="btn btn-primary btn-lg btn-block mb-4">Avaliar vídeo</a>
					<?php endif; ?>
					<?php polen_get_talent_video_buttons($talent, $video_url, $video->vimeo_url_download, $video->hash, $product); ?>
				</div>
			</div>
		</div>
	</div>
<?php
}

function polen_player_video_modal_ajax_invalid_hash()
{
?>
	<h4>Conteúdo indisponível</h4>
<?php
}
