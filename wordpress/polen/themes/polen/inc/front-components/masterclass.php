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
					<img src="" alt="">
				</figure>
				<h1 class="title">Aprenda a escolher, apreciar e harmonizar vinhos com Ronnie Von</h1>
				<video src="" class="mc-home-video"></video>
				<h2 class="subtitle">Participe do grupo do Whatsapp para ter um desconto exclusivo no dia das inscrições.</h2>
				<div class="row">
					<div class="col-12">
						<form action="" id="form-email-masterclass">
							<input type="hidden" name="action" value="polen_whatsapp_form">
							<input type="hidden" name="page_source" value="<?= filter_input(INPUT_SERVER, 'REQUEST_URI'); ?>" />
							<input type="hidden" name="is_mobile" value="<?= polen_is_mobile() ? "1" : "0"; ?>" />
							<input type="hidden" name="security" value=<?php echo wp_create_nonce("mudar-123"); ?>>
							<input type="email" name="" placeholder="Entre com seu e-mail" required />
							<input type="submit" value="Quero participar do grupo de Whatsapp" class="btn btn-primary btn-lg btn-block gradient" />
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}
