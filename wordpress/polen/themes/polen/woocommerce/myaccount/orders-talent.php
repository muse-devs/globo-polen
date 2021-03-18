<?php

use Polen\Includes\Polen_Talent;

$polen_talent = new Polen_Talent();

$talent_orders = '';
$logged_user = wp_get_current_user();
if (in_array('user_talent',  $logged_user->roles)) {
	$talent_id = $logged_user->ID;
	$talent_orders = $polen_talent->get_talent_orders($talent_id);
}

?>
<section>
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e('Suas Solicitações', 'polen'); ?></h1>
	</header><!-- .page-header -->
	<div class="page-content">
		<?php
		if (empty($talent_orders)) {
			echo "<p>Você não possui novas solicitações</p>";
		} else {
			echo "<p class='mb-5'>Você tem <strong>" . count($talent_orders) . " pedidos de vídeo</strong>, seus pedidos expiram em até 7 dias.</p>";
			if (count($talent_orders) > 0) {
				foreach ($talent_orders as $order) :
		?>
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<div class="talent-orders">
									<header class="row header">
										<div class="col-md-4">
											<p class="p">Valor</p>
											<span class="value">R$200</span>
										</div>
										<div class="col-md-4">
											<p class="p small">Tempo estimado</p>
											<span class="time">2 minuos</span>
										</div>
										<div class="col-md-4">
											<p class="p small">Válido por</p>
											<span class="time">7 dias</span>
										</div>
									</header>
									<div class="row">
										<div class="col">
											<div>
												<p class="p small">Vídeo de</p>
												<span class="name"><?php echo $order['from']; ?></span>
											</div>
											<?php polen_icon_arrows(); ?>
											<div>
												<p class="p small">Para</p>
												<span class="name"><?php echo $order['name']; ?></span>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col">
											<p class="p small">Ocasião</p>
											<span class="category"><?php echo $order['category']; ?></span>
										</div>
									</div>
									<div class="row">
										<div class="col">
											<p class="p small">Instruções</p>
											<p class="text"><?php echo $order['instructions']; ?></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

		<?php
				endforeach;
			}
		}



		?>


	</div><!-- .page-content -->
</section><!-- .no-results -->