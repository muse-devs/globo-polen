<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */
defined( 'ABSPATH' ) || exit;

use \Polen\includes\Polen_Talent;
$user = wp_get_current_user();
$polen_talent = new Polen_Talent;
if( $polen_talent->is_user_talent( $user ) ) {
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
    get_template_part( 404 );
    exit();
}else{

do_action( 'woocommerce_before_edit_account_form' ); ?>
<div class="row mb-3">
	<div class="col-md-12">
		<h1>Meus Dados</h1>
	</div>
</div>

<form class="woocommerce-EditAccountForm edit-account mt-3" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

	<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first mb-3">
		<input type="text" placeholder="<?php esc_html_e( 'First name', 'woocommerce' ); ?>" class="woocommerce-Input woocommerce-Input--text input-text form-control form-control-lg" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
	</p>
	<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last mb-3">
		<input type="text" placeholder="<?php esc_html_e( 'Last name', 'woocommerce' ); ?>" class="woocommerce-Input woocommerce-Input--text input-text form-control form-control-lg" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" />
	</p>
	<div class="clear"></div>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-3">
		<input type="text" placeholder="<?php esc_html_e( 'Display name', 'woocommerce' ); ?>" class="woocommerce-Input woocommerce-Input--text input-text form-control form-control-lg" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" />
		<small><?php esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ); ?></small>
	</p>
	<div class="clear"></div>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<input type="email" placeholder="<?php esc_html_e( 'Email address', 'woocommerce' ); ?>" class="woocommerce-Input woocommerce-Input--email input-text form-control form-control-lg" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
	</p>

	<fieldset class="mt-4">
		<legend class="col-form-label"><?php esc_html_e( 'Password change', 'woocommerce' ); ?></legend>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-3">
			<input type="password" placeholder="<?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?>" class="woocommerce-Input woocommerce-Input--password input-text form-control form-control-lg" name="password_current" id="password_current" autocomplete="off" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-3">
			<input type="password" placeholder="<?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?>" class="woocommerce-Input woocommerce-Input--password input-text form-control form-control-lg" name="password_1" id="password_1" autocomplete="off" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<input type="password" placeholder="<?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?>" class="woocommerce-Input woocommerce-Input--password input-text form-control form-control-lg" name="password_2" id="password_2" autocomplete="off" />
		</p>
	</fieldset>
	<div class="clear"></div>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<p>
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<button type="submit" class="woocommerce-Button mt-4 btn btn-primary btn-lg btn-block" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
		<input type="hidden" name="action" value="save_account_details" />
	</p>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>

<?php
}
?>
