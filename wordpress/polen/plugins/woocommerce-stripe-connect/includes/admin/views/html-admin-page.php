<?php
/**
 * Admin options screen.
 *
 * @package WooCommerce_PagSeguro_Assinaturas/Admin/Settings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<h3><?php echo esc_html( $this->method_title ); ?></h3>

<?php echo wpautop( esc_html( $this->method_description ) ); ?>

<table class="form-table">
	<?php $this->generate_settings_html(); ?>
</table>