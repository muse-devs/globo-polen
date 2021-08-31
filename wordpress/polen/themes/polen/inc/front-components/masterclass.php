<?php

function mc_get_home_banner($link)
{
?>
	<div class="row mt-4">
		<div class="col-12">
			<div class="mc-banner">
				<img class="image mobile-img" src="<?php echo TEMPLATE_URI . '/assets/img/masterclass/mc-banner-mobile.png'; ?>" alt="Polen Masterclass">
				<img class="image desktop-img" src="<?php echo TEMPLATE_URI . '/assets/img/masterclass/mc-banner-desktop.png'; ?>" alt="Polen Masterclass">
				<div class="content">
					<div class="left">
						<img class="img-responsive" src="<?php echo TEMPLATE_URI . '/assets/img/masterclass/masterclass-logo.png'; ?>" alt="Polen Masterclass"></img>
						<p class="mt-3">
							Aprenda como escolher, apreciar e <br>
							harmonizar vinhos com Ronnie Von
						</p>
						<a href="<?php echo $link; ?>" class="btn btn-primary btn-md">Conheça<span class="ml-2"><?php Icon_Class::polen_icon_chevron_right(); ?></span></a>
					</div>
					<div class="right">
						<img class="img-responsive" src="<?php echo TEMPLATE_URI . '/assets/img/masterclass/mask.png'; ?>" alt="Polen Masterclass"></img>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}


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
					<video id="mc-video" playsinline poster="<?php echo TEMPLATE_URI; ?>/assets/img/masterclass/player-poster.png">
						<source src="https://player.vimeo.com/external/595532426.sd.mp4?s=ab2b9eebb3b1c17cd060ebe49d31ed2949472cea&profile_id=164" type="video/mp4">
					</video>
				</div>
				<div class="row">
					<div class="col-12 col-md-6 m-md-auto">
						<h2 class="subtitle">Participe do grupo do Whatsapp para ter um desconto exclusivo no dia das inscrições.</h2>
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
							<input type="email" name="email" class="form-control form-control-lg" placeholder="Entre com seu e-mail" required />
							<input type="submit" value="Quero participar do grupo de Whatsapp" class="btn btn-primary btn-lg btn-block mt-4 gradient" />
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		const video = document.querySelector("#mc-video");
		const success = "<?php echo master_class_url_success(); ?>";

		function addVideoListener() {
			video.load();
			video.addEventListener("click", playVideo);
		}

		function playVideo() {
			video.play();
			video.controls = true;
			video.removeEventListener("click", playVideo);
		}

		addVideoListener();
		video.addEventListener("ended", addVideoListener);

		const formName = "form#form-email-masterclass";
		document.querySelector(formName).addEventListener("submit", function(evt) {
			evt.preventDefault();
			polAjaxForm(formName, function() {
				setSessionMessage(
					CONSTANTS.SUCCESS,
					"Obrigado!",
					"e-mail cadastrado com sucesso"
				)
				window.location.href = success;
			}, function(err) {
				polMessages.error(err);
			});
		});
	</script>
<?php
}
