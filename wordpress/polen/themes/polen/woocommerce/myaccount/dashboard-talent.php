<?php
use Polen\Includes\Polen_Talent;
$polen_talent = new Polen_Talent();
$current_user = wp_get_current_user();

if( $polen_talent->is_user_talent( $current_user ) ){
?>
<section class="talent-dashboard-start">
	<header class="page-header">
		<div class="row">
			<div class="col-md-8">
				<h1 class="page-title"><?php esc_html_e('Início', 'polen'); ?></h1>
			</div>
			<div class="col-md-4 text-right">
				<select name="select" id="select" class="form-control">
					<option value="">Selecionar todos</option>
				</select>
			</div>
		</div>
		<p>Aceite ou recuse seus pedidos de vídeos.</p>
	</header><!-- .page-header -->

	<div class="page-content">
		<div class="row mt-4">
			<div class="col-md-12">
				<div class="row msg msg-header">
					<div class="col-md-6">
						<p class="p">Você já ganhou no Polen</p>
						<span class="value"><?php echo $polen_talent->get_total_by_order_status( $current_user->ID, 'wc-completed' );?></span>
					</div>
					<div class="col-md-6">
						<p class="p">Você tem para receber</p>
						<span class="value"><?php echo $polen_talent->get_total_by_order_status( $current_user->ID );?></span>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="row msg msg-body">
					<div class="col-md-4">
						<figure class="icon">
							<img src="<?= TEMPLATE_URI; ?>/assets/img/ico-selfie.png" alt="ícone" />
						</figure>
					</div>
					<div class="col-md-8 py-5 my-4">
						<?php 
						$pending = $polen_talent->get_talent_orders( $current_user->ID, false, true );
						if( is_array( $pending ) && isset( $pending['qtd'] ) && (int) $pending['qtd'] > 0  ){
							$time_to_spend = (int) $pending['qtd']*45;
							$total_time = $time_to_spend;
							
							if( $time_to_spend > 60 ){
								$hours = floor($total_time/3600);
								$minutes = floor(($total_time/60) % 60);
								$seconds = $total_time % 60;
								
								if( empty( $hours ) && !empty( $minutes ) ){
									$total_time = str_pad( $minutes, 2, 0, STR_PAD_LEFT ).':'.str_pad( $seconds, 2, 0, STR_PAD_LEFT ).' minutos ';
								}

								if( empty( $minutes ) && !empty( $seconds ) ){
									$total_time = str_pad( $seconds, 2, 0, STR_PAD_LEFT ).' segundos ';
								}
							}
							
						?>
							<div class="row">
								<div class="col-md-12 d-flex justify-content-start">
									<div>
										<p class="p small">Invista hoje</p>
										<span class="time"><?php echo $total_time;?></span>
									</div>
									<div class="mx-5">
										<?php polen_icon_arrows(); ?>
									</div>
									<div>
										<p class="p small">E receba até</p>
										<span class="value small"><?php echo $polen_talent->get_total_by_order_status( $current_user->ID );?></span>
									</div>
								</div>
							</div>
							<p class="mt-4"><a class="btn btn-primary btn-lg" href="<?php echo wc_get_account_endpoint_url( 'orders' ); ?>">Ganhar agora</a></p>
						<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div><!-- .page-content -->
</section><!-- .no-results -->
<?php
}