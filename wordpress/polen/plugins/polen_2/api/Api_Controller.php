<?php

namespace Polen\Api;

use Exception;
use WP_REST_Response;

class Api_Controller{

    /**
     * Term_id Campanha
     */
    private int $campaign;

    /**
     * Notificará o erro, caso ocorra na classe
     */
    private bool $error = false;

    /**
     * Mensagem de erro
     */
    private string $error_message;

    /**
     * Endpoint talent
     *
     * Retorar todos os talentos
     * @param $request
     */
    public function talents($request): WP_REST_Response
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

            wp_send_json_success($data, 200);

        } catch (\Exception $e) {
            wp_send_json_error($e->getMessage(), 422);
            wp_die();
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

            $product_obj = get_page_by_path($talent_slug, OBJECT, 'product');

            if ($product_obj->ID == null) {
                throw new Exception('Talento não encontrado', 422);
            }

            $product = wc_get_product($product_obj->ID);
            $image_object = $this->get_object_image($product_obj->ID);

            $items[] = array(
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

            wp_send_json_success($items, 200);

        } catch (\Exception $e) {
            wp_send_json_error($e->getMessage(), 422);
            wp_die();
        }
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

    private function error($e)
    {
        wp_send_json_error($e, 422);
    }

}