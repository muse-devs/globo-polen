<?php

function mc_get_buy_button($product)
{
	if(!$product || empty($product)) {
		return;
	}
	?>
		<div class="row mb-4">
			<div class="col-12 col-md-6 m-md-auto">
				<a href="<?php echo $product['url_to_checkout']; ?>" class="btn btn-primary btn-lg btn-block mt-4 gradient mc-custom-button">Quero me inscrever<br>De <s><?php echo $product['price_regular']?></s> Por <?php echo $product['price']; ?></a>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-md-6 m-md-auto text-center">
				<p class="subtitle">Desconto de primeiro lote. Vagas limitadas.</p>
			</div>
		</div>
	<?php
}

function mc_get_top_banner_lp()
{
?>
	<div class="row mb-4">
		<div class="col-12">
			<div class="mc-landing-banner">
				<figure class="mc-logo">
					<img class="image" src="<?php echo TEMPLATE_URI; ?>/assets/img/masterclass/ronnievon-logo.png" alt="Logo Beabá do Vinho" />
				</figure>
				<div class="row">
					<div class="col-12 col-md-6 m-md-auto">
						<h1 class="title">Aprenda a escolher, apreciar e harmonizar vinhos com Ronnie Von</h1>
					</div>
				</div>
				<div class="mc-home-video mb-4">
					<video id="mc-video" playsinline poster="<?php echo TEMPLATE_URI; ?>/assets/img/masterclass/player-poster.jpg?v=2">
						<source src="https://player.vimeo.com/external/595532426.sd.mp4?s=ab2b9eebb3b1c17cd060ebe49d31ed2949472cea&profile_id=164" type="video/mp4">
					</video>
				</div>
				<div class="row">
					<div class="col-12 col-md-6 m-md-auto">
						<h2 class="subtitle">Participe do grupo de pré-inscrição no WhatsApp para ter um desconto exclusivo no primeiro dia das inscrições.</h2>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-12 col-md-6 m-md-auto">
						<form action="" id="form-email-masterclass">
							<?php //TODO action e nonce
							?>
							<input type="hidden" name="action" value="send_form_request">
							<input type="hidden" name="page_source" value="<?= filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" />
							<input type="hidden" name="is_mobile" value="<?= polen_is_mobile() ? "1" : "0"; ?>" />
							<input type="hidden" name="security" value=<?php echo wp_create_nonce("send-form-request"); ?>>
							<input type="email" name="email" class="form-control form-control-lg" placeholder="Digite seu e-mail" required />
							<input type="submit" value="Quero ganhar desconto" class="btn btn-primary btn-lg btn-block mt-4 gradient" />
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		const success = '<?php echo home_url('/ronnie-von/beaba-do-vinho/sucesso'); ?>';

		polVideoTag("#mc-video");

		const formName = "form#form-email-masterclass";
		document.querySelector(formName).addEventListener("submit", function(evt) {
			evt.preventDefault();
			polAjaxForm(formName, function() {
				window.location.href = success;
			}, function(err) {
				polMessages.error(err);
			});
		});
	</script>
<?php
}

function mc_get_top_banner($product)
{
	if(!$product || empty($product)) {
		return;
	}
?>
	<div class="row mb-4">
		<div class="col-12">
			<div class="mc-landing-banner">
				<figure class="mc-logo">
					<img class="image" src="<?php echo TEMPLATE_URI; ?>/assets/img/masterclass/ronnievon-logo.png" alt="Logo Beabá do Vinho" />
				</figure>
				<div class="row">
					<div class="col-12 col-md-6 m-md-auto text-center">
						<span class="gadget">Inscrições abertas</span>
					</div>
					<div class="col-12 col-md-6 m-md-auto">
						<h1 class="title">Aprenda a escolher, apreciar e harmonizar vinhos com Ronnie Von</h1>
					</div>
				</div>
				<div class="mc-home-video mb-2">
					<video id="mc-video" playsinline poster="<?php echo TEMPLATE_URI; ?>/assets/img/masterclass/player-poster.jpg?v=2">
						<source src="https://player.vimeo.com/external/595532426.sd.mp4?s=ab2b9eebb3b1c17cd060ebe49d31ed2949472cea&profile_id=164" type="video/mp4">
					</video>
				</div>
				<?php mc_get_buy_button($product); ?>
			</div>
		</div>
	</div>
	<script>polVideoTag("#mc-video");</script>
<?php
}

