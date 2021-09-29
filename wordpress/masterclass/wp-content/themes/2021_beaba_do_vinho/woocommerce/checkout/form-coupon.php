<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) {
	return;
}

$applied_coupons = '';
if (!empty(WC()->cart->applied_coupons)) {
    $applied_coupons = implode(WC()->cart->applied_coupons);
}

?>
<form class="woocommerce-form-coupon" method="post">
	<div class="box-round box-color mt-4 p-4 px-3">
		<div class="row">
			<div class="col-12">
				<label for="coupon_code" class="form-title">Adicionar Cupom de desconto</label>
				<div class="row">
				<div class="col-12 d-flex mb-4">
					<input
                        type="text"
                        name="coupon_code"
                        class="form-control
                        form-control-lg mr-3"
                        placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>"
                        id="coupon_code" value="<?php echo $applied_coupons; ?>" />
					<button type="submit" class="btn btn-outline-light btn-lg" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>">Aplicar</button>
				</div>
			</div>
		</div>
	</div>
</form>