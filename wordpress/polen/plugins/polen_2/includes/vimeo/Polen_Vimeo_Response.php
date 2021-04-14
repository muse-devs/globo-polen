<?php

namespace Polen\Includes\Vimeo;

/**
 * Polen_Vimeo_Response é uma classe para interpretar o response do Vimeo
 */
class Polen_Vimeo_Response
{
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
}
