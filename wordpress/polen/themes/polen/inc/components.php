<?php

use Polen\Includes\Debug;

function polen_front_get_banner()
{
	// $mobile_video = array(
	// 	"poster" => TEMPLATE_URI . "/assets/img/video_poster1.jpg",
	// 	"video" => TEMPLATE_URI . "/assets/video/home1.m4v",
	// 	"class" => "video-mobile"
	// );
	// $desktop_video = array(
	// 	"poster" => TEMPLATE_URI . "/assets/img/video_poster2.jpg",
	// 	"video" => TEMPLATE_URI . "/assets/video/home2.m4v",
	// 	"class" => "video-desktop"
	// );

	$carrousel = array(
		array(
			"mobile" => TEMPLATE_URI . "/assets/img/banner-home-mobile.png",
			"desktop" => TEMPLATE_URI . "/assets/img/img-home-desktop.jpeg"
		)
	);

?>
	<section class="top-banner mb-4">
		<?php /* <video id="video-banner" class="video" autoplay muted loop playsinline poster="<?php echo polen_is_mobile() ? $mobile_video['poster'] : $desktop_video['poster']; ?>">
			<source src="<?php echo polen_is_mobile() ? $mobile_video['video'] : $desktop_video['video']; ?>" type="video/mp4">
		</video>
		*/ ?>
		<div class="carrousel">
			<?php foreach ($carrousel as $item) : ?>
				<figure class="image">
					<img loading="lazy" src="<?php echo $item['mobile']; ?>" alt="Banner da home" class="mobile" />
					<img loading="lazy" src="<?php echo $item['desktop']; ?>" alt="Banner da home" class="desktop" />
				</figure>
			<?php endforeach; ?>
		</div>
		<div class="content">
			<h2 class="title mb-5">Presenteie e<br />surpreenda com vídeos personalizados.</h2>
			<a href="<?php echo polen_get_all_talents_url(); ?>" class="banner-button-link">
				<span class="mr-3">Ver todos os artistas</span>
				<?php Icon_Class::polen_icon_chevron_right(); ?>
			</a>
		</div>
		<?php /* <script>
			const home_video = {
				mobile: <?php echo json_encode($mobile_video); ?>,
				desktop: <?php echo json_encode($desktop_video); ?>
			}
		</script>
		*/ ?>
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
		$image = wp_get_attachment_image_src(get_post_thumbnail_id($item['ID']), 'polen-thumb-lg');
	} else {
		$image = array();
		$image[] = '';
	}

	$donate = get_post_meta($item['ID'], '_is_charity', true);

?>
	<div class="<?= $class; ?>">
		<div class="polen-card <?= $size; ?>">
			<figure class="image">
				<?php $donate ? polen_donate_badge("Social") : null; ?>
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
	<section class="row mb-2">
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
	<section class="row tutorial mt-4 mb-4">
		<div class="col-md-12">
			<header class="row mb-3">
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
							<div class="col-12 text-center icon subtitle"><?php Icon_Class::polen_icon_phone(); ?></div>
							<div class="col-12 text-center mt-2">
								<p>Peça o vídeo para o seu ídolo</p>
							</div>
						</div>
					</div>
					<div class="col-4">
						<div class="row">
							<div class="col-12 text-center icon subtitle"><?php Icon_Class::polen_icon_camera_video(); ?></div>
							<div class="col-12 text-center mt-2">
								<p>Receba seu vídeo</p>
							</div>
						</div>
					</div>
					<div class="col-4">
						<div class="row">
							<div class="col-12 text-center icon subtitle"><?php Icon_Class::polen_icon_hand_thumbs_up(); ?></div>
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

/**
 * Funcao que retorna ou uma tag IMG ou um SPAN com as iniciais
 * @param int
 * @param string
 * @return IMG|SPAN
 */
function polen_get_avatar($user_id, $size = 'polen-square-crop-lg')
{
	if (is_plugin_active('wp-user-avatar/wp-user-avatar.php') && has_wp_user_avatar($user_id)) {
		return get_wp_user_avatar($user_id, $size);
	} else {
		$user = get_user_by('id', $user_id);
		$initials_name = polen_get_initials_name_by_user($user);
		return '<span>' . $initials_name   . '</span>';
	}
}