function mc_get_carrossel_how_to()
{
?>
	<div class="row mb-4">
		<div class="col-12">
			<h3 class="title mb-4">Como funciona?</h3>
		</div>
		<div class="col-12">
			<div id="how-to-carousel" class="owl-carousel owl-theme">
				<div class="item">
					<div class="box-round py-3 px-3">
						<div class="row">
							<div class="col-12 mb-1 d-flex justify-content-center">
								<?php Icon_Class::polen_icon_camera_video(); ?>
							</div>
							<div class="col-12">
								<h4>Ao vivo</h4>
								<p class="text-center">Participe de aulas ao vivo e converse em tempo real, tirando todas suas dúvidas com Ronnie Von</p>
							</div>
						</div>
					</div>
				</div>
				<div class="item">
					<div class="box-round py-3 px-3">
						<div class="row">
							<div class="col-12 mb-1 d-flex justify-content-center">
								<?php Icon_Class::polen_icon_clock(); ?>
							</div>
							<div class="col-12">
								<h4>Duração</h4>
								<p class="text-center">Participe do curso com duração de 90 minutos, exclusivo e feito sob medida para amantes de vinho.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="item">
					<div class="box-round py-3 px-3">
						<div class="row">
							<div class="col-12 mb-1 d-flex justify-content-center">
								<?php Icon_Class::polen_icon_hand_thumbs_up(); ?>
							</div>
							<div class="col-12">
								<h4>Disponibilidade</h4>
								<p class="text-center">Tenha acesso e tire suas dúvidas diretamanente com o Ronnie Von.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		jQuery('#how-to-carousel').owlCarousel({
			loop: true,
			items: 1,
			autoplayTimeout: 5000,
			animateOut: 'fadeOut',
			autoplayHoverPause: true,
			margin: 0,
			nav: false,
			autoplay: true,
			dots: true,
			autoHeight: false,
		});
	</script>
<?php
}

function mc_get_box_content()
{
?>
	<div class="row">
		<div class="col-12 mb-3">
			<h3 class="title mb-2">Conteúdo do curso</h3>
		</div>
		<div class="col-12">
			<div class="box-round p-4 masterclass-content-box">
				<div class="row">
					<div class="col-2">
						<img src="<?php echo TEMPLATE_URI . "/assets/img/masterclass/taca.svg"; ?>" alt="Ícone garrafa" />
					</div>
					<div class="col-10 pl-0 ml-0">
						<p class="description"><strong>História e Importância do Vinho:</strong> quais os principais tipos de vinhos.</p>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-2">
						<img src="<?php echo TEMPLATE_URI . "/assets/img/masterclass/taca.svg"; ?>" alt="Ícone garrafa" />
					</div>
					<div class="col-10 pl-0 ml-0">
						<p class="description"><strong>Só vinho caro tem qualidade?</strong> Como escolher vinho bom e barato.</p>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-2">
						<img src="<?php echo TEMPLATE_URI . "/assets/img/masterclass/taca.svg"; ?>" alt="Ícone garrafa" />
					</div>
					<div class="col-10 pl-0 ml-0">
						<p class="description"><strong>O que ler nos rótulos das garrafas para escolher seu vinho?</strong> Tipos de taças para cada tipo de vinho.</p>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-2">
						<img src="<?php echo TEMPLATE_URI . "/assets/img/masterclass/taca.svg"; ?>" alt="Ícone garrafa" />
					</div>
					<div class="col-10 pl-0 ml-0">
						<p class="description">Tipos de taças para cada tipo de vinho.</p>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-2">
						<img src="<?php echo TEMPLATE_URI . "/assets/img/masterclass/taca.svg"; ?>" alt="Ícone garrafa" />
					</div>
					<div class="col-10 pl-0 ml-0">
						<p class="description">Técnicas simples para não errar na harmonização.</p>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-2">
						<img src="<?php echo TEMPLATE_URI . "/assets/img/masterclass/taca.svg"; ?>" alt="Ícone garrafa" />
					</div>
					<div class="col-10 pl-0 ml-0">
						<p class="description">Análise sensorial: visual, olfativa e gustativa.</p>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-2">
						<img src="<?php echo TEMPLATE_URI . "/assets/img/masterclass/taca.svg"; ?>" alt="Ícone garrafa" />
					</div>
					<div class="col-10 pl-0 ml-0">
						<p class="description">Conheça os principais tipos de aromas.</p>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-2">
						<img src="<?php echo TEMPLATE_URI . "/assets/img/masterclass/taca.svg"; ?>" alt="Ícone garrafa" />
					</div>
					<div class="col-10 pl-0 ml-0">
						<p class="description">Técnicas para treinar o paladar.</p>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-2">
						<img src="<?php echo TEMPLATE_URI . "/assets/img/masterclass/taca.svg"; ?>" alt="Ícone garrafa" />
					</div>
					<div class="col-10 pl-0 ml-0">
						<p class="description"><strong>Na prática:</strong> pratos populares harmonizados com vinhos.</p>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-2">
						<img src="<?php echo TEMPLATE_URI . "/assets/img/masterclass/taca.svg"; ?>" alt="Ícone garrafa" />
					</div>
					<div class="col-10 pl-0 ml-0">
						<p class="description"><strong>Na rua:</strong> como escolher vinhos em restaurante</p>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}

