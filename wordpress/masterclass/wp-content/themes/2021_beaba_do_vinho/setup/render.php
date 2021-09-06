<?php

/**
 * Customiza a paginação dos posts
 *
 * @param array $args
 * @return string
 */
function _theme_show_pagination(array $args)
{
    $query = new WP_Query($args);

    $maxPage = 99999;
    $pages = paginate_links(array(
        'base' => str_replace($maxPage, '%#%', esc_url(get_pagenum_link($maxPage))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $query->max_num_pages,
        'type' => 'array',
        'prev_next' => true,
        'prev_text' => __('<i aria-hidden="true" class="fas fa-fw fa-chevron-left"></i>'),
        'next_text' => __('<i aria-hidden="true" class="fas fa-fw fa-chevron-right"></i>'),
    ));

    $output = '';
    if (is_array($pages)) {
        $output .= '<ul class="pagination">';
        foreach ($pages as $page) {
            $output .= "<li class=\"pagination__number\">{$page}</li>";
        }
        $output .= '</ul>';
    }
    wp_reset_query();

    return $output;
}

/**
 * Adiciona um script no footer que vai inserir uma variável js com uma URL
 * que será utilizada para requisições AJAX
 */
function _theme_load_ajax()
{
    $script = '<script>';
    $script .= 'var ajaxUrl = "' . admin_url('admin-ajax.php') . '";';
    $script .= '</script>';

    echo $script;
}
add_action('wp_footer', '_theme_load_ajax');

/**
 * Renderiza o código do analytics salvo pelo ACF no admin
 *
 * @return false|string
 */
function _theme_render_analytics()
{
    if (function_exists('get_field')) {
        $codeAnalytics = get_field('analytics_code', 'option');
        if (!empty($codeAnalytics)) {
            ob_start();
            ?>
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $codeAnalytics; ?>"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', '<?php echo $codeAnalytics; ?>');
            </script>
            <?php

            return ob_get_clean();
        }
    }
}

/**
 * Gera um iframe do youtube a partir de uma URL do youtube em qualquer formato
 *
 * @param string $url
 * @param string $classes
 * @return string|string[]|null
 */
function _theme_generate_youtube_iframe(string $url, string $classes = '')
{
    return preg_replace(
        "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
        "<iframe src=\"//www.youtube.com/embed/$2\" class=\"$classes\" allowfullscreen></iframe>",
        $url
    );
}

/**
 * Retorna imagem destacada, caso não tenha ele retorna o placeholder
 *
 * @param int|null $postId
 * @return false|mixed|string|null
 */
function _theme_get_thumbnail(int $postId = null)
{
    $thumb = get_field('placeholder', 'options');
    if (null !== $postId) {
        if (has_post_thumbnail($postId)) {
            $thumb = get_the_post_thumbnail_url($postId);
        }
    }

    return $thumb;
}

/**
 * Retorna a logo principal
 *
 * @return mixed|null
 */
function _theme_get_logo()
{
    return get_field('logo', 'options');
}

/**
 * Retorna a logo do rodapé
 *
 * @return mixed|null
 */
function _theme_get_footer_logo()
{
    return get_field('footer_logo', 'options');
}

/**
 * Retorna as redes sociais
 *
 * @return mixed|null
 */
function _theme_get_social_networks()
{
    return get_field('social_networks', 'options');
}

/**
 * Retorna o endereço
 *
 * @return mixed|null
 */
function _theme_get_address()
{
    return get_field('address', 'options');
}

/**
 * Esta função deverá ser utilizada para paginação via AJAX, onde é possível paginar qualquer
 * post_type e usar parâmetros de busca, taxonomies, authors e categorias como filtro.
 */
function _theme_load_more()
{
    $taxonomies = (isset($_POST['taxonomies']) ? ($_POST['taxonomies']) : null);
    $postType = (isset($_POST['postType']) ? sanitize_text_field($_POST['postType']) : 'post');
    $search = (isset($_POST['search']) ? sanitize_text_field($_POST['search']) : null);
    $paged = (isset($_POST['paged']) ? sanitize_text_field($_POST['paged']) : get_query_var('paged'));
    $catIds = (isset($_POST['catIds']) ? sanitize_text_field($_POST['catIds']) : null);
    $author = (isset($_POST['author']) ? sanitize_text_field($_POST['author']) : null);
    $perPage = (isset($_POST['per_page']) ? sanitize_text_field($_POST['per_page']) : get_option('posts_per_page'));

    $args = array(
        'paged' => $paged,
        'post_status' => 'publish',
        'post_type' => $postType,
        'posts_per_page' => $perPage,
    );

    if (null !== $catIds) {
        $args['cat'] = $catIds;
    }

    if (null !== $author) {
        $args['author'] = $author;
    }

    if (!empty($search)) {
        $args['s'] = $search;
    }

    if (!empty($taxonomies) && count($taxonomies)) {
        $taxQueries = array();
        foreach ($taxonomies as $taxonomy) {
            if (isset($taxQueries[$taxonomy['name']])) {
                $taxQueries[$taxonomy['name']]['terms'][] = $taxonomy['term_id'];
            } else {
                $taxQueries[$taxonomy['name']] = array(
                    'taxonomy' => $taxonomy['name'],
                    'field' => 'id',
                    'terms' => array($taxonomy['term_id']),
                );
            }
        }

        $args['tax_query'] = array_values($taxQueries);
    }

    $query = new WP_Query($args);
    $isFinished = $query->post_count > (get_option('posts_per_page') - 1);

    ob_start();
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); ?>
            <!-- Insira o HTML do loop aqui -->
        <?php
        endwhile;
        $html = ob_get_clean();
    else :
        $html = '<p>Nenhum resultado encontrado</p>';
    endif;

    echo json_encode([
        'dataHtml' => $html,
        'isFinished' => $isFinished,
    ]);

    wp_reset_query();
    wp_die();
}
add_action('wp_ajax__theme_load_more', '_theme_load_more');
add_action('wp_ajax_nopriv__theme_load_more', '_theme_load_more');

/**
 * Adiciona meta tags para compartilhamento nas redes sociais
 */
function _theme_add_share_meta_tags()
{
    global $post;

    if (is_single()) : ?>
        <meta name="title" content="<?php echo get_the_title($post->ID); ?>" />
        <meta name="description" content="<?php echo get_the_excerpt($post->ID); ?>" />
        <?php if (has_post_thumbnail($post->ID)) : ?>
            <link rel="image_src" href="<?php echo get_the_post_thumbnail_url($post->ID); ?>" />
        <?php endif;
    endif;
}
add_action('wp_head', '_theme_add_share_meta_tags');

/**
 * Gera o HTML do Menu
 *
 * @param string $slug
 */
function _theme_the_menu(string $slug)
{
    $menu = _theme_get_menu($slug);
    ob_start(); ?>
        <nav class="menu-container">
            <ul class="list-style-remove menu-container__listing">
                <?php foreach ($menu as $item) : ?>
                    <li class="menu-item">
                        <a href="<?php echo $item->guid; ?>"
                            <?php echo isset($item->target) ? 'target="' . $item->target . '"' : ''; ?>
                        >
                            <?php echo $item->title; ?>
                        </a>
                        <?php if (property_exists($item, 'children')) : ?>
                            <ul class="menu-item__submenu list-style-remove">
                                <?php foreach ($item->children as $child) : ?>
                                    <li>
                                        <a href="<?php echo $child->guid; ?>"
                                            <?php echo isset($child->target) ? 'target="'.$child->target.'"' : ''; ?>
                                        >
                                            <?php echo $child->title; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    <?php
    $html = ob_get_clean();

    echo $html;
}
