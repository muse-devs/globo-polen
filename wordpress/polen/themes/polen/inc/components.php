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

function polen_front_get_news($items, $title, $link)
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
		<div class="col-md-12">
			<div class="row card-list">
				<div class="col-md-12 p-0 p-md-0">
					<div class="banner-wrapper">
						<div class="banner-content">
							<?php foreach ($items as $item) : ?>
								<?php polen_front_get_card($item, "responsive"); ?>
							<?php endforeach; ?>
						</div>
					</div>
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
	<section class="row tutorial pt-4 mb-4">
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
		<div class="card row p-2">
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

function polen_get_talent_video_buttons($talent, $video_url, $video_download)
{
	$wa_url = "https://wa.me/?text=";
	$wa_message = htmlentities(urlencode("Veja que legal: "));
	$wa_link = $video_url;
?>
	<a href="<?php echo $wa_url . $wa_message . $wa_link; ?>" class="btn btn-outline-light btn-lg btn-block share-link" target="_blank"><?php Icon_Class::polen_icon_social('whatsapp'); ?>Whatsapp</a>
<?php
}

function polen_video_icons($user_id, $iniciais)
{
?>
	<div class="video-icons">
		<figure class="image-cropper small">
			<?php echo get_avatar($user_id); ?>
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
		$order = wc_get_order($item->order_id);
		$cart_item = \Polen\Includes\Cart\Polen_Cart_Item_Factory::polen_cart_item_from_order($order);
		$items[] = [
			'title' => '',
			'image' =>  $item->vimeo_thumbnail,
			'video' => $item->vimeo_link,
			'hash' => $item->hash,
			'initials' => strtoupper(substr($cart_item->get_name_to_video(), 0, 2)),
		];
	}
	if (sizeof($items) < 1) {
		return;
	}

	$video_url = home_url() . "/v/";
?>
	<section id="talent-videos" class="row mb-1 banner-scrollable" data-public-url="<?php echo $video_url; ?>">
		<div class="d-none d-md-block col-md-12 text-right custom-slick-controls"></div>
		<div class="col-md-12 p-0">
			<div class="banner-wrapper">
				<div class="banner-content type-video">
					<?php foreach ($items as $item) : ?>
						<div class="polen-card-video">
							<figure class="video-cover">
								<img loading="lazy" src="<?= $item['image']; ?>" alt="<?= $item['title']; ?>" data-url="<?= $item['video']; ?>">
								<a href="javascript:openVideoByURL('<?= $item['video']; ?>')" class="video-player-button"></a>
								<?php polen_video_icons($talent->user_id, $item['initials']); ?>
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
						<!-- <input type="text" id="share-input" class="share-input" /> -->
						<?php /* <a id="copy-video" class="btn btn-outline-light btn-lg btn-block share-link"><?php Icon_Class::polen_icon_copy(); ?>Copiar link</a> */ ?>
						<?php //polen_get_talent_video_buttons($talent, null);
						?>
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
function polen_get_video_player($talent, $video, $user_id)
{
	if (!$talent || !$video) {
		return;
	}
	wp_enqueue_script('vimeo');
	$video_url = home_url() . "/v/" . $video->hash;
	$isRated = \Polen\Includes\Polen_Order_Review::review_alredy_exist($user_id, $video->order_id);
?>
	<div class="row">
		<div class="col-12 col-md-12">
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
								width: document.getElementById("polen-video").offsetWidth,
							});
						})
					</script>
				</header>
				<div class="content col-md-6 mt-4 mx-3 mx-md-0">
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
							<!-- <input type="text" id="share-input" class="share-input" /> -->
							<?php if ($user_id !== 0 && !$isRated) : ?>
								<a href="/my-account/create-review/<?= $video->order_id; ?>" class="btn btn-primary btn-lg btn-block">Avaliar vídeo</a>
							<?php endif; ?>
							<?php /* <a href="javascript:copyToClipboard('<?php echo $video_url; ?>')" class="btn btn-outline-light btn-lg btn-block share-link"><?php Icon_Class::polen_icon_copy(); ?>Copiar link</a> */ ?>
							<?php polen_get_talent_video_buttons($talent, $video_url, $video->vimeo_url_download); ?>
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
			<div class="col-md-12 d-flex align-items-center">
				<div class="avatar avatar-sm" style="background-image: url(<?php echo isset($talent["avatar"]) ? $talent["avatar"] : TEMPLATE_URI . '/assets/img/avatar.png';  ?>)"></div>
				<h4 class="name ml-3"><?php echo $talent["name"]; ?></h4>
			</div>
		</header>
		<div class="price-box pt-3">
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
			<div class="col-md-12 text-center">
				<img src="<?php echo $image; ?>" alt="<?php echo $text; ?>">
			</div>
			<div class="col-md-12 text-center mt-4">
				<p><?php echo $text; ?></p>
			</div>
		</div>
	</div>
<?php
}


/**
 * Criar o card com as estrelas da nota do Review especifico
 * @param int nota
 * @return HTML
 */
function polen_get_stars($quant)
{
	for ($i = 1; $i <= 5; $i++) {
		Icon_Class::polen_icon_star($i <= $quant);
	}
?>
	<span class="skill-value"><?php echo $quant; ?>.0</span>
<?php
}


