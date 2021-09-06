<?php

/**
 * Registra os arquivos de CSS e JS do tema
 */
function _theme_assets()
{
    $uri = get_template_directory_uri();
    wp_enqueue_style('_theme-style', $uri . '/assets/css/default-woocommerce.css');
    wp_enqueue_script('_theme-js-main', $uri . '/assets/js/develop.js', array(), null, true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', '_theme_assets');
