<?php

/**
 * Ativa alguns features do wordpress
 */
function _theme_setup()
{
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    register_nav_menus(array(
        'main' => esc_html__('Principal'),
        'footer' => esc_html__('Rodapé'),
    ));

}
add_action('after_setup_theme', '_theme_setup');

/**
 * Gera URL de compartilhamento nas redes sociais
 *
 * @param string $socialNetwork
 * @param int|null $postId
 * @return string
 */
function _theme_social_share(string $socialNetwork, int $postId = null)
{
    if (null === $postId) {
        global $post;
        $postId = $post->ID;
    }

    $baseUrlFacebook = 'https://www.facebook.com/sharer.php?u=';
    $socialNetworks = array(
        'facebook' => $baseUrlFacebook . get_permalink($postId) . '&t=' . urlencode(get_the_title($postId)),
        'twitter' => 'https://twitter.com/intent/tweet?text=' . urlencode(get_permalink($postId)),
        'whatsapp' => 'https://wa.me/?text=' . get_permalink($postId),
        'telegram' => 'https://t.me/share/url?url=' . get_permalink($postId),
        'linkedin' => 'https://www.linkedin.com/cws/share?url=' . get_permalink($postId),
    );

    if (!isset($socialNetworks[$socialNetwork])) {
        return '';
    }

    return $socialNetworks[$socialNetwork];
}

/**
 * Gera URL para Chat do WhatsApp
 *
 * @param string $phoneNumber Número do telefone com DDD
 * @param string $text Mensagem que será enviada no chat
 * @return string
 */
function _theme_get_whatsapp_chat(string $phoneNumber, string $text)
{
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
    return "https://api.whatsapp.com/send?phone={$phoneNumber}&text={$text}";
}

/**
 * Remover tabs padrões do woocommerce que não serão utilizadas
 * @param $tabs
 * @return mixed
 */
function woocommerce_remove_default_tabs($tabs)
{
    if ( isset( $tabs['reviews'] ) ) {
        unset( $tabs['reviews'] );
    }

    if ( isset( $tabs['additional_information'] ) ) {
        unset( $tabs['additional_information'] );

    }
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'woocommerce_remove_default_tabs' );

/**
 * @hook
 * Remover campo de nota do checkout do woocommerce
 */
add_filter( 'woocommerce_enable_order_notes_field', '__return_false', 9999 );

/**
 * @hook
 * Remover preview de preço no checkout do woocommerce
 */

function remove_checkout_totals()
{
    remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
}
add_action( 'woocommerce_checkout_order_review', 'remove_checkout_totals', 1 );


/**
 * Retornar ID do produto atual na tela de checkout
 *
 * @return string
 */
function get_product_checkout(): string
{
    $cart = WC()->cart->get_cart();
    if (empty($cart) && $cart == null) {
        return '';
    }

    $cart_items_ids = '';
    foreach ($cart as $item_value) {
        $cart_items_ids = $item_value['product_id'];
    }

    return $cart_items_ids;
}

/**
 * Redirecionar ao checkout após um adição no carrinho
 *
 * @return string
 */
function redirect_to_checkout(): string
{
    return wc_get_checkout_url();
}
add_filter ('woocommerce_add_to_cart_redirect', 'redirect_to_checkout');

/**
 * Remover detalhes do pedido da página thankyou
 */
remove_action('woocommerce_thankyou', 'woocommerce_order_details_table', 10);

/**
 * Verificar se um produto ja está no carrinho
 * @param int $productId
 * @return bool
 */
function _theme_find_product_in_cart(int $productId): bool
{
    $productCartId = WC()->cart->generate_cart_id($productId);

    return WC()->cart->find_product_in_cart($productCartId);
}


/**
 * Retornar informações do produto masterclass
 *
 * @return array
 */
