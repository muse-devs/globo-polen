<?php

function mc_get_buy_button($product)
{
	if (!$product || empty($product)) {
		return;
	}
?>
	<div class="row mb-4">
		<div class="col-12 col-md-6 m-md-auto">
			<a href="<?php echo $product['url_to_checkout']; ?>" class="btn btn-primary btn-lg btn-block mt-4 gradient mc-custom-button">
				de <s><?php echo $product['price_regular']; ?></s> por <?php echo $product['price']; ?>
			</a>
		</div>
	</div>
	<!-- <div class="row">
		<div class="col-12 col-md-6 m-md-auto text-center">
			<p class="subtitle"><b>16 de Setembro às 20h, ao vivo</b></p>
		</div>
	</div> -->
<?php
}

function mc_get_top_banner_lp()
{
	$poster = TEMPLATE_URI . "/assets/img/masterclass/player-poster.jpg?v=3";
	$video = "https://player.vimeo.com/external/595532426.sd.mp4?s=ab2b9eebb3b1c17cd060ebe49d31ed2949472cea&profile_id=164";
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
					<video id="mc-video" playsinline poster="<?php echo $poster; ?>">
						<source src="<?php echo $video; ?>" type="video/mp4">
					</video>
				</div>
				<div class="row">
					<div class="col-12 col-md-6 m-md-auto">
						<h2 class="subtitle">Obrigado por seu interesse, mas infelizmente as inscrições para a Masterclass estão fechadas no momento.
                            Cadastre-se na lista de espera para ser notificado em primeira mão assim que a próxima turma for aberta.</h2>
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
							<input type="submit" value="ENTRAR NA LISTA DE ESPERA"" class="btn btn-primary btn-lg btn-block mt-4 gradient" />
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		const success = '<?php echo home_url('/ronnie-von/beaba-do-vinho/sucesso'); ?>';

		//polVideoTag("#mc-video");

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
	if (!$product || empty($product)) {
		return;
	}
?>
	<div class="row mb-4">
		<div class="col-12">
			<div class="mc-landing-banner" style="background:url('<?php echo TEMPLATE_URI . "/assets/img/masterclass/top-bg-gustavo.png"; ?>');background-size: cover;">
				<figure class="mc-logo">
					<img class="image" src="<?php echo TEMPLATE_URI; ?>/assets/img/masterclass/title-sendo-vc.png" alt="Seja Outro Sendo Você" />
				</figure>
				<div class="row">
					<div class="col-12">
						<div class="row">
							<div class="col-md-6 m-md-auto text-center">
								<span class="gadget">06 de Outubro às 20h</span>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-6 m-md-auto">
						<h1 class="title">Aprenda com dois mestres da comédia a se comunicar melhor e transforme a sua vida profissional e pessoal.</h1>
					</div>
				</div>
				<div class="mc-home-video mb-2">
					<img class="image" src="<?php echo TEMPLATE_URI; ?>/assets/img/masterclass/cover-sendo-vc.png" alt="Seja Outro Sendo Você" />
				</div>
				<div class="row">
					<div class="col-12 col-md-6 m-md-auto">
						<h2 class="title">Ao vivo com Gustavo Mendes e Gueminho Bernardes | 06 de Outubro às 20h</h2>
					</div>
				</div>
				<?php mc_get_buy_button($product); ?>
			</div>
		</div>
	</div>
	<script>
		//polVideoTag("#mc-video");
	</script>
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
			<div id="how-to-carousel" class="owl-carousel owl-theme box-round">
				<div class="item">
					<div class="box-round py-3 px-3">
						<div class="row">
							<div class="col-12 mb-1 d-flex justify-content-center">
								<?php Icon_Class::polen_icon_camera_video(); ?>
							</div>
							<div class="col-12">
								<h4>Ao vivo</h4>
								<p class="text-center"> Participe da Masterclass ao vivo no dia 06 de Outubro às 20h.</p>
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
								<p class="text-center">A masterclass terá uma duração média de 90 minutos.</p>
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
								<p class="text-center">O curso ficará disponível por 30 dias para você ver e rever quantas vezes quiser.</p>
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
			dots: true,
			autoHeight: false,
			responsive : {
				0 : {
					items: 1,
					autoplay: true,
				},
				992 : {
					items: 3,
					autoplay: false,
				}
			}
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
					<div class="col-12 pl-0 ml-3">
						<p class="description">● <strong>Apresentação</strong></p>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-12 pl-0 ml-3">
						<p class="description">● <strong>1ª PARTE - INTRODUÇÃO E CONTEXTO</strong></p>
					</div>
					<div class="col-12 pl-0 ml-3">
						<p class="description">○ HISTÓRIA DE VIDA</p>
					</div>
					<div class="col-12 pl-0 ml-3">
						<p class="description">○ POR QUE RESOLVEMOS DAR ESSA AULA?</p>
					</div>
					<div class="col-12 pl-0 ml-3">
						<p class="description">○ O QUE ESSA AULA OFERECE?</p>
					</div>
					<div class="col-12 pl-0 ml-3">
						<p class="description">○ POR QUE VOCÊS VIERAM FAZER O CURSO?</p>
					</div>
					<div class="col-12 pl-0 ml-3">
						<p class="description">○ QUAL A SUA EXPECTATIVA?</p>
					</div>
				</div>
				<div class="row button-more">
					<div class="col-12">
						<a href="javascript:showMore()">Ver mais</a>
					</div>
				</div>
				<div class="row d-none more">
					<div class="col-12">
						<div class="row mt-4">
							<div class="col-12 pl-0 ml-3">
								<p class="description">● <strong>2ª PARTE – O ATOR E O PERSONAGEM</strong></p>
							</div>
							<div class="col-12 pl-0 ml-3">
								<p class="description">○ CONSCIÊNCIA CORPORAL</p>
							</div>
							<div class="col-12 pl-0 ml-3">
								<p class="description">○ PREPARAÇÃO CORPORAL</p>
							</div>
							<div class="col-12 pl-0 ml-3">
								<p class="description">○ EXPRESSÃO CORPORAL</p>
							</div>
							<div class="col-12 pl-0 ml-3">
								<p class="description">○ TIMIDEZ</p>
							</div>
							<div class="col-12 pl-0 ml-3">
								<p class="description">○ CONSTRUÇÃO DE PERSONAGEM</p>
							</div>
							<div class="col-12 pl-0 ml-3">
								<p class="description">○ CONTROLE DO PERSONAGEM</p>
							</div>
							<div class="col-12 pl-0 ml-3">
								<p class="description">○ THE PLAY IS THE THING</p>
							</div>
						</div>
						<div class="row mt-4">
							<div class="col-12 pl-0 ml-3">
								<p class="description">● <strong>3ª PARTE – A COMÉDIA</strong></p>
							</div>
							<div class="col-12 pl-0 ml-3">
								<p class="description">○ O COMEDIANTE</p>
							</div>
							<div class="col-12 pl-0 ml-3">
								<p class="description">○ COMO NASCEM AS PIADAS</p>
							</div>
							<div class="col-12 pl-0 ml-3">
								<p class="description">○ COMO FAZER RIR</p>
							</div>
						</div>
						<div class="row mt-4">
							<div class="col-12 pl-0 ml-3">
								<p class="description">● <strong>4ª PARTE – BÔNUS</strong></p>
							</div>
							<div class="col-12 pl-0 ml-3">
								<p class="description">○ REFERÊNCIAS</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		function showMore() {
			document.querySelector(".button-more").classList.add("d-none");
			document.querySelector(".more").classList.remove("d-none");
		}
	</script>
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
				<div class="col-md-6 col-sm-12 mt-3">
					<div class="box-round book-info-wrapp p-4">
						<div class="row">
							<div class="col-12 mb-3">
								<img class="img-responsive rounded-circle" src="<?php echo TEMPLATE_URI . '/assets/img/masterclass/gustavo.png'; ?>" alt="Gustavo Mendes"></img>
							</div>
							<div class="col-12">
								<p>
									<b>Gustavo Mendes</b> é comediante. Nasceu em Guarani e ainda adolescente começou carreira fazendo shows de stand-up em bares e depois conquistou grandes palcos. Com a Dilma explodiu nacionalmente e virou um fenômeno no Youtube; de onde seguiu para o “Casseta e Planeta” da Globo e para o “Agora É Tarde” da Band.
								</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-12 mt-3">
					<div class="box-round book-info-wrapp p-4">
						<div class="row">
							<div class="col-12 mb-3">
								<img class="img-responsive rounded-circle" src="<?php echo TEMPLATE_URI . '/assets/img/masterclass/gueminho.png'; ?>" alt="Gueminho Bernardes"></img>
							</div>
							<div class="col-12">
								<p>
									<b>Gueminho Bernardes</b> é escritor e comediante. Nasceu em Juiz de Fora, onde criou em 1979 uma das mais importantes companhias de comédia de Minas e do Brasil, o Teatro de Quintal, com quem montou mais de 50 espetáculos. É autor do texto “O Camarim”, vencedor do 3º Prêmio Minas de Dramaturgia.
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
				<div class="col-12 d-flex justify-content-center">
					<img class="img-responsive" src="<?php echo TEMPLATE_URI . '/assets/img/masterclass/polen-masterclass.png'; ?>" alt="Polen Masterclass"></img>
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
				<div class="image-top">
					<img class="img-responsive rounded-circle mr-3" src="<?php echo TEMPLATE_URI . '/assets/img/masterclass/gustavo.png'; ?>" alt="Gustavo Mendes"></img>
					<img class="img-responsive rounded-circle" src="<?php echo TEMPLATE_URI . '/assets/img/masterclass/gueminho.png'; ?>" alt="Gueminho Bernardes"></img>
				</div>
				<h3>Obrigada por pedir seu curso “Seja outro sendo você"</h3>
				<p>Você vai receber os e-mails com o link do acesso ao curso.</p>
			</div>
		</div>
	</div>
<?php
}

function mc_get_bank_ticket_box($date = null)
{
	if ($date === null) {
		$date = date('d/m/Y');
	}
?>
	<div class="row mb-3">
		<div class="col-12">
			<div class="thank-you-box">
				<div class="image-top">
					<img class="img-responsive rounded-circle mr-3" src="<?php echo TEMPLATE_URI . '/assets/img/masterclass/gustavo.png'; ?>" alt="Gustavo Mendes"></img>
					<img class="img-responsive rounded-circle" src="<?php echo TEMPLATE_URI . '/assets/img/masterclass/gueminho.png'; ?>" alt="Gueminho Bernardes"></img>
				</div>
				<h3>Obrigada por pedir seu curso “Seja outro sendo você"</h3>
				<p><b>Para não perder o curso faça o pagamento do boleto.</b> O prazo para pagamento do boleto é <?php echo $date; ?></p>
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
