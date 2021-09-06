<?php
$files = array(
    'setup/assets.php',
    'setup/core.php',
    'setup/helper.php',
    'setup/post-types.php',
    'setup/functions_front/render.php',
);

foreach ($files as $file) {
    if (!$filePath = locate_template($file)) {
        error_log(sprintf(__('Erro ao carregar arquivo : %s'), $file), E_USER_ERROR);
    }
    include_once $filePath;
}

/**
 * Adicionar suporte para que o tema possa modificar os arquivos
 * de configuração padrão do woocommerce
 */
function _theme_add_woocommerce_support()
{
    add_theme_support('woocommerce');
}

add_action('after_setup_theme', '_theme_add_woocommerce_support');