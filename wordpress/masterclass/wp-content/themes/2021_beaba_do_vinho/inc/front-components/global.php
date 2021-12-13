<?php

use Polen\Includes\Polen_Order;

function polen_front_get_banner_with_carousel($social = false)
{
	$carrousel = array(
		array(
			"mobile" => TEMPLATE_URI . "/assets/img/banner-home-mobile-new.png",
			"desktop" => TEMPLATE_URI . "/assets/img/img-home-desktop-new.jpeg"
		)
	);
	$carrousel2 = array(
		array(
			"mobile" => TEMPLATE_URI . "/assets/img/bg-setembro.png",
			"desktop" => TEMPLATE_URI . "/assets/img/bg-setembro.png"
		)
	);
?>
	<section class="top-banner mb-4">
		<div id="top-carousel" class="owl-carousel owl-theme">
			<?php if (!$social) : ?>
				<div class="item">
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
				</div>
				<?php else: ?>
					<div class="item">
					<div class="carrousel">
						<?php foreach ($carrousel2 as $item) : ?>
							<figure class="image">
								<img loading="lazy" src="<?php echo $item['mobile']; ?>" alt="Banner da home" class="mobile" />
								<img loading="lazy" src="<?php echo $item['desktop']; ?>" alt="Banner da home" class="desktop" />
							</figure>
						<?php endforeach; ?>
					</div>
					<div class="content">
						<h2 class="title mb-5">Setembro é o mês da prevenção ao suicídio.<br>Agir salva vidas!</h2>
						<a href="javascript:openModalSa()" class="banner-button-link button-yellow">
							<?php Icon_Class::polen_icon_donate(); ?>
							<span class="mr-3 ml-2">Veja os depoimentos</span>
							<?php Icon_Class::polen_icon_chevron_right(); ?>
						</a>
					</div>
				</div>
				<?php endif; ?>
		</div>
	</section>
	<script>
		function openModalSa() {
			document.getElementById("sa-modal").classList.add("d-block");
			changeHash("sa-modal");
		}
		if(window.location.hash.substring(1) == "sa-modal") {
			openModalSa();
		}
	</script>
<?php
}

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
function polen_front_get_card($item, $size = "small", $social = false)
{
	$social = product_is_social_base( wc_get_product( $item['ID'] ) );
	// $social == false ? $social = social_product_is_social(wc_get_product($item['ID']), social_get_category_base()) : false;
	$class = $size;
	if ($size === "small") {
		$class = "col-6 col-md-2";
	} elseif ($size === "medium") {
		$class = "col-6 col-md-3";
	}

	if ($social) {
		$size .= " yellow";
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
		<div class="polen-card <?= $size; ?>" itemscope itemtype="https://schema.org/Offer">
			<figure class="image">
				<?php if ($social) {
					polen_donate_badge("Setembro Amarelo", true, false, true);
				} ?>
				<img loading="lazy" src="<?php echo $image[0]; ?>" alt="<?= $item["name"]; ?>">
				<?php if(!$social) : ?>
					<div class="price text-right" itemprop="price">
						<?php if ($item['in_stock']) : ?>
							<span class="mr-2"><?php Icon_Class::polen_icon_camera_video(); ?></span>
							<span><?php echo $item['price_formatted']; ?></span>
						<?php else : ?>
							<span>Esgotado</span>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<a href="<?= $item["talent_url"]; ?>" class="link"></a>
			</figure>
			<h4 class="title text-truncate">
				<a href="<?= $item["talent_url"]; ?>" title="<?= $item["name"]; ?>" itemprop="name"><?= $item["name"]; ?></a>
			</h4>
			<h5 class="category text-truncate">
				<a href="<?= $item["category_url"]; ?>"><?= $item["category"]; ?></a>
			</h5>
		</div>
	</div>
<?php
}

function polen_banner_scrollable($items, $title, $link, $subtitle = "", $social = false)
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
				<?php if ($subtitle != "") : ?>
					<div class="col-12">
						<p class="my-1"><?php echo $subtitle; ?></p>
					</div>
				<?php endif; ?>
			</header>
		</div>
		<div class="col-md-12 p-0 p-md-0">
			<div class="banner-wrapper">
				<div class="banner-content">
					<?php foreach ($items as $item) : ?>
						<?php polen_front_get_card($item, "responsive", $social); ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
<?php
}

function polen_front_get_news($items, $title, $link, $social = false)
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
					<?php if ($link) : ?>
						<a href="<?php echo $link; ?>">Ver todos <?php Icon_Class::polen_icon_chevron_right(); ?></a>
					<?php endif; ?>
				</div>
			</header>
		</div>
		<div class="col-md-12">
			<div class="row card-list">
				<div class="col-md-12 p-0 p-md-0">
					<div class="banner-wrapper">
						<div class="banner-content">
							<?php foreach ($items as $item) : ?>
								<?php polen_front_get_card($item, "responsive", $social); ?>
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

