<?php

function polen_front_get_banner()
{
?>
	<section class="top-banner mb-5">
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
				<img loading="lazy" src="<?php echo $image[0]; ?>" alt="<?= $item["name"]; ?>">
				<span class="price"><span class="mr-2"><?php Icon_Class::polen_icon_camera_video(); ?></span>R$<?= $item["price"]; ?></span>
				<a href="<?= $item["talent_url"]; ?>" class="link"></a>
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
	<section class="row mb-2 banner-scrollable">
		<div class="col-md-12">
			<header class="row mb-3">
				<div class="col-12 d-flex justify-content-between align-items-center">
					<h2 class="mr-2"><?php echo $title; ?></h2>
					<a href="<?php echo $link; ?>">Ver todos <?php Icon_Class::polen_icon_chevron_right(); ?></a>
				</div>
			</header>
		</div>
		<div class="col-md-12 p-0 p-md-0">
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
	<section class="row pt-2 mb-5 news">
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

function polen_front_get_categories($items, $link = '#')
{
	if (!$items) {
		return;
	}
?>
	<section class="row pt-2 mb-5 categories">
		<div class="col-md-12">
			<header class="row mb-4">
				<div class="col-12 d-flex justify-content-between align-items-center">
					<h2 class="mr-2">Categorias</h2>
					<a href="<?php echo $link; ?>">Ver todos</a>
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
	<section class="row pt-2 mb-5 all-artists">
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
	<section class="row tutorial pt-2 mb-5">
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
		<div class="card row px-3 py-2">
			<div class="col-12 col-md-12 d-flex flex-column justify-content-center align-items-center text-center">
				<?php polen_get_avatar($talent->avatar); ?>
				<p class="mt-2">E aí, ficou com vontade de ter um vídeo do <?php echo $talent->nome; ?>?</p>
				<a href="#pedirvideo" class="btn btn-outline-light btn-lg">Peça o seu vídeo</a>
			</div>
		</div>
	</div>
<?php
}

function polen_get_talent_socials($talent)
{
?>
	<?php if ($talent->facebook) : ?>
		<a href="<?php echo $talent->facebook; ?>" class="btn btn-outline-light btn-lg btn-block share-link" target="_blank"><?php Icon_Class::polen_icon_social('facebook'); ?>Facebook</a>
	<?php endif; ?>
	<?php if ($talent->instagram) : ?>
		<a href="<?php echo $talent->instagram; ?>" class="btn btn-outline-light btn-lg btn-block share-link" target="_blank"><?php Icon_Class::polen_icon_social('instagram'); ?>Instagram</a>
	<?php endif; ?>
	<?php if ($talent->twitter) : ?>
		<a href="<?php echo $talent->twitter; ?>" class="btn btn-outline-light btn-lg btn-block share-link" target="_blank"><?php Icon_Class::polen_icon_social('twitter'); ?>Twitter</a>
	<?php endif; ?>
<?php
}

function polen_video_icons($img_perfil, $iniciais)
{
?>
	<div class="video-icons">
		<figure class="image-cropper small">
			<img loading="lazy" src="<?php echo isset($img_perfil) && !empty($img_perfil) ? $img_perfil : TEMPLATE_URI . '/assets/img/avatar.png'; ?>" alt="Foto do Perfil">
		</figure>
		<div class="text-cropper small"><?php echo $iniciais; ?></div>
	</div>
<?php
}

function polen_front_get_talent_videos($talent)
{
	$items = array();
	$items_raw = Polen\Includes\Polen_Video_Info::select_by_talent_id($talent->user_id);
	foreach ($items_raw as $item) {
		$items[] = [
			'title' => '',
			'image' =>  $item->vimeo_thumbnail,
			'video' => $item->vimeo_link,
			'hash' => $item->hash
		];
	}
	if (sizeof($items) < 1) {
		return;
	}

	$img_perfil = "";
	$iniciais = "AA";
	$video_url = home_url() . "/v/";
?>
	<section id="talent-videos" class="row mb-4 banner-scrollable" data-public-url="<?php echo $video_url; ?>">
		<div class="d-none d-md-block col-md-12 text-right custom-slick-controls"></div>
		<div class="col-md-12 p-0">
			<div class="banner-wrapper">
				<div class="banner-content type-video">
					<?php foreach ($items as $item) : ?>
						<div class="polen-card-video">
							<figure class="video-cover">
								<img loading="lazy" src="<?= $item['image']; ?>" alt="<?= $item['title']; ?>" data-url="<?= $item['video']; ?>">
								<a href="javascript:openVideoByURL('<?= $item['video']; ?>')" class="video-player-button"></a>
								<?php polen_video_icons($img_perfil, $iniciais); ?>
							</figure>
						</div>
					<?php endforeach; ?>
					<?php polen_talent_promo_card($talent); ?>
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
						<?php echo polen_get_avatar($talent->avatar);  ?>
					</div>
					<div class="col-9">
						<h4 class="name"><?php echo $talent->nome; ?></h4>
						<h5 class="cat"><?php echo $talent->profissao; ?></h5>
						<a href="<?php echo $video_url; ?>" id="video-url" class="url"><?php echo $video_url; ?></a>
					</div>
				</header>
				<div class="row mt-4 share">
					<div class="col-12">
						<input type="text" id="share-input" class="share-input" />
						<a id="copy-video" class="btn btn-outline-light btn-lg btn-block share-link"><?php Icon_Class::polen_icon_copy(); ?>Copiar link</a>
						<?php polen_get_talent_socials($talent); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}


/**
 * Cria a tela para assitir video
 * @param WP_User $talent
 * @param Polen_Video_Info $video
 * @return html
 */
function polen_get_video_player($talent, $video)
{
	if (!$talent || !$video) {
		return;
	}
	wp_enqueue_script('vimeo');
	$video_url = home_url() . "/v/" . $video->hash;
?>
	<div class="row">
		<div class="col-12 col-md-8 m-md-auto">
			<div class="video-card">
				<header>
					<div id="video-box">
						<div id="polen-video" class="polen-video"></div>
					</div>
					<script>
						jQuery(document).ready(function() {
							var videoPlayer = new Vimeo.Player("polen-video", {
								url: "<?php echo $video->vimeo_link; ?>",
								autoplay: false,
								width: document.getElementById("polen-video").offsetWidth,
							});
						})
					</script>
				</header>
				<div class="content mt-4 mx-3">
					<header class="row content-header">
						<div class="col-3">
							<?php echo polen_get_avatar($talent->avatar);  ?>
						</div>
						<div class="col-9">
							<h4 class="name"><?php echo $talent->nome; ?></h4>
							<h5 class="cat"><?php echo $talent->profissao; ?></h5>
							<a href="<?php echo $video_url; ?>" class="url"><?php echo $video_url; ?></a>
						</div>
					</header>
					<div class="row mt-4 share">
						<div class="col-12">
							<input type="text" id="share-input" class="share-input" />
							<a href="javascript:copyToClipboard(window.location.href)" class="btn btn-outline-light btn-lg btn-block share-link"><?php Icon_Class::polen_icon_copy(); ?>Copiar link</a>
							<?php polen_get_talent_socials($talent); ?>
						</div>
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
						<?php
						if (empty(!$talent["from"])) : ?>
							<div class="item">
								<span class="title big">Vídeo de</span>
								<p class="value"><?php echo $talent["from"]; ?></p>
							</div>
							<div class="item mx-3">
								<?php Icon_Class::polen_icon_arrows(); ?>
							</div>
						<?php endif; ?>
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

function polen_box_image_message($image, $text)
{
?>
	<div class="box-round">
		<div class="row p-4">
			<div class="col-md-12 text center">
				<img src="<?php echo $image; ?>" alt="<?php echo $text; ?>">
			</div>
			<div class="col-md-12 text center mt-4">
				<p><?php echo $text; ?></p>
			</div>
		</div>
	</div>
<?php
}
