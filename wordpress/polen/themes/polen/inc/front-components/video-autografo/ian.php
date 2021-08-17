<?php

function va_get_home_banner($link)
{
?>
	<div class="row mt-4">
		<div class="col-12">
			<div class="va-banner">
				<img class="image mobile-img" src="<?php echo TEMPLATE_URI . '/assets/img/video-autografo/va-banner-mobile.png'; ?>" alt="De Porta em Porta">
                <img class="image desktop-img" src="<?php echo TEMPLATE_URI . '/assets/img/video-autografo/va-banner-desktop.png'; ?>" alt="De Porta em Porta">
				<div class="content">
                    <h2>De porta em porta</h2>
					<p class="mt-3">
                        Agora você pode comprar o livro<br>
                        e ter um autógrafo em vídeo do Luciano Huck.
                    </p>
					<a href="<?php echo $link; ?>" class="btn btn-primary btn-md">Conheça<span class="ml-2"><?php Icon_Class::polen_icon_chevron_right(); ?></span></a>
				</div>
			</div>
		</div>
	</div>
<?php
}
?>