<?php
use Polen\Admin\Polen_Admin_Order_Custom_Fields;
use Polen\Includes\Polen_Cart;
use Polen\Includes\Polen_Order;
use Polen\Includes\Polen_WooCommerce;
?>
<div class="wrap">
    <?php
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
        <?php
            if ($j == 'Instruções do vídeo') {
        ?>
        <tr>
            <td>
                <strong><?php echo $j?>:</strong>
            </td>
            <td>
                <p id="video-instructions"><?php echo $info ?></p>
                <a href="#" class="edit-video-instruction" data-old-value="<?= $info; ?>" data-field="<?= Polen_Cart::ITEM_INSTRUCTION_TO_VIDEO; ?>" data-action="polen_edit_order_custom_fields">Editar</a>
            </td>
        </tr>
        <?php
            } else {
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
        ?>
        <?php
                    }
                }
            }
        }
        ?>
        <tr>
            <td>
                <strong>Expira em:</strong>
            </td>
            <td>
                <?php
                try {
                    $_order = wc_get_order( $order_id );
                    $deadline = $_order->get_meta( Polen_Order::META_KEY_DEADLINE, true );
                    $field = Polen_Order::META_KEY_DEADLINE;
                    $deadline_datetime = \WC_DateTime::createFromFormat( 'U', $deadline );
                    if( empty( $deadline_datetime ) ) {
                        throw new \Exception( 'Problema com a data de expiração, entre em contato com o Admin', 500 );
                    }
                    $deadline_date = $deadline_datetime->format( 'd/m/Y' );
                    echo $deadline_date;
                } catch ( \Exception $e ) {
                    echo  $e->getMessage();
                }
                    echo <<<HTML
                        <a href="#" class="edit-video-instruction" data-old-value="{$deadline_date}" data-field="{$field}" data-action="polen_edit_order_custom_fields_deadline">Editar</a>
                    HTML;
                ?>
            </td>
        </tr>
    </table>
    <div class="clear"></div>
</div>
<script>
    let instruction = document.getElementById('video-instructions').textContent;
    document.getElementById('video-instructions').innerHTML = instruction.replace(/&#13;/g, "<br>").replace(/&#10;/g, "<br>");
    jQuery(function(){
        jQuery('.edit-video-instruction').click(function(evt){
            evt.preventDefault();
            let new_value = prompt( 'Nova valor', jQuery(evt.currentTarget).attr('data-old-value').replace(/&#13;/g, " ").replace(/&#10;/g, " ") );
            if (new_value === null) {
                return;
            }
            let data_update = {
                action : jQuery(evt.currentTarget).attr('data-action'),
                field : jQuery(evt.currentTarget).attr('data-field'),
                security : '<?= wp_create_nonce( Polen_Admin_Order_Custom_Fields::NONCE_ACTION ); ?>',
                value : new_value,
                order_id: <?= $_GET['post']; ?>
            };
            jQuery.post(ajaxurl, data_update, function(data,a,b){
                alert(data.data);
                document.location.reload();
            }).fail( (xhr, textStatus, errorThrown) => {
                console.log(xhr, textStatus, errorThrown);
                alert(xhr.responseJSON.data);
            });
        });
    });
</script>