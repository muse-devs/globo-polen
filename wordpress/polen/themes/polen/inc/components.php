<?php

function polen_icon_share()
{
	echo '<i class="bi bi-share-fill"></i>';
}

function polen_icon_clock()
{
	echo '<i class="bi bi-clock"></i>';
}

function polen_icon_star($active = false)
{
	if ($active) {
		echo '<i class="bi bi-star-fill" style="color: #FFF963;"></i>';
	} else {
		echo '<i class="bi bi-star"></i>';
	}
}

function polen_front_get_banner()
{
	ob_start();
?>
	<section class="row my-5 py-4 top-banner">
		<div class="col-sm-12">
			<video class="video" autoplay muted>
				<source src="<?= TEMPLATE_URI; ?>/assets/video.mp4" type="video/mp4">
				<!-- <source src="movie.ogg" type="video/ogg"> -->
			</video>
			<div class="content">
				<h2 class="title">Presenteie e<br />surpreenda com vídeos personalizados.</h2>
				<a href="#como" class="player-button">Como funciona</a>
			</div>
		</div>
	</section>
<?php
	$data = ob_get_contents();
	ob_end_clean();
	echo $data;
}

// $size pode ser 'medium' e 'small'
function polen_front_get_card($item, $size = "medium")
{
	$class = "col-md-3";
	if ($size === "small") {
		$class = "col-flex-20";
	}
	ob_start();
?>
	<div class="<?= $class; ?>">
		<div class="polen-card <?= $size; ?>">
			<figure class="image">
				<a href="<?= $item["talent_url"]; ?>">
					<img src="<?= $item["image"]; ?>" alt="<?= $item["name"]; ?>">
				</a>
				<span class="price">R$<?= $item["price"]; ?></span>
			</figure>
			<h4 class="title">
				<a href="<?= $item["talent_url"]; ?>"><?= $item["name"]; ?></a>
			</h4>
			<h5 class="category">
				<a href="<?= $item["category_url"]; ?>"><?= $item["category"]; ?></a>
			</h5>
		</div>
	</div>
<?php
	$data = ob_get_contents();
	ob_end_clean();
	echo $data;
}

function polen_front_get_news($items)
{
	if (!$items) {
		return;
	}
	ob_start();
?>
	<section class="row my-5 py-4 news">
		<div class="col-md-12">
			<header class="row mb-4">
				<div class="col">
					<h2>Novidades</h2>
				</div>
				<div class="col d-flex justify-content-end align-items-center"><a href="#">Ver todos</a></div>
			</header>
		</div>
		<div class="col-md-12">
			<div class="row">
				<?php foreach ($items as $item) : ?>
					<?php polen_front_get_card($item); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php
	$data = ob_get_contents();
	ob_end_clean();
	echo $data;
}

function polen_front_get_categories($items)
{
	if (!$items) {
		return;
	}
	ob_start();
?>
	<section class="row my-5 py-4 categories">
		<div class="col-md-12">
			<header class="row mb-4">
				<div class="col">
					<h2>Categorias</h2>
				</div>
				<div class="col d-flex justify-content-end align-items-center"><a href="#">Ver todos</a></div>
			</header>
		</div>
		<div class="col md-12">
			<div class="row">
				<?php foreach ($items as $item) : ?>
					<div class="col-md-3">
						<figure class="polen-card category">
							<img src="<?= $item["image"] ?>" alt="<?= $item["title"] ?>">
							<a href="<?= $item["url"] ?>" class="link"><?= $item["title"] ?></a>
						</figure>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php
	$data = ob_get_contents();
	ob_end_clean();
	echo $data;
}

function polen_front_get_artists($items, $title)
{
	if (!$items) {
		return;
	}
	ob_start();
?>
	<section class="row my-5 py-4 all-artists">
		<div class="col-md-12">
			<header class="row mb-4">
				<div class="col">
					<h2><?= $title; ?></h2>
				</div>
				<div class="col d-flex justify-content-end align-items-center"><a href="#">Ver mais</a></div>
			</header>
		</div>
		<div class="col-md-12">
			<div class="row d-flex justify-content-between flex-wrap">
				<?php foreach ($items as $item) : ?>
					<?php polen_front_get_card($item, "small"); ?>
				<?php endforeach; ?>
			</div>
			<div class="row mt-5">
				<div class="col-md-12 text-center">
					<button type="button" class="btn btn-primary btn-lg">Ver mais</button>
				</div>
			</div>
		</div>
	</section>
<?php
	$data = ob_get_contents();
	ob_end_clean();
	echo $data;
}

function polen_front_get_tutorial()
{
	echo '
	<section class="row my-5 py-4 tutorial">
		<div class="col-md-12">
			<header class="row mb-4">
				<div class="col text-center">
					<h2>Como funciona</h2>
					<p class="mt-4">Presenteie e surpreenda com vídeos personalizados.</p>
				</div>
			</header>
		</div>
		<div class="col-md-12">
			<div class="row mt-5">
				<div class="col-md-4">
					<div class="tutorial-card">
						<figure class="image">
							<img src="https://picsum.photos/200/200" alt="Conect-se aos ídolos">
						</figure>
						<h5 class="title">Conecte-se aos ídoles</h5>
						<p class="description">Peça um vídeo personalizado com o seu ídolo para celebar ocasiões especiais.</p>
					</div>
				</div>
				<div class="col-md-4">
					<div class="tutorial-card">
						<figure class="image">
							<img src="https://picsum.photos/200/200" alt="Conect-se aos ídolos">
						</figure>
						<h5 class="title">Receba sua encomenda</h5>
						<p class="description">Ídolo recebe o seu pedido, atende e entrega pela plataforma. </p>
					</div>
				</div>
				<div class="col-md-4">
					<div class="tutorial-card">
						<figure class="image">
							<img src="https://picsum.photos/200/200" alt="Conect-se aos ídolos">
						</figure>
						<h5 class="title">Mande para todo mundo</h5>
						<p class="description">Você pode enviar o vídeo para os amigos ou postar nas redes.</p>
					</div>
				</div>
			</div>
		</div>
	</section>
	';
}

