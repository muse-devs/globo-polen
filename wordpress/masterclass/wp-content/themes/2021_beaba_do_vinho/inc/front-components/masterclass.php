<?php

function mc_get_top_banner()
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
						<input type="submit" value="Quero ganhar desconto" class="btn btn-primary btn-lg btn-block mt-4 gradient" />
					</div>
				</div>
			</div>
		</div>
	</div>
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
								<p class="text-center">Participe do curso com duração de x dias, exclusivo e feito sobre medida para amantes de vinho.</p>
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
	<div class="row mb-4">
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
						<img src="<?php echo TEMPLATE_URI . "/assets/img/masterclass/garrafa.svg"; ?>" alt="Ícone garrafa" />
					</div>
					<div class="col-10 pl-0 ml-0">
						<p class="description"><strong>Só vinho caro tem qualidade?</strong> Como escolher vinho bom e barato.</p>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-2">
						<img src="<?php echo TEMPLATE_URI . "/assets/img/masterclass/taca_garrafa.svg"; ?>" alt="Ícone garrafa" />
					</div>
					<div class="col-10 pl-0 ml-0">
						<p class="description"><strong>O que ler nos rótulos das garrafas para escolher seu vinho?</strong> Tipos de taças para cada tipo de vinho.</p>
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

function pol_get_footer()
{
	?>
	<?php
}