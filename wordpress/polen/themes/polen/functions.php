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
	define( '_S_VERSION', '1.0.0' );
}

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

/**
 * Enqueue scripts and styles.
 */
function polen_scripts() {
	wp_enqueue_style( 'polen-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'polen-style', 'rtl', 'replace' );

	wp_enqueue_script( 'polen-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'polen_scripts' );

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


if( is_admin() ) {
	remove_action( 'welcome_panel', 'wp_welcome_panel' );
	add_action( 'wp_dashboard_setup', 'polen_dashboard_widgets');
	function polen_dashboard_widgets(){
		 remove_meta_box('welcome_panel', 'dashboard', 'normal');
		 remove_meta_box('dashboard_site_health', 'dashboard', 'normal');     // Status do Diagnóstico
		 remove_meta_box('dashboard_activity', 'dashboard', 'normal');        // Atividade
		 remove_meta_box('dashboard_right_now', 'dashboard', 'normal');       // Agora
		 remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); // Comentários Recentes
		 remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');  // Links
		 remove_meta_box('dashboard_plugins', 'dashboard', 'normal');         // Plugins
		 remove_meta_box('dashboard_quick_press', 'dashboard', 'side');       // Quick Press
		 remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');     // Rascunhos
		 remove_meta_box('dashboard_primary', 'dashboard', 'side');           // Novidades e eventos do Wordpress
		 remove_meta_box('dashboard_secondary', 'dashboard', 'side');         // Outas novidades do Wordpress
	}
}

add_action( 'admin_init', 'footer_text' );
function footer_text() {
     add_filter( 'admin_footer_text', '__return_false' );
}
add_filter( 'update_footer', '__return_false' );