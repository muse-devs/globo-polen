<?php /* Template Name: Vídeo enviado */ ?>


<?php get_header(); ?>

<main id="primary" class="site-main mt-5">
	<div class="row">
		<div class="col-12 text-center">
			<img src="<?php echo TEMPLATE_URI; ?>/assets/img/cup.png" alt="">
			<p class="mt-5">Pronto! Seu vídeo foi<br />enviado. Você ganhou:</p>
			<p style="font-size: 44px; font-weight: 700;">R$500</p>
		</div>
		<div class="col-12 text-center">
			<a href="/my-account/orders/" class="btn btn-outline-light btn-lg">Ver mais pedidos</a>
		</div>
	</div>
</main>

<?php
get_footer();
