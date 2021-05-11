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
	return '#/talents/new';
}

/**
 * Responsible to return a link for all categories
 * 
 * @return string link
 */
function polen_get_all_cetegories_url()
{
	return '#/categories/new';
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
