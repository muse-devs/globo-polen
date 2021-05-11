<?php

/**
 * Esse arquivo é responsavel por pegar qualquer
 * dado do banco de dados para o Front
 */





/**
 * Pegar informações das categorias
 * @param ints $ids
 */
function _polen_get_first_category_object( $ids ) {
    foreach ($ids as $id) {
        return get_term( $id, 'product_cat', OBJECT );
    }
}


/**
 * Pegar os dados para preenchimento do card de artista pelo Obj Produto do artista
 * @param \WP_Product $talent_object
 * @return type
 */
function _polen_get_info_talent_by_product_id( \WC_Product $talent_object ) {
        $talent = [];
        $talent['ID'] = $talent_object->get_id();
        $talent['image'] = $talent_object->get_image();
        $talent['talent_url'] = $talent_object->get_permalink();
        $talent['price'] = $talent_object->get_price();
        $talent['price_formatted'] = $talent_object->get_price_html();
        $talent['name'] = $talent_object->get_title();

        $ids = $talent_object->get_category_ids();
        $category = _polen_get_first_category_object( $ids );
        //TODO pegar a URL da categoria, onde isso vai dar?
        $talent['category_url'] = get_term_link( $category->term_id, 'product_cat' );
        $talent['category'] = $category->name;
        return $talent;
}


/**
 * Usando a funcao _get_info_talent_by_product_id dentro de um loop para
 * retornar varios talentos no formato dos cards
 *
 * @param array $args
 * @return type
 */
function _polen_get_info_talents_by_args( array $args )
{
    $talents_objects = wc_get_products( $args );
    $talents = [];
    foreach ( $talents_objects as $talent_object ) {
        $talents[] = _polen_get_info_talent_by_product_id( $talent_object );
    }
    return $talents;
}


/**
 * Pegar os artistas recentes informando qual o maximo de resultado
 *
 * @param int quantity
 * @return array
 */
function polen_get_new_talents(int $quantity = 4)
{
    $args = [
        'numberposts' => $quantity,
        'post_status' => 'publish',
        'order' => 'date_created',
        'orderby' => 'DESC'
    ];
    $talents = _polen_get_info_talents_by_args( $args );
    return $talents;
}


/**
 * Pegar dados e formatar para o cord de categorias
 *
 * @param \WP_Term $category_object
 * @return type
 */
function _polen_get_category_info( \WP_Term $category_object )
{
    $category = [];
    $category[ 'ID' ] = $category_object->term_id;
    $category[ 'title' ] = $category_object->name;
    //TODO resolver qual vai ser a URL da categoria e onde vai ser o resultado
    $category[ 'url' ] = '/#catogoria/' . $category_object->slug;

    $thumbnail_id = get_term_meta( $category_object->term_id, 'thumbnail_id', true );
    $category[ 'image' ] = wp_get_attachment_url( $thumbnail_id );

    return $category;
}


/**
 * Pegar as categorias que serao apresentadas na Home
 *
 * @param int quantity
 * @return array
 */
function polen_get_categories_home(int $quantity = 4)
{
    $args = [
        'taxonomy' => 'product_cat',
        'number' => $quantity,
        'hide_empty' => true,
        'order' => 'count',
        'exclude' => '15',
    ];

    $categories_object = get_terms( $args );
    $categories = [];
    foreach ( $categories_object as $category_object ) {
        $categories[] = _polen_get_category_info( $category_object );
    }
    return $categories;
}


/**
 * Pegar todos os artistas informando qual o maximo de resultado
 *
 * @param int quantity
 * @return array
 */
function polen_get_talents( int $quantity = 10 )
{
    $args = [
        'numberposts' => $quantity,
        'post_status' => 'publish',
        'order' => 'RAND',
        'orderby' => 'DESC'
    ];
    $talents = _polen_get_info_talents_by_args( $args );
    return $talents;
}


/**
 * Retorna a URL do arquivo JSON das occasions para a tela do brief do videos
 * antes da compara pelo costumer.
 *
 * @return string
 */
function polen_get_occasions_json()
{
    $occasion_list = new Polen\Includes\Polen_Occasion_List();
    return $occasion_list->get_url_occasion_json();
}
