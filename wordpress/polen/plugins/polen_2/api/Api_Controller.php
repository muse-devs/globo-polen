<?php
namespace Polen\Api;

use Exception;
use Polen\Includes\API\Polen_Api_Video_Info;
use Polen\Includes\Cart\Polen_Cart_Item_Factory;
use Polen\Includes\Polen_Talent;
use Polen\Includes\Polen_Video_Info;
use WC_Order;
use WP_REST_Response;
use WP_REST_Request;

class Api_Controller{

    private $checkout;

    public function __construct()
    {
        $this->checkout = new Api_Checkout();
    }

    /**
     * Endpoint talent
     *
     * Retorar todos os talentos
     * @param WP_REST_Request $request
     */
    public function talents( $request ): WP_REST_Response
    {
        try{
            $api_product = new Api_Product();
            $params = $request->get_params();

            $slug = '';
            if (isset($params['campaign']) || isset($params['campaign_category'])) {
                $slug = $params['campaign_category'] ?? $params['campaign'];
            }

            $products = $api_product->polen_get_products_by_campagins($params, $slug);

            $items = array();
            foreach ($products as $product) {
                $image_object = $this->get_object_image($product->get_id());
                $items[] = array(
                    'id' => $product->get_id(),
                    'name' => $product->get_name(),
                    'slug' => $product->get_slug(),
                    'image' => $image_object,
                    'categories' => wp_get_object_terms($product->get_id() , 'product_cat'),
                    'stock' => $product->get_stock_quantity() ?? 0,
                    'price' => $product->get_price(),
                    'regular_price' => $product->get_regular_price(),
                    'sale_price' => $product->get_sale_price(),
                    'createdAt' => get_the_date('Y-m-d H:i:s', $product->get_id()),
                );
            }

            $data = array(
                'items' => $items,
                'total' => $api_product->get_products_count($params, $slug),
                'current_page' => $request->get_param('paged') ?? 1,
                'per_page' => count($items),
            );

            return api_response($data, 200);

        } catch (\Exception $e) {
            return api_response(
                array('message' => $e->getMessage()),
                $e->getCode()
            );
        }
    }