function polen_talent_promo_card($talent)
{
	global $product;
?>
	<div id="video-promo-card" class="video-promo-card">
		<div class="box-color card row">
			<div class="col-12 col-md-12 d-flex flex-column justify-content-center align-items-center text-center p-2">
				<div class="image-cropper">
					<?php echo polen_get_avatar($talent->user_id, 'polen-square-crop-lg'); ?>
				</div>
				<p class="mt-2">E aí, ficou com vontade de ter um vídeo?</p>
				<?php if ($product->is_in_stock()) : ?>
					<a href="#pedirvideo" class="btn btn-outline-light btn-lg">Peça o seu vídeo</a>
				<?php else : ?>
					<a href="#pedirvideo" class="btn btn-outline-light btn-lg">Indisponível</a>
				<?php endif; ?>
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

function polen_get_talent_video_buttons($talent, $video_url, $video_download, $hash = null)
{
?>
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

function polen_video_icons($user_id, $iniciais)
{
?>
	<div class="video-icons">
		<figure class="image-cropper color small">
			<?php echo polen_get_avatar($user_id, 'polen-square-crop-lg'); ?>
		</figure>
		<div class="text-cropper small"><?php echo $iniciais; ?></div>
	</div>
<?php
}


/**
 * Criar a lista de videos já feitos
 * @param stdClass Polen_Talent_Fields
 * @return HTML
 */
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
			'initials' => polen_get_initials_name($cart_item->get_name_to_video()),
		];
	}

	$video_url = home_url() . "/v/";
?>
	<section id="talent-videos" class="row mb-1 banner-scrollable" data-public-url="<?php echo $video_url; ?>">
		<div class="d-none d-md-block col-md-12 text-right custom-slick-controls"></div>
		<div class="col-md-12 p-0">
			<div class="banner-wrapper">
				<div class="banner-content type-video<?php if (sizeof($items) < 1) echo " ml-3 ml-md-0" ?>">
					<?php foreach ($items as $item) : ?>
						<div class="polen-card-video">
							<figure class="video-cover">
								<img loading="lazy" src="<?= $item['image']; ?>" alt="<?= $item['title']; ?>" data-url="<?= $item['video']; ?>">
								<a href="javascript:openVideoByHash('<?= $item['hash']; ?>')" class="video-player-button"></a>
								<?php polen_video_icons($talent->user_id, $item['initials']); ?>
							</figure>
						</div>
					<?php endforeach; ?>
					<?php polen_talent_promo_card($talent); ?>
				</div>
			</div>
		</div>
	</section>

	<div id="video-modal" class="background video-modal">
		<div class="video-card-body">
			<button id="close-button" class="close-button" onclick="hideModal()"><?php Icon_Class::polen_icon_close(); ?></button>
			<div id="video-box"></div>
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
function polen_get_video_player($talent, $video, $user_id)
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
					<?php polen_get_talent_video_buttons($talent, $video_url, $video->vimeo_url_download, $video->hash); ?>
				</div>
			</div>
		</div>
	</div>
<?php
}

