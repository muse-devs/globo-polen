<div class="wrap">
    <?php
    use Polen\Includes\Polen_WooCommerce;
    $Polen_WooCommerce = new Polen_WooCommerce();
    $details = $Polen_WooCommerce->get_order_items( $order_id );
    ?>
    <table class="wc-order-totals">
        <?php 
        if( $details && ! is_null( $details ) && is_array( $details ) && ! empty( $details) ) {
            foreach( $details as $k => $v ) {
                foreach( $v as $j => $info ) {
                    if( $j != 'id' && $info != 'other_one' ) {
        ?>
        <tr>
            <td>
                <strong><?php echo $j?>:</strong>
            </td>
            <td>
                <?php echo $info ?>
            </td>
        </tr>
        <?php
                    }
                }
            }
        }
        ?>
    </table>
    <div class="clear"></div>
</div>