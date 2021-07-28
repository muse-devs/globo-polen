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
					<div class="col-md-12">
						<figure class="image">
							<img src="<?php echo TEMPLATE_URI . '/assets/img/criesp/logo-criesp-color.png';  ?>" alt="Logo da empresa de doação">
						</figure>
						<p><strong>Sobre o Criança Esperança</strong></p>
						<p>Criança Esperança (anteriormente SOS Nordeste) é uma campanha nacional de mobilização social que busca a conscientização em prol dos direitos da criança e do adolescente, promovida pela Globo, inicialmente em parceria com a UNICEF e atualmente com a UNESCO.</p>
					</div>
					<div class="col-md-12">
						<p><strong>Fotos</strong></p>
					</div>
					<div class="col-md-12">
						<p><strong>Videos</strong></p>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php
}
