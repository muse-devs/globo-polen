<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Polen\Includes;

use Polen\Publics\Polen_Public;
use DateInterval;
class Polen_Order
{
    
//    const METADATA_VIMEO_VIDEO_ID = 'vimeo_video_id';
//    const METADATA_VIMEO_VIDEO_URL = 'vimeo_video_url';
//    const METADATA_VIMEO_VIDEO_EMBED_CONTENT = 'vimeo_video_embed_content';
    const SLUG_ORDER_COMPLETE = 'completed';
    const SLUG_ORDER_COMPLETE_INSIDE = 'wc-completed';

    const SLUG_ORDER_PAYMENT_APPROVED = 'payment-approved';
    const SLUG_ORDER_TALENT_ACCEPTED  = 'talent-accepted';

    const ORDER_STATUSES_NEED_TALENT_ACTION = [ self::SLUG_ORDER_PAYMENT_APPROVED, self::SLUG_ORDER_TALENT_ACCEPTED ];

    const WHATSAPP_NUMBER_META_KEY = 'polen_whatsapp_number';
    const WHATSAPP_NUMBER_NONCE_ACTION = 'polen_whatsapp_nonce_action';

    const META_KEY_DEADLINE = '_polen_deadline';
    
    public function __construct( $static = false ) {
        if( $static ) {
            add_action(    'wp_ajax_create_first_order',         array( $this, 'create_first_order' ) );
            add_action(    'wp_ajax_search_order_status',        array( $this, 'check_order_status' ) );
            add_action(    'wp_ajax_nopriv_search_order_status', array( $this, 'check_order_status' ) );
            add_action(    'wp_ajax_nopriv_polen_whatsapp_form', array( $this, 'set_whatsapp_into_order' ) );
            add_action(    'wp_ajax_polen_whatsapp_form',        array( $this, 'set_whatsapp_into_order' ) );
            add_shortcode( 'polen_search_order',                 array( $this, 'polen_search_order_shortcode' ) );
            add_shortcode( 'polen_search_result_shortcode',      array( $this, 'polen_search_result_shortcode' ) );
            add_shortcode( 'polen_video_shortcode',              array( $this, 'polen_watch_video' ) );
        }
    }

    /**
     * Ajax da busca de pedido por pedido e número
     */
    public function check_order_status(){
        if( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'user_search_order' ) ) {
            wp_send_json( array( 'nonce_fail' => 1 ) );
            exit;
        }
        $response = array();
        $_wpnonce = wp_verify_nonce( $_POST['security'], 'user_search_order' );
        if( $_wpnonce === 1 ){        
            $email = strip_tags( $_POST['email'] );
            $order_number = strip_tags( $_POST['order'] );
            $fan_orders = $this->get_orders_by_user_email( $email, $order_number );
            if( empty( $fan_orders ) ) {
                $response = array(  'success'       => true, 
                                    'message-title' => 'Nenhum não encontrado', 
                                    'message'       => 'Número digitado não foi encontrado, confira e tente novamente', 
                                    'found'         => 0 );
            } else {
                $response = array(  'success'       => true,
                                    'message-title' => 'Possui pedidos',
                                    'message'       => '',
                                    'found'         => 1 );
            }
        }
        
