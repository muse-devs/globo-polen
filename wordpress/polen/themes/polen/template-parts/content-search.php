<?php 
$product = wc_get_product( get_the_ID() );

polen_front_get_card(array(
    "talent_url" => $product->get_permalink(),
    "image" => wp_get_attachment_url($product->get_image_id()),
    "name" => $product->get_title(),
    "price" => $product->get_regular_price(),
    "category_url" => "",
    "category" => ""
), "small");
