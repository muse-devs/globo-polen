<?php

namespace Polen\Api;

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

            $term_id = '';
            if (isset($params['campaign']) || isset($params['campaign_category'])) {
                $term_id = $params['campaign_category'] ?? $params['campaign'];
            }

            $tax = get_term($term_id, 'campaigns');
            if ($tax == null) {
                throw new Exception('Oops, Id da campanha solicitada não foi encontrada na nossa base de dados!', 422);
            }

            $query = $api_product->polen_get_products_by_campagins($params, $term_id);

            $items = array();
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $attachment = get_post(get_post_thumbnail_id(get_the_ID()));
                    $product = wc_get_product(get_the_ID());
                    $image_object = array(
                        'id' => $attachment->ID,
                        'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true ),
                        'caption' => $attachment->post_excerpt,
                        'description' => $attachment->post_content,
                        'src' => get_the_post_thumbnail_url(get_the_ID()),
                        'title' => $attachment->post_title,
                    );

                    $items[] = array(
                        'id' => $product->get_id(),
                        'name' => $product->get_name(),
                        'slug' => $product->get_slug(),
                        'image' => $image_object,
                        'categories' => wp_get_object_terms(get_the_ID() , 'product_cat'),
                        'stock' => $product->get_stock_quantity(),
                        'price' => $product->get_price(),
                        'regular_price' => $product->get_regular_price(),
                        'sale_price' => $product->get_sale_price(),
                        'createdAt' => get_the_date('Y-m-d H:i:s', get_the_ID()),
                    );
                }
            }

            $data = array(
                'items' => $items,
                'total' => (int) $query->found_posts,
                'current_page' => $request->get_param('paged') ?? 1,
                'per_page' => count($items),
            );

            wp_send_json_success($data, 200);

        } catch (\Exception $e) {
            wp_send_json_error($e->getMessage(), 422);
            wp_die();
        }
    }

    private function error($e)
    {
        wp_send_json_error($e, 422);
    }

}