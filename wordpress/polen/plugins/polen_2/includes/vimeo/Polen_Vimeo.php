<?php
namespace Polen\Includes\Vimeo;

use Polen\Includes\Cart\Polen_Cart_Item_Factory;
use Polen\Includes\Polen_Video_Info;
use Polen\Includes\Talent\Polen_Talent_Controller;
use Vimeo\Exceptions\VimeoRequestException;

ABSPATH ?? die;

class Polen_Vimeo
{
        /**
     * Handler para o AJAX onde é executado quando o Talento, seleciona um video e
     * envia, antes do envio é criado no Vimeo um Slot para receber o Video com o 
     * mesmo tamanho em bytes
     * 
     * @param int
     * @param int
     * @param string
     * @throws VimeoRequestException
     * @return Polen_Vimeo_Response
     */
    public function make_video_slot_vimeo($order_id, $file_size, $name_to_video)
    {
        $lib = Polen_Vimeo_Factory::create_vimeo_instance_with_redux();

        $order_id       = filter_var($order_id, FILTER_SANITIZE_NUMBER_INT);
        $file_size      = filter_var($file_size, FILTER_SANITIZE_NUMBER_INT);
        $name_to_video  = filter_var($name_to_video);
        $args           = Polen_Vimeo_Vimeo_Options::get_option_insert_video($file_size, $name_to_video);
        $vimeo_response = $lib->request('/me/videos', $args, 'POST');
        
        $response = new Polen_Vimeo_Response($vimeo_response);

        if($response->is_error()) {
            throw new VimeoRequestException($response->get_developer_message(), 500);
        }
        
        $order = wc_get_order($order_id);
        if(empty($order)) {
            throw new VimeoRequestException('Pedido não encontrado', 404);
        }
        $cart_item = Polen_Cart_Item_Factory::polen_cart_item_from_order($order);
        
        $video_info = Polen_Video_Info::mount_video_info_with_order_cart_item_vimeo_response(
            $order,
            $cart_item,
            $response,
            Polen_Video_Info::VIDEO_LOGO_STATUS_WAITING
        );
        $video_info->insert();
        
        //recalcula o tempo de resposta do talento
        $talent_controller = new Polen_Talent_Controller();
        $talent_controller->average_video_response(get_current_user_id());

        return $response;
    }
}
