<?php
namespace Polen\Includes\Talent;

class Polen_Talent_Part_Theme
{
    public function __construct()
    {
        add_action( 'polen_before_upload_video', [$this, 'add_script'] );
    }
    
    public function enqueue_script()
    {
        $min = get_assets_folder();
        wp_enqueue_script( 'talent-scripts', TEMPLATE_URI . '/assets/js/' . $min . 'upload-video.js', array("jquery"), _S_VERSION, true );
    }
}
