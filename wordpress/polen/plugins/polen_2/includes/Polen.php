<?php

namespace Polen\Includes;

use Polen\Includes\Polen_Plugin_Settings;
use Polen\Publics\Polen_Public;
use Polen\Admin\Polen_Admin;
use Polen\Includes\Polen_Talent;
use Polen\Includes\Polen_Occasion_List;
use Polen\Includes\Polen_Cart;
use Polen\Includes\Polen_Checkout;
use Polen\Includes\Talent\{Polen_Talent_Router, Polen_Talent_Controller, Polen_Talent_Part_Theme};
use Polen\Includes\Polen_Order;
use Polen\Includes\Order_Review\{Polen_Order_Review_Controller, Polen_Order_Review_Router};

class Polen {

    protected $loader;
    protected $plugin_name;
    protected $version;
    protected $polen_signIn;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
        if (defined('POLEN_VERSION')) {
            $this->version = POLEN_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'polen';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

        $this->init_classes();
        
    }

    private function init_classes()
    {
        new Polen_SignInUser();
        new Polen_Talent( true );
        new Polen_SMTP( true );
        new Polen_Emails();
        new Polen_Occasion_List( true );
        new Polen_Cart( true );
        new Polen_Plugin_Settings( true );
        new Polen_Update_Fields( true );
        new Polen_DisableAdminBar();
        new Polen_Checkout( true );
        new Polen_Account( true );
        new Polen_Order( true );
        new Polen_WooCommerce( true );
        new Polen_Talent_Part_Theme( true );
        new Polen_Video_Player( true );

        //Endpoints Talent Logged
        $ctler = new Polen_Talent_Controller();
        $talent_area_router = new Polen_Talent_Router($ctler);
        $talent_area_router->init_routes();
        
        //Endpoint OrderReview
        $order_review_router = new Polen_Order_Review_Router( new Polen_Order_Review_Controller() );
        $order_review_router->init_routes();

        //OrderReview Theme Area
        new Polen_Order_Review_Rewrite( true );
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Polen_Loader. Orchestrates the hooks of the plugin.
     * - Polen_i18n. Defines internationalization functionality.
     * - Polen_Admin. Defines all hooks for the admin area.
     * - Polen_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
        $this->loader = new Polen_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Polen_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {
        $plugin_i18n = new Polen_i18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new Polen_Admin($this->get_plugin_name(), $this->get_version());
        $plugin_admin->init_classes( true );
        
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {
        $plugin_public = new Polen_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        //Tela de cadastro de usuário
        $this->loader->add_filter('woocommerce_created_customer', $this->polen_signIn, 'update_user_date', 10, 3);
        $this->loader->add_action('woocommerce_register_form_start', $this->polen_signIn, 'add_fields_sign_in', 10, 0);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Polen_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}
