<?php

use Polen\Includes\Polen_Talent;

$polen_talent = new Polen_Talent();
$current_user = wp_get_current_user();

if ($polen_talent->is_user_talent($current_user)) {
?>
	<section class="talent-dashboard-start">
		<header class="page-header">
			<div class="row">
				<div class="co-12 col-md-12">
					<h1>Bem-vindo, <?php echo $current_user->display_name; ?></h1>
					<p class="mt-2">Aceite ou recuse seus pedidos de vídeos.</p>
				</div>
			</div>
		</header><!-- .page-header -->

		<div class="page-content">
			<div class="row">
				<div class="col-md-12">

				</div>
			</div>
			<div class="row mt-3">
				<div class="col-md-12">
					<div class="msg msg-header">
						<div class="row">
							<div class="col-md-6 mb-3">
								<p class="p">Você já ganhou</p>
								<span class="value"><?php echo $polen_talent->get_total_by_order_status($current_user->ID, 'wc-completed'); ?></span>
							</div>
							<div class="col-md-6">
								<p class="p">Você tem para receber</p>
								<span class="value"><?php echo $polen_talent->get_total_by_order_status($current_user->ID); ?></span>
							</div>
						</div>
					</div>
				</div>
				<?php
				$total_time = $polen_talent->get_time_to_videos($current_user);
				if ($total_time) {
				?>
					<div class="col-md-12">
						<div class="msg msg-body">
							<div class="row">
								<div class="col-12">
									<figure class="icon text-center">
										<img src="<?= TEMPLATE_URI; ?>/assets/img/tutorial_img_2.png" alt="ícone" />
									</figure>
								</div>
							</div>
							<div class="row">
								<div class="col-12 col-md-8 mx-md-auto">
									<div class="row">
										<div class="col-12 col-md-12">
											<div class="text-md-center">
												<p class="p small">Invista hoje</p>
												<span class="value small"><?php echo $total_time; ?></span>
											</div>
											<div class="text-md-center">
												<p class="p small mt-3">E receba até</p>
												<span class="value small"><?php echo $polen_talent->get_total_by_order_status($current_user->ID); ?></span>
											</div>
										</div>
									</div>
									<p class="mt-4 text-center"><a class="btn btn-primary btn-lg btn-block" href="<?php echo wc_get_account_endpoint_url('orders'); ?>">Ganhar agora</a></p>
								</div>
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
