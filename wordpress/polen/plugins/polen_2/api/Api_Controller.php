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

    public function __construct()
    {
        $this->campaign = $this->campaign_config_redux();
    }

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

            if ($this->error !== false) {
               $this->error($this->error_message);
            }

            $query = $api_product->polen_get_products_by_campagins($request->get_params(), $this->campaign);

            $items = array();
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $attachment = get_post(get_post_thumbnail_id(get_the_ID()));
                    $image_object = array(
                        'id' => $attachment->ID,
                        'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true ),
                        'caption' => $attachment->post_excerpt,
                        'description' => $attachment->post_content,
                        'src' => get_the_post_thumbnail_url(get_the_ID()),
                        'title' => $attachment->post_title,
                    );

                    $items[] = array(
                        'id' => get_the_ID(),
                        'name' => get_the_title(get_the_ID()),
                        'slug' => basename(get_permalink()),
                        'image' => $image_object,
                        'categories' => wp_get_object_terms(get_the_ID() , 'product_cat'),
                        'content' => get_the_content(null, null, get_the_ID()),
                        'createdAt' => get_the_date('Y-m-d H:i:s', get_the_ID()),
                    );
                }
            }

            $data = array(
                'items' => $items,
                'total' => (int) $query->found_posts,
                'currentPage' => $request->get_param('paged'),
                'perPage' => count($items),
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

    /**
     * Retornar term_id da campanha do galo pelo o redux
     *
     * @return ?int
     */
    private function campaign_config_redux(): ?int
    {
        global $Polen_Plugin_Settings;

        if (!isset($Polen_Plugin_Settings['campaign_categories'])) {
            $this->error = true;
            $this->error_message = 'Oops, campanha não configurada!';

            return 0;
        }

        return implode($Polen_Plugin_Settings['campaign_categories']);
    }

}