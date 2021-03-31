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

function polen_icon_download()
{
	echo '<i class="bi bi-download"></i>';
}

function polen_icon_copy()
{
	echo '<i class="bi bi-clipboard"></i>';
}

function polen_icon_chevron_right()
{
	echo '<i class="bi bi-chevron-right"></i>';
}

function polen_icon_close()
{
	echo '<i class="bi bi-x"></i>';
}

function polen_icon_social($ico)
{
	$ret = '';
	switch ($ico) {
		case 'facebook':
			$ret = '<i class="bi bi-facebook"></i>';
			break;

		case 'instagram':
			$ret = '<i class="bi bi-instagram"></i>';
			break;

		case 'linkedin':
			$ret = '<i class="bi bi-linkedin"></i>';
			break;

		case 'twitter':
			$ret = '<i class="bi bi-twitter"></i>';
			break;

		default:
			$ret = '';
			break;
	}

	echo $ret;
}

function polen_front_get_banner()
{
?>
	<section class="top-banner mb-2">
		<video class="video" autoplay muted loop>
			<source src="<?= TEMPLATE_URI; ?>/assets/video.mp4" type="video/mp4">
			<!-- <source src="movie.ogg" type="video/ogg"> -->
		</video>
		<div class="content">
			<h2 class="title">Presenteie e<br />surpreenda com vídeos personalizados.</h2>
			<a href="#como" class="player-button-link">Como funciona</a>
		</div>
	</section>
<?php
}

// $size pode ser 'medium' e 'small'
function polen_front_get_card($item, $size = "medium")
{
	$class = "col-6 col-md-3";
	if ($size === "small") {
		$class = "col-6";
	}

	if( isset( $item['ID'] ) ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $item['ID'] ), 'single-post-thumbnail' );
	} else {
		$image = array();
		$image[] = '';
	}

?>
	<div class="<?= $class; ?>">
		<div class="polen-card <?= $size; ?>">
			<figure class="image" style="background-image: url(<?php echo $image[0]; ?>);">
				<a href="<?= $item["talent_url"]; ?>" class="link">
					<img src="<?php echo $image[0]; ?>" alt="<?= $item["name"]; ?>">
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
}

function polen_front_get_news($items)
{
	if (!$items) {
		return;
	}
?>
	<section class="row pt-4 news">
		<div class="col-md-12">
			<header class="row mb-3">
				<div class="col">
					<h2>Destaques</h2>
				</div>
				<div class="col d-flex justify-content-end align-items-center"><a href="#">Ver todos <?php polen_icon_chevron_right(); ?></a></div>
			</header>
		</div>
		<div class="col-md-12">
			<div class="row slick-padding">
				<?php foreach ($items as $item) : ?>
					<?php polen_front_get_card($item); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php
}

function polen_front_get_categories($items)
{
	if (!$items) {
		return;
	}
?>
	<section class="row py-4 categories">
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
}

function polen_front_get_artists($items, $title)
{
	if (!$items) {
		return;
	}
?>
	<section class="row py-4 all-artists">
		<div class="col-md-12">
			<header class="row mb-4">
				<div class="col">
					<h2><?= $title; ?></h2>
				</div>
				<div class="col d-flex justify-content-end align-items-center"><a href="#">Ver todos <?php polen_icon_chevron_right(); ?></a></div>
			</header>
		</div>
		<div class="col-md-12">
			<div class="row">
				<?php foreach ($items as $item) : ?>
					<?php polen_front_get_card($item, "small"); ?>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="col-12">
			<div class="row mt-md-5">
				<div class="col-md-12 text-center">
					<button type="button" class="btn btn-primary btn-lg">Ver todos</button>
				</div>
			</div>
		</div>
	</section>
<?php
}

