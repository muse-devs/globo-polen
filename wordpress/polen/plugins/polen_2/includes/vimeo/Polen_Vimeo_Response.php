<?php

namespace Polen\Includes\Vimeo;

/**
 * Polen_Vimeo_Response é uma classe para interpretar o response do Vimeo
 */
class Polen_Vimeo_Response
{
    const STATUS_UPLOADING = 'uploading';
    const STATUS_TRANSCODING = 'transcoding';
    const STATUS_AVAILABLE = 'available';
    
    const URL_IMAGE_640_VIMEO_DEFULT = 'https://i.vimeocdn.com/video/default_640x360?r=pad';

    public $response;
    
    public function __construct( $response )
    {
        $this->response = $response;
    }
    
    
    public function get_vimeo_id()
    {
        return $this->response['body']['uri'];
    }
    
    
    public function get_vimeo_link()
    {
        return $this->response['body']['link'];
    }
    
    
    public function is_error()
    {
        $return = false;
        if( isset( $this->response['body']['error'] ) && !empty( $this->response['body']['error'] ) ) {
            $return = true;
        }
        return $return;
    }
    
    public function get_error()
    {
        return !empty( $this->get_developer_message() )
            ? $this->get_developer_message()
            : $this->response['body']['error'];
    }
    
    
    public function get_developer_message()
    {
        return isset( $this->response['body']['developer_message'] )
            ? $this->response['body']['developer_message']
            : null;
    }
    
    
    public function get_status()
    {
        return $this->response['body']['status'];
    }
    
    
    public function video_processing_is_complete()
    {
        if( 
                $this->response['body']['status'] == self::STATUS_AVAILABLE &&
                $this->response['body']['pictures']['sizes'][3]['link'] != self::URL_IMAGE_640_VIMEO_DEFULT
            ) {
            return true;
        }
        return false;
    }
    
    public function get_image_url_640()
    {
        return $this->response['body']['pictures']['sizes'][3]['link'];
    }
    
    
    public function get_duration()
    {
        return $this->response['body']['duration'];
    }
}
