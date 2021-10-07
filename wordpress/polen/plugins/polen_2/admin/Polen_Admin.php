<?php
namespace Polen\Admin;
defined( 'ABSPATH' ) || die;

use Polen\Includes\Ajax\Polen_Cupom_Create_Controller;
use \Polen\Includes\Polen_Update_Fields;
use Polen\Tributes\Tributes_Admin;
use Polen\Tributes\Tributes_Details_Admin;

class Polen_Admin {


	private $plugin_name;

	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        
		$this->actions( true );
        if( is_admin() ) {
            new Tributes_Admin( true );
            new Tributes_Details_Admin( true );
        }
	}
        
        public function actions()
        {
            add_action( 'admin_init', [ $this, 'init_classes' ], 10 );
        }
        
        public function init_classes( bool $static = true )
        {
                new Polen_Admin_DisableMetabox( $static );
                // new Polen_Update_Fields( $static );
                new Polen_Admin_RedirectTalentAccess();
                new Polen_Admin_Order_Custom_Fields( $static );
                new Polen_Cupom_Create_Controller( $static );
                new Polen_Admin_Video_Info( $static );
                new Polen_Admin_B2B_Product_Fields( $static );
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
        wp_register_script( 'vuejs', plugin_dir_url( __FILE__ ) . 'js/vendor/' . get_assets_folder() . 'vue.js', array(), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/polen-admin.js', array( 'jquery', 'vuejs' ), $this->version, false );
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
