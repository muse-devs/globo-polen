<?php

use Polen\Includes\Polen_Update_Fields;
$polen_fields = new Polen_Update_Fields();

use Polen\Includes\Polen_Talent;
$polen_talent = new Polen_Talent();
$current_user = wp_get_current_user();

if( $polen_talent->is_user_talent( $current_user ) ){
    $bank_data = $polen_fields->get_vendor_data( $current_user->ID );
?>
<section>
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e('Pagamento', 'polen'); ?></h1>
        <p class="mb-5">Entre em contato conosco para alterar seus dados de pagamento.</p>
	</header>
</section>
<section class="talent-dashboard-start">
    <div class="page-content">
        <div class="row mb-5" box-id="">
            <div class="col md-12">
                <div class="talent-order">
                    <div class="row mb-4 pb-3 bordered">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="title">Valor pago até agora</p>
                                    <span class="value small"><?php echo $polen_talent->get_total_by_order_status( $current_user->ID, 'wc-completed' );?></span>
                                </div>
                                <div class="col-md-4">
                                    <p class="title">Saldo a liberar</p>
                                    <span class="value small"><?php echo $polen_talent->get_total_by_order_status( $current_user->ID );?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                        </div>
                    </div>
                    <?php
                    if( !empty( $bank_data ) ){ ?>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="row mb-5">
                                    <div class="col-md-8">
                                        <p class="title">Banco</p>
                                        <span class="value small"><?php echo $bank_data->banco; ?></span>
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-md-4">
                                        <p class="title">Agencia</p>
                                        <span class="value small"><?php echo $bank_data->agencia; ?></span>
                                    </div>
                                    <div class="col-md-8">
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <div class="col-md-4">
                                        <p class="title">Conta</p>
                                        <span class="value small"><?php echo $bank_data->conta; ?></span>
                                    </div>
                                    <div class="col-md-8">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>

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
								<?php Icon_Class::polen_icon_arrows(); ?>
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
				<button class="icon-button reject mx-3 talent-check-order" type="reject"><?php Icon_Class::polen_icon_accept_reject('reject'); ?></button>
				<button class="icon-button accept mx-3 talent-check-order" type="accept"><?php Icon_Class::polen_icon_accept_reject(); ?></button>
			</div>
		</div>

	</div><!-- .page-content -->
</section>
<?php
}
?>