        echo wp_json_encode( $response );
        exit();
    }

    public function order_status_track(){
        if( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'user_search_order' ) ) {
            wp_send_json( array( 'nonce_fail' => 1 ) );
            exit;
        }

        $_wpnonce = wp_verify_nonce( $_POST['_wpnonce'], 'user_search_order' );
        if( $_wpnonce === 1 ){   
            $email = strip_tags( $_POST['fan_email'] );
            $order_number = strip_tags( $_POST['order_number'] );
            $fan_orders = $this->get_orders_by_user_email( $email, $order_number );
            $response = array();
            $arr_status = array();
            $order_status = $fan_orders->get_status();

            $arr_status['on-hold'] = array( 'message-title' => 'Pedido feito com sucesso', 'message' => 'Seu número de pedido é '+$_POST['order'] );

            $arr_status['processing'] = array(  'message-title' => 
                                                'Aguardando confirmação do talento', 
                                                'message' => 'Caso seu pedido não seja aprovado pelo talento o seu dinheiro será devoldido imediatamente' );

            $arr_status['processing'] = array(  'message-title' => 
                                                'Aguardando gravação do vídeo', 
                                                'message' => 'Quando o artista disponibilizar o vídeo será exibido aqui' );

            $arr_status['completed'] = array(  'message-title' => 
                                                'Pedido finalizado', 
                                                'message' => 'Agora você pode visualizar e compartilhar' );

            if( $order_status == 'on-hold' ){
                $response['on-hold'] = $arr_status['on-hold'];
            } 

            if( $order_status == 'processing' ){
                $response[] = $arr_status['processing'];
            } 
            
            if( $order_status == 'processing' ){
                $response['on-hold'] = $arr_status['on-hold'];
                $response['processing'] = $arr_status['processing'];
            } 

            if( $order_status == 'completed' ){
                $response['on-hold'] = $arr_status['on-hold'];
                $response['processing'] = $arr_status['processing'];
                $response['completed'] = $arr_status['completed'];
            }
            
            return $response;
        }else{
            return "Não foi possível realizar a consulta";
        }    
    }


    /**
     * Busca os pedidos por e-mail e número do pedido
     */
    public function get_orders_by_user_email( $email, $order_number ){
        $check_email = get_post_meta( $order_number, '_billing_email', true );
        if( trim( $check_email ) === trim( $email ) ){
            $order = wc_get_order( $order_number );
            return $order;
        }

        return false;
    }
    
    
    static public function is_completed( \WC_Order $order)
    {
        if( $order->get_status() == self::SLUG_ORDER_COMPLETE ) {
            return true;
        }
        return false;
    }
    
    
    

    public function polen_search_order_shortcode() {
        $url_page_tracking = get_permalink( get_page_by_path( 'acompanhar-pedido-detalhes' ));
        
        ob_start();
        wp_nonce_field('user_search_order', '_wpnonce', true, true );
        $nonce_html = ob_get_contents();
        ob_end_clean();
        
        $html_raw = file_get_contents( Polen_Public::static_get_path_public_patials() . 'polen_order_tracking_anonimous_credentials.php' );
        $html = vsprintf( $html_raw, [$url_page_tracking, $nonce_html] );
        return $html;
    } 




    public function polen_search_result_shortcode()
    {
        $order_number = filter_input( INPUT_POST, 'order_number', FILTER_VALIDATE_INT );
        $fan_email = filter_input( INPUT_POST, 'fan_email', FILTER_VALIDATE_EMAIL );
        if( !$order_number || !$fan_email ) {
            if( function_exists( 'wc_add_notice' ) ) {
                wc_add_notice( 'Email ou numero do pedidos inválidos', 'error' );
            }
            wp_safe_redirect( get_permalink( get_page_by_path( 'acompanhar-pedido' ) ) );
            exit;
        }

        $order = wc_get_order( $order_number );
        if( empty( $order ) ) {
            if( function_exists( 'wc_add_notice' ) ) {
                wc_add_notice( 'Email e pedido não são iguais (2)', 'error' );
            }
            wp_safe_redirect( get_permalink( get_page_by_path( 'acompanhar-pedido' ) ) );
            exit;
        }
        $email_inside_order = $order->get_billing_email();

        if( $fan_email != $email_inside_order ) {
            if( function_exists( 'wc_add_notice' ) ) {
                wc_add_notice( 'Email e pedido não são iguais', 'error' );
            }
            wp_safe_redirect( get_permalink( get_page_by_path( 'acompanhar-pedido' ) ) );
            exit;
        }

        // $order = wc_get_order(77);
        $notes = $order->get_customer_order_notes();
        $order_number = $order->get_order_number();
        $order_status = $order->get_status();
        include_once TEMPLATE_DIR . '/woocommerce/checkout/thankyou.php'; 

    }

    public function polen_watch_video(){ 
        echo $_SERVER['REQUEST_URI'];
        global $wp_query;

        if (isset($wp_query->query_vars['yourvarname']))
        {
        print $wp_query->query_vars['yourvarname'];
        }
    ?>
        <p>Aqui para assistir ao vídeo</p>
    <?php
    }


    /**
     * Criar uma primeira ORDER
     */
    public function create_first_order()
    {
        $user = wp_get_current_user();
        if( !in_array( 'administrator', $user->roles ) ) {
            wp_send_json_error( false, 403 );
            die;
        }
        $args = array(
            'status'        => Polen_WooCommerce::ORDER_STATUS_PAYMENT_APPROVED,
            'customer_id'   => 1,
            'customer_note' => 'Primeiro Video',
            'created_via'   => 'creation_talent',
        );
        $product_id = filter_input( INPUT_POST, 'product_id' );
        
        $order = wc_create_order( $args );
        $order_id = $order->get_id();
        $product = wc_get_product( $product_id );
        $order_item_id = wc_add_order_item( $order_id, array(
            'order_item_name' => $product->get_title(),
            'order_item_type' => 'line_item', // product
        ));
        
        $talent_name = $product->get_title();
        $instruction = "
        Bem-vindo <nome>, nós da Polen estamos MUITO felizes em ter você na plataforma!<br />
            Pronto para conhecer nosso sistema e ver como é divertido gravar Vídeos-Polen personalizados?<br />
            O seu primeiro Vídeo-Polen é um vídeo de apresentação bem simples!<br />
            Nesse vídeo-polen lembre-se de dizer:<br />
            - seu nome<br />
            - que agora faz parte da Polen<br />
            - que esta pronto para gravar Vídeos-Polen para todos os fãs<br />
            Para continuar basta aceitar no botão verde! E aí <nome>, pronto para se conectar com seus fãs!?
        ";
        $final_instruction = str_replace( '<nome>', $product->get_title(), $instruction );
        
        wc_add_order_item_meta( $order_item_id, '_qty', 1, true );
        wc_add_order_item_meta( $order_item_id, '_product_id', $product->get_id(), true );
        wc_add_order_item_meta( $order_item_id, '_line_subtotal', 0, true );
        wc_add_order_item_meta( $order_item_id, '_line_total', 0, true );
        //Polen Custom Meta Order_Item
        wc_add_order_item_meta( $order_item_id, 'offered_by'            , '', true );
        wc_add_order_item_meta( $order_item_id, 'video_to'              , 'to_myself', true );
        wc_add_order_item_meta( $order_item_id, 'first_order'           , '1', true );
        wc_add_order_item_meta( $order_item_id, 'name_to_video'         , 'Polen.me', true );
        wc_add_order_item_meta( $order_item_id, 'email_to_video'        , 'polen@polen.me', true );
        wc_add_order_item_meta( $order_item_id, 'video_category'        , 'Novidade', true );
        wc_add_order_item_meta( $order_item_id, 'instructions_to_video' , $final_instruction, true );
        wc_add_order_item_meta( $order_item_id, 'allow_video_on_page'   , 'on', true );
        wc_add_order_item_meta( $order_item_id, '_fee_amount'           , 0, true );
        wc_add_order_item_meta( $order_item_id, '_line_total'           , 0, true );
        $order = new \WC_Order( $order_id );
        $order->calculate_totals();
        // $order->set_status( Polen_WooCommerce::ORDER_STATUS_TALENT_ACCEPTED );
        wp_send_json_success( 'ok', 201 );
        wp_die();
    }


    /**
     * Salvar na Order o numero de whatsapp para
     * recebimento do video final
     */
    public function set_whatsapp_into_order()
    {
        $nonce = filter_input( INPUT_POST, 'security' );
        $order_id = filter_input( INPUT_POST, 'order', FILTER_SANITIZE_NUMBER_INT );
        $phone_number = filter_input( INPUT_POST, 'phone_number' );

        try {
            $this->validate_nonce( $nonce, self::WHATSAPP_NUMBER_NONCE_ACTION );
            $order = wc_get_order( $order_id );
            $this->validate_order_empty( $order );
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage(), $e->getCode() );
            wp_die();
        }

        //TODO: Validação do Numero de telefone
        
        $order->add_meta_data( self::WHATSAPP_NUMBER_META_KEY, $phone_number, true );
        $order->save();
        wp_send_json_success();
        wp_die();
    }


    /**
     * Validacao de Nonce
     * @param string
     * @param string
     * @throws \Exception
     */
    private function validate_nonce( $nonce, $action )
    {
        if( ! wp_verify_nonce( $nonce, $action ) ) {
            throw new \Exception( 'Erro na segurança', 403 );
        }
    }

    private function validate_order_empty( $order )
    {
        if( empty( $order ) ) {
            throw new \Exception( 'Pedido não encontrado', 403 );
        }
    }

    /**
     * Funcao que retona WC_Orders baseado num array de status de uma deadline,
     * retorna as Orders que o mate _polen_deadline for menor que o passado por parametro
     * @param array [ 'payment-approved', 'talent-accepted' ]
     * @param Timestamp 
     */
    public static function get_order_ids_by_deadline( $statuses, $deadline )
    {
        $args = [
            'fields' => 'ids',
            'post_status' => $statuses,
            'meta_query' => [
                [ 'key' => self::META_KEY_DEADLINE, 'value' => intval( $deadline ), 'compare' => '<' ],
            ],
            'post_type' => wc_get_order_types(),
        ];
        $wp_query = new \WP_Query( $args );
        $order_ids = $wp_query->get_posts();
        return $order_ids;
    }

    /**
     * Retorna o intevalo de dados para Order Social
     * @return \DateInterval
     */
    public static function get_interval_order_social()
    {
        return new DateInterval( 'P15D' );
    }

    /**
     * Retorna o Intervalo de Order Video-Autografo
     * @return \DateInterval
     */
    public static function get_interval_order_event()
    {
        return new DateInterval( 'P30D' );
    }

    /**
     * Retorna o intevalo da data de uma Order basica
     * @return \DateInterval
     */
    public static function get_interval_order_basic()
    {
        return new DateInterval( 'P7D' );
    }

    /**
     * Retorna o Intervalo de data por tipo de ordem
     * @param \WC_Order
     * @return \DateInterval
     */
    public static function get_deadline_interval_order_by_social_event( $order )
    {
        if( empty( $order ) ) {
            return false;
        }
        if( social_order_is_social( $order ) ) {
            $interval_time = self::get_interval_order_social();
        } elseif ( event_promotional_order_is_event_promotional( $order ) ) {
            $interval_time = self::get_interval_order_event();
        } else {
            $interval_time = self::get_interval_order_basic();
        }
        return $interval_time;
    }

    /**
     * Retorna o Timestamp baseado na data de criacao da Order e do inteval
     * @param \WC_Order
     * @param \DateInterval
     * @return int Timestamp
     */
    public static function get_deadline_timestamp_by_social_event( $order, $interval )
    {
        if( empty( $order ) || empty( $interval ) ) {
            return false;
        }
        $created_at = new \WC_DateTime( 'now' );
        $created_at->add( $interval );
        return $created_at->getTimestamp();
    }


    /**
     * Pega o Timestamp por uma order
     * @param \WC_Order
     * @return int Timestamp
     */
    public static function get_deadline_timestamp_by_order( $order )
    {
        if( empty( $order ) ) {
            return false;
        }
        $dataInterval = self::get_deadline_interval_order_by_social_event( $order );
        $timestamp = self::get_deadline_timestamp_by_social_event( $order, $dataInterval );
        return $timestamp;
    }

    /**
     * Sava no meta de deadline o timestamp
     * @param \WC_Order
     * @param int $timestamp
     * @return int
     */
    public static function save_deadline_timestamp_in_order( \WC_Order $order, $timestamp )
    {
        if( empty( $order ) || empty( $timestamp ) ) {
            return false;
        }
        $order->add_meta_data( self::META_KEY_DEADLINE, $timestamp, true );
        return $order->save();
    }
}
