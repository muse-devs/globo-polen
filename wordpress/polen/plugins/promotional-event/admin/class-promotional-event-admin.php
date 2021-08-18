<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://polen.me
 * @since      1.0.0
 *
 * @package    Promotional_Event
 * @subpackage Promotional_Event/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Promotional_Event
 * @subpackage Promotional_Event/admin
 * @author     Polen.me <glaydson.queiroz@polen.me>
 */
class Promotional_Event_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version )
    {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
    {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/promotional-event-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.2.0/css/bootstrap.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
    {
        wp_enqueue_script( 'bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', $this->version, false );
        wp_enqueue_script( 'dataTables-script', plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/promotional-event-admin.js', array( 'jquery' ), $this->version, true );
        wp_localize_script( 'ajax-script', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }

    /**
     * Adiciona os menus no dashboard do wordpress
     *
     * @since    1.0.0
     */
    public function add_menu()
    {
        add_menu_page('Evento Promocional',
            'Evento Promocional',
            'manage_options',
            'promotional-event',
            array($this, 'show_promotions')
        );

        add_submenu_page('promotional-event',
            'Configurações',
            'Configurar Cupons',
            'manage_options',
            'options_event',
            array($this, 'show_options'),
        );
    }

    /**
     * View página principal do plugin
     *
     * @since    1.0.0
     */
    public function show_promotions()
    {
        $sql = new Coupons();
        $values_code = $sql->get_codes();
        require 'partials/promotional-event-admin-display.php';
    }

    /**
     * View página de opções do plugin
     *
     * @since    1.0.0
     */
    public function show_options()
    {
        $sql = new Coupons();
        $count = $sql->count_rows();
        require 'partials/promotional-event-options.php';
    }

    public function create_coupons()
    {
        $foo = addslashes($_POST['qty']);
        $sql = new Coupons();
        $sql->insert_coupons($foo);
    }

}
