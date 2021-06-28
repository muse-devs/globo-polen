<?php
$product_sku = get_query_var( 'lp_product_sku' );
$product_id = wc_get_product_id_by_sku(['sku' => $product_sku]);
$product = wc_get_product( $product_id );
$event = "landpage";
$landpage_signin_nonce = wp_create_nonce('landpage-signin');

get_header();
?>
<div class="landpage-card">
		<div class="row">
			<div class="col-12 col-md-12 col-lg-10">
				<div class="row">
					<div class="col-7 m-auto m-md-0 col-md-4">
						<figure class="image-cropper">
							<?php echo $product->get_image(); ?>
						</figure>
					</div>
					<div class="col-12 mt-3 col-md-8 pl-md-5">
						<h1 class="title">Você sabia que Cauã Reymond apoia o projeto XPTO?</h1>
						<p class="subtitle">Pedindo um vídeo na Polen todo o valor será revertido nesta causa.</p>
						<form action="./" method="POST" id="landpage-form" class="landpage-form">
							<div class="row">
								<div class="mt-4 col-md-9 mt-md-5">
									<div class="row">
										<div class="mb-3 col-md-12">
											<label for="signin_landpage" class="label">Você quer apoiar o Cauã nesta causa?</label>
											<input type="email" name="signin_landpage" id="signin_landpage" placeholder="Entre com o seu e-mail" class="form-control form-control-lg" />
											<input type="hidden" name="signin_landpage_page_source" value="<?= filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" />
											<input type="hidden" name="signin_landpage_event" value="<?= $event; ?>" />
											<input type="hidden" name="signin_landpage_is_mobile" value="<?= polen_is_mobile() ? "1" : "0"; ?>" />
											<input type="hidden" name="wnonce" value="<?php echo $landpage_signin_nonce; ?>" />
										</div>
										<div class="col-md-12">
											<button class="signin-landpage-button btn btn-primary btn-lg btn-block">Quero um vídeo Polen</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
get_footer();
