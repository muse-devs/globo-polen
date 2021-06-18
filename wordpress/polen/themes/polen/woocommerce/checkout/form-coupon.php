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

defined('ABSPATH') || exit;

if (!wc_coupons_enabled()) { // @codingStandardsIgnoreLine.
	return;
}

?>
<form class="checkout_coupon woocommerce-form-coupon box-color mt-4 px-3" method="post">
	<div class="row">
		<div class="col-12">
			<label for="coupon_code" class="form-title"><?php echo __('Adicionar Cupom de desconto', 'cubo9-marketplace'); ?></label>
			<div class="row">
				<div class="col-12 d-flex">
					<input type="text" name="coupon_code" class="form-control form-control-lg mr-3" placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>" id="coupon_code" value="" />
					<button type="submit" class="btn btn-primary btn-lg" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"><?php esc_html_e('Ok', 'woocommerce'); ?></button>
				</div>
			</div>
		</div>
	</div>
</form>
