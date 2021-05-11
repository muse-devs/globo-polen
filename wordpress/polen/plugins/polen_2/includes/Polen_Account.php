<?php

namespace Polen\Includes;

use \Polen\includes\Polen_Talent;
use \Polen\Includes\Polen_Video_Info;

class Polen_Account
{

    public function __construct( $static = false ) {
        if( $static ) {
            add_filter( 'wp_pre_insert_user_data', array( $this, 'set_user_login' ), 10, 3 );
            add_filter( 'woocommerce_endpoint_orders_title', array( $this,  'my_account_custom' ), 20, 2 );
            add_filter( 'woocommerce_account_menu_items', array( $this, 'my_account_menu_title' ) );
            add_filter( 'woocommerce_endpoint_view-order_title', array( $this,  'view_order_custom' ), 20, 2 );
            add_filter( 'woocommerce_before_account_orders', array( $this, 'my_orders_title' ));
            add_action( 'template_redirect', array( $this, 'my_account_redirect' ) );
            add_action( 'woocommerce_account_watch-video_endpoint', array( $this, 'my_account_watch_video' ) );
            add_action( 'woocommerce_account_create-review_endpoint', array( $this, 'my_account_create_review' ) );
            add_action( 'init', function() {
                add_rewrite_endpoint('watch-video', EP_PAGES, 'watch-video' );
                add_rewrite_endpoint('create-review', EP_PAGES, 'create-review' );
            });
        }
    }

    public function set_user_login( $data, $update, $id ) {
        $data['user_nicename'] = $data['user_email'];
        $data['user_login']    = $data['user_email'];
        return $data;
    }

    public function my_account_custom( $title, $endpoint ) {
        $title = __( " ", "polen" );
        return $title;
    }

    public function my_orders_title(){
        $logged_user = wp_get_current_user();
		if( in_array( 'user_talent',  $logged_user->roles ) )
		{ 
            echo '<h1 class="entry-title">Suas solicitações</h1>';
        }else{
            echo '<h1 class="entry-title">Meus pedidos</h1>';
        }    
    }

    public function view_order_custom( $title, $endpoint ) {
        $title = ' ';
        return $title;
    }

    public function my_account_menu_title( $items ) {
        $logged_user = wp_get_current_user();
        if( in_array( 'user_talent',  $logged_user->roles ) )
        { 
            $menu_items = array(
                'dashboard'       => 'Início',
                'orders'          => 'Meus pedidos',
                'payment-options' => 'Pagamento',
                'customer-logout' => __( 'Logout', 'woocommerce' ),
            );
        }else{
            $menu_items = array(
                'orders'          => 'Meus pedidos',
                'payment-options' => 'Pagamento',
                'edit-account'    => 'Meus dados',
                'customer-logout' => __( 'Logout', 'woocommerce' ),
            );           
        }    
        return $menu_items;
    }

    /**
     * Faz my-account redirecionar para a lista de pedidos ao invés do dashboard
     */
    public function my_account_redirect() {
        if( is_user_logged_in() ){
            $logged_user = wp_get_current_user();
            if( !in_array( 'user_talent',  $logged_user->roles ) )
            { 
                $current_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";        
                $dashboard_url = get_permalink( get_option('woocommerce_myaccount_page_id'));
                if( is_user_logged_in() && $dashboard_url == $current_url ){
                    $url = get_home_url() . '/my-account/orders';
                    wp_redirect( $url );
                    exit;
                }
            } 
        }   
    }

    /**
     * Tela para visualizar o vídeo
    */
    public function my_account_watch_video()
    {
        if( is_user_logged_in() ){
            $user = wp_get_current_user();
            $polen_talent = new Polen_Talent;
            if( $polen_talent->is_user_talent( $user ) ) {
                wp_safe_redirect(site_url('my-account/orders'));
                exit;
            }
            $order_id = get_query_var('watch-video');
            if( isset( $order_id ) && !empty( $order_id) ){
                $video_info = Polen_Video_Info::get_by_order_id( $order_id );
                $video_hash = $video_info->hash;
                if( !empty( $video_hash ) ){
                    require_once PLUGIN_POLEN_DIR . '/publics/partials/polen_watch_video.php';
                } else {
                    $this->set_404();
                }    
            }
        }
    }


    /**
     * Tela para fã criar um review
    */
    public function my_account_create_review()
    {
        $order_id = get_query_var('create-review');
        $order = wc_get_order( $order_id );
        if( empty( $order) ) {
            $this->set_404();
        }
        $item_cart = \Polen\Includes\Cart\Polen_Cart_Item_Factory::polen_cart_item_from_order( $order );
        $talent_id = $item_cart->get_talent_id();
        
        require_once PLUGIN_POLEN_DIR . '/publics/partials/polen_create_review.php';
    }


    private function set_404()
    {
        global $wp_query;
        $wp_query->set_404();
        status_header( 404 );
        get_template_part( 404 );
        exit();
    }
}