/**
 * Criar a tela com a lista dos comentários (Order_Review)
 * @param array [ ['id'=>xx,'rate'=>x,'name'=>'...','data'=>'...','comment'=>'...'] ]
 * @return HTML
 */
function polen_comment_card($args = array())
{
	if (empty($args)) {
		return;
	}
?>
	<div class="box-round mb-3">
		<div class="row p-4 comment-box">
			<div class="col-md-12 box-stars">
				<?php polen_get_stars($args['rate']); ?>
			</div>
			<div class="col-md-12 mt-3">
				<p>Avaliação por <?php echo $args["name"]; ?> - <?php echo $args["date"]; ?></p>
			</div>
			<div class="col-md-12 mt-2">
				<p class="alt">
					<input type="checkbox" name="expanded-<?php echo $args['id']; ?>" id="expanded-<?php echo $args['id']; ?>">
					<span class="truncate truncate-4"><?php echo $args['comment']; ?></span>
					<label for="expanded-<?php echo $args['id']; ?>">Exibir mais</label>
				</p>
			</div>
		</div>
	</div>
<?php
}


/**
 * Retorna o HTML com o form para a criação de uma Order_Review
 * @param int $order_id
 * @return HTML
 */
function polen_create_review($order_id)
{
	wp_enqueue_script('comment-scripts');
?>
	<div id="comment-box" class="box-round mb-3">
		<form action="./" id="form-comment">
			<div class="row p-4 comment-box">
				<pol-stars v-bind:rate="rate" v-bind:handle="changeRate"></pol-stars>
				<input type="hidden" name="action" value="create_order_review">
				<input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
				<div class="col-md-12 mt-3">
					<h4>Comentário</h4>
					<textarea name="comment" id="comment" rows="2" class="form-control" placeholder="Escreva sua avaliação" v-model="comment"></textarea>
					<button id="send-comment" class="btn btn-primary btn-lg btn-block mt-3" v-on:click="sendComment">Avaliar</button>
				</div>
			</div>
		</form>
	</div>
<?php
}


/**
 * Cria o box dos produtos relacionados dentro de content-single-product
 * @param int $product_id
 * @return HTML
 */
function polen_box_related_product_by_product_id($product_id)
{
?>
	<div class="row">
		<div class="col-12 col-md-12">
			<?php
			$args = polen_get_array_related_products($product_id);
			$cat_link = polen_get_url_category_by_product_id($product_id);
			polen_banner_scrollable($args, "Relacionados", $cat_link);
			?>
		</div>
	</div>
<?php
}


/**
 * Cria o card onde apresentar a quantidade de avaliacoes e a media das avaliações
 * @param WP_Post $post
 * @param stdClass Polen_Update_Fields
 */
function polen_card_talent_reviews_order(\WP_Post $post, $Talent_Fields)
{
?>
	<div class="col-md-12">
		<div class="row">
			<div class="col-12 col-md-6 m-md-auto">
				<div class="row">
					<div class="col-6 col-md-6 text-center text-md-center">
						<span class="skill-title">Responde em</span>
					</div>
					<div class="col-6 col-md-6 text-center text-md-center">
						<?php
						$total_reviews = get_post_meta($post->ID, "total_review", true);
						if (empty($total_reviews)) {
							$total_reviews = "0";
						}
						?>
						<span class="skill-title">Avaliações (<?php echo  $total_reviews; ?>)</span>
					</div>
					<div class="col-6 col-md-6 text-center text-md-center mt-2">
						<?php Icon_Class::polen_icon_clock(); ?>
						<span class="skill-value"><?= $Talent_Fields->tempo_resposta; ?>h</span>
					</div>
					<div class="col-6 col-md-6 text-center text-md-center mt-2">
						<?php Icon_Class::polen_icon_star(true); ?>
						<?php
						$total_review = intval(get_post_meta($post->ID, "total_review", true));
						$sum_rate_reviews = intval(get_post_meta($post->ID, "sum_rate", true));
						$avg_rate = $total_review > 0 ? ($sum_rate_reviews / $total_review) : 0;
						?>
						<a href="<?= polen_get_url_review_page(); ?>" class="skill-value no-underline"><?php echo number_format($avg_rate, 1); ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}

function polen_get_order_flow_layout($array_status)
{
	//status: complete, in-progress, pending, fail
	//title: string
	//description: string

	if (empty($array_status) || !$array_status) {
		return;
	}

	$class = "";
	$new_array = Order_Class::clearArray($array_status);

	if($new_array[0]['status'] === "fail") {
		$class = " none";
	}
	if ($new_array[1]['status'] === "complete" && $new_array[2]['status'] !== "fail") {
		$class = " half";
	}
	if ($new_array[2]['status'] === "complete") {
		$class = " complete";
	}
?>
	<div class="row">
		<div class="col-md-12">
			<ul class="order-flow<?php echo $class; ?>">
				<?php foreach ($array_status as $key => $value) : ?>
					<li class="item <?php echo "item" . $key; ?> <?php echo $value['status']; ?>">
						<span class="status">
							<?php Icon_Class::polen_icon_check_o(); ?>
							<?php Icon_Class::polen_icon_exclamation_o(); ?>
						</span>
						<span class="text">
							<h4 class="title"><?php echo $value['title']; ?></h4>
							<p class="description"><?php echo $value['description']; ?></p>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
<?php
}