function get_product_masterclass($masterclass_id = 69): array
{
    $masterclass_product = wc_get_product($masterclass_id);

    if (is_wp_error($masterclass_product) || empty($masterclass_product)) {
        return [
            'error' => 'Produto masterclass não encontrado!',
        ];
    }

    $url_checkout = _theme_find_product_in_cart($masterclass_id) ? wc_get_checkout_url() : "?add-to-cart={$masterclass_id}";

    return [
        'name' => $masterclass_product->get_name(),
        'price_regular' => wc_price($masterclass_product->get_regular_price()),
        'price' => wc_price($masterclass_product->get_price()),
        'image_url' => get_the_post_thumbnail_url($masterclass_id) ?? '',
        'url_to_checkout' => $url_checkout,
    ];
}


function filter_plugin_updates( $value ) {
    unset( $value->response['wc-pagarme-pix-payment/woocommerce-pagarme-pix-payment.php'] );
    return $value;
}
add_filter( 'site_transient_update_plugins', 'filter_plugin_updates' );

/**
 * Desabilitar a atualização de quantidade de itens.
 * Assim o usuário será limitado a comprar somente uma unidade
 * de cada produto.
 */
add_filter('woocommerce_is_sold_individually', function () {
    return true;
}, 9999, 2);


/**
 * Função para request webwook
 *
 * @params email // required
 * @route admin-ajax.php?action=send_form_request
 */
function send_form_request()
{
    try{
        $email = sanitize_text_field($_POST['email']);

        if( !filter_input( INPUT_POST, 'email', FILTER_VALIDATE_EMAIL ) ) {
            wp_send_json_error( 'Email incorreto', 422 );
        }

        $url = 'https://hooks.zapier.com/hooks/catch/10583855/b483m4i/';
        $response = wp_remote_post($url, array(
                'method' => 'POST',
                'timeout' => 45,
                'headers' => array(),
                'body' => array(
                    'email' => $email,
                ),
            )
        );

        if (is_wp_error($response)) {
            wp_send_json_error( 'Sistema indisponível. Por favor entre em contato com o suporte', 503 );
            wp_die();
        }

        wp_send_json_success( 'ok', 200 );

    } catch (\Exception $e) {
        wp_send_json_error($e->getMessage(), 422);
        wp_die();
    }
}
add_action('wp_ajax_send_form_request', 'send_form_request');
add_action('wp_ajax_nopriv_send_form_request', 'send_form_request');


/**
 * Diparar request para quando o pedido mudar de status para completo
 * @param $order_id
 */
function send_email_success_order($order_id)
{
    try{
        $order = wc_get_order($order_id);
        $order_status = $order->get_status();

        if ( !in_array( $order_status, [ 'completed', 'processing' ] ) ) {
            return;
        }

        $email = $order->get_billing_email();
        $name = $order->get_billing_first_name();
        $last_name = $order->get_billing_last_name();

        $url = 'https://hooks.zapier.com/hooks/catch/10583855/b60qc0g/';
        $response = wp_remote_post($url, array(
                'method' => 'POST',
                'timeout' => 45,
                'headers' => array(),
                'body' => array(
                    'name' => $name,
                    'last_name' => $last_name,
                    'email' => $email,
                ),
            )
        );

        if (is_wp_error($response)) {
            wp_send_json_error( 'Sistema indisponível. Por favor entre em contato com o suporte', 503 );
            wp_die();
        }

        wp_send_json_success( 'ok', 200 );

    } catch (\Exception $e) {
        wp_send_json_error($e->getMessage(), 422);
        wp_die();
    }

}
add_action('woocommerce_order_status_changed', 'send_email_success_order');

/**
 * Remover campos padrões do woocommerce
 *
 * @param $fields
 * @return mixed
 */
function override_checkout_fields($fields)
{
    unset($fields['billing']['billing_company']); //remover empresa
    unset($fields['billing']['billing_address_2']); //remover endereço 2
    unset($fields['billing']['billing_cellphone']); //remover celular
    unset($fields['order']['order_comments']); //remover comentários do pedido / compra

    return $fields;
}

add_filter('woocommerce_checkout_fields' , 'override_checkout_fields');

