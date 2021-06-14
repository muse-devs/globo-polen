<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Polen\Includes;

use Polen\Publics\Polen_Public;

class Polen_Order
{
    
//    const METADATA_VIMEO_VIDEO_ID = 'vimeo_video_id';
//    const METADATA_VIMEO_VIDEO_URL = 'vimeo_video_url';
//    const METADATA_VIMEO_VIDEO_EMBED_CONTENT = 'vimeo_video_embed_content';
    const SLUG_ORDER_COMPLETE = 'completed';
    const SLUG_ORDER_COMPLETE_INSIDE = 'wc-completed';
    
    public function __construct( $static = false ) {
        if( $static ) {
            add_action(    'wp_ajax_search_order_status',        array( $this, 'check_order_status' ) );
            add_action(    'wp_ajax_nopriv_search_order_status', array( $this, 'check_order_status' ) );
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
}
