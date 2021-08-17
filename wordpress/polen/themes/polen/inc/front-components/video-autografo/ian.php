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

function va_magalu_box_thank_you()
{
?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="magalu-box">
                <div class="header-box">
                    <img src="<?php echo TEMPLATE_URI . '/assets/img/video-autografo/lu.png'; ?>" alt="Lu"></img>
                    <h3>Obrigada por pedir seu<br>vídeo-autógrafo</h3>
                </div>
                <div class="content-box">
                    <p>Luciano vai enviar um vídeo<br>personalizado em até 15 dias.</p>
                </div>
            </div>
        </div>
    </div>
<?php
}

function va_magalu_box_cart()
{
?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="magalu-box">
                <div class="header-box">
                    <img src="<?php echo TEMPLATE_URI . '/assets/img/video-autografo/lu.png'; ?>" alt="Lu"></img>
                    <h3>Para pedir um Vídeo-autógrafo você precisa:</h3>
                </div>
                <div class="content-box mt-3">
                    <div class="row">
                        <div class="col-12 col-md-6 m-md-auto d-flex align-items-center">
                            <ul class="order-flow half">
                                <li class="item itempayment-approved complete">
                                    <span class="background status">1</span>
                                    <span class="text">
                                        <p class="description">Comprar o livro no site da <b>Magalu</b></p>
                                    </span>
                                </li>
                                <li class="item itempayment-approved complete">
                                    <span class="background status">2</span>
                                    <span class="text">
                                        <p class="description">Confira o e-mail da Magalu com o código de vídeo-autógrafo</p>
                                    </span>
                                </li>
                                <li class="item itempayment-approved complete">
                                    <span class="background status">3</span>
                                    <span class="text">
                                        <p class="description">Adicione o código que você recebeu abaixo</p>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}  
?>