function mc_get_bio()
{
?>
	<div class="row">
		<div class="col-12 text-center mb-4">
			<div class="row">
				<div class="col-12">
					<h3 class="title mb-4">Com quem você vai aprender?</h3>
				</div>
				<div class="col-12">
					<div class="box-round book-info-wrapp p-4">
						<div class="row">
							<div class="col-12 mb-3">
								<img class="img-responsive" src="<?php echo TEMPLATE_URI . '/assets/img/masterclass/ronnie.png'; ?>" alt="Ronnie Von"></img>
							</div>
							<div class="col-12">
								<p>
									Ronnie Von tem uma extensa carreira de sucesso como cantor, compositor, ator e apresentador. Grande apreciador de vinhos desde jovem, hoje também é enólogo formado e compartilha dicas sobre vinho em suas entrevistas e redes sociais.
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}

function mc_get_footer()
{
?>
	<div class="row">
		<div class="col-12 text-center mb-4">
			<div class="row">
				<div class="col-12">
					<h3 class="title mb-4">Realização</h3>
				</div>
				<div class="col-12 d-flex justify-content-around">
					<img class="img-responsive" src="<?php echo TEMPLATE_URI . '/assets/img/masterclass/polen-masterclass.png'; ?>" alt="Polen Masterclass"></img>
					<img class="img-responsive" src="<?php echo TEMPLATE_URI . '/assets/img/masterclass/todo-vino.png'; ?>" alt="Todo Vino"></img>
				</div>
			</div>
		</div>
	</div>
<?php
}

function mc_get_thank_you_box() 
{
?>
	<div class="row mb-3">
		<div class="col-12">
			<div class="thank-you-box">
				<img class="img-responsive" src="<?php echo TEMPLATE_URI . '/assets/img/masterclass/ronnie.png'; ?>" alt="Ronnie Von"></img>
				<h3>Obrigada por pedir seu curso “Bebá do vinho"</h3>
				<p>Você vai receber os e-mails com o link do acesso ao curso.</p>
			</div>
		</div>
	</div>
<?php
}

function mc_get_bank_ticket_box() 
{
?>
	<div class="row mb-3">
		<div class="col-12">
			<div class="thank-you-box">
				<img class="img-responsive" src="<?php echo TEMPLATE_URI . '/assets/img/masterclass/ronnie.png'; ?>" alt="Ronnie Von"></img>
				<h3>Obrigada por pedir seu curso “Bebá do vinho"</h3>
				<p><b>Para não perder o curso faça o pagamento do boleto.</b> O prazo para pagamento do boleto é 29/08/2021</p>
				<a href="#" id="payment_ticket_custom_button" class="btn btn-primary btn-lg btn-block mt-4 gradient ticket-custom-button">Pagar Boleto</a>
			</div>
		</div>
	</div>
<?php
}

function pol_get_footer()
{
	?>
	<?php
}