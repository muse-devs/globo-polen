<?php

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
			"mobile" => TEMPLATE_URI . "/assets/img/banner-home-mobile-new.png",
			"desktop" => TEMPLATE_URI . "/assets/img/img-home-desktop-new.jpeg"
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
				<span class="price"><span class="mr-2"><?php Icon_Class::polen_icon_camera_video(); ?></span><?php echo $item["price"] == "0" ? 'GRÁTIS' : $item['price_formatted']; ?></span>
				<a href="<?= $item["talent_url"]; ?>" class="link"></a>
			</figure>
			<h4 class="title text-truncate">
				<a href="<?= $item["talent_url"]; ?>" title="<?= $item["name"]; ?>"><?= $item["name"]; ?></a>
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
			'first_order' => $item->first_order,
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
								<?php polen_video_icons($talent->user_id, $item['initials'], $item['first_order'] == "1"); ?>
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
		<form id="newsletter">
			<div class="row">
				<div class="col-md-8 mb-2 mb-md-0">
					<input type="hidden" name="action" value="polen_newsletter_signin">
					<input type="hidden" name="page_source" value="<?= filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" />
					<input type="hidden" name="event" value="<?= $event; ?>" />
					<input type="hidden" name="is_mobile" value="<?= polen_is_mobile() ? "1" : "0"; ?>" />
					<input type="hidden" name="security" value=<?php echo wp_create_nonce('news-signin'); ?>>
					<input type="email" name="email" placeholder="Entre com o seu e-mail" class="form-control form-control-lg" required />
				</div>
				<div class="col-md-4 mt-2 mt-md-0 d-md-flex align-items-md-center">
					<input type="submit" value="Enviar" class="signin-newsletter-button btn btn-outline-light btn-lg btn-block" />
				</div>
				<div class="col-md-8 mb-2 mb-md-0 small signin-response"></div>
			</div>
		</form>
	</div>
<?php
}
