<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$email_heading='';
echo "= " . $email_heading . " =\n\n";

sprintf( 'vc se cadastrou nem de comecar neh? show A sua compra #%1$s de %2$s não foi aceita. Detalhes da compra:', 'woocommerce', '10','10' );

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );
