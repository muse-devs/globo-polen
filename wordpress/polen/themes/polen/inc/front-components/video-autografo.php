<?php

function va_get_home_banner($link)
{
?>
	<div class="row mt-4">
		<div class="col-12">
			<div class="va-banner">
				<img class="image mobile-img" src="<?php echo TEMPLATE_URI . '/assets/img/video-autografo/va-banner-mobile.png'; ?>" alt="De Porta em Porta">
				<img class="image desktop-img" src="<?php echo TEMPLATE_URI . '/assets/img/video-autografo/va-banner-desktop.png'; ?>" alt="De Porta em Porta">
				<div class="content">
					<h2>De porta em porta</h2>
					<p class="mt-3">
						Agora você pode comprar o livro<br>
						e ter um autógrafo em vídeo do Luciano Huck.
					</p>
					<a href="<?php echo $link; ?>" class="btn btn-primary btn-md">Conheça<span class="ml-2"><?php Icon_Class::polen_icon_chevron_right(); ?></span></a>
				</div>
			</div>
		</div>
	</div>
<?php
}

function va_magalu_box_thank_you()
{
?>
	<div class="row mt-4">
		<div class="col-12">
			<div class="magalu-box">
				<div class="header-box text-center py-4 px-5">
					<?php /*<img src="<?php echo TEMPLATE_URI . '/assets/img/video-autografo/lu.png'; ?>" alt="Lu"></img>*/ ?>
					<h3>Obrigada por pedir seu<br>vídeo-autógrafo</h3>
				</div>
				<div class="content-box">
					<p>Luciano vai enviar um vídeo<br>personalizado em até 15 dias.</p>
				</div>
			</div>
		</div>
	</div>
<?php
}

function va_magalu_box_cart()
{
?>
	<div class="row mb-4">
		<div class="col-12">
			<div class="magalu-box">
				<div class="header-box text-center py-4 px-5">
					<?php /*<img src="<?php echo TEMPLATE_URI . '/assets/img/video-autografo/lu.png'; ?>" alt="Lu"></img>*/ ?>
					<h3>Para pedir um Vídeo-autógrafo você precisa:</h3>
				</div>
				<div class="content-box mt-3 px-2">
					<div class="row">
						<div class="col-12 col-md-6 m-md-auto d-flex align-items-center">
							<ul class="order-flow half">
								<li class="item itempayment-approved complete">
									<span class="background status">1</span>
									<span class="text">
										<p class="description">Comprar o livro no site da <a href="#" target="_blank"><b>Magalu</b></a></p>
									</span>
								</li>
								<li class="item itempayment-approved complete">
									<span class="background status">2</span>
									<span class="text">
										<p class="description">Confira o e-mail da Magalu com o código de vídeo-autógrafo</p>
									</span>
								</li>
								<li class="item itempayment-approved complete">
									<span class="background status">3</span>
									<span class="text">
										<p class="description">Adicione o código que você recebeu abaixo</p>
									</span>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}

function va_partners_footer()
{
?>
	<div class="row mt-3">
		<div class="col-12">
			<h2>Esse produto é feito em parceria com:</h2>
		</div>
		<div class="col-12">
			<img src="<?php echo TEMPLATE_URI . '/assets/img/video-autografo/magalu-logo.png'; ?>" alt="Magazine Luiza">
			<img src="<?php echo TEMPLATE_URI . '/assets/img/video-autografo/cia-das-letras-logo.png'; ?>" alt="Cia das Letras">
		</div>
	</div>
<?php
}

function va_get_banner_book($small = false)
{
	$img_bg = TEMPLATE_URI . "/assets/img/video-autografo/bg_lh_right.png";
	$img_book = TEMPLATE_URI . "/assets/img/video-autografo/book_cover.png";

?>
	<div class="row mb-3">
		<div class="col-12">
			<div class="va-top-banner<?php echo $small ? ' small' : ''; ?>">
				<div class="box-round">
					<img src="<?php echo $img_bg; ?>" alt="Fundo do Box" class="img-bg" />
				</div>
				<div class="content<?php echo $small ? '' : ' pb-2'; ?>">
					<img src="<?php echo $img_book; ?>" alt="Capa do Livro" class="book-cover" />
					<h1 class="title"><?php echo $small ? 'Livro - ' : ''; ?>De porta em porta</h1>
				</div>
			</div>
		</div>
	</div>
<?php
}

function va_get_book_infos()
{
?>
	<div class="row mb-4">
		<div class="col-12">
			<div class="box-round book-info-wrapp py-3 px-3">
				<div class="row">
					<div class="col-12">
						<h4 class="title">Sobre o Livro</h4>
						<p>Em seu novo livro, Luciano Huck compila memórias pessoais, aprendizados e conversas com representantes de várias áreas do conhecimento para trazer luz ao debate sobre a responsabilidade individual para a construção de uma sociedade mais igualitária.<br />
							<a href="javascript:showMoreText()" class="link-more-text show">Mostrar mais</a>
						</p>
						<p class="more-text">De porta em porta reúne as contribuições de figuras como Yuval Noah Harari, Esther Duflo, Michael Sandel e Anne Applebaum, além de memórias muito pessoais de Huck e relatos de encontros com brasileiros anônimos, mas cheios de histórias para contar.</p>
					</div>
				</div>
				<div class="row mb-4">
					<div class="col-12 col-md-6">
						<h4 class="title">Informações do livro</h4>
						<div class="row">
							<div class="col-3">
								<div class="book-info">
									<div class="title">Páginas</div>
									<?php Icon_Class::va_icons("pages"); ?>
									<div class="description">264 páginas</div>
								</div>
							</div>
							<div class="col-3">
								<div class="book-info">
									<div class="title">Idioma</div>
									<?php Icon_Class::va_icons("language"); ?>
									<div class="description">Português</div>
								</div>
							</div>
							<div class="col-3">
								<div class="book-info">
									<div class="title">Editora</div>
									<?php Icon_Class::va_icons("books"); ?>
									<div class="description">Objetiva</div>
								</div>
							</div>
							<div class="col-3">
								<div class="book-info">
									<div class="title">Publicação</div>
									<?php Icon_Class::va_icons("calendar"); ?>
									<div class="description">20 agosto 2021</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-12">
						<h4 class="title text-md-center">Avaliação</h4>
						<div class="row book-rate text-center">
							<div class="col-12">
								<?php polen_get_stars(4.2); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		function showMoreText() {
			document.querySelector(".link-more-text").classList.remove("show");
			document.querySelector(".more-text").classList.add("show");
		}
	</script>
<?php
}

function va_ctas()
{
?>
	<div class="row mb-4">
		<div class="col 12">
			<div class="row mb-3">
				<div class="col-12">
					<a href="#" class="btn btn-primary btn-lg btn-block">Quero meu Vídeo-autógrafo</a>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<a href="#" class="btn btn-outline-primary btn-lg btn-block" target="_blank">Comprar na Magalu</a>
				</div>
			</div>
		</div>
	</div>
<?php
}

function va_what_is()
{
?>
	<div class="row va-what-is">
		<div class="col-12 text-center">
			<h3 class="title"><span class="ico mr-2"><?php Icon_Class::polen_icon_camera_video(); ?></span>O que é o Vídeo-autógrafo</h3>
			<p>O vídeo-autógrafo é uma nova maneira de conectar e criar novas experiências digitais entre leitores e seus autores favoritos. Ao adquirir uma cópia do livro De porta em porta na Magalu, você pode ganhar um vídeo exclusivo e personalizado gravado pelo Luciano Huck.</p>
		</div>
	</div>
<?php
}

function va_front_get_talent_videos($talent, $product_id = 15)
{
	if (!$talent) {
		return;
	}
	$items = polen_get_videos_by_talent($talent);

	$video_url = home_url() . "/v/";
?>
	<div class="row">
		<div class="col-12">
			<h3 class="title">Vídeos-autógrafo</h3>
		</div>
	</div>
	<section id="talent-videos" class="row mb-1 banner-scrollable" data-public-url="<?php echo $video_url; ?>">
		<div class="col-md-12 p-0">
			<div class="banner-wrapper">
				<div class="banner-content type-video<?php if (sizeof($items) < 1) echo " ml-3 ml-md-0" ?>">
					<?php foreach ($items as $item) : ?>
						<div class="polen-card-video">
							<figure class="video-cover large">
								<img loading="lazy" src="<?= $item['cover']; ?>" alt="<?= $item['title']; ?>" data-url="<?= $item['video']; ?>">
								<a href="javascript:openVideoByHash('<?= $item['hash']; ?>')" class="video-player-button"></a>
							</figure>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<input type="hidden" id="product_id" value="<?php echo $product_id; ?>" />

	<div id="video-modal" class="background video-modal">
		<div class="video-card-body">
			<button id="close-button" class="close-button" onclick="hideModal()"><?php Icon_Class::polen_icon_close(); ?></button>
			<div id="video-box"></div>
		</div>
	</div>
<?php
}

function va_cart_form($coupon = "")
{
?>
	<div class="row mb-3">
		<div class="col-12">
			<div class="row">
				<div class="col-12 d-flex">
					<div class="mr-3 va-check-o">
						<?php Icon_Class::polen_icon_check_o(); ?>
					</div>
					<div>
						<p><strong>Seu Código foi validado!</strong><br />Para continuar, preencha os dados abaixo</p>
					</div>
				</div>
			</div>
			<form id="va-cart-form">
				<input type="hidden" name="action" value="create_orders_video_autograph" />
				<input type="hidden" name="coupon" value="<?php echo $coupon; ?>" />
				<p>
					<label for="" class="lg">Nome</label>
					<input type="text" name="name" class="form-control form-control-lg" placeholder="Para quem é esse vídeo-autógrafo" required />
				</p>
				<p>
					<label for="" class="lg">Cidade</label>
					<input type="text" name="city" class="form-control form-control-lg" placeholder="Digite a cidade do homenageado" required />
				</p>
				<p>
					<label for="" class="lg">e-mail</label>
					<input type="email" name="email" class="form-control form-control-lg" placeholder="e-mail de acompanhamento do pedido" required />
				</p>
				<?php if (wc_terms_and_conditions_checkbox_enabled()) : ?>
					<p class="form-row validate-required">
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox d-flex">
							<input type="checkbox" class="form-control form-control-lg" name="terms" <?php checked(apply_filters('woocommerce_terms_is_checked_default', isset($_POST['terms'])), true); // WPCS: input var ok, csrf ok.
																										?> id="terms" />
							<span class="woocommerce-terms-and-conditions-checkbox-text ml-2"><?php wc_terms_and_conditions_checkbox_text(); ?>&nbsp;<span class="required">*</span></span>
						</label>
						<input type="hidden" name="terms-field" value="1" />
					</p>
				<?php endif; ?>
				<p>
					<input type="submit" class="btn btn-primary btn-lg btn-block" value="Pedir meu vídeo-autógrafo" />
				</p>
			</form>
		</div>
	</div>
	<script>
		const formId = '#va-cart-form';
		const form = document.querySelector(formId);
		form.addEventListener("submit", function(e) {
			e.preventDefault();
			polAjaxForm(formId, function() {
				console.log("foi");
			}, function(e) {
				polMessages.error(e);
			});
		});
	</script>
<?php
}

function va_coupon()
{
?>
	<div class="row mb-3">
		<div class="col-12">
			<h1 class="title mb-3">Inserir código</h1>
			<form id="va-check-code">
				<input type="hidden" name="action" value="check_coupon" />
				<input type="text" name="coupon" class="form-control form-control-lg mb-2" placeholder="Inserir código fornecido pela Magalu" required />
				<input type="submit" class="btn btn-primary btn-lg btn-block" value="Checar" />
			</form>
		</div>
	</div>
	<script>
		const formId = '#va-check-code';
		const form = document.querySelector(formId);
		form.addEventListener("submit", function(e) {
			e.preventDefault();
			polAjaxForm(formId, function() {
				console.log("foi");
			}, function(e) {
				polMessages.error(e.Error);
			});
		});
	</script>
<?php
}
