<?php

include_once dirname( __FILE__ ) . '/init.php';

use Vimeo\Exceptions\{ExceptionInterface, VimeoRequestException};
use Polen\Includes\Polen_Video_Info;
use Polen\Includes\Vimeo\Polen_Vimeo_Factory;
use Polen\Includes\Vimeo\Polen_Vimeo_Response;

$videos = Polen_Video_Info::get_all_vimeo_file_play_empty();
$vimeo_api = Polen_Vimeo_Factory::create_vimeo_instance_with_redux();

echo "Total Videos: " . count( $videos ) . "\n";
echo "START\n";
foreach ( $videos as $video ) {
    try {
        $response = new Polen_Vimeo_Response( $vimeo_api->request( $video->vimeo_id ) );
        if( $response->is_error() ) {
            throw new VimeoRequestException( $response->get_error() );
        }
        
        if( $response->video_processing_is_complete() ) {
            $video->vimeo_file_play = $response->get_play_link();
            $video->update();
            echo "Achei: {$video->vimeo_id} \n";
        }
        
    } catch ( ExceptionInterface $e ) {
        echo "Triste dia: {$video->vimeo_id} -> {$e->getMessage()}\n";
    }
}

echo( "END \n" );
