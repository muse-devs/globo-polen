<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://polen.me
 * @since      1.0.0
 *
 * @package    Promotional_Event
 * @subpackage Promotional_Event/admin/partials
 */
?>

<div class="container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading clearfix">
            <div class="col-md-10"><h3>Lista de Cupons</h3></div>
<!--            <div class="col-md-2"><a href="#" class="btn btn-success pull-right" id="export">Exportar CSV</a></div>-->
        </div>
        <div class="panel-body">
            <table id="list-table" class="table table-striped table-bordered hover" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Código</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">ID Produto</th>
                </tr>
                </thead>
                <tfoot>
                <tr class="text-center">
                    <th class="text-center">#</th>
                    <th class="text-center">Código</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">ID Produto</th>
                </tr>
                </tfoot>
                <tbody>
                <?php foreach ($values_code as $code): ?>
                    <tr class="text-center">
                        <td><?php echo $code->id; ?></td>
                        <td><?php echo $code->code; ?></td>
                        <?php if ($code->is_used == 1) : ?>
                            <td><?php echo "Utilizado"; ?></td>
                        <?php else: ?>
                            <td><?php echo "Não Utilizado"; ?></td>
                        <?php endif; ?>
                        <td><?php echo !empty($code->product_id) ? $code->product_id : '--'; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready( function ($) {
        $('#list-table').DataTable({
            // "pageLength": 5,
            // "pagingType": "full_numbers"
        });
        $('#list-table').removeClass( 'display' ).addClass('table table-striped table-bordered');
    });

</script>

</script>