function polen_get_talent_card($talent)
{
?>
	<div class="talent-card alt">
		<header class="row pb-3 header">
			<div class="col-md-12 d-flex align-items-center">
				<div class="avatar avatar-sm" style="background-image: url(<?php echo isset($talent["avatar"]) ? $talent["avatar"] : TEMPLATE_URI . '/assets/img/avatar.png';  ?>)"></div>
				<h4 class="name ml-3"><?php echo $talent["name"]; ?></h4>
			</div>
		</header>
		<div class="price-box pt-3">
			<span class="cat">Você vai pagar</span>
			<p class="price mt-2">
				<?php wc_cart_totals_order_total_html(); ?>
			</p>
			<?php if (!empty($talent['discount'])) : ?>
				<div class="row">
					<div class="col-12 mt-3">
						<table style="width: 60%;">
							<tr>
								<td>Valor:</td>
								<td><?php echo $talent['price']; ?></td>
							</tr>
							<tr>
								<td>Desconto:</td>
								<td><?php echo $talent['discount']; ?></td>
							</tr>
							<tr>
								<td>Total:</td>
								<td><?php wc_cart_totals_order_total_html(); ?></td>
							</tr>
						</table>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($talent["has_details"]) : ?>
				<button class="show-details d-flex justify-content-center" onclick="showDetails()"><?php Icon_Class::polen_icon_chevron("down") ?></button>
			<?php endif; ?>
		</div>
		<footer class="row details-box">
			<div class="col pt-4 mt-3 details">
				<div class="row personal">
					<div class="col d-flex justify-content-between">
						<?php
						if (empty(!$talent["from"])) : ?>
							<div class="item">
								<span class="title">Vídeo de</span>
								<p class="value mt-2"><?php echo $talent["from"]; ?></p>
							</div>
							<div class="item">
								<?php Icon_Class::polen_icon_arrows(); ?>
							</div>
						<?php endif; ?>
						<div class="item">
							<span class="title">Para</span>
							<p class="value mt-2"><?php echo $talent["to"]; ?></p>
						</div>
					</div>
				</div>
				<div class="row ocasion mt-4">
					<div class="col-12">
						<span class="title">Ocasião</span>
						<p class="value mt-2"><?php echo $talent["category"]; ?></p>
					</div>
				</div>
				<div class="row mail mt-4">
					<div class="col-12">
						<span class="title">e-mail</span>
						<p class="value mt-2"><?php echo $talent["mail"]; ?></p>
					</div>
				</div>
				<div class="row description mt-4">
					<div class="col-12">
						<span class="title">Instruções</span>
						<p class="value mt-2"><?php echo $talent["description"]; ?></p>
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
				<img src="<?php echo $image; ?>" alt="<?php echo $text; ?>" class="correct-margin">
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
						<p class="p mb-0 mt-2">
							<span class="skill-value">
								<?php Icon_Class::polen_icon_clock(); ?>
								<?= $Talent_Fields->tempo_resposta; ?>h
							</span>
						</p>
					</div>
					<div class="col-6 col-md-6 text-center text-md-center">
						<?php
						$total_reviews = get_post_meta($post->ID, "total_review", true);
						if (empty($total_reviews)) {
							$total_reviews = "0";
						}
						?>
						<a href="<?= polen_get_url_review_page(); ?>" class="no-underline">
							<span class="skill-title">Avaliações (<?php echo  $total_reviews; ?>)</span>
							<p class="p mb-0 mt-2 skill-value">
								<?php Icon_Class::polen_icon_star(true); ?>
								<?php
								$total_review = intval(get_post_meta($post->ID, "total_review", true));
								$sum_rate_reviews = intval(get_post_meta($post->ID, "sum_rate", true));
								$avg_rate = $total_review > 0 ? ($sum_rate_reviews / $total_review) : 0;
								?>
								<?php echo number_format($avg_rate, 1); ?>
							</p>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}

/**
 *
 */
