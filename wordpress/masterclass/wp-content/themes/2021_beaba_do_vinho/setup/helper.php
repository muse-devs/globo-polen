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
    global $woocommerce;
    $cart = $woocommerce->cart->get_cart();

    if (empty($cart) && $cart == null) {
        return '';
    }

    $cart_items_ids = '';
    foreach ($cart as $item_key => $item_value) {
        $cart_items_ids = $item_value['data']->id;
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
 * Retornar informações do produto masterclass
 *
 * @return array
 */
function get_product_masterclass(): array
{
    $product_masterclass = wc_get_product(69);

    if (is_wp_error($product_masterclass) || empty($product_masterclass)) {
        return [
            'error' => 'Produto masterclass está com ID diferente',
        ];
    }

    return [
        'name' => $product_masterclass->get_name(),
        'price' => wc_price($product_masterclass->get_price()),
        'image_url' => get_the_post_thumbnail_url(69) ?? '',
        'url_to_checkout' => home_url('/') . '?add-to-cart=69',
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
