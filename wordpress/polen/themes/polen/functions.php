<?php
/**
 * Polen functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Polen
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.5' );
}

define('TEMPLATE_URI', get_template_directory_uri());
define('TEMPLATE_DIR', get_template_directory());
define('DEVELOPER', defined('ENV_DEV') && ENV_DEV);

if ( ! function_exists( 'polen_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function polen_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Polen, use a find and replace
		 * to change 'polen' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'polen', TEMPLATE_DIR . '/languages' );

		// Add default posts and comments RSS feed links to head.
		//add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		$fat = 1.5;

		add_image_size( 'polen-thumb-sm', 156*$fat, 190*$fat, false );
		add_image_size( 'polen-thumb-md', 163*$fat, 190*$fat, false );
		add_image_size( 'polen-thumb-lg', 200*$fat, 290*$fat, false );

		add_image_size( 'polen-square-crop-sm', 32*$fat, 32*$fat, true );
		add_image_size( 'polen-square-crop-md', 40*$fat, 40*$fat, true );
		add_image_size( 'polen-square-crop-lg', 48*$fat, 48*$fat, true );
		add_image_size( 'polen-square-crop-xl', 120*$fat, 120*$fat, true );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'polen' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'polen_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'polen_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function polen_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'polen_content_width', 640 );
}
add_action( 'after_setup_theme', 'polen_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function polen_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'polen' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'polen' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'polen_widgets_init' );

function get_assets_folder() {
	$min = "min/";
	if (DEVELOPER) {
		$min = "";
	}
	return $min;
}

/**
 * Enqueue scripts and styles.
 */
function polen_scripts() {
	global $wp_styles;
	$min = get_assets_folder();

	if( is_front_page() || is_page_template( 'inc/landpage.php' ) || (is_singular() && is_product())) {
		foreach( $wp_styles->queue as $style ) {
			if( strpos( $style, 'cookie-law-info' ) === false) {
				wp_dequeue_style( $wp_styles->registered[$style]->handle );
			}
		}
	}

	if(is_front_page()) {
		wp_enqueue_script( 'home-scripts', TEMPLATE_URI . '/assets/js/' . $min . 'front-page.js', array(), _S_VERSION, true );
	}

	// Registrando Scripts ------------------------------------------------------------------------------
	wp_register_script( 'vimeo', 'https://player.vimeo.com/api/player.js', array(), '', true );
	wp_register_script('vuejs', TEMPLATE_URI . '/assets/vuejs/' . $min . 'vue.js', array(), '', false);
	wp_register_script( 'comment-scripts', TEMPLATE_URI . '/assets/js/' . $min . 'comment.js', array("vuejs"), _S_VERSION, true );
	// --------------------------------------------------------------------------------------------------

	wp_enqueue_style('polen-custom-styles', TEMPLATE_URI . '/assets/css/style.css', array(), _S_VERSION);
	if(is_singular() && is_product()) {
		// wp_enqueue_script( 'slick-slider', TEMPLATE_URI . '/assets/slick/slick.min.js', array("jquery"), '', true );
		wp_enqueue_script( 'vimeo');
		wp_enqueue_script( 'talent-scripts', TEMPLATE_URI . '/assets/js/' . $min . 'talent.js', array("vimeo"), _S_VERSION, true );
	}

	if( is_cart() ) {
		wp_enqueue_script( 'polen-cart', TEMPLATE_URI . '/assets/js/' . $min . 'cart.js', array("jquery"), _S_VERSION, true );
	}

	if( is_checkout() ) {
		wp_enqueue_script( 'polen-checkout', TEMPLATE_URI . '/assets/js/' . $min . 'checkout.js', array("jquery"), _S_VERSION, true );
	}

	wp_enqueue_script( 'bootstrap-js', TEMPLATE_URI . '/assets/bootstrap-4.6.0/dist/js/bootstrap.min.js', array("jquery"), _S_VERSION, true );

	if(is_user_logged_in()) {
		wp_enqueue_script( 'header-scripts', TEMPLATE_URI . '/assets/js/' . $min . 'navigation.js', array("jquery"), _S_VERSION, true );
	}

	wp_enqueue_script( 'global-js', TEMPLATE_URI . '/assets/js/' . $min . 'global.js', array("jquery"), _S_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'polen_scripts' );

/**
 * File responsible to utils functions
 */
require_once TEMPLATE_DIR . '/inc/utils.php';

/**
 * Implement the Custom Header feature.
 */
require TEMPLATE_DIR . '/inc/custom-header.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require TEMPLATE_DIR . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require TEMPLATE_DIR . '/inc/customizer.php';


/**
 * Components.
 */
require TEMPLATE_DIR . '/inc/components.php';

/**
 * File responsible to get all collection for front
 */
require_once TEMPLATE_DIR . '/inc/collection-front.php';

/**
 * Arquivo responsavel por retornos HTML e icones
 */
require_once TEMPLATE_DIR . '/classes/Icon_Class.php';

/**
 * Arquivo responsavel por retornos da tela de acompanhamento de pedidos
 */
require_once TEMPLATE_DIR . '/classes/Order_Class.php';


add_action('wc_gateway_stripe_process_response', function($response, $order) {
	// $response
	// $order
	if( $response->status == 'succeeded' ) {
		$order->update_status( 'payment-approved', 'Pago com Sucesso' );
	}

	if ( $response->status == 'failed') {
		$order->update_status( 'payment-rejected', 'Erro no Pagamento' );
	}
}, 10, 3);

add_action('wc_gateway_stripe_process_webhook_payment_error', function($order, $notification){
	$order->update_status( 'payment-rejected', 'Erro no Pagamento' );
}, 10, 2);