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
            add_action( 'woocommerce_checkout_create_order', array( $this, 'order_meta' ), 12, 2 );
            add_action( 'add_meta_boxes', array( $this, 'add_metaboxes' ) );
            add_action( 'admin_head', array( $this, 'remove_metaboxes' ) );

            add_action( 'init', function( $array ) {
                foreach ( $this->order_statuses as $order_status => $values ) 
                {
                    $action_hook = 'woocommerce_order_status_' . $order_status;
                    add_action( $action_hook, array( WC(), 'send_transactional_email' ), 10, 1 );
                    $action_hook_notification = 'woocommerce_order_' . $order_status . '_notification';
                    add_action( $action_hook_notification, array( WC(), 'send_transactional_email' ), 10, 1 );
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

    public function order_meta( $order, $data ) 
    {
        $items = WC()->cart->get_cart();
        $key = array_key_first( $items );
        $billing_email = $items[ $key ][ 'email_to_video' ];
        if ( $billing_email && ! is_null( $billing_email ) && ! empty( $billing_email ) ) 
        {
            $order->update_meta_data( '_polen_customer_email', $billing_email );
            $order->update_meta_data( '_billing_email', $billing_email );
        }
    }

    public function remove_metaboxes() 
    {
        global $current_screen;
        if( $current_screen && ! is_null( $current_screen ) && isset( $current_screen->id ) && $current_screen->id == 'shop_order' && isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit' ) 
        {
            remove_meta_box( 'woocommerce-order-items', 'shop_order', 'normal', 'high' );
        }

        remove_meta_box( 'postcustom', 'shop_order', 'normal', 'high' );
        remove_meta_box( 'woocommerce-order-downloads', 'shop_order', 'normal', 'high' );
        remove_meta_box( 'pageparentdiv', 'shop_order', 'side', 'high' );
    }

    public function add_metaboxes() {
        global $current_screen;
        if( $current_screen && ! is_null( $current_screen ) && isset( $current_screen->id ) && $current_screen->id == 'shop_order' && isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit' ) 
        {
            add_meta_box( 'Polen_Order_Details', 'Instruções', array( $this, 'metabox_order_details' ), 'shop_order', 'normal', 'low' );
        }
    }

    public function metabox_order_details() {
        global $post;
        $order_id = $post->ID;
        if( file_exists( TEMPLATEPATH . '/woocommerce/admin/metaboxes/metabox-order-details.php' ) ) {
            require_once TEMPLATEPATH . '/woocommerce/admin/metaboxes/metabox-order-details.php';
        } else {
            require_once PLUGIN_POLEN_DIR . '/admin/partials/metaboxes/metabox-order-details.php';
        }
    }

    public function get_order_items( $order_id ) 
    {
        global $wpdb;

        $sql_items = "SELECT `order_item_id`, `order_item_name` FROM `" . $wpdb->base_prefix . "woocommerce_order_items` WHERE `order_id`=" . $order_id . " AND `order_item_type`='line_item'";
        $res_items = $wpdb->get_results( $sql_items );

        if( $res_items && ! is_null( $res_items ) && ! is_wp_error( $res_items ) && is_array( $res_items ) && ! empty( $res_items ) ) 
        {
            $items = array();

            $meta_labels = array(
                'offered_by'            => 'Oferecido por', 
                'video_to'              => 'Vídeo para', 
                'name_to_video'         => 'Quem vai receber?', 
                'email_to_video'        => 'E-mail',
                'video_category'        => 'Ocasião', 
                'instructions_to_video' => 'Instruções do vídeo', 
                'allow_video_on_page'   => 'Permite que o vídeo apareça na página do talento?',
            );

            foreach( $res_items as $k => $item ) 
            {
                $sql = "SELECT `meta_key`, `meta_value` FROM `" . $wpdb->base_prefix . "woocommerce_order_itemmeta` WHERE `order_item_id`=" . $item->order_item_id . " AND `meta_key` IN ( 'offered_by', 'video_to', 'name_to_video', 'email_to_video', 'video_category', 'instructions_to_video', 'allow_video_on_page' )";
                $res = $wpdb->get_results( $sql );
                
                $args = array(
                    'id'   => $item->order_item_id,
                    'Talento' => $item->order_item_name,
                );

                if( $res && ! is_null( $res ) && ! is_wp_error( $res ) && is_array( $res ) && ! empty( $res ) ) 
                {
                    foreach( $res as $l => $meta ) {
                        $meta_key   = $meta->meta_key;
                        $meta_value = $meta->meta_value;
                        if( $meta_key == 'allow_video_on_page' ) {
                            $meta_value = ( $meta->meta_value == 'on' ) ? 'Sim' : 'Não';
                        }
                        $args[ $meta_labels[ $meta_key ] ] = $meta_value;
                    }
                }

                $items[] = $args;
            }

            return $items;
        }
    }
}