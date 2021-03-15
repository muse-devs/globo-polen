<?php

namespace Polen\Includes;

use \Polen\Admin\Polen_Admin;

class Polen_Account
{

    public function __construct( $static = false ) {
        if( $static ) {
            add_filter( 'woocommerce_endpoint_orders_title', array( $this,  'my_account_custom' ), 20, 2 );
            add_filter( 'woocommerce_account_menu_items', array( $this, 'my_account_menu_title' ) );
            add_filter( 'woocommerce_endpoint_view-order_title', array( $this,  'view_order_custom' ), 20, 2 );
            add_action( 'woocommerce_account_polen-favorite_endpoint', array( $this, 'polen_favorite_content' ) );
            //add_action( 'init', array( $this, 'polen_add_favorite_endpoint' ) );
            add_filter( 'woocommerce_before_account_orders', array( $this, 'my_orders_title' ));
            add_filter('woocommerce_show_page_title', array( $this, 'wat' ) );
        }
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
        $items['edit-account'] = 'Meus dados';
       // unset( $items['dashboard'] );
        unset( $items['downloads'] );
        unset( $items['edit-address'] );

        $logged_user = wp_get_current_user();
		if( in_array( 'user_talent',  $logged_user->roles ) )
		{ 
            $items['orders'] = 'Suas solicitações';
            $items['dashboard'] = 'Início';
        }    
        else{
            unset( $items['dashboard'] );
            $items['orders']       = 'Meus pedidos';
            $favorite = array( 'polen-favorite' => 'Favoritos' );
            $items = array_slice( $items, 0, 2, true ) + $favorite + array_slice( $items, 2, NULL, true );
        }    



        return $items;
    }

    public function polen_favorite_content() {
        var_dump('favorite talent');
    }

    public function polen_add_favorite_endpoint() {
        add_rewrite_endpoint( 'polen-favorite', EP_PAGES );
    }
}
