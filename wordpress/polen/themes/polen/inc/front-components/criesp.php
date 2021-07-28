<?php

function criesp_get_home_banner($link)
{
?>
	<div class="row mt-4">
		<div class="col-12">
			<div class="criesp-banner">
				<img class="image" src="<?php echo TEMPLATE_URI . '/assets/img/criesp/bg-criesp.jpg'; ?>" alt="Fundo Criança Esperança">
				<div class="content">
					<img src="<?php echo TEMPLATE_URI . '/assets/img/criesp/logo-criesp.png';  ?>" alt="Logo Criança Esperança" />
					<p class="mt-3">Na Polen 100% do cachê dos vídeos serão revertidos em doações para o Criança Esperança.</p>
					<a href="<?php echo $link; ?>" class="btn btn-primary btn-md">Doe agora<span class="ml-2"><?php Icon_Class::polen_icon_chevron_right(); ?></span></a>
				</div>
			</div>
		</div>
	</div>
<?php
}

function polen_front_get_donation_box_criesp()
{
?>
	<section class="row donation-box custom-donation-box mt-4 mb-4">
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
					<div class="col-md-12">
						<figure class="image">
							<img src="<?php echo TEMPLATE_URI . '/assets/img/criesp/logo-criesp-color.png';  ?>" alt="Logo da empresa de doação">
						</figure>
						<p><strong>Sobre o Criança Esperança</strong></p>
						<p class="small">Criança Esperança (anteriormente SOS Nordeste) é uma campanha nacional de mobilização social que busca a conscientização em prol dos direitos da criança e do adolescente, promovida pela Globo, inicialmente em parceria com a UNICEF e atualmente com a UNESCO.</p>
					</div>
					<div class="col-md-12 mt-3">
						<p><strong>Fotos</strong></p>
						<div class="image-slider">
							<div class="image-slider-content">
								<figure class="item">
									<img width="107" height="102" src="https://loremflickr.com/107/102" alt="Foto">
								</figure>
								<figure class="item">
									<img width="107" height="102" src="https://loremflickr.com/107/102" alt="Foto">
								</figure>
								<figure class="item">
									<img width="107" height="102" src="https://loremflickr.com/107/102" alt="Foto">
								</figure>
							</div>
						</div>
					</div>
					<div class="col-md-12 mt-3">
						<p><strong>Videos</strong></p>
						<div class="video-slider">
							<video muted="" autoplay="" loop="" playsinline="" poster="https://especiaiscomunicacaoprod.s3.amazonaws.com/criesp/doacoes/crianca/doacao/maik-doacao.png?Expires=1627496328&amp;AWSAccessKeyId=AKIAJXGK6DAEMAYESHFQ&amp;Signature=a1SxmAD%2BObKTL06O%2FhkTZUIu3dE%3D" data-fallback="/static/img/Maik-Doar.jpg">
								<source src="https://especiaiscomunicacaoprod.s3.amazonaws.com/criesp/doacoes/crianca/doacao/maik-doacao.mp4?Expires=1627496328&amp;AWSAccessKeyId=AKIAJXGK6DAEMAYESHFQ&amp;Signature=ye%2FCEcntuMo9XhQW0pkDoy7SYTI%3D" type="video/mp4">
							</video>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php
}
