<?php

namespace Polen\Includes;

class Polen_WooCommerce 
{
    public function __construct( $static = false ) 
    {
        $this->order_statuses = array(
            'wc-payment-in-revision' => array(
                'label'                     => __( 'Aguardando confirmação do pagamento', 'polen' ),
                'public'                    => false,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( 'Aguardando confirmação do pagamento <span class="count">(%s)</span>', 'Aguardando confirmação do pagamento <span class="count">(%s)</span>', 'polen' ),
            ),
            'wc-payment-rejected' => array(
                'label'                     => __( 'Pagamento rejeitado', 'polen' ),
                'public'                    => false,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( 'Pagamento rejeitado <span class="count">(%s)</span>', 'Pagamento rejeitado <span class="count">(%s)</span>', 'polen' ),
            ),
            'wc-payment-approved' => array(
                'label'                     => __( 'Pagamento aprovado', 'polen' ),
                'public'                    => false,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( 'Pagamento aprovado <span class="count">(%s)</span>', 'Pagamento aprovado <span class="count">(%s)</span>', 'polen' ),
            ),
            'wc-talent-rejected' => array(
                'label'                     => __( 'O talento não aceitou', 'polen' ),
                'public'                    => false,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( 'O talento não aceitou <span class="count">(%s)</span>', 'O talento não aceitou <span class="count">(%s)</span>', 'polen' ),
            ),
            'wc-talent-accepted' => array(
                'label'                     => __( 'O talento aceitou', 'polen' ),
                'public'                    => false,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( 'O talento aceitou <span class="count">(%s)</span>', 'O talento aceitou <span class="count">(%s)</span>', 'polen' ),
            ),
            'wc-order-expired' => array(
                'label'                     => __( 'Pedido expirado', 'polen' ),
                'public'                    => false,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( 'Pedido expirado <span class="count">(%s)</span>', 'Pedido expirado <span class="count">(%s)</span>', 'polen' ),
            ),
        );

        if( $static ) {
            add_action( 'init', array( $this, 'register_custom_order_statuses' ) );
            add_filter( 'wc_order_statuses', array( $this, 'add_custom_order_statuses' ) );
            add_filter( 'bulk_actions-edit-shop_order', array( $this, 'dropdown_bulk_actions_shop_order' ), 20, 1 );
            add_filter( 'woocommerce_email_actions', array( $this, 'email_actions' ), 20, 1 );

            add_action( 'init', function( $array ) {
                foreach ( $this->order_statuses as $order_status => $values ) 
                {
                    $action_hook = 'woocommerce_order_status_' . $order_status;
                    add_action( $action_hook, array( WC(), 'send_transactional_email' ), 10, 1 );
                }
            } );
        }
    }

    public function register_custom_order_statuses() 
    {
        foreach ( $this->order_statuses as $order_status => $values ) 
        {
			register_post_status( $order_status, $values );
		}
    }

    public function add_custom_order_statuses( $order_statuses )
    {
        foreach ( $this->order_statuses as $order_status => $values ) 
        {
			$order_statuses[ $order_status ] = $values[ 'label' ];
		}
        return $order_statuses;
    }

    function dropdown_bulk_actions_shop_order( $actions ) 
    {
        foreach ( $this->order_statuses as $order_status => $values ) 
        {
			$actions[ $order_status ] = $values[ 'label' ];
		}
        return $actions;
    }

    function email_actions( $actions ) 
    {
        foreach ( $this->order_statuses as $order_status => $values ) 
        {
			$actions[] = 'woocommerce_order_status_' . $order_status;
		}
        return $actions;
    }

}