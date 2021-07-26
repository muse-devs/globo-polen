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
