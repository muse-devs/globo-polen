<div class="wrap">

<?php if ( in_array( $order->get_status(), array( 'processing', 'completed', 'refunded' ), true ) && ! empty( $order->get_date_paid() ) ) : ?>

<table class="wc-order-totals">
    <tr>
        <td class="<?php echo $order->get_total_refunded() ? 'label' : 'label label-highlight'; ?>"><?php esc_html_e( 'Paid', 'woocommerce' ); ?>: <br /></td>
        <td width="1%"></td>
        <td class="total">
            <?php echo wc_price( $order->get_total(), array( 'currency' => $order->get_currency() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </td>
    </tr>
    <tr>
        <td>
            <span class="description">
            <?php
            if ( $order->get_payment_method_title() ) {
                echo esc_html( sprintf( __( '%1$s via %2$s', 'woocommerce' ), $order->get_date_paid()->date_i18n( get_option( 'date_format' ) ), $order->get_payment_method_title() ) );
            } else {
                echo esc_html( $order->get_date_paid()->date_i18n( get_option( 'date_format' ) ) );
            }
            ?>
            </span>
        </td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td colspan="3">
            <b>Transaction id:</b><?php echo get_post_meta( $order->get_id(), 'braspag_order_transaction_id', true );  ?>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <b>NSU:</b><?php echo get_post_meta( $order->get_id(), 'braspag_order_nsu', true );  ?>
        </td>
    </tr>  
    <tr>
        <td colspan="3">
            <b>TID:</b><?php echo get_post_meta( $order->get_id(), 'braspag_order_tid', true );  ?>
        </td>
    </tr> 
    <tr>
        <td colspan="3">
            <b>Authorization Code:</b><?php echo get_post_meta( $order->get_id(), 'braspag_order_AuthorizationCode', true );  ?>
        </td>
    </tr>            
    <tr>
        <td colspan="3">
            <b>Valor:</b><?php echo get_post_meta( $order->get_id(), 'braspag_order_amount', true );  ?>
        </td>
    </tr>    
    <tr>
        <td colspan="3">
            <b>Parcelas:</b><?php echo get_post_meta( $order->get_id(), 'braspag_order_installments', true );  ?>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <b>Link:</b>
            <?php 
                $link_array = unserialize( get_post_meta( $order->get_id(), 'braspag_order_links', true ) );
                if( $link_array[1]['Method'] == 'GET' ){
                    echo $link_array[1]['Href'];
                }    
            ?>
        </td>
    </tr>

</table>

<div class="clear"></div>

<?php endif; ?>
</div>
<?php
/*
$order_id = ( empty( $order->get_parent_id() ) ) ? $order->ID : $order->get_parent_id();
$payment_id = get_post_meta( $order_id, 'braspag_transaction_id', true );
$nsu = get_post_meta( $order_id, 'braspag_nsu', true );
$tid = get_post_meta( $order_id, 'braspag_tid', true );
$tid = get_post_meta( $order_id, 'braspag_tid', true );
$authorizationCode = get_post_meta( $order_id, 'braspag_authorizationCode', true );
$customer_id = get_post_meta( $order_id, '_customer_user', true );
$customer = get_user_by( 'id', (int)$customer_id);
if( ! empty( $payment_id ) ) {
?>

<p><strong>ID do Pagamento:</strong> <?php echo $payment_id; ?></p>
<?php
}

if( ! empty( $nsu ) ) {
?>
<p><strong>NSU:</strong> <?php echo $nsu; ?></p>
<?php
}

if( ! empty( $tid ) ) {
?>
<p><strong>ID da Transação:</strong> <?php echo $tid; ?></p>
<?php
}

if( ! empty( $authorizationCode ) ) {
?>
<p><strong>Código de autorização:</strong> <?php echo $authorizationCode; ?></p>
<?php
}
*/
?>