<?php
/**
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product ); // WPCS: XSS ok.

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<?php
		do_action( 'woocommerce_before_add_to_cart_quantity' );

		woocommerce_quantity_input(
			array(
				'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
				'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
				'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
			)
		);

		do_action( 'woocommerce_after_add_to_cart_quantity' );
		?>

		<?php $donate = get_post_meta( get_the_ID(), '_is_charity', true ); ?>
		<?php $social = social_product_is_social($product, social_get_category_base()) ?>

		<button type="submit"
				name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"
				class="single_add_to_cart_button alt btn btn-<?php echo $social ? 'success' : 'primary' ?> btn-lg btn-block btn-get-video py-3">
					<?php if($donate && !$social) : ?>
						<span class="mr-2"><?php Icon_Class::polen_icon_donate(); ?></span>
					<?php endif; ?>
					<?php if($social) : ?>
						<span class="mr-2"><?php Icon_Class::polen_icon_criesp(); ?></span>
					<?php endif; ?>
					<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
		</button>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>

<?php if ( !$product->is_in_stock() ) : ?>
	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

		<a href="<?php echo home_url( 'shop' ); ?>" class="single_add_to_cart_button alt btn btn-primary btn-lg btn-block btn-get-video py-3">
				Escolher outro artista
		</a>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
<?php endif; ?>
