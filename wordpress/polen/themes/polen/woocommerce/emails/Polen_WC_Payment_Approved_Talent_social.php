<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_email_header', $email_heading, $email );

?>

<p><?php printf( esc_html__( 'Olá, acabamos de receber mais uma doação para o Criança Esperança! O pedido #%1$s de %2$s, foi aprovado! Envie um vídeo para agradecer!', 'woocommerce' ), esc_html( $order->get_order_number() ), esc_html( $order->get_formatted_billing_full_name() ) ); ?></p>

<?php

do_action( 'woocommerce_email_order_details_criesp', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );
// do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

if( isset( $additional_content ) && ! empty( $additional_content ) ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );
