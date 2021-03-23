<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Polen\Includes;

class Polen_Order
{
    
    public function __construct( $static = false ) {
        if( $static ) {
            add_action( 'wp_ajax_search_order_status', array( $this, 'check_order_status' ) );
            add_action( 'wp_ajax_nopriv_search_order_status', array( $this, 'check_order_status' ) );
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
                    $response[] = $arr_status['on-hold'];
                } 

                if( $order_status == 'processing' ){
                    $response[] = $arr_status['processing'];
                } 
                
                if( $order_status == 'processing' ){
                    $response[] = $arr_status['processing'];
                } 

                if( $order_status == 'completed' ){
                    $response[] = $arr_status['completed'];
                }

                $response = $arr_status;
            }

        }
        echo wp_json_encode( $response );
        wp_die();
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

    public function polen_search_order_shortcode() { 
        $html = '
        <div id="primary" class="content-area cart-other">
        <main id="main" class="site-main" role="main">
            <form action="#" method="post">
                '. wp_nonce_field('user_search_order', '_wpnonce', true, true) .'
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
                            <button class="btn btn-primary btn-lg btn-block py-4 btn-search-order" name="" value="">Buscar</button>
                        </div>
                    </div>    
                </div>
            </form>    
        </main>
        </div>';
        return $html;
    } 

}
$Polen_Order = new Polen_Order;
add_shortcode( 'polen_search_order', array( $Polen_Order, 'polen_search_order_shortcode' ) );