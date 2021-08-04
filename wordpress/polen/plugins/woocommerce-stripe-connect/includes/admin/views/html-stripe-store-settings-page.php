<?php
// version 2.0.16
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $zcwc_stripe_connect_notices;
?>

<div class="wrap">

	<h2><?php esc_html_e( 'Stripe Settings', 'woocommerce-stripe-connect' ); ?></h2>

	<?php 
	if( ! empty( $zcwc_stripe_connect_notices ) ) {
		foreach( $zcwc_stripe_connect_notices as $type => $msg) { ?>
			<div class="notice notice-<?php echo $type; ?> is-dismissible">
                <p><?php echo esc_html( $msg ); ?></p>
            </div>
		<?php }
	}

	?>
	
	<form id="zcwc-stripe-connect-settings" action="" method="post">
		<input type="hidden" name="page" value="zcwc-stripe-connect-settings"/>
		
		<table class="form-table">
			<tbody>

				<?php if( '' != $stripe_user_id  && true === $charges_enabled ) : ?>

					<tr class="form-field">
						<th scope="row" valign="top"><label for="zcwc-stripe-connect-account"><?php esc_html_e( 'Stripe Account ID', 'woocommerce-stripe-connect' ); ?></label></th>
						<td>
							<p><?php echo esc_html( $stripe_user_id ); ?></p>

						</td>
					</tr>

					<tr class="form-field">
						<th scope="row" valign="top"><label for="zcwc-stripe-connect-livemode"><?php esc_html_e( 'Stripe Live Mode', 'woocommerce-stripe-connect' ); ?></label></th>
						
						<td>
							<p><?php echo esc_html( $stripeLiveMode ); ?></p>
						</td>
					</tr>

					<tr class="form-field">
						<th scope="row" valign="top"><label for="zcwc-stripe-connect-livemode"><?php esc_html_e( 'Account Type', 'woocommerce-stripe-connect' ); ?></label></th>
						
						<td>
							<p><?php echo esc_html( ucfirst( $account_type ) ); ?></p>
						</td>
					</tr>

					<?php if( '' != $account_link ) : ?>
						<tr class="form-field">
							<th scope="row" valign="top"><label for="zcwc-stripe-connect-livemode"><?php esc_html_e( 'Profile', 'woocommerce-stripe-connect' ); ?></label></th>
							
							<td>
								<p><a href="<?php echo $account_link; ?>" class="button"><?php esc_html_e( 'Edit Profile', 'woocommerce-stripe-connect' ); ?></a></p>
							</td>
						</tr>
					<?php endif; ?>

					<tr class="form-field">
						<th scope="row" valign="top"><label for="zcwc-stripe-connect-livemode"><?php esc_html_e( 'Deauthorize', 'woocommerce-stripe-connect' ); ?></label></th>
						
						<td>
							<p><a href="<?php echo admin_url('admin.php?page=zcwc-stripe-connect&revoke_stripe_connect'); ?>" class="button button-remove remove"><?php esc_html_e( 'Disconnect Account', 'woocommerce-stripe-connect' ); ?></a></p>
						</td>
					</tr>	

				<?php else:?>

					<tr class="form-field">
						<th scope="row" valign="top"><label for="zcwc-stripe-connect-auth"><?php esc_html_e( 'Authorize Stripe', 'woocommerce-stripe-connect' ); ?></label></th>
						
						<td>
							<a href="<?php echo esc_url( $connect_standard_url ); ?>" class="button button-secondary"><?php esc_html_e( 'Connect a Standard Stripe Account', 'woocommerce-stripe-connect' ); ?></a>

							<a href="<?php echo esc_url( $connect_express_url ); ?>" class="button button-secondary"><?php esc_html_e( 'Connect a Express Stripe Account', 'woocommerce-stripe-connect' ); ?></a>

							<p><?php esc_html_e( 'Choose an option above to connect your Stripe account.', 'woocommerce-stripe-connect' ); ?></p>
						</td>
					</tr>

				<?php endif; ?>
				
				
			</tbody>
		</table>
	</form>
</div>
		