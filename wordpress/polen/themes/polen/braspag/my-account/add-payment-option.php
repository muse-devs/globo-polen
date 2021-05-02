<div class="row woocommerce-Payment-Options payment-options">
	<!-- Dados do cartão -->
	<div class="col-md-12">
		<h1 class="mb-4">Adicionar Cartão</h1>
		<div class="row">
			<div class="col-12 mb-4">
				<input type="text" placeholder="<?php echo __('Número do cartão', 'cubo9'); ?>" class="form-control form-control-lg" name="braspag_creditcardNumber" id="braspag_creditcardNumber" aria-describedby="<?php echo __('Número do cartão de crédito', 'cubo9'); ?>">
			</div>
			<div class="col-12 mb-4">
				<input type="text" placeholder="<?php echo __('Nome impresso no cartão de crédito', 'cubo9'); ?>" class="form-control form-control-lg" name="braspag_creditcardName" id="braspag_creditcardName" aria-describedby="<?php echo __('Nome impresso no cartão de crédito', 'cubo9'); ?>" maxlength="50">
			</div>
			<div class="col-12 col-md-6 mb-4">
				<input type="text" placeholder="<?php echo __('Validade', 'cubo9'); ?>" class="form-control form-control-lg" name="braspag_creditcardValidity" id="braspag_creditcardValidity" aria-describedby="<?php echo __('Validade', 'cubo9'); ?>">
			</div>
			<div class="col-12 col-md-6 mb-4">
				<input type="text" placeholder="<?php echo __('Código de segurança', 'cubo9'); ?>" class="form-control form-control-lg" name="braspag_creditcardCvv" id="braspag_creditcardCvv" aria-describedby="<?php echo __('Código de segurança', 'cubo9'); ?>">
			</div>
			<div class="col-12">
				<a class="woocommerce-Button btn btn-primary btn-lg btn-block braspag_SaveMyCard" href="#">Adicionar cartão</a>
			</div>
		</div>
	</div>
</div>
