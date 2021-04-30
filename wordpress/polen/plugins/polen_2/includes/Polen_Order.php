<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Polen\Includes;

class Polen_Order
{
    
//    const METADATA_VIMEO_VIDEO_ID = 'vimeo_video_id';
//    const METADATA_VIMEO_VIDEO_URL = 'vimeo_video_url';
//    const METADATA_VIMEO_VIDEO_EMBED_CONTENT = 'vimeo_video_embed_content';
    const SLUG_ORDER_COMPLETE = 'completed';
    const SLUG_ORDER_COMPLETE_INSIDE = 'wc-completed';
    
    public function __construct( $static = false ) {
        if( $static ) {
            add_action( 'wp_ajax_search_order_status', array( $this, 'check_order_status' ) );
            add_action( 'wp_ajax_nopriv_search_order_status', array( $this, 'check_order_status' ) );
            add_shortcode( 'polen_search_order', array( $this, 'polen_search_order_shortcode' ) );
            add_shortcode( 'polen_search_result_shortcode', array( $this, 'polen_search_result_shortcode' ) );
            add_shortcode( 'polen_video_shortcode', array( $this, 'polen_watch_video' ) );
            add_action( 'init', array( $this, 'custom_rewrite_basic' ) );
            add_filter( 'query_vars', array( $this, 'addnew_query_vars' ), 10, 1 );
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
            if( empty( $fan_orders ) ){
                $response = array(  'success' => true, 
                                    'message-title' => 'Nenhum não encontrado', 
                                    'message' => 'Número digitado não foi encontrado, confira e tente novamente', 
                                    'found' => 0 );
            }else{
                $response = array(  'success' => true, 
                                    'message-title' => 'Possui pedidos', 
                                    'message' => '', 
                                    'found' => 1 );
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
    ?>    
        <div id="primary" class="content-area cart-other">
        <main id="main" class="site-main" role="main">
            <form action="/acompanhamento" method="post" class="form_search_order">
                <?php wp_nonce_field('user_search_order', '_wpnonce', true, true );?>
                <div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <input id="fan_email" class="form-control form-control-lg" name="fan_email" value="" placeholder="E-mail" required="required"/>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <input id="order_number" class="form-control" name="order_number" value="" placeholder="Número do pedido" required="required"/>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary btn-lg btn-block py-4 btn-search-order" name="" value="">Buscar</button>
                        </div>
                    </div>    
                </div>
            </form>    
        </main>
        </div>
    <?php
    } 

    public function polen_search_result_shortcode() { 
    ?>    
        <div id="primary" class="content-area cart-other">
        <main id="main" class="site-main" role="main">
            <?php var_dump( $this->order_status_track() );?>
        </main>
        </div>
    <?php
    }

    public function polen_watch_video(){ 
        //var_dump($_SERVER['REQUEST_URI']);    
        echo $_SERVER['REQUEST_URI'];
        global $wp_query;

        var_dump($wp_query->query_vars);
        if (isset($wp_query->query_vars['yourvarname']))
        {
        print $wp_query->query_vars['yourvarname'];
        }
    ?>
        <p>Aqui para assistir ao vídeo</p>
    <?php
    }

    public function addnew_query_vars($vars)
    {   
        //$vars[] = 'assistir'; // c is the name of variable you want to add       
        $url_arg = explode( 'assistir/', $_SERVER['REQUEST_URI'] );
        $vars['video-id'] = $url_arg[1];
        //var_dump($vars, $_SERVER['REQUEST_URI'], );die;

        return $vars;
    }
    
    public function custom_rewrite_basic() 
    {
        
        //add_rewrite_rule('^assistir-video/([0-9]+)/?', '?assistir-video=$1', 'top');
        add_rewrite_rule(
            '^assistir/([-a-z]+)/?$',
            '?assistir=$1',
            'top'
        );
        global $wp_query;

        

    }

}
//$Polen_Order = new Polen_Order;

