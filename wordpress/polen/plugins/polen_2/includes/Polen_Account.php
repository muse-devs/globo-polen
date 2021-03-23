<?php

namespace Polen\Includes;

use \Polen\Admin\Polen_Admin;

class Polen_Account
{

    public function __construct( $static = false ) {
        if( $static ) {
            add_filter( 'wp_pre_insert_user_data', array( $this, 'set_user_login' ), 10, 3 );
            add_filter( 'woocommerce_endpoint_orders_title', array( $this,  'my_account_custom' ), 20, 2 );
            add_filter( 'woocommerce_account_menu_items', array( $this, 'my_account_menu_title' ) );
            add_filter( 'woocommerce_endpoint_view-order_title', array( $this,  'view_order_custom' ), 20, 2 );
            add_filter( 'woocommerce_before_account_orders', array( $this, 'my_orders_title' ));
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
                'edit-account'    => 'Meus dados',
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
}
