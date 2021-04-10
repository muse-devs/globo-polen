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
	define( '_S_VERSION', '1.0.3' );
}

define('TEMPLATE_URI', get_template_directory_uri());
define('TEMPLATE_DIR', get_template_directory());

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
		load_theme_textdomain( 'polen', get_template_directory() . '/languages' );

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
	if (defined('ENV_DEV') && ENV_DEV) {
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

	if(is_front_page()) {
		foreach( $wp_styles->queue as $style ) {
			wp_dequeue_style($wp_styles->registered[$style]->handle);
		}
	}

	// wp_enqueue_style('font-poppins', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap', array(), '1.0.0');
	// wp_enqueue_style('font-inter', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700&display=swap', array(), '1.0.0');
	wp_enqueue_style('polen-custom-styles', TEMPLATE_URI . '/assets/css/style.min.css', array(), _S_VERSION);

	if(is_singular() && is_product()) {
		wp_enqueue_script( 'slick-slider', TEMPLATE_URI . '/assets/slick/slick.min.js', array("jquery"), _S_VERSION, true );
		wp_enqueue_script( 'vimeo', 'https://player.vimeo.com/api/player.js', array(), '', true );
		wp_enqueue_script( 'talent-scripts', TEMPLATE_URI . '/assets/js/' . $min . 'talent.js', array("slick-slider", "vimeo"), _S_VERSION, true );
	}

	if( is_front_page()) {
		wp_enqueue_script( 'slick-slider', TEMPLATE_URI . '/assets/slick/slick.min.js', array("jquery"), _S_VERSION, true );
		wp_enqueue_script( 'home-scripts', TEMPLATE_URI . '/assets/js/' . $min . 'front-page.js', array("slick-slider"), _S_VERSION, true );
	}

	if( is_cart() ) {
		wp_enqueue_script( 'polen-cart', TEMPLATE_URI . '/assets/js/cart' . $min . '.js', array("jquery"), _S_VERSION, true );
	}

	wp_enqueue_script( 'bootstrap-js', TEMPLATE_URI . '/assets/bootstrap-4.6.0/dist/js/bootstrap.min.js', array("jquery"), _S_VERSION, true );

	// if (defined('ENV_DEV') && ENV_DEV) {
	// 	wp_enqueue_script( 'vuejs', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js', array(), '2.6.12', false );
	// } else {
	// 	wp_enqueue_script( 'vuejs', 'https://cdn.jsdelivr.net/npm/vue@2.6.12', array(), '2.6.12', false );
	// }


	// if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
	// 	wp_enqueue_script( 'comment-reply' );
	// }
}
add_action( 'wp_enqueue_scripts', 'polen_scripts' );

/**
 * File responsible to utils functions
 */
require_once get_template_directory() . '/inc/utils.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';


/**
 * Components.
 */
require get_template_directory() . '/inc/components.php';

/**
 * File responsible to get all collection for front
 */
require_once get_template_directory() . '/inc/collection-front.php';

/**
 * Arquivo responsavel por retornos HTML e icones
 */
require_once get_template_directory() . '/classes/Icon_Class.php';