function polen_front_get_talent_videos($items = array(
	array("title" => "Video 1", "image" => "http://i.vimeocdn.com/video/731672459_640.jpg", "video" => "https://vimeo.com/280815263"),
	array("title" => "Video 2", "image" => "http://i.vimeocdn.com/video/649503401_640.jpg", "video" => "https://vimeo.com/229243103"),
	array("title" => "Video 3", "image" => "http://i.vimeocdn.com/video/735151132_640.jpg", "video" => "https://vimeo.com/297461374"),
	array("title" => "Video 1", "image" => "http://i.vimeocdn.com/video/731672459_640.jpg", "video" => "https://vimeo.com/280815263"),
	array("title" => "Video 2", "image" => "http://i.vimeocdn.com/video/649503401_640.jpg", "video" => "https://vimeo.com/229243103"),
	array("title" => "Video 3", "image" => "http://i.vimeocdn.com/video/735151132_640.jpg", "video" => "https://vimeo.com/297461374"),
	array("title" => "Video 1", "image" => "http://i.vimeocdn.com/video/731672459_640.jpg", "video" => "https://vimeo.com/280815263"),
	array("title" => "Video 2", "image" => "http://i.vimeocdn.com/video/649503401_640.jpg", "video" => "https://vimeo.com/229243103"),
	array("title" => "Video 3", "image" => "http://i.vimeocdn.com/video/735151132_640.jpg", "video" => "https://vimeo.com/297461374"),
))
{
	ob_start();
?>
	<div class="talent-carousel">
		<?php foreach ($items as $item) : ?>
			<figure class="item">
				<img src="<?= $item['image']; ?>" alt="<?= $item['title']; ?>" data-url="<?= $item['video']; ?>">
				<a href="javascript:openVideoByURL('<?= $item['video']; ?>')" class="player-button"></a>
			</figure>
		<?php endforeach; ?>
	</div>

	<div id="video-modal" class="video-modal" onclick="hideModal()"></div>
	<div id="video-box" class="video-box">
		<header>
			<button id="share-button" class="share-button"><?php polen_icon_share(); ?></button>
		</header>
	</div>

	<script>
		var modal = document.getElementById('video-modal');
		var video_box = document.getElementById('video-box');
		var share_button = document.getElementById('share-button');

		jQuery(document).ready(function() {
			jQuery('.talent-carousel').slick({
				infinite: true,
				speed: 300,
				slidesToShow: 1,
				variableWidth: true
			});
			var id = window.location.hash.substring(1);
			if (id) {
				openVideoById(id);
			}
		});

		share_button.addEventListener('click', async () => {
			var shareData = {
				title: '',
				text: '',
				url: '',
			}
			if (navigator.share) {
				try {
					await navigator.share(shareData)
				} catch (err) {
					alert('Error: ' + err);
				}
			}
		});

		function changeHash(hash) {
			window.location.hash = hash || "";
		}

		function addVideo() {
			var div = document.createElement('DIV');
			div.id = "polen-video";
			div.className = "polen-video";
			video_box.appendChild(div);
		}

		function killVideo() {
			var video = document.getElementById('polen-video');
			video.parentNode.removeChild(video);
		}

		function showModal() {
			modal.classList.add('show');
			video_box.classList.add('show');
		}

		function hideModal(e) {
			changeHash();
			killVideo();
			modal.classList.remove('show');
			video_box.classList.remove('show');
		}

		function openVideoByURL(url) {
			addVideo();
			showModal();
			var videoPlayer = new Vimeo.Player('polen-video', {
				url: url
			});
			videoPlayer.getVideoId().then(function(id) {
				console.log('id:', id);
				changeHash(id);
			});
		}

		function openVideoById(id) {
			addVideo();
			showModal();
			var videoPlayer = new Vimeo.Player('polen-video', {
				id: id
			});
			changeHash(id);
		}
	</script>
<?php
	$data = ob_get_contents();
	ob_end_clean();
	echo $data;
}

/**
 * Gets the thumbnail url for a vimeo video using the video id. This only works for public videos.
 *
 * @param string $id        The video id.
 * @param string $thumbType Thumbnail image size. supported sizes: small, medium (default) and large.
 *
 * @return string|bool
 */

function getVimeoVideoThumbnailByVideoId($id = '', $thumbType = 'large')
{

	$id = trim($id);

	if ($id == '') {
		return FALSE;
	}

	$apiData = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$id.php"));

	if (is_array($apiData) && count($apiData) > 0) {

		$videoInfo = $apiData[0];

		switch ($thumbType) {
			case 'small':
				return $videoInfo['thumbnail_small'];
				break;
			case 'large':
				return $videoInfo['thumbnail_large'];
				break;
			case 'medium':
				return $videoInfo['thumbnail_medium'];
			default:
				break;
		}
	}

	return FALSE;
}
