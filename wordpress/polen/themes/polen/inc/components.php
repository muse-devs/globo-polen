<?php

function polen_front_get_banner()
{
?>
	<section class="top-banner mb-4">
		<video class="video video-mobile" autoplay muted loop playsinline poster="<?= TEMPLATE_URI; ?>/assets/img/video_poster1.jpg">
			<source src="<?= TEMPLATE_URI; ?>/assets/video/home1.m4v" type="video/mp4">
			<!-- <source src="movie.ogg" type="video/ogg"> -->
		</video>
		<?php if (!polen_is_mobile()) : ?>
			<video class="video video-desktop" autoplay muted loop playsinline poster="<?= TEMPLATE_URI; ?>/assets/img/video_poster2.jpg">
				<source src="<?= TEMPLATE_URI; ?>/assets/video/home2.m4v" type="video/mp4">
				<!-- <source src="movie.ogg" type="video/ogg"> -->
			</video>
		<?php endif; ?>
		<div class="content">
			<h2 class="title">Presenteie e<br />surpreenda com vídeos personalizados.</h2>
			<!-- <a href="#como" class="player-button-link">Como funciona</a> -->
		</div>
	</section>
<?php
}

// $size pode ser 'medium' e 'small'
function polen_front_get_card($item, $size = "small")
{
	$class = $size;
	if ($size === "small") {
		$class = "col-6 col-md-2";
	} elseif ($size === "medium") {
		$class = "col-6 col-md-3";
	}

	if (isset($item['ID'])) {
		$image = wp_get_attachment_image_src(get_post_thumbnail_id($item['ID']), 'polen-thumb-md');
	} else {
		$image = array();
		$image[] = '';
	}

?>
	<div class="<?= $class; ?>">
		<div class="polen-card <?= $size; ?>">
			<figure class="image">
				<a href="<?= $item["talent_url"]; ?>" class="link">
					<img loading="lazy" src="<?php echo $image[0]; ?>" alt="<?= $item["name"]; ?>">
				</a>
				<span class="price"><span class="mr-2"><?php Icon_Class::polen_icon_camera_video(); ?></span>R$<?= $item["price"]; ?></span>
			</figure>
			<h4 class="title text-truncate">
				<a href="<?= $item["talent_url"]; ?>"><?= $item["name"]; ?></a>
			</h4>
			<h5 class="category text-truncate">
				<a href="<?= $item["category_url"]; ?>"><?= $item["category"]; ?></a>
			</h5>
		</div>
	</div>
<?php
}

function polen_banner_scrollable($items, $title, $link)
{
	if (!$items) {
		return;
	}
?>
	<section class="row mb-4 banner-scrollable">
		<div class="col-md-12">
			<header class="row mb-3">
				<div class="col-12 d-flex justify-content-between align-items-center">
					<h2 class="mr-2"><?php echo $title; ?></h2>
					<a href="<?php echo $link; ?>">Ver todos <?php Icon_Class::polen_icon_chevron_right(); ?></a>
				</div>
			</header>
		</div>
		<div class="col-md-12" style="padding: 0;">
			<div class="banner-wrapper">
				<div class="banner-content">
					<?php foreach ($items as $item) : ?>
						<?php polen_front_get_card($item, "responsive"); ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
<?php
}

function polen_front_get_news($items)
{
	if (!$items) {
		return;
	}
?>
	<section class="row pt-2 mb-4 news">
		<div class="col-md-12">
			<header class="row mb-3">
				<div class="col-12 d-flex justify-content-between align-items-center">
					<h2 class="mr-2">Destaque</h2>
					<a href="#">Ver todos <?php Icon_Class::polen_icon_chevron_right(); ?></a>
				</div>
			</header>
		</div>
		<div class="col-md-12">
			<div class="slick-alt">
				<div class="slick-padding">
					<?php foreach ($items as $item) : ?>
						<?php polen_front_get_card($item, "responsive"); ?>
					<?php endforeach; ?>
				</div>
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
	<section class="row pt-2 mb-4 categories">
		<div class="col-md-12">
			<header class="row mb-4">
				<div class="col-12 d-flex justify-content-between align-items-center">
					<h2 class="mr-2">Categorias</h2>
					<a href="#">Ver todos</a>
				</div>
			</header>
		</div>
		<div class="col md-12">
			<div class="row">
				<?php foreach ($items as $item) : ?>
					<div class="col-md-3">
						<figure class="polen-card category">
							<img loading="lazy" src="<?= $item["image"] ?>" alt="<?= $item["title"] ?>">
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
	<section class="row pt-2 mb-4 all-artists">
		<div class="col-12 col-md-12">
			<header class="row mb-4">
				<div class="col-12 d-flex justify-content-between align-items-center">
					<h2 class="mr-2"><?= $title; ?></h2>
					<a href="#">Ver todos <?php Icon_Class::polen_icon_chevron_right(); ?></a>
				</div>
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
			<div class="row mt-md-5 mt-4">
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
?>
	<section class="row tutorial pt-2 mb-4">
		<div class="col-md-12">
			<header class="row mb-4">
				<div class="col">
					<h2>Como funciona</h2>
				</div>
			</header>
		</div>
		<div class="col-md-12">
			<div class="box-round py-4 px-4">
				<div class="row">
					<div class="col-4">
						<div class="row">
							<div class="col-12 text-center icon"><?php Icon_Class::polen_icon_phone(); ?></div>
							<div class="col-12 text-center mt-2">
								<p>Peça o vídeo para o seu ídolo</p>
							</div>
						</div>
					</div>
					<div class="col-4">
						<div class="row">
							<div class="col-12 text-center icon"><?php Icon_Class::polen_icon_camera_video(); ?></div>
							<div class="col-12 text-center mt-2">
								<p>Receba seu vídeo</p>
							</div>
						</div>
					</div>
					<div class="col-4">
						<div class="row">
							<div class="col-12 text-center icon"><?php Icon_Class::polen_icon_hand_thumbs_up(); ?></div>
							<div class="col-12 text-center mt-2">
								<p>Compartilhe com todo mundo</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php
}

