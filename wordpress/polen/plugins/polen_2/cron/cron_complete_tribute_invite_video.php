<?php

include_once dirname( __FILE__ ) . '/init.php';

use Polen\Includes\Vimeo\Polen_Vimeo_Factory;
use Vimeo\Exceptions\{ExceptionInterface, VimeoRequestException};
use Polen\Includes\Vimeo\Polen_Vimeo_Response;
use Polen\Tributes\Tributes_Invites_Model;

$vimeo_api = Polen_Vimeo_Factory::create_vimeo_colab_instance_with_redux();
$invites = Tributes_Invites_Model::get_vimeo_not_processed_yet();

echo "Total Colabs: " . count( $invites ) . "\n";
echo "START\n";
foreach ( $invites as $invite ) {
    try {
        $response = new Polen_Vimeo_Response( $vimeo_api->request( $invite->vimeo_id ) );
        if( $response->is_error() ) {
            throw new VimeoRequestException( $response->get_error() );
        }
        
        if( $response->video_processing_is_complete() ) {
            $data_update = array(
                'ID' => $invite->ID,
                'vimeo_thumbnail' => $response->get_image_url_640(),
                'vimeo_process_complete' => '1',
                'vimeo_link' => $response->get_vimeo_link(),
                'duration' => $response->get_duration(),
                'vimeo_url_file_play' => $response->get_play_link(),
                'video_sent_date' => date('Y-m-d H:i:s')
            );
            Tributes_Invites_Model::update( $data_update );
            echo "Invite: {$invite->vimeo_id} \n";
        }
        
    } catch ( ExceptionInterface $e ) {
        // if( "The requested video couldn\'t be found" == $e->getMessage() ) {
            $update_remove = array(
                'ID' => $invite->ID,
                'vimeo_error' => '1'
            );
            Tributes_Invites_Model::update( $update_remove );
        // }
        echo "Triste dia: {$invite->vimeo_id} -> {$e->getMessage()}\n";
    }
}

echo( "END \n" );
