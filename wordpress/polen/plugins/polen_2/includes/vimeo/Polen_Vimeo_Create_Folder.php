<?php
namespace Polen\Includes\Vimeo;

class Polen_Vimeo_Create_Folder
{
    static function create_folder( $vimeo_api, $folder_name );
    {
        $vimeo_api = Polen_Vimeo_Factory::create_vimeo_colab_instance_with_redux();
        $args = [
            'name' => $folder_name,
        ];
        $result = Polen_Vimeo_Response( $vimeo_api->request( "/me/projects", $args, 'POST' ) );
        return $result;
    }
}