function polen_get_avatar($url, $size = "md")
{
	$url = isset($url) && !empty($url) ? $url : TEMPLATE_URI . '/assets/img/avatar.png';
	$classes = !empty($size) ? " avatar-" . $size : "";
?>
	<div class="avatar<?php echo $classes; ?>" style="background-image: url(<?php echo $url ?>);"></div>
<?php
}

function polen_talent_promo_card($talent)
{
?>
	<div class="video-promo-card">
		<div class="row d-flex h-100 align-items-center px-3 py-2">
			<div class="col-md-12 text-center">
				<?php polen_get_avatar($talent->avatar); ?>
				<p class="mt-2">E aí, ficou com vontade de ter um vídeo do <?php echo $talent->nome; ?>?</p>
				<a href="#pedirvideo" class="btn btn-outline-light btn-lg">Peça o seu vídeo</a>
			</div>
		</div>
	</div>
<?php
}

function polen_front_get_talent_videos($talent, $items = array(
	array("title" => "Video 1", "image" => "http://i.vimeocdn.com/video/1106294518_640.jpg", "video" => "https://vimeo.com/534168147"),
	array("title" => "Video 2", "image" => "https://i.vimeocdn.com/video/1106293939_640.jpg", "video" => "https://vimeo.com/534171508"),
	array("title" => "Video 3", "image" => "http://i.vimeocdn.com/video/1106294834_640.jpg", "video" => "https://vimeo.com/534173040"),
	array("title" => "Video 1", "image" => "http://i.vimeocdn.com/video/1106294518_640.jpg", "video" => "https://vimeo.com/534168147"),
	// array("title" => "Video 2", "image" => "https://i.vimeocdn.com/video/1106293939_640.jpg", "video" => "https://vimeo.com/534171508"),
	// array("title" => "Video 3", "image" => "http://i.vimeocdn.com/video/1106294834_640.jpg", "video" => "https://vimeo.com/534173040"),
	// array("title" => "Video 1", "image" => "http://i.vimeocdn.com/video/1106294518_640.jpg", "video" => "https://vimeo.com/534168147"),
	// array("title" => "Video 2", "image" => "https://i.vimeocdn.com/video/1106293939_640.jpg", "video" => "https://vimeo.com/534171508"),
	// array("title" => "Video 3", "image" => "http://i.vimeocdn.com/video/1106294834_640.jpg", "video" => "https://vimeo.com/534173040"),
	// array("title" => "Video 3", "image" => "http://i.vimeocdn.com/video/1106294834_640.jpg", "video" => "https://vimeo.com/534173040"),
))
{
?>
	<section class="row mb-4 banner-scrollable">
		<div class="d-none d-md-block col-md-12 text-right custom-slick-controls"></div>
		<div class="col-md-12" style="padding: 0;">
			<div class="banner-wrapper">
				<div class="banner-content type-video">
					<?php foreach ($items as $item) : ?>
						<div class="polen-card type-video">
							<figure class="video-cover">
								<img loading="lazy" src="<?= $item['image']; ?>" alt="<?= $item['title']; ?>" data-url="<?= $item['video']; ?>">
								<a href="javascript:openVideoByURL('<?= $item['video']; ?>')" class="video-player-button"></a>
							</figure>
						</div>
					<?php endforeach; ?>
					<?php //polen_talent_promo_card($talent); ?>
				</div>
			</div>
		</div>
	</section>

	<div id="video-modal" class="video-modal">
		<div class="video-card">
			<header>
				<button id="close-button" class="close-button" onclick="hideModal()"><?php Icon_Class::polen_icon_close(); ?></button>
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
						<a href="javascript:copyToClipboard(window.location.href)" class="btn btn-outline-light btn-lg btn-block share-link"><?php Icon_Class::polen_icon_copy(); ?>Copiar link</a>
						<a href="javascript:void(0)" class="btn btn-outline-light btn-lg btn-block share-link" target="_blank"><?php Icon_Class::polen_icon_download(); ?>Download</a>
						<a href="<?php echo $talent->facebook; ?>" class="btn btn-outline-light btn-lg btn-block share-link" target="_blank"><?php Icon_Class::polen_icon_social('facebook'); ?>Facebook</a>
						<a href="<?php echo $talent->instagram; ?>" class="btn btn-outline-light btn-lg btn-block share-link" target="_blank"><?php Icon_Class::polen_icon_social('instagram'); ?>Instagram</a>
						<a href="<?php echo $talent->twitter; ?>" class="btn btn-outline-light btn-lg btn-block share-link" target="_blank"><?php Icon_Class::polen_icon_social('twitter'); ?>Twitter</a>
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
	<div class="talent-card">
		<header class="row pb-3 header">
			<div class="col-3">
				<div class="avatar" style="background-image: url(<?php echo isset($talent["avatar"]) ? $talent["avatar"] : TEMPLATE_URI . '/assets/img/avatar.png';  ?>)"></div>
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
				<button class="show-details" onclick="showDetails()"><?php Icon_Class::polen_icon_chevron("down") ?></button>
			<?php endif; ?>
		</div>
		<footer class="row details-box">
			<div class="col pt-4 mt-3 details">
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
	<script>
		var details = document.querySelector(".details-box");
		var btn = document.querySelector(".show-details");

		function showDetails() {
			details.classList.toggle("show");
			btn.classList.toggle("-active");
		}
	</script>
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
