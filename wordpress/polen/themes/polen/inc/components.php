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

function polen_icon_arrows()
{
	echo '<img src="' . TEMPLATE_URI . '/assets/img/arrows.png" />';
}

function polen_icon_accept_reject($type = 'accept')
{
	if ($type === 'reject') {
		echo '<i class="bi bi-x"></i>';
	} else {
		echo '<i class="bi bi-check"></i>';
	}
}

function polen_icon_upload()
{
	echo '<i class="bi bi-cloud-arrow-up"></i>';
}

function polen_front_get_banner()
{
	ob_start();
?>
	<section class="row my-5 py-md-4 top-banner">
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
	$class = "col-6 col-md-3";
	if ($size === "small") {
		$class = "col-6 col-md-3 col-lg-2";
	}
	ob_start();
?>
	<div class="<?= $class; ?>">
		<div class="polen-card <?= $size; ?>">
			<figure class="image" style="background-image: url(<?= $item["image"]; ?>);">
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
	<section class="row my-5 py-md-4 news">
		<div class="col-md-12">
			<header class="row mb-4">
				<div class="col">
					<h2>Destaques</h2>
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
	<section class="row my-5 py-md-4 categories">
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
	<section class="row my-5 py-md-4 all-artists">
		<div class="col-md-12">
			<header class="row mb-4">
				<div class="col">
					<h2><?= $title; ?></h2>
				</div>
				<div class="col d-flex justify-content-end align-items-center"><a href="#">Ver mais</a></div>
			</header>
		</div>
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12 d-flex flex-wrap">
					<?php foreach ($items as $item) : ?>
						<?php polen_front_get_card($item, "small"); ?>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="row mt-md-5">
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
			<header class="row mb-md-4">
				<div class="col text-center">
					<h2>Como funciona</h2>
					<p class="mt-4">Presenteie e surpreenda com vídeos personalizados.</p>
				</div>
			</header>
		</div>
		<div class="col-md-12">
			<div class="row mt-3 mt-md-5">
				<div class="col-md-4 mb-4">
					<div class="tutorial-card">
						<figure class="image">
							<img src="' . TEMPLATE_URI . '/assets/img/tutorial_img_1.png" alt="Conect-se aos ídolos">
						</figure>
						<h5 class="title">Conecte-se aos ídolos</h5>
						<p class="description">Peça um vídeo personalizado com o seu ídolo para celebar ocasiões especiais.</p>
					</div>
				</div>
				<div class="col-md-4 mb-4">
					<div class="tutorial-card">
						<figure class="image">
							<img src="' . TEMPLATE_URI . '/assets/img/tutorial_img_2.png" alt="Conect-se aos ídolos">
						</figure>
						<h5 class="title">Receba sua encomenda</h5>
						<p class="description">Ídolo recebe o seu pedido, atende e entrega pela plataforma. </p>
					</div>
				</div>
				<div class="col-md-4 mb-4">
					<div class="tutorial-card">
						<figure class="image">
							<img src="' . TEMPLATE_URI . '/assets/img/tutorial_img_3.png" alt="Conect-se aos ídolos">
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
	<input id="share-input" type="text" class="share-input" />
	<div id="video-box" class="video-box">
		<header>
			<button id="share-button" class="share-button"><?php polen_icon_share(); ?></button>
		</header>
	</div>
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
