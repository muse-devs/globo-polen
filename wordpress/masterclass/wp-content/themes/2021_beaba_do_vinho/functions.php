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

add_action( 'phpmailer_init', 'masterclass_load_settings');

function masterclass_load_settings() {
    global $phpmailer;
    $phpmailer->isSMTP();
    $phpmailer->SMTPAuth   = true;
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->Host       = 'email-smtp.us-east-2.amazonaws.com';
    $phpmailer->Port       = '587';
    $phpmailer->Username   = 'AKIASWGKUEIQNIMMOAID';
    $phpmailer->Password   = 'BI1e3yLlNCIzJVvNOMt7LTXpUDirxVuzlU39UlWvNLuv';
    $phpmailer->From       = 'no-reply@polen.me';
    $phpmailer->FromName   = 'Polen';
}

add_filter('rest_authentication_errors', 'required_rest_authentication');
function required_rest_authentication($result) 
{
     if (!empty($result)) {
         return $result;
     }

     if (!is_user_logged_in()) {
         return new WP_Error('rest_not_logged_in', 'Dados Privados.', array('status' => 401));
     }
     
     return $result;
}

