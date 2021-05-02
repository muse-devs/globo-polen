<?php /* Template Name: Vídeo enviado */ ?>


<?php get_header(); ?>

<main id="primary" class="site-main mt-5">
	<div class="row video-sent-success">
		<div class="col-12 col-md-8 m-md-auto text-center">
			<img src="<?php echo TEMPLATE_URI; ?>/assets/img/video-sucesso.png" alt="">
			<p class="mt-5"><strong>Pronto!</strong><br /><strong>Seu vídeo foi enviado.</strong><br />Você ganhou:</p>
			<p class="money">R$500</p>
		</div>
		<div class="col-12 col-md-6 m-md-auto text-center">
			<a href="/my-account/orders/" class="btn btn-outline-light btn-lg btn-block">Ver mais pedidos</a>
		</div>
	</div>
</main>

<?php
get_footer();
