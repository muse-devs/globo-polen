<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_email_header', $email_heading, $email );

?>

<p><?php printf( esc_html__( 'Olá a compra de número #%1$s expirou e você perdeu de ganhar %2$s. Detalhes da compra:', 'woocommerce' ), esc_html( $order->get_order_number() ), esc_html( $order->get_formatted_billing_full_name() ) ); ?></p>

<?php

do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );
// do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

if( isset( $additional_content ) && ! empty( $additional_content ) ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );