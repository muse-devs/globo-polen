<?php

namespace Polen\Tributes;

use Polen\Admin\Partials\Tributes_Display;
use Polen\Includes\Debug;
use Polen\Includes\Vimeo\Polen_Vimeo_Response;
use Vimeo\Vimeo;

class Tributes_Details_Admin
{
    public function __construct( $static = false )
    {
        if( $static ) {
            add_action( 'admin_menu', array( $this, 'tributes_add_admin_menu' ) );

            add_action( 'wp_ajax_tributes_download_video', [ $this, 'check_hash_exists' ] );
        }
    }

    /**
     * Admin Menu
     */
    public function tributes_add_admin_menu(){
        add_submenu_page( 
            null,
            'My Custom Submenu Page',
            'My Custom Submenu Page',
            'manage_options',
            'tributes_details',
            array( $this, 'show_tribute_details' ),
        );
    }

    public function show_tribute_details()
    {
        $tribute_id = filter_input( INPUT_GET, 'tribute_id', FILTER_VALIDATE_INT );
        $tribute = Tributes_Model::get_by_id( $tribute_id );
        $tribute_success = tributes_tax_success_tribute( $tribute_id );
        $invites = Tributes_Invites_Model::get_all_by_tribute_id( $tribute_id );
        $deadline = date( 'd/m/Y', strtotime( $tribute->deadline ) );
        ?>
        <div class="wrap">
            <h2>Detalhes do Colab </h2>

            <div>
                <table class="wp-list-table widefat fixed table-view-list">
                    <tr>
                        <th>Tx sucesso</th>
                        <th>Link</th>
                        <th>Prazo</th>
                    </tr>
                    <tr>
                        <td><?= $tribute_success;?></td>
                        <td><a href="<?= tribute_get_url_tribute_detail( $tribute->hash );?>" target="_blank">Ir para o tributo</a></td>
                        <td><?= $deadline;?></td>
                    </tr>
                </table>
            </div>

            <div>
                <h4>Lista de convites</h4>
                <table class="wp-list-table widefat fixed striped table-view-list">
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Enviado</th>
                        <th>Completo</th>
                        <th>Link download</th>
                    </tr>
                <?php foreach( $invites as $invite ) : ?>
                    <tr>
                        <td><?= $invite->email_inviter;?></td>
                        <td><?= $invite->name_inviter;?></td>
                        <td><?= $this->show_icon_if_row_table_is_1( $invite->video_sent );?></td>
                        <td><?= $this->show_icon_if_row_table_is_1( $invite->vimeo_process_complete );?></td>
                        <td><a href="#" class="download_vimeo_link" data="<?= $invite->ID;?>">Download</a></td>
                    </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>

        <script>
            jQuery(function(){
                jQuery('.download_vimeo_link').click(function(evt){
                    evt.preventDefault();
                    let invite_id = evt.currentTarget.getAttribute('data');
                    <?php $url_admin = admin_url('admin-ajax.php') . '?action=tributes_download_video'; ?>
                    jQuery.post("<?= $url_admin; ?>", {invite_id}, function(data){
                        if( data.success == true ) {
                            window.location.href = data.data;
                        }
                    });
                });
            });
        </script>
        <?php
    }

    private function show_icon_if_row_table_is_1( $param )
    {
        if( $param == '1' ) {
            return '<span class="dashicons dashicons-yes"></span>';
        } else {
            return '<span class="dashicons dashicons-no-alt"></span>';
        }
    }


    public function check_hash_exists()
    {
        global $Polen_Plugin_Settings;

        $client_id = $Polen_Plugin_Settings['polen_vimeo_client_id'];
        $client_secret = $Polen_Plugin_Settings['polen_vimeo_client_secret'];
        $token = $Polen_Plugin_Settings['polen_vimeo_access_token'];

        $invite_id = filter_input( INPUT_POST, 'invite_id' );
        $invite = Tributes_Invites_Model::get_by_id( $invite_id );

        $lib = new Vimeo( $client_id, $client_secret, $token );
        try {
            $response = new Polen_Vimeo_Response( $lib->request( $invite->vimeo_id ) );
            wp_send_json_success( $response->get_download_source_url(), 200 );
        } catch ( \Exception $e ) {
            \wp_send_json_error( $e->getMessage(), $e->getCode() );
        }
        wp_die();
    }
}