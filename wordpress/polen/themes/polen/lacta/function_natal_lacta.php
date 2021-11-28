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
        $product = wc_get_product($talents_campaign->ID);
        $ids = $product->get_category_ids();
        $category = _polen_get_first_category_object($ids);

        $talents[] = [
            'ID' => $product->get_id(),
            'name' => $product->get_title(),
            'image' => get_the_post_thumbnail_url($talents_campaign->ID),
            'talent_url' => $product->get_permalink(),
            'price' => $product->get_price(),
            'in_stock' => $product->is_in_stock(),
            'slug' => $product->get_slug(),
            'category' => $category->name,
        ];
    }

    return $talents;
}
