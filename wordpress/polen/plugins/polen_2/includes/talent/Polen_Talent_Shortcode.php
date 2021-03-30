<?php

namespace Polen\Includes\Talent;

use Polen\Includes\Polen;
use \Polen\Publics\Polen_Public;

class Polen_Talent_Shortcode
{
    public function __construct()
    {
        add_shortcode( 'polen_sjhdfgsjhfgkasdhfgasjhdfgakjdfhgkzsjhdfg', [ $this, 'render' ] );
    }
    
    private function get_path_public_file()
    {
        $file = 'polen_talent_send_video_file.php';
        $polen = new Polen();
        $polen_public = new Polen_Public( $polen->get_plugin_name(), $polen->get_version() );
        return $polen_public->get_path_public_patials() . $file;
    }
    
    public function render()
    {
        ob_start();
        include $this->get_path_public_file();
        $data = ob_get_contents();
        ob_end_clean();
        
        return $data;
    }
}
