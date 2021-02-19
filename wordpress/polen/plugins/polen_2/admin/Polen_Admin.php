<?php

namespace Polen\Admin;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       rodolfoneto.com.br
 * @since      1.0.0
 *
 * @package    Polen
 * @subpackage Polen/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Polen
 * @subpackage Polen/admin
 * @author     Rodolfo <rodolfoneto@gmail.com>
 */
class Polen_Admin {


	private $plugin_name;

	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		
	}
        
        public function actions()
        {
//            add_action('admin_init', [ $this, 'init_classes'], 10 );
        }
        
        public function init_classes( bool $static )
        {
            new Polen_Admin_DisableMetabox( true );die(':D');
        }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/polen-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/polen-admin.js', array( 'jquery' ), $this->version, false );

	}

}
