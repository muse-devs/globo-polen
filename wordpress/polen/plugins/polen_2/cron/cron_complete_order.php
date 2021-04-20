<?php

//Se a execução não for pelo CLI gera Exception
if( strpos(php_sapi_name(), 'cli' ) === false ) {
    echo 'Silence is Golden';
    die;
}

include_once dirname( __FILE__ ) . '/init.php';

use Vimeo\Vimeo;
use Vimeo\Exceptions\{ExceptionInterface, VimeoRequestException};
use Polen\Includes\Polen_Video_Info;
use Polen\Includes\Vimeo\Polen_Vimeo_Response;

$client_id = '1306bc73699bfe32ef09370f448c922d62f080d3';
$client_secret = 'KN1bXutJtv8rYmlxU6Pbo4AhhCl8yhDKd20LHQqWDi0jXxcXGIVsmVHTxkcIVJzsDcrzZ0WNl'
               . 'y9sP+CGU9gpLZBneKr0VfdpEFL/MSVS7jae0jLAoi/ev/P85gPV4oUS';
$access_token = 'c341235becba51280401b3fd1567f0c7';

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
            $video->vimeo_thumbnail = $response->get_image_url_640();
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
