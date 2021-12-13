<?php
require_once 'vendor/autoload.php';

use Vimeo\Exceptions\VimeoUploadException;
use Vimeo\Exceptions\VimeoRequestException;
use Vimeo\Vimeo;

$client_id = '1306bc73699bfe32ef09370f448c922d62f080d3';
$client_secret = 'KN1bXutJtv8rYmlxU6Pbo4AhhCl8yhDKd20LHQqWDi0jXxcXGIVsmVHTxkcIVJzsDcrzZ0WNl'
               . 'y9sP+CGU9gpLZBneKr0VfdpEFL/MSVS7jae0jLAoi/ev/P85gPV4oUS';
$token = 'ecdf5727a7b96ec6179c5090db5851ba';

$lib = new Vimeo( $client_id, $client_secret, $token );

$file_size = filter_input( INPUT_POST, 'file_size', FILTER_SANITIZE_NUMBER_INT);

$args = [
    'upload' => [
        'approach' => 'post',
        'size' => $file_size,
        'redirect_url' => 'http://polen.globo/talent/order/ID'],
        'privacy' => [ "view" => "disable" ],
        'name' => 'Aniversário de Fulano de tal'
    ];
try {
    $response = $lib->request('/me/videos', $args, 'POST');
    echo json_encode( $response );
} catch ( VimeoUploadException $e ) {
    http_response_code( $e->getCode() );
    echo $e->getMessage();
} catch ( VimeoRequestException $e ) {
    http_response_code( $e->getCode() );
    echo $e->getMessage();
}