function polen_front_get_artists($items, $title, $social = false)
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
					<?php polen_front_get_card($item, "small", $social); ?>
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

function polen_get_avatar_src($user_id, $size = 'polen-squere-crop-lg')
{
	if (is_plugin_active('wp-user-avatar/wp-user-avatar.php') && has_wp_user_avatar($user_id)) {
		return get_wp_user_avatar_src($user_id, $size);
	}
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
	$args = polen_get_array_related_products($product_id);
	$cat_link = polen_get_url_category_by_product_id($product_id);
?>
	<div class="row">
		<div class="col-12 col-md-12">
			<?php polen_banner_scrollable($args, "Veja também", $cat_link); ?>
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


function polen_form_add_whatsapp($order_number, $whatsapp_number = "")
{
	wp_enqueue_script('form-whatsapp');
?>
	<div id="add-whatsapp" class="add-whatsapp row">
		<div class="col-12 col-md-6">
			<div class="box-round d-flex p-4 my-3">
				<div class="mr-2">
					<img width="57" src="<?php echo TEMPLATE_URI; ?>/assets/img/icon-whatsapp.png" alt="Ícone do Whatsapp" />
				</div>
				<div>
					<span v-if="!savedPhone">
						<p class="mb-2"><strong>Receba seu vídeo no Whatsapp</strong></p>
						<p>Caso você queira receber o seu vídeo via whatsapp, preencha o campo abaixo:</p>
					</span>
					<span v-if="savedPhone">
						<p>Você vai receber o vídeo no seu Whatsapp {{savedPhone}}</p>
						<p><button class="btn-link alt" v-on:click="handleEdit" v-bind:class="edit ? 'd-none' : ''">Editar</button></p>
					</span>
					<form action="/" method="POST" v-bind:class="edit ? '' : 'd-none'" v-on:submit.prevent="handleSubmit" id="form-add-whatsapp">
						<?php //TODO botar o value que precisa ser enviado ao endpoint
						?>
						<input type="hidden" name="action" value="polen_whatsapp_form">
						<input type="hidden" name="page_source" value="<?= filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" />
						<input type="hidden" name="is_mobile" value="<?= polen_is_mobile() ? "1" : "0"; ?>" />
						<input type="hidden" name="security" value=<?php echo wp_create_nonce(Polen_Order::WHATSAPP_NUMBER_NONCE_ACTION); ?>>
						<input type="hidden" name="order" value="<?php echo $order_number; ?>" />

						<input type="hidden" id="phone_cache" value="<?php echo $whatsapp_number; ?>" />
						<input type="text" name="phone_number" v-model="phone" v-on:keyup="handleChange" placeholder="(00) 00000-0000" maxlength="15" class="form-control form-control-lg" style="background-color: transparent;" required />
						<button type="submit" class="btn btn-outline-light btn-lg mt-3 px-5">Salvar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php
}

function polen_get_share_icons()
{
?>
	<div class="share-options row mt-4 mb-4">
		<div class="share-button col-12 text-center">
			<button class="btn btn-outline-light btn-md" onclick="shareVideo('Compartilhado', null)"><span class="mr-2"><?php Icon_Class::polen_icon_share(); ?></span>Compartilhar</button>
		</div>
		<div class="share-icons col-12">
			<div class="row">
				<div class="col-12 text-center">
					<span class="mr-2"><?php Icon_Class::polen_icon_share(); ?></span>Compartilhar
				</div>
				<div class="col-12 d-flex justify-content-center mt-3">
					<div class="row">
						<div class="col-4 text-center">
							<a href="javascript:shareSocial.send(shareSocial.network.facebook);" class="share-icons__icon facebook"><?php Icon_Class::polen_icon_social("facebook"); ?></a>
						</div>
						<div class="col-4 text-center">
							<a href="javascript:shareSocial.send(shareSocial.network.twitter);" class="share-icons__icon twitter"><?php Icon_Class::polen_icon_social("twitter"); ?></a>
						</div>
						<div class="col-4 text-center">
							<a href="javascript:shareSocial.send(shareSocial.network.whatsapp);" class="share-icons__icon whatsapp"><?php Icon_Class::polen_icon_social("whatsapp"); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		if (!navigator.share) {
			document.querySelector('.share-icons').classList.add("show");
		}
	</script>
<?php
}

function polen_get_share_button()
{
?>
	<button class="share-button btn btn-outline-light btn-md" onclick="shareVideo('Compartilhado', null)"><?php Icon_Class::polen_icon_share(); ?></button>
	<script>
		if (navigator.share) {
			document.querySelector('.share-button').classList.add("show");
		}
	</script>
<?php
}
