<?php

include_once dirname( __FILE__ ) . '/init.php';

use Vimeo\Vimeo;
use Vimeo\Exceptions\{ExceptionInterface, VimeoRequestException};
use Polen\Includes\Polen_Video_Info;
use Polen\Includes\Vimeo\Polen_Vimeo_Response;

$client_id = $Polen_Plugin_Settings['polen_vimeo_client_id'];
$client_secret = $Polen_Plugin_Settings['polen_vimeo_client_secret'];
$access_token = $Polen_Plugin_Settings['polen_vimeo_access_token'];

$videos = Polen_Video_Info::select_all_videos_incompleted();
$vimeo_api = new Vimeo($client_id, $client_secret, $access_token);

echo "Total Videos: " . count($videos) . "\n";
echo "START\n";
foreach ( $videos as $video ) {
    try {
        $response = new Polen_Vimeo_Response( $vimeo_api->request( $video->vimeo_id ) );
        if( $response->is_error() ) {
            throw new VimeoRequestException( $response->get_error() );
        }
        
        if( $response->video_processing_is_complete() ) {
            Polen\Includes\Debug::def($response->response);
            $video->vimeo_process_complete = 1;
            //TODO colocar esse '300x435' em um lugar, tirar o hardcode
            $video->vimeo_thumbnail = $response->get_image_url_custom_size( '300x435' );
            $video->duration = $response->get_duration();
            $video->updated_at = date('Y-m-d H:i:s');
            $video->update();
            echo "Achei: {$video->vimeo_id} \n";
        }
        
    } catch ( ExceptionInterface $e ) {
        echo "Triste dia: {$video->vimeo_id} -> {$e->getMessage()}\n";
    }
}

echo( "END \n" );
