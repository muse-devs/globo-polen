<?php

namespace Polen\Admin;

use \Polen\Includes\Polen_Update_Fields;

class Polen_Admin {


	private $plugin_name;

	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
                
		$this->init_classes( true );
	}
        
        public function actions()
        {
            add_action('admin_init', [ $this, 'init_classes'], 10 );
        }
        
        public function init_classes( bool $static = true )
        {
                new Polen_Admin_DisableMetabox( $static );
                new Polen_Update_Fields( $static );
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
        
        
        /**
         * Retorna o caminho do arquivo de um metabox
         * @param type $file
         * @return string
         */
        public static function get_metabox_path( string $file )
        {
            $dir_admin = PLUGIN_POLEN_DIR . 'admin/partials/metaboxes/' . $file;
            return $dir_admin;
        }
        
        
        /**
         * Retorna a url do arquivo de um JS
         * @param string $file
         * @return string
         */
        public static function get_js_url( string $file )
        {
            return PLUGIN_POLEN_URL . 'admin/js/' . $file;
        }

}
