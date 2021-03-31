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
			<?php 
			$total_time = $polen_talent->get_time_to_videos( $current_user );
			if( $total_time ){
			?>
				<div class="col-md-12">
					<div class="row msg msg-body">
						<div class="col-md-4">
							<figure class="icon">
								<img src="<?= TEMPLATE_URI; ?>/assets/img/ico-selfie.png" alt="ícone" />
							</figure>
						</div>
						<div class="col-md-8 py-5 my-4">
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
						</div>
					</div>
				</div>
			<?php
			}
			?>
		</div>
	</div><!-- .page-content -->
</section><!-- .no-results -->
<?php
}