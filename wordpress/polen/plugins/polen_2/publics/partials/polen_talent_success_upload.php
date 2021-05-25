<?php
// $order vem do controller Polen_Talent::my_account_success_upload_content()
// $order é um Automattic\WooCommerce\Admin\Overrides\Order
?>
<main id="primary" class="site-main mt-5">
	<div class="row box-video-success">
		<div class="col-12 text-center">
			<img src="<?php echo TEMPLATE_URI; ?>/assets/img/video-sucesso.png" alt="Imagem de sucesso de video" />
			<p class="text mt-5"><strong>Pronto!<br />Seu vídeo foi enviado.</strong><br />Você ganhou:</p>
            <p class="value">R$<?= number_format( $order->get_total(), 2, ',', '' ); ?></p>
		</div>
		<div class="col-12 text-center mt-3">
			<a href="/my-account/orders/" class="btn btn-outline-light btn-lg">Ver mais pedidos</a>
		</div>
	</div>
</main>