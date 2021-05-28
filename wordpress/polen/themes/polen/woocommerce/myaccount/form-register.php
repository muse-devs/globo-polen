<div class="woocommerce">
	<div class="row mt-4 justify-content-md-center talent-login">
		<div class="col-12 col-md-6 mx-md-auto" id="customer_register">
			<div class="col-12 col-md-12">
				<h1><?php esc_html_e('Register', 'woocommerce'); ?></h1>
			</div>
			<?php do_action('woocommerce_before_customer_login_form'); ?>
			<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>

				<?php //do_action('woocommerce_register_form_start'); ?>

				<div class="row">
					<div class="col-12 col-md-12">
						<?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>

							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
								<input type="text" placeholder="<?php esc_html_e('Username', 'woocommerce'); ?>" class="woocommerce-Input woocommerce-Input--text input-text form-control form-control-lg" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine
																																																																																											?>
							</p>

						<?php endif; ?>

						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
							<input type="email" placeholder="<?php esc_html_e('Email address', 'woocommerce'); ?>" class="woocommerce-Input woocommerce-Input--text input-text form-control form-control-lg" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine
																																																																																								?>
						</p>

						<?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>

							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
								<input type="password" placeholder="<?php esc_html_e('Password', 'woocommerce'); ?>" class="woocommerce-Input woocommerce-Input--text input-text form-control form-control-lg" name="password" id="reg_password" autocomplete="new-password" />
							</p>

						<?php else : ?>

							<p class="mb-4"><?php esc_html_e('A password will be sent to your email address.', 'woocommerce'); ?></p>

						<?php endif; ?>

						<?php do_action('woocommerce_register_form'); ?>

						<p class="woocommerce-form-row form-row">
							<?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
							<button type="submit" class="woocommerce-button btn btn-primary btn-lg btn-block btn-login woocommerce-form-register__submit g-recaptcha" data-sitekey="6LdDkPMaAAAAANmJ1fuoYu0dWelkrW30XYe5QKUF" data-callback='polen_onSubmit' data-action='submit' name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Register', 'woocommerce'); ?></button>
							<input type="hidden" name="register" value="Cadastre-se" />
						</p>
					</div>
				</div>

				<?php do_action('woocommerce_register_form_end'); ?>
			</form>
		</div>
	</div>
</div>
