<?php
/**
 * Polen Theme Customizer
 *
 * @package Polen
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function polen_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'polen_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'polen_customize_partial_blogdescription',
			)
		);
	}
}
add_action( 'customize_register', 'polen_customize_register' );


function your_theme_customizer_setting($wp_customize) {
	// add a setting
		$wp_customize->add_setting('logo_theme_white');
	// Add a control to upload the hover logo
		$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'logo_theme_white', array(
			'label' => 'Logo tema Branco',
			'section' => 'title_tagline', //this is the section where the custom-logo from WordPress is
			'settings' => 'logo_theme_white',
			'priority' => 8 // show it just below the custom-logo
		)));
}

add_action('customize_register', 'your_theme_customizer_setting');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function polen_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function polen_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function polen_customize_preview_js() {
	wp_enqueue_script( 'polen-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'polen_customize_preview_js' );
