<?php

namespace Polen\Api;

use WP_Query;

class Api_Product
{
    /**
     * Retornar talentos de acordo com a campanha
     *
     * @param array $params
     * @param int $campaingn
     * @return WP_Query
     */
    public function polen_get_products_by_campagins(array $params, $term_id = ''): WP_Query
    {
        $per_page = $params['per_page'] ?? get_option('posts_per_page');
        $paged = $params['paged'] ?? 1;

        $args = array(
            'post_type' => 'product',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => $per_page,
            'paged' => $paged,
        );

        if (!empty($term_id)) {
            $args['tax_query'][] = array(
                'taxonomy' => 'campaigns',
                'field' => 'term_id',
                'terms' => $term_id,
            );
        }

        if (isset($params['s'])) {
            $args['s'] = $params['s'];
        }

        return new WP_Query($args);
    }
}