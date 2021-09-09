<?php

/**
 * Registra todos os custom post types adicionados no arquivo post-types.php
 */
function _theme_register_custom_post_types()
{
    $parameters = _theme_post_types();
    if (!empty($parameters)) {
        foreach ($parameters as $parameter) {
            register_post_type('post_' . $parameter['slug'], array(
                'labels' => array(
                    'name' => __($parameter['name']),
                    'singular_label' => __($parameter['singular_name']),
                    'menu_name' => __($parameter['name']),
                    'parent_item_colon' => ('Parent'),
                    'all_items' => __('Listar todos'),
                    'view_item' => __('Visualizar'),
                    'add_new_item' => __('Adicionar ' . $parameter['singular_name']),
                    'add_new' => __('Adicionar ' . $parameter['singular_name']),
                    'edit_item' => __('Editar ' . $parameter['singular_name']),
                    'update_item' => __('Atualizar ' . $parameter['singular_name']),
                    'search_items' => __('Pesquisar ' . $parameter['singular_name']),
                    'not_found' => __('Registro não encontrado'),
                    'not_found_in_trash' => __('Nenhum registro encontrado na lixeira'),
                ),
                'menu_icon' => $parameter['dashicon'] ? $parameter['dashicon'] : 'dashicons-welcome-widgets-menus',
                'public' => true,
                'has_archive' => false,
                'rewrite' => array('slug' => strtolower($parameter['singular_name'])),
                'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'author', 'revisions', 'comments')
            ));

            if (!empty($parameter['taxonomy']) && count($parameter['taxonomy'])) {
                foreach ($parameter['taxonomy'] as $value) {
                    register_taxonomy(
                        'tax_' . $parameter['slug'] . '_' . strtolower($value['slug']),
                        'post_' . $parameter['slug'],
                        array(
                            'label' => __($value['name']),
                            'rewrite' => array(
                                'slug' => $parameter['slug'] . '-' . str_replace(' ', '-', strtolower($value['name']))
                            ),
                            'hierarchical' => true,
                        )
                    );
                }
            }
        }
    }
}
add_action('init', '_theme_register_custom_post_types');

if (function_exists('add_theme_support')) {
    add_image_size('admin-thumb', 100, 100);
}

add_filter('manage_posts_columns', '_theme_config_thumbnail_in_list', 5);
add_action('manage_posts_custom_column', '_theme_show_thumbnail_in_list', 5, 2);

/**
 * Configura imagem destacada para ser exibida na listagem dos posts
 *
 * @param $defaults
 * @return mixed
 */
function _theme_config_thumbnail_in_list($defaults)
{
    $defaults['custom_post_thumbs'] = __('Imagem destacada');
    return $defaults;
}

/**
 * Exibe a imagem destacada na listagem dos posts
 *
 * @param $columnName
 */
function _theme_show_thumbnail_in_list($columnName)
{
    if ($columnName === 'custom_post_thumbs') {
        the_post_thumbnail('admin-thumb');
    }
}

/**
 * Gera menu com submenus usando recursividade
 *
 * @param array $elements
 * @param int $parentId
 * @return array
 */
function _theme_build_menu(array &$elements, $parentId = 0)
{
    $data = [];
    foreach ($elements as &$element) {
        if ($element->menu_item_parent == $parentId) {
            $children = _theme_build_menu($elements, $element->ID);
            if ($children) {
                $element->children = $children;
            }

            $data[$element->ID] = $element;
            unset($element);
        }
    }

    return $data;
}

/**
 * Retorna um menu no formato de array pelo ID
 *
 * @param $menuId
 * @return array
 */
function _theme_get_menu($menuId)
{
    $locations = get_nav_menu_locations();
    if (!isset($locations[$menuId])) {
        return [];
    }

    $menuId = $locations[$menuId];
    $items = wp_get_nav_menu_items($menuId);

    return  $items ? _theme_build_menu($items, 0) : [];
}

/**
 * Exibe a Role do autor junto com seu nome no select que é exibido dentro do post
 *
 * @return string
 */
function _theme_show_role_author()
{
    global $post;

    $output = '<select id="post_author_override" name="post_author_override">';
    $users = get_users();
    foreach ($users as $user) {
        $userId = $post->post_author;

        $selected = $userId == $user->data->ID ? "selected='selected'" : '';
        $output .= '<option value="' . $user->data->ID . '" '. $selected . '>'
            . $user->data->user_login . ' (' . $user->roles[0] .')</option>';
    }
    $output .= "</select>";

    return $output;
}
add_filter('wp_dropdown_users', '_theme_show_role_author', 10, 2);
add_filter('post_author_meta_box', '_theme_show_role_author');