function polen_get_order_flow_layout($array_status)
{
	//status: complete, in-progress, pending, fail
	//title: string
	//description: string

	if (empty($array_status) || !$array_status) {
		return;
	}

	$class = "";
	$new_array = array_values($array_status);

	if ($new_array[0]['status'] === "fail" || $new_array[0]['status'] === "in-progress") {
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
						<span class="background status">
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


function polen_player_video_modal_ajax_invalid_hash()
{
?>
	<h4>Conteúdo indisponível</h4>
<?php
}


/**
 * Criar form para cadastro da newsletter ou outro lugar
 * no site onde pode-se cadastrar no table da newsletter
 * @param string $newsletter
 * @return HTML
 */
function polen_form_signin_newsletter(string $event = 'newsletter')
{
?>
	<div id="signin-newsletter" class="col-md-6 mt-4 order-md-2">
		<h5 class="title">Junte-se à nossa lista</h5>
		<p class="description">Seja o primeiro a saber sobre as estrelas mais recentes e as melhores ofertas no <?php bloginfo('name'); ?></p>
		<div class="row">
			<div class="col-md-8 mb-2 mb-md-0">
				<input type="email" name="signin_newsletter" placeholder="Entre com o seu e-mail" class="form-control form-control-lg" />
				<input type="hidden" name="signin_newsletter_page_source" value="<?= filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" />
				<input type="hidden" name="signin_newsletter_event" value="<?= $event; ?>" />
				<input type="hidden" name="signin_newsletter_is_mobile" value="<?= polen_is_mobile() ? "1" : "0"; ?>" />
			</div>
			<?php
			$newsletter_signin_nonce = wp_create_nonce('news-signin');
			?>
			<div class="col-md-4 mt-2 mt-md-0 d-md-flex align-items-md-center">
				<button class="signin-newsletter-button btn btn-outline-light btn-lg btn-block" code="<?php echo $newsletter_signin_nonce; ?>">Enviar</button>
			</div>
			<div class="col-md-8 mb-2 mb-md-0 small signin-response"></div>
		</div>
	</div>
<?php
}

function polen_donate_badge(string $text = "", bool $inside_card = true)
{
	if ($text === "") {
		return;
	}
?>
	<span class="donate-badge<?php echo $inside_card ? "" : " alt"; ?>">
		<?php Icon_Class::polen_icon_donate(); ?>
		<strong><?php echo $text; ?></strong>
	</span>
<?php
}

function polen_front_get_donation_box(string $img = "", string $text = "")
{
	if ($text === "") {
		return;
	}
?>
	<section class="row donation-box mt-4 mb-4">
		<div class="col-md-12">
			<header class="row mb-3">
				<div class="col">
					<h2>Sobre a doação</h2>
				</div>
			</header>
		</div>
		<div class="col-md-12">
			<div class="box-round py-4 px-4">
				<div class="row">
					<div class="col-2 col-md-1 text-center">
						<?php Icon_Class::polen_icon_donate(); ?>
					</div>
					<div class="col-10 col-md-11">
						<figure class="image">
							<img src="<?php echo $img; ?>" alt="Logo da empresa de doação">
						</figure>
						<p><?php echo $text; ?></p>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php
}

function polen_front_get_suggestion_box()
{
?>
	<div class="row">
		<div class="col-12">
			<div class="box-round p-4">
				<div class="row">
					<div class="col-3 col-md-2 col-lg-1">
						<img src="<?php echo TEMPLATE_URI; ?>/assets/img/logo-round.svg" alt="Logo redonda">
					</div>
					<div class="col-9 col-md-10 col-lg-11">
						<p><strong>E aí, ficou com vontade de ver seu artista favorito no Polen?</strong></p>
						<a href="#pedirartista" class="btn btn-outline-light btn-md">Pedir artista</a>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}

function polen_front_get_suggestion_form()
{
	wp_enqueue_script('suggestion-scripts');
?>
	<div class="row">
		<div class="col-12 col-md-12 mb-3">
			<h1>Pedir artista</h1>
		</div>
		<div class="col-12 col-md-12">
			<form id="talent-suggestion">
				<input type="hidden" name="action" value="aindanaosei">
				<p class="mb-4">
					<input type="text" id="fan_name" name="fan_name" placeholder="Seu nome" class="form-control form-control-lg" required />
				</p>
				<p class="mb-4">
					<input type="email" id="fan_email" name="fan_email" placeholder="Seu e-mail" class="form-control form-control-lg" required />
				</p>
				<p class="mb-4">
					<input type="text" id="talent_name" name="talent_name" placeholder="Nome do seu ídolo" class="form-control form-control-lg" required />
				</p>
				<p class="mb-4">
					<input type="text" id="talent_instagram" name="talent_instagram" placeholder="Instagram do seu ídolo" class="form-control form-control-lg" />
				</p>
				<p class="mb-4">
					<input type="submit" class="btn btn-primary btn-lg btn-block" />
				</p>
			</form>
		</div>
	</div>
<?php
}

function polen_front_get_landpage($product = null)
{
	wp_enqueue_script('landpage-scripts');
	$event = "landpage";
	$landpage_signin_nonce = wp_create_nonce('landpage-signin');
?>
	<div class="landpage-card">
		<div class="row">
			<div class="col-12 col-md-12 col-lg-10">
				<div class="row">
					<div class="col-7 m-auto m-md-0 col-md-4">
						<div class="image-cropper">
							<?php echo polen_get_avatar($product->user_id, 'polen-square-crop-xl'); ?>
						</div>
					</div>
					<div class="col-12 mt-3 col-md-8 pl-md-5">
						<h1 class="title">Você sabia que Cauã Reymond apoia o projeto XPTO?</h1>
						<p class="subtitle">Pedindo um vídeo na Polen todo o valor será revertido nesta causa.</p>
						<form action="./" method="POST" id="landpage-form" class="landpage-form">
							<div class="row">
								<div class="mt-4 col-md-9 mt-md-5">
									<div class="row">
										<div class="mb-3 col-md-12">
											<label for="signin_landpage" class="label">Você quer apoiar o Cauã nesta causa?</label>
											<input type="email" name="signin_landpage" id="signin_landpage" placeholder="Entre com o seu e-mail" class="form-control form-control-lg" />
											<input type="hidden" name="signin_landpage_page_source" value="<?= filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" />
											<input type="hidden" name="signin_landpage_event" value="<?= $event; ?>" />
											<input type="hidden" name="signin_landpage_is_mobile" value="<?= polen_is_mobile() ? "1" : "0"; ?>" />
											<input type="hidden" name="wnonce" value="<?php echo $landpage_signin_nonce; ?>" />
										</div>
										<div class="col-md-12">
											<button class="signin-landpage-button btn btn-primary btn-lg btn-block">Quero um vídeo Polen</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}
