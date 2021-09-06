<?php
define('TEMPLATE_URI', get_template_directory_uri());
define('TEMPLATE_DIR', get_template_directory());
define('DEVELOPER', defined('ENV_DEV') && ENV_DEV);

function get_assets_folder() {
	$min = "min/";
	if (DEVELOPER) {
		$min = "";
	}
	return $min;
}

/**
 * Registra os arquivos de CSS e JS do tema
 */
function _theme_assets()
{
    $min = get_assets_folder();
    wp_enqueue_style('_theme-style', TEMPLATE_URI . '/assets/css/default-woocommerce.css');
    wp_enqueue_script('_theme-js-main', TEMPLATE_URI. '/assets/js/develop.js', array(), null, true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Arquivos front-end
    if (!is_checkout()) {
        wp_enqueue_style('_theme-style-front', TEMPLATE_URI . '/assets/css/style.css', array(), filemtime(TEMPLATE_DIR . '/assets/css/style.css'));
        wp_enqueue_script('_theme-js-front', TEMPLATE_URI . '/assets/js/' . $min . 'global.js', array("jquery"), filemtime(TEMPLATE_DIR . '/assets/js/global.js'), false);
    }
}
add_action('wp_enqueue_scripts', '_theme_assets');