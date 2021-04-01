<?php

use Polen\Includes\Polen_Talent;

$polen_talent = new Polen_Talent();

$talent_orders = '';
$logged_user = wp_get_current_user();
if (in_array('user_talent',  $logged_user->roles)) {
	$talent_id = $logged_user->ID;
	$talent_orders = $polen_talent->get_talent_orders($talent_id);
	$video_time = $polen_talent->video_time;
}

?>
<section>
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e('Meus Pedidos', 'polen'); ?></h1>
	</header><!-- .page-header -->
	<div class="page-content">
		<?php
		if (empty($talent_orders)) {
			echo "<p>Você não possui novas solicitações</p>";
		} else {
			echo "<p class='mb-5'>Você tem <strong><span id='order-count'>" . count($talent_orders) . "</span> pedido(s) de vídeo</strong>, seus pedidos expiram em até 7 dias.</p>";
			if (count($talent_orders) > 0) {
				foreach ($talent_orders as $order) : ?>
					<div class="row mb-5" box-id="<?php echo $order['order_id']; ?>">
						<div class="col md-12">
							<div class="talent-order">
								<div class="row mb-4 pb-3 bordered">
									<div class="col-md-8">
										<div class="row">
											<div class="col-md-3">
												<p class="title">Vídeo de</p>
												<p class="description"><?php echo $order['from']; ?></p>
											</div>
											<div class="col-md-3"><?php polen_icon_arrows(); ?></div>
											<div class="col-md-3">
												<p class="title">Para</p>
												<p class="description"><?php echo $order['name']; ?></p>
											</div>
											<div class="col-md-3">
												<p class="title">Ocasião</p>
												<p class="description"><?php echo $order['category']; ?></p>
											</div>
										</div>
									</div>
									<div class="col-md-4 text-right">
										<button class="btn btn-primary btn-sm">Visualizar</button>
										<!-- <button class="btn btn-outline-light btn-sm">Visualizar</button> -->
									</div>
								</div>
								<div class="row">
									<div class="col-md-7">
										<div class="row">
											<div class="col-md-4">
												<p class="title">Valor</p>
												<p class="description"><?php echo $order['total']; ?></p>
											</div>
											<div class="col-md-4">
												<p class="title">Tempo estimado</p>
												<p class="description"><?php echo $video_time.' segundos';?></p>
											</div>
											<div class="col-md-4">
												<p class="title">Válido por</p>
												<p class="description"><?php echo $polen_talent->video_expiration_time( $logged_user, $order['order_id'] );?></p>
											</div>
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

		<div class="row" style="display: none;">
			<div class="col-md-12">
				<div class="talent-order-modal">
					<header class="row d-flex align-items-center header">
						<div class="col-md-4">
							<p class="p">Valor</p>
							<span class="value">R$200</span>
						</div>
						<div class="col-md-4 text-center">
							<p class="p small">Tempo estimado</p>
							<span class="time">2 minutos</span>
						</div>
						<div class="col-md-4 text-center">
							<p class="p small">Válido por</p>
							<span class="time">7 dias</span>
						</div>
					</header>
					<div class="body">
						<div class="row d-flex align-items-center">
							<div class="col">
								<p class="p small">Vídeo de</p>
								<span class="name"><?php echo $order['from']; ?></span>
							</div>
							<div class="col text-center">
								<?php polen_icon_arrows(); ?>
							</div>
							<div class="col">
								<div>
									<p class="p small">Para</p>
									<span class="name"><?php echo $order['name']; ?></span>
								</div>
							</div>
						</div>
						<div class="row mt-4">
							<div class="col">
								<p class="p small mb-3">Ocasião</p>
								<span class="category"><?php echo $order['category']; ?></span>
							</div>
						</div>
						<div class="row mt-4">
							<div class="col">
								<p class="p small mb-2">Instruções</p>
								<p class="text"><?php echo $order['instructions']; ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			$accept_reject_nonce = wp_create_nonce('polen-order-accept-nonce');
			?>
			<div class="col-md-12 d-flex justify-content-center my-5" button-nonce="<?php echo $accept_reject_nonce; ?>" order-id="<?php echo $order['order_id']; ?>">
				<button class="icon-button reject mx-3 talent-check-order" type="reject"><?php polen_icon_accept_reject('reject'); ?></button>
				<button class="icon-button accept mx-3 talent-check-order" type="accept"><?php polen_icon_accept_reject(); ?></button>
			</div>
		</div>

	</div><!-- .page-content -->
</section><!-- .no-results -->