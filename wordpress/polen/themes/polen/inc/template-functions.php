<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Polen
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function polen_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'polen_body_classes' );


/**
 * Responsible to return a link for all talents
 *
 * @return string link
 */
function polen_get_all_new_talents_url()
{
	return polen_get_all_talents_url() . '?orderby=date';
}

/**
 * Retorna a URL de todos os talentos
 *
 * @return string link
 */
function polen_get_all_talents_url()
{
	return get_permalink( wc_get_page_id( 'shop' ) );
}


/**
 * Responsible to return a link for all categories
 *
 * @return string link
 */
function polen_get_all_categories_url()
{
	return site_url( get_option( 'category_base', null ) );
}


/**
 * Get a URL para assistir video passando a $order_id
 * @param int $order_id
 */
function polen_get_link_watch_video_by_order_id( $order_id )
{
	return wc_get_account_endpoint_url('watch-video') . "{$order_id}";
}


/**
 * Funcao para pegar a URL do My-Account
 */
function polen_get_url_my_account()
{
	return get_permalink( get_option('woocommerce_myaccount_page_id') );
}

/**
 * Funcao para pegar a URL dos Pedidos (Talento)
 */
function polen_get_url_my_orders()
{
	return polen_get_url_my_account() . "/orders";
}


/**
 * Pegar a URL da categoria pela CategoriaID
 */
function polen_get_url_category_by_term_id( $term_id )
{
	return get_term_link( $term_id, 'product_cat' );
}


/**
 * Pegar a URL da categoria pelo ProductID
 */
function polen_get_url_category_by_product_id ( $product_id )
{
	$cat_terms = wp_get_object_terms( $product_id, 'product_cat' );
    $cat_link = '';
	$cat = array_pop( $cat_terms );
    if ( !empty($cat) ) {
        $cat_link = get_term_link($cat->term_id);
    }
	return $cat_link;
}

function polen_get_url_review_page()
{
	return './reviews/';
}
