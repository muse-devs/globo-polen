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
	<div class="row">
		<div class="col-12">

		</div>
	</div>
	<?php
}
