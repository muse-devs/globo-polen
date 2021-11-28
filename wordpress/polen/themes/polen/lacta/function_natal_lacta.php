<?php
/**
 * Retornar talentos de acordo com a campanha
 *
 * @param string $campaingn
 * @return array
 */
function polen_get_talents_by_campaingn(string $campaingn): array
{
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'tax_query' => array(
            array(
               'taxonomy' => 'campaigns',
                'field' => 'slug',
                'terms' => $campaingn,
            )
        ),
    );

    $query = new WP_Query($args);
    $query->get_posts();
    $talents = [];
    foreach ($query->get_posts() as $talents_campaign) {
        $terms = get_the_terms($talents_campaign->ID, 'product_cat');
        $product = wc_get_product($talents_campaign->ID);
        $talents[] = [
            'id' => $product->get_id(),
            'name' => $product->get_name(),
            'slug' => $product->get_slug(),
            'image' => get_the_post_thumbnail_url($talents_campaign->ID),
            'stock' => $product->get_stock_quantity() ?? 0,
            'category' => $terms,
        ];
    }

    return $talents;
}