function polen_front_get_tutorial()
{
	echo '
	<section class="row tutorial">
		<div class="col-md-12">
			<header class="row mb-4">
				<div class="col text-center">
					<h2>Como funciona</h2>
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

function polen_front_get_talent_videos($talent, $items = array(
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
?>
	<div class="talent-carousel">
		<?php foreach ($items as $item) : ?>
			<figure class="item" style="background-image: url(<?= $item['image']; ?>);">
				<img src="<?= $item['image']; ?>" alt="<?= $item['title']; ?>" data-url="<?= $item['video']; ?>">
				<a href="javascript:openVideoByURL('<?= $item['video']; ?>')" class="player-button"></a>
			</figure>
		<?php endforeach; ?>
	</div>

	<div id="video-modal" class="video-modal">
		<div class="video-card">
			<header>
				<button id="close-button" class="close-button" onclick="hideModal()"><?php polen_icon_close(); ?></button>
				<div id="video-box"></div>
			</header>
			<div class="content mt-4 mx-3">
				<header class="row content-header">
					<div class="col-3">
						<div class="avatar" style="background-image: url(<?php echo isset($talent->avatar) ? $talent->avatar : TEMPLATE_URI . '/assets/img/avatar.png';  ?>)"></div>
					</div>
					<div class="col-9">
						<h4 class="name"><?php echo $talent->nome; ?></h4>
						<h5 class="cat"><?php echo $talent->profissao; ?></h5>
						<a href="www.muse.me/v/600f82be59bee5001dc70ea8" class="url">www.muse.me/v/600f82be59bee5001dc70ea8</a>
					</div>
				</header>
				<div class="row mt-4 share">
					<div class="col-12">
						<input type="text" id="share-input" class="share-input" />
						<a href="javascript:copyToClipboard(window.location.href)" class="btn btn-outline-light btn-lg btn-block share-link"><?php polen_icon_copy(); ?>Copiar link</a>
						<a href="javascript:void(0)" class="btn btn-outline-light btn-lg btn-block share-link" target="_blank"><?php polen_icon_download(); ?>Download</a>
						<a href="<?php echo $talent->facebook; ?>" class="btn btn-outline-light btn-lg btn-block share-link" target="_blank"><?php polen_icon_social('facebook'); ?>Facebook</a>
						<a href="<?php echo $talent->instagram; ?>" class="btn btn-outline-light btn-lg btn-block share-link" target="_blank"><?php polen_icon_social('instagram'); ?>Instagram</a>
						<a href="<?php echo $talent->twitter; ?>" class="btn btn-outline-light btn-lg btn-block share-link" target="_blank"><?php polen_icon_social('twitter'); ?>Twitter</a>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}

function polen_get_talent_card($talent)
{
?>
	<div class="my-4 talent-card">
		<header class="row pb-3 header">
			<div class="col-3">
				<div class="avatar" style="background-image: url(<?php echo isset($talent->avatar) ? $talent->avatar : TEMPLATE_URI . '/assets/img/avatar.png';  ?>)"></div>
			</div>
			<div class="col-9 mt-2">
				<h4 class="name"><?php echo $talent["name"]; ?></h4>
				<h5 class="cat"><?php echo $talent["career"]; ?></h5>
			</div>
		</header>
		<div class="price-box pt-2">
			<span class="cat">Você vai pagar</span>
			<p class="price mt-2"><?php echo $talent["price"]; ?></p>
			<?php if ($talent["has_details"]) : ?>
				<button class="show-details"><?php Icon_Class::polen_icon_chevron("down") ?></button>
			<?php endif; ?>
		</div>
		<footer class="row mt-3 details">
			<div class="col pt-4">
				<div class="row personal">
					<div class="col d-flex">
						<div class="item">
							<span class="title big">Vídeo de</span>
							<p class="value"><?php echo $talent["from"]; ?></p>
						</div>
						<div class="item mx-3">
							<?php Icon_Class::polen_icon_arrows(); ?>
						</div>
						<div class="item">
							<span class="title big">Vídeo para</span>
							<p class="value"><?php echo $talent["to"]; ?></p>
						</div>
					</div>
				</div>
				<div class="row ocasion">
					<div class="col-12">
						<span class="title big">Ocasião</span>
						<p class="value"><?php echo $talent["category"]; ?></p>
					</div>
				</div>
				<div class="row mail">
					<div class="col-12">
						<span class="title">e-mail</span>
						<p class="value"><?php echo $talent["mail"]; ?></p>
					</div>
				</div>
				<div class="row description">
					<div class="col-12">
						<span class="title">Instruções</span>
						<p class="value"><?php echo $talent["description"]; ?></p>
					</div>
				</div>
			</div>
		</footer>
	</div>
<?php
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
