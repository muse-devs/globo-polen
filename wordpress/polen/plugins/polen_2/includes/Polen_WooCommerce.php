<?php

namespace Polen\Includes;

class Polen_WooCommerce 
{

    public function __construct( $static = false ) 
    {
        if( $static ) {
            add_action( 'init', array( $this, 'register_custom_order_statuses' ) );
            add_filter( 'wc_order_statuses', array( $this, 'add_custom_order_statuses' ) );
        }
    }

    public function register_custom_order_statuses() 
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
        );

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

}