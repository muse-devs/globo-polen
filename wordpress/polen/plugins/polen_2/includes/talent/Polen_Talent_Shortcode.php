<?php

namespace Polen\Includes\Talent;

use Polen\Includes\Polen;
use Polen\Publics\Polen_Public;
use Polen\Includes\Cart\Polen_Cart_Item;
use \Polen\Includes\Cart\Polen_Cart_Item_Factory;

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
        $this->populate_script();

        ob_start();
        include $this->get_path_public_file();
        $data = ob_get_contents();
        ob_end_clean();

        return $data;
    }
    
    private function populate_script()
    {
        $order_id = filter_input( INPUT_GET, 'order_id', FILTER_SANITIZE_NUMBER_INT );
        if( empty( $order_id ) ) {
            wp_die();
        }
        
        $order = wc_get_order( $order_id );
        $item = Polen_Cart_Item_Factory::polen_cart_item_from_order( $order );
        
        $ajax_settings = array(
//            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'my-action_' ),
            'action' => 'create_video_slot_vimeo',
            'email_to_video' => $item->get_email_to_video(),
            'instructions_to_video' => $item->get_instructions_to_video(),
            'name_to_video' => $item->get_name_to_video(),
            'offered_by' => $item->get_offered_by(),
            'video_category' => $item->get_video_category(),
            'video_to' => $item->get_video_to(),
        );
        
        $polen_public = new Polen_Public('', '');
        $plugin_url = $polen_public->get_url_public_js();
        wp_enqueue_script( 'polen-upload-video', "{$plugin_url}upload-video.js" );
        
        wp_localize_script(
            'polen-upload-video',
            'upload_video',
            $ajax_settings
        );
    }
}
