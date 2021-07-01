<?php get_header('tributes'); ?>

<main class="overflow-hidden">
	<div class="container py-3 tribute-container tribute-home">
		<section class="row mt-2 pb-5">
			<div class="col-md-6 d-flex align-items-center">
				<div class="row">
					<div class="col-md-12">
						<h1 class="title">Dê o <span class="color">presente mais significativo</span> do mundo</h1>
						<p class="mt-4">O Tributo simplifica a criação de um vídeo-presente em grupo que você pode dar em qualquer ocasião importante.</p>
						<div class="row">
							<div class="col-10 mt-4">
								<a href="#start" class="btn btn-primary btn-lg btn-block">Comece uma homenagem</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="presentation-wrap">
					<div class="presentation with-video">
						<video id="tribute-home-video" src="<?php echo TEMPLATE_URI; ?>/tributes/assets/video-presentation.mp4"></video>
						<button id="btn-play" class="btn-play">
							<img src="<?php echo TEMPLATE_URI; ?>/tributes/assets/img/play.svg" alt="Botão play">
						</button>
					</div>
					<div class="presentation-extra-area">
						<div class="presentation">
							<img src="<?php echo TEMPLATE_URI; ?>/tributes/assets/img/tribute-img1.jpg" alt="Imagem de fundo 1">
						</div>
						<div class="presentation">
							<img src="<?php echo TEMPLATE_URI; ?>/tributes/assets/img/tribute-img1.jpg" alt="Imagem de fundo 1">
						</div>
						<div class="presentation">
							<img src="<?php echo TEMPLATE_URI; ?>/tributes/assets/img/tribute-img1.jpg" alt="Imagem de fundo 1">
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="row tutorial mt-4 mb-4 pt-4 border-top">
			<div class="col-12">
				<h2 class="title text-center">Como funciona?</h2>
				<p class="text-center">Leva 60 segundos para começar e você pode criar seu Tributo em qualquer dispositivo!</p>
				<div class="d-flex justify-content-md-between mt-5">
					<div class="box-round how-to-tribute">
						<div class="ico">
							<img src="<?php echo TEMPLATE_URI; ?>/tributes/assets/img/user-to-user-transmission.svg" alt="Convide Amigos">
						</div>
						<h4 class="title">Convide amigos</h4>
						<p class="description">Convide amigos e familiares para participar da celebração.</p>
					</div>
					<div class="box-round how-to-tribute">
						<div class="ico">
							<img src="<?php echo TEMPLATE_URI; ?>/tributes/assets/img/carousel-video.svg" alt="Colete vídeos">
						</div>
						<h4 class="title">Colete vídeos</h4>
						<p class="description">Todo mundo recebe um prompt, faz um vídeo e o carrega.</p>
					</div>
					<div class="box-round how-to-tribute">
						<div class="ico">
							<img src="<?php echo TEMPLATE_URI; ?>/tributes/assets/img/share-one.svg" alt="Compartilhe">
						</div>
						<h4 class="title">Compartilhe</h4>
						<p class="description">Envie os vídeos e peça que nossa equipe faça isso por você.</p>
					</div>
				</div>
			</div>
		</section>
		<section class="row mt-5">
			<div class="col-12">
				<div class="box-round p-5">
					<div class="row align-items-center">
						<div class="col-7">
							<div class="title">Comece uma homenagem em 60 segundos ou menos!</div>
						</div>
						<div class="col-5">
							<a href="#start" class="btn btn-primary btn-lg btn-block">Comece uma homenagem</a>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</main>

<?php get_footer('tributes'); ?>
