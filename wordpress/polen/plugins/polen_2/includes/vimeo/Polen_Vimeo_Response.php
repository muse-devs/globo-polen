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
        if( isset( $this->response['body']['error_code'] ) && is_int( $this->response['body']['error_code'] ) && $this->response['body']['error_code'] > 0)
        {
            $return = true;
        }
        return $return;
    }
    
    
    public function get_developer_message()
    {
        return $this->response['body']['developer_message'];
    }
    
    
    public function get_status()
    {
        return $this->response['body']['status'];
    }
    
    
    public function is_video_complete()
    {
        if( $this->response['body']['status'] == self::STATUS_AVAILABLE )
        {
            return true;
        }
        return false;
    }
}
