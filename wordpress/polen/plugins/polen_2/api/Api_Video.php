<?php

namespace Polen\Api;

use Polen\Includes\API\Polen_Api_Video_Info;
use Polen\Includes\Polen_Video_Info;
use WP_REST_Response;

class Api_Video
{
    /**
     * 
     * @param \WP_REST_Request
     */
    public function get_item_by_hash( $request )
    {
        $hash = (int) $request['id'];
        $video_info = Polen_Video_Info::get_by_hash( $hash );

        if( empty( $video_info ) ) {
            return new WP_REST_Response( $video_info, 404 );
        }
        $polen_api_video_info = new Api_Controller();
        $data = $polen_api_video_info->create_item_video( $video_info );

        return rest_ensure_response( $data );
    }
}