    /**
     * Endpoint talent
     *
     * Retorar todos os talentos
     * @param $request
     */
    public function talent($request): WP_REST_Response
    {
        try{
            $talent_slug = $request->get_param('slug');
            if (empty($talent_slug)) {
                throw new Exception('slug é obrigatório', 422);
            }

            $campaign_slug = $request->get_param('campaign');
            if (empty($campaign_slug)) {
                throw new Exception('Campanha é obrigatório', 422);
            }

            $product_obj = get_page_by_path($talent_slug, OBJECT, 'product');

            if (empty($product_obj->ID)) {
                throw new Exception('Talento não encontrado', 404);
            }

            $tax_product = get_the_terms($product_obj->ID, 'campaigns');

            if (!isset($tax_product[0]) || $tax_product[0]->taxonomy !== 'campaigns') {
                throw new Exception('Talento não encontrado', 404);
            }

            $product = wc_get_product($product_obj->ID);
            $image_object = $this->get_object_image($product_obj->ID);

            $items = array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'slug' => $product->get_slug(),
                'b2b' => get_post_meta($product_obj->ID, 'polen_is_b2b', true),
                'image' => $image_object,
                'categories' => wp_get_object_terms($product->get_id() , 'campaigns'),
                'stock' => $product->get_stock_quantity() ?? 0,
                'price' => $product->get_price(),
                'regular_price' => $product->get_regular_price(),
                'sale_price' => $product->get_sale_price(),
                'createdAt' => get_the_date('Y-m-d H:i:s', $product->get_id()),
            );

            return api_response($items, 200);

        } catch (\Exception $e) {
            return api_response(
                array('message' => $e->getMessage()),
                $e->getCode()
            );
        }
    }

    /**
     * Validar requisição para carrinho
     *
     * @param $request
     */
    public function cart($request)
    {
        try{
            $product_id = $request->get_param('product_id');
            if (empty($product_id)) {
                throw new Exception('Parametro product_id é obrigatório', 422);
            }

            $product = wc_get_product($product_id);
            if (empty($product)) {
                throw new Exception('Produto não encontrado', 404);
            }

            if ($product->get_stock_status() !== 'instock') {
                throw new Exception('Produto sem estoque', 422);
            }

        } catch (\Exception $e) {
            wp_send_json_error($e->getMessage(), 422);
            wp_die();
        }

    }

    /**
     * Endpoint que receberá o request e criará uma order através da class Api_Checkout
     *
     * @param $request
     */
    public function payment($request)
    {
        $this->checkout->create_order($request);
    }

    /**
     * Rota para resetar a senha do usuario
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    function forgot_password(WP_REST_Request $request): WP_REST_Response
    {
        try {
            $email = $request->get_param('email') ?? null;

            if (null === $email) {
                throw new Exception('E-mail é obrigatório.', 422);
            }

            $user_wp = get_user_by('email', $email);
            if (!$user_wp) {
                throw new Exception('Usuário não encontrado', 503);
            }

            $new_password = wp_generate_password();

            $to = $email;
            $subject = 'Recuperação de Senha';
            $headers = array('Content-Type: text/html; charset=UTF-8');

            $args = array(
                'ID' => $user_wp->ID,
                'user_pass' => $new_password,
            );
            wp_update_user($args);

            $body = "<p>Segue abaixo sua nova senha para acesso ao Polen</p>";
            $body .= "<p><strong>Nova Senha: {$new_password}</strong></p>";

            wp_mail($to, $subject, $body, $headers);

            return api_response(null, 204);

        } catch (\Exception $e) {
            return api_response(
                array('message' => $e->getMessage()),
                $e->getCode()
            );
        }
    }

    /**
     * Rotornar status e informações basicas de um pedido
     *
     * @param $order_id
     * @return WP_REST_Response
     */
    public function get_order_status($request): WP_REST_Response
    {
        try {
            $order_id = $request->get_param('order_id');
            $order = new WC_Order($order_id);

            $data = array(
                'id' => $order_id,
                'status_order' => $order->get_status(),
                'customer_email' => $order->get_billing_email(),
            );

            return api_response($data);
        } catch (\Exception $e) {
            return api_response(
                array('message' => $e->getMessage()),
                $e->getCode()
            );
        }
    }

    private function error($e)
    {
        wp_send_json_error($e, 422);
    }

    /**
     * Retornar meta dados da imagem
     *
     * @param int $talent_id
     * @return array
     */
    private function get_object_image(int $talent_id): array
    {
        $attachment = get_post(get_post_thumbnail_id($talent_id));
        return array(
            'id' => $attachment->ID,
            'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
            'caption' => $attachment->post_excerpt,
            'description' => $attachment->post_content,
            'src' => get_the_post_thumbnail_url($talent_id),
            'title' => $attachment->post_title,
        );
    }

    /**
     * 
     */
    public function get_product_videos( $request )
    {
        $product_id = $request[ 'id' ];
        
        $Polen_Talent = new Polen_Talent();
        $talent = $Polen_Talent->get_talent_from_product( $product_id );
        $videos = Polen_Video_Info::select_by_talent_id( $talent->ID, 5 );

        $data = [];
        foreach( $videos as $video ) {
            $order = wc_get_order( $video->order_id );
            $cart_item = Polen_Cart_Item_Factory::polen_cart_item_from_order( $order );
            $data[] = [
                'video_info_id'=> $video->ID,
                'talent_id'    => $video->talent_id,
                'video_id'     => $video->vimeo_id,
                'talent_thumb' => polen_get_avatar_src($video->talent_id, 'polen-square-crop-lg'),
                'cover'        =>  $video->vimeo_thumbnail,
                'video_url'    => $video->vimeo_file_play,
                'initials'     => polen_get_initials_name( $cart_item->get_name_to_video() ),
            ];
        }
        return api_response( $data, 200 );
    }
}