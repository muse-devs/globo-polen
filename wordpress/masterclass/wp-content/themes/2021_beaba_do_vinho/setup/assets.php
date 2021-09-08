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
    // Arquivos front-end
    wp_register_script( 'owl-carousel', TEMPLATE_URI . '/assets/js/vendor/owl.carousel.min.js', array(), "1.0.0", true );
    wp_enqueue_script('_theme-js-front', TEMPLATE_URI . '/assets/js/' . $min . 'global.js', array("jquery", "owl-carousel"), filemtime(TEMPLATE_DIR . '/assets/js/global.js'), false);
    wp_enqueue_style('_theme-style-front', TEMPLATE_URI . '/assets/css/style.css', array(), filemtime(TEMPLATE_DIR . '/assets/css/style.css'));

    if (is_checkout()) {
        wp_enqueue_style('_theme-style', TEMPLATE_URI . '/assets/css/default-woocommerce.css');
        wp_enqueue_script('_theme-js-main', TEMPLATE_URI. '/assets/js/develop.js', array(), null, true);
    }

}
add_action('wp_enqueue_scripts', '_theme_assets');
