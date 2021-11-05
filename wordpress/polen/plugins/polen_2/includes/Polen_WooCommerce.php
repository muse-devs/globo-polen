<?php

namespace Polen\Includes;

use Polen\Admin\Polen_Admin_B2B_Product_Fields;
use Polen\Admin\Polen_Admin_Social_Base_Product_Fields;

class Polen_WooCommerce 
{
    const ORDER_STATUS_PAYMENT_IN_REVISION = 'payment-in-revision';
    const ORDER_STATUS_PAYMENT_REJECTED    = 'payment-rejected';
    const ORDER_STATUS_PAYMENT_APPROVED    = 'payment-approved';
    const ORDER_STATUS_TALENT_REJECTED     = 'talent-rejected';
    const ORDER_STATUS_TALENT_ACCEPTED     = 'talent-accepted';
    const ORDER_STATUS_ORDER_EXPIRED       = 'order-expired';

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

            add_filter( 'woocommerce_product_data_tabs', array( $this, 'charity_tab' ) );
            add_filter( 'woocommerce_product_data_tabs', array( $this, 'promotional_event' ) );
            // add_filter( 'woocommerce_product_data_tabs', array( $this, 'social_base_event' ) );
            
            add_filter( 'woocommerce_product_data_panels', array( $this, 'charity_product_data_product_tab_content' ) );
            add_filter( 'woocommerce_product_data_panels', array( $this, 'promotional_event_product_data_product_tab_content' ) );
            // add_filter( 'woocommerce_product_data_panels', array( $this, 'social_base_product_data_product_tab_content' ) );

            add_action( 'woocommerce_update_product', array( $this, 'on_product_save' ) );

            //Todas as compras gratis vão para o status payment-approved
            add_action( 'woocommerce_checkout_no_payment_needed_redirect', [ $this, 'set_free_order_payment_approved' ], 10, 3 );

        }
    }
    

    /**
     * Colocar os status de uma order gratis como pagamento aprovado
     */
    public function set_free_order_payment_approved( $order_received_url, $order )
    {
        $order->set_status('payment-approved');
        $order->save();
        return $order_received_url;
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
        $actions[] = 'woocommerce_order_status_wc-payment-approved_to_wc-talent-rejected';
        $actions[] = 'woocommerce_order_status_wc-payment-approved_to_wc-talent-accepted';
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
            add_meta_box( 'Polen_Order_Details_Video_Info', 'Info do Video', array( $this, 'metabox_order_details_video_info' ), 'shop_order', 'normal', 'low' );
            add_meta_box( 'Polen_Refund_Order_tuna', 'Reembolsar pedido', array( $this, 'metabox_create_refund_order_tuna' ), 'shop_order', 'side', 'default' );
        }

        if( $current_screen && ! is_null( $current_screen ) && isset( $current_screen->id ) && $current_screen->id == 'product' && isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit' )  {
            global $post;
            $product_id = $post->ID;
            add_meta_box( 'Polen_Product_First_Order', 'Primeira Order', array( $this, 'metabox_create_first_order' ), 'product', 'side', 'default' );
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

    public function metabox_order_details_video_info() {
        global $post;
        $order_id = $post->ID;
        if( file_exists( TEMPLATEPATH . '/woocommerce/admin/metaboxes/metabox-video-info.php' ) ) {
            require_once TEMPLATEPATH . '/woocommerce/admin/metaboxes/metabox-video-info.php';
        } else {
            require_once PLUGIN_POLEN_DIR . '/admin/partials/metaboxes/metabox-video-info.php';
        }
    }

    /**
     * Adicionar metabox na edição de produtos
     */
    public function metabox_create_refund_order_tuna()
    {
        global $post;
        $product_id = $post->ID;
        if( file_exists( TEMPLATEPATH . '/woocommerce/admin/metaboxes/metabox-refund-order-tuna.php' ) ) {
            require_once TEMPLATEPATH . '/woocommerce/admin/metaboxes/metabox-refund-order-tuna.php';
        } else {
            require_once PLUGIN_POLEN_DIR . '/admin/partials/metaboxes/metabox-refund-order-tuna.php';
        }
    }

    public function metabox_create_first_order()
    {
        global $post;
        $product_id = $post->ID;
        if( file_exists( TEMPLATEPATH . '/woocommerce/admin/metaboxes/metabox-first-order.php' ) ) {
            require_once TEMPLATEPATH . '/woocommerce/admin/metaboxes/metabox-first-order.php';
        } else {
            require_once PLUGIN_POLEN_DIR . '/admin/partials/metaboxes/metabox-first-order.php';
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
                'email_to_video'        => 'e-mail',
                'video_category'        => 'Ocasião', 
                'instructions_to_video' => 'Instruções do vídeo', 
                'allow_video_on_page'   => 'Publico?',
            );
            $order = wc_get_order( $order_id );
            if( !empty( $order ) && "talent-rejected" == $order->get_status() ) {
                $meta_labels[ 'reason_reject' ] = 'Rejeitou por';
                $meta_labels[ 'reason_reject_description' ] = 'Explicação';
            }

            foreach( $res_items as $k => $item ) 
            {
                $sql = "SELECT `meta_key`, `meta_value` FROM `" . $wpdb->base_prefix . "woocommerce_order_itemmeta` WHERE `order_item_id`=" . $item->order_item_id . " AND `meta_key` IN ( 'offered_by', 'video_to', 'name_to_video', 'email_to_video', 'video_category', 'instructions_to_video', 'allow_video_on_page', 'reason_reject', 'reason_reject_description' )";
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

    public function charity_tab( $array ){
        $array['charity'] = array(
            'label'    => 'Caridade',
            'target'   => 'charity_product_data',
            'class'    => array(),
            'priority' => 90,
        );
        return $array;
    }
    public function promotional_event( $array ){
        $array['promotional_event'] = array(
            'label'    => 'Video-Autógrafo',
            'target'   => 'promotional_event_product_data',
            'class'    => array(),
            'priority' => 90,
        );
        return $array;
    }

    // public function social_base_event( $array )
    // {
    //     $array['social_base'] = array(
    //         'label'    => 'Base Social',
    //         'target'   => 'social_base_product_data',
    //         'class'    => array(),
    //         'priority' => 90,
    //     );
    //     return $array;
    // }

    public function charity_product_data_product_tab_content() {
        global $product_object;
    ?>
        <div id="charity_product_data" class="panel woocommerce_options_panel hidden">
            <div class='options_group'>
            <?php
                woocommerce_wp_checkbox(
                    array(
                        'id'      => '_is_charity',
                        'value'   => $product_object->get_meta( '_is_charity' ) == 'yes' ? 'yes' : 'no',
                        'label'   => 'Para Caridade',
                        'cbvalue' => 'yes',
                    )
                );
            ?>
            </div>
        
            <div class="options_group">
                <?php
                woocommerce_wp_text_input(
                    array(
                        'id'                => '_charity_name',
                        'value'             => $product_object->get_meta( '_charity_name' ),
                        'label'             => 'Charity Name',
                        'desc_tip'          => true,
                        'description'       => 'Nome da instituição de Caridade',
                        'type'              => 'text',
                    )
                );
                ?>
            </div>
        
            <div class="options_group">
                <?php
                woocommerce_wp_text_input(
                    array(
                        'id'                => '_url_charity_logo',
                        'value'             => $product_object->get_meta( '_url_charity_logo' ),
                        'label'             => 'Logo da instituição',
                        'desc_tip'          => true,
                        'description'       => 'Logo da instituição de Caridade',
                        'type'              => 'text',
                    )
                );
                ?>
            </div>
        
            <div class="options_group">
                <?php
                woocommerce_wp_textarea_input(
                    array(
                        'id'          => '_description_charity',
                        'value'       => $product_object->get_meta( '_description_charity' ),
                        'label'       => 'Descrição da instituição',
                        'desc_tip'    => true,
                        'description' => 'Descrição da instituição de caridade.',
                    )
                );
                ?>
            </div>

            <div class="options_group">
                <?php
                woocommerce_wp_text_input(
                    array(
                        'id'          => '_charity_subordinate_merchant_id',
                        'value'       => $product_object->get_meta( '_charity_subordinate_merchant_id' ),
                        'label'       => 'Subordinate Merchant ID',
                        'desc_tip'    => true,
                        'description' => 'Código Braspag para a instituição de caridade.',
                        'type'        => 'text',
                    )
                );
                ?>
            </div>
        </div>
    <?php
    }

    public function promotional_event_product_data_product_tab_content()
    {
        global $product_object; ?>

        <div id="promotional_event_product_data" class="panel woocommerce_options_panel hidden">
            <div class='options_group'>
            <?php
                woocommerce_wp_checkbox(
                    array(
                        'id'      => '_promotional_event',
                        'value'   => $product_object->get_meta( '_promotional_event' ) == 'yes' ? 'yes' : 'no',
                        'label'   => 'É um Vídeo-Autógrafo',
                        'cbvalue' => 'yes',
                    )
                );
            ?>
            </div>
            
            <div class="options_group">
                <?php
                woocommerce_wp_text_input(
                    array(
                        'id'                => '_promotional_event_pages_quantity',
                        'value'             => $product_object->get_meta( '_promotional_event_pages_quantity' ),
                        'label'             => 'Qtd de Paginas',
                        'desc_tip'          => true,
                        'description'       => 'Quantidade de pagidas do Livro',
                        'type'              => 'text',
                    )
                );
                ?>
            </div>
            
            <div class="options_group">
                <?php
                woocommerce_wp_text_input(
                    array(
                        'id'                => '_promotional_event_language',
                        'value'             => $product_object->get_meta( '_promotional_event_language' ),
                        'label'             => 'Idioma',
                        'desc_tip'          => true,
                        'description'       => 'Idioma do Livro',
                        'type'              => 'text',
                    )
                );
                ?>
            </div>
            
            <div class="options_group">
                <?php
                woocommerce_wp_text_input(
                    array(
                        'id'          => '_promotional_event_publishing',
                        'value'       => $product_object->get_meta( '_promotional_event_publishing' ),
                        'label'       => 'Editora',
                        'desc_tip'    => true,
                        'description' => 'Editora do livro.',
                        'type'        => 'text',
                    )
                );
                ?>
            </div>
            
            <div class="options_group">
                <?php
                woocommerce_wp_text_input(
                    array(
                        'id'          => '_promotional_event_published_in',
                        'value'       => $product_object->get_meta( '_promotional_event_published_in' ),
                        'label'       => 'Publicado em',
                        'desc_tip'    => true,
                        'description' => 'Data de publicação.',
                        'type'        => 'date',
                    )
                );
                ?>
            </div>
            
            <div class="options_group">
                <?php
                woocommerce_wp_text_input(
                    array(
                        'id'          => '_promotional_event_rating',
                        'value'       => $product_object->get_meta( '_promotional_event_rating' ),
                        'label'       => 'Score do Livro ex: <b>4.2</b>',
                        'desc_tip'    => true,
                        'description' => 'Nota dos leitores do livro.',
                        'type'        => 'text',
                    )
                );
                ?>
            </div>

            <div class="options_group">
                <?php
                woocommerce_wp_text_input(
                    array(
                        'id'          => '_promotional_event_link_buy',
                        'value'       => $product_object->get_meta( '_promotional_event_link_buy' ),
                        'label'       => 'Link de compra',
                        'desc_tip'    => true,
                        'description' => 'Link para comprar o livro.',
                        'type'        => 'text',
                    )
                );
                ?>
            </div>

            <div class="options_group">
                <?php
                woocommerce_wp_text_input(
                    array(
                        'id'          => '_promotional_event_author',
                        'value'       => $product_object->get_meta( '_promotional_event_author' ),
                        'label'       => 'Autor',
                        'desc_tip'    => true,
                        'description' => 'Autor do livro.',
                        'type'        => 'text',
                    )
                );
                ?>
            </div>

            <div class="options_group">
                <?php
                woocommerce_wp_text_input(
                    array(
                        'id'          => '_promotional_event_wartermark',
                        'value'       => $product_object->get_meta( '_promotional_event_wartermark' ),
                        'label'       => 'Marca d`agua',
                        'desc_tip'    => true,
                        'description' => 'URL da marca d`agua do video player, copiar o link de galeria',
                        'type'        => 'text',
                    )
                );
                ?>
            </div>


            
        </div>
        <?php
    }

    /*
    public function social_base_product_data_product_tab_content()
    {
        global $product_object;
        ?>
            <div id="social_base_product_data" class="panel woocommerce_options_panel hidden">
                <div class="options_group">
                    <?php
                    woocommerce_wp_checkbox(
                        array(
                            'id'      => '_is_social_base',
                            'value'   => $product_object->get_meta( '_is_social_base' ) == 'yes' ? 'yes' : 'no',
                            'label'   => 'Produto é Social',
                            'cbvalue' => 'yes',
                        )
                    );
                    ?>
                </div>

                <div class="options_group">
                    <?php
                    woocommerce_wp_text_input(
                        array(
                            'id'          => '_social_base_slug_campaing',
                            'value'       => $product_object->get_meta( '_social_base_slug_campaing' ),
                            'label'       => 'Slug da Campanha',
                            'desc_tip'    => true,
                            'description' => 'Slug da companha que este produto é parte',
                            'type'        => 'text',
                        )
                    );
                    ?>
                </div>

                <div class="options_group">
                    <?php
                    woocommerce_wp_text_input(
                        array(
                            'id'          => '_social_base_video_testimonial',
                            'value'       => $product_object->get_meta( '_social_base_video_testimonial' ),
                            'label'       => 'Link do video',
                            'desc_tip'    => true,
                            'description' => 'URL do Video com o video de talento',
                            'type'        => 'text',
                        )
                    );
                    ?>
                </div>
            </div>
        <?php
    }*/

    public function on_product_save( $product_id ) {
        if( is_admin() ){
            $screen = get_current_screen();
            if ( $screen->base == 'post' && $screen->post_type == 'product' ){            
                $product = wc_get_product( $product_id );
                $charity = strip_tags( $_POST['_is_charity'] );
                $charity_name = strip_tags( $_POST['_charity_name'] );
                $charity_url = strip_tags( $_POST['_url_charity_logo'] );
                $charity_description = strip_tags( $_POST['_description_charity'] );
                $charity_subordinate_id = strip_tags( $_POST['_charity_subordinate_merchant_id'] );

                $promotional_event = strip_tags( $_POST[ '_promotional_event' ] );
                $promotional_event_pages_quantity = strip_tags( $_POST[ '_promotional_event_pages_quantity' ] );
                $promotional_event_language = strip_tags( $_POST[ '_promotional_event_language' ] );
                $promotional_event_publishing = strip_tags( $_POST[ '_promotional_event_publishing' ] );
                $promotional_event_published_in = strip_tags( $_POST[ '_promotional_event_published_in' ] );
                $promotional_event_rating = strip_tags( $_POST[ '_promotional_event_rating' ] );
                $promotional_event_link_buy = strip_tags( $_POST[ '_promotional_event_link_buy' ] );
                $promotional_event_author = strip_tags( $_POST[ '_promotional_event_author' ] );
                $promotional_event_wartermark = strip_tags( $_POST[ '_promotional_event_wartermark' ] );
                
                // $is_social_base = strip_tags( $_POST[ '_is_social_base' ]);
                // $social_base_slug_campaing = strip_tags( $_POST[ '_social_base_slug_campaing' ]);
                // $social_base_video_testimonial = strip_tags( $_POST[ '_social_base_video_testimonial' ]);

                $product->update_meta_data( '_is_charity', $charity );
                $product->update_meta_data( '_charity_name', $charity_name );
                $product->update_meta_data( '_url_charity_logo', $charity_url );
                $product->update_meta_data( '_description_charity', $charity_description );
                $product->update_meta_data( '_charity_subordinate_merchant_id', $charity_subordinate_id );

                $this->save_meta($product, $promotional_event, '_promotional_event' );
                $this->save_meta($product, $promotional_event_pages_quantity, '_promotional_event_pages_quantity' );
                $this->save_meta($product, $promotional_event_language, '_promotional_event_language' );
                $this->save_meta($product, $promotional_event_publishing, '_promotional_event_publishing' );
                $this->save_meta($product, $promotional_event_published_in, '_promotional_event_published_in' );
                $this->save_meta($product, $promotional_event_rating, '_promotional_event_rating' );
                $this->save_meta($product, $promotional_event_link_buy, '_promotional_event_link_buy' );
                $this->save_meta($product, $promotional_event_author, '_promotional_event_author' );
                $this->save_meta($product, $promotional_event_wartermark, '_promotional_event_wartermark' );

                // $this->save_meta($product, $is_social_base, '_is_social_base' );
                // $this->save_meta($product, $social_base_slug_campaing, '_social_base_slug_campaing' );
                // $this->save_meta($product, $social_base_video_testimonial, '_social_base_video_testimonial' );

                do_action( Polen_Admin_Social_Base_Product_Fields::ACTION_NAME , $product_id );
                do_action( Polen_Admin_B2B_Product_Fields::ACTION_NAME         , $product_id );
                  
                remove_action( 'woocommerce_update_product', array( $this, 'on_product_save' ) );
                $product->save();
                add_action( 'woocommerce_update_product', array( $this, 'on_product_save' ) );
            }
        }  
    }

    private function save_meta( &$product, $value, $key )
    {
        if( !empty( $value ) ) {
            $product->update_meta_data( $key, $value );
        } else {
            $product->delete_meta_data( $key );
        }
    }
}