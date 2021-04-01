<?php

namespace Polen\Includes\Talent;

class Polen_Talent_Controller extends Polen_Talent_Controller_Base
{
    public function login()
    {
        
        $username = sanitize_email( filter_input( INPUT_POST, 'login' ) );
        $password = trim( filter_input( INPUT_POST, 'password' ) );

        $auth_result = wp_authenticate( $username, $password );
        if( is_wp_error( $auth_result ) ) {
            wp_send_json_error( null, 401 );
        }
        
        //TODO: Verificar se a role é talent
        
        $response = array(
            'user_name' => '',
            'user_email' => '',
            'display_name' => ''
        );
        echo wp_send_json( $auth_result );
    }
    
    public function get_total_a_receber()
    {
        
    }
    
    
    /**
     * O Talento aceita ou rejeita um pedido de video
     *
     */
    public function talent_acceptance(){
       if( $this->check_permission() ){
            //var_dump('aqui', ( !isset( $_POST['order'] ) ));
            if( !isset( $_POST['security'] ) || !wp_verify_nonce( $_POST['security'], 'polen-order-accept-nonce' ) ) {
                wp_send_json( array( 'nonce_fail' => 1 ) );
                exit;
            }
        
            if( !isset( $_POST['order'] ) ) {
                wp_send_json( array( 'order_fail' => 1 ) );
                exit;
            }

            if( !isset( $_POST['type'] ) || ( trim( $_POST['type']) != 'accept' && trim( $_POST['type']) != 'reject' ) ){
                wp_send_json( array( 'type_fail' => 1 ) );
                exit;
            }
            
            $response = array();
            global $wpdb;

            require_once ABSPATH . '/wp-includes/pluggable.php';
            $talent_id = get_current_user_id();
            $order_id = trim($_POST['order']); 
            $type = strip_tags( $_POST['type'] );
 
            $sql_product = " SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'product' and post_author = ".$talent_id;
            $talent_products = $wpdb->get_results( $sql_product );

            
            if( is_countable( $talent_products ) && count( $talent_products ) > 0 ){
                $first_product = reset($talent_products);

                if( is_object( $first_product ) && isset( $first_product->ID ) ){
                    $sql = " SELECT order_items.order_id
                        FROM {$wpdb->prefix}woocommerce_order_items as order_items
                        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
                        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
                        WHERE posts.post_type = 'shop_order'
                            AND order_items.order_id = ".$order_id."
                            AND order_items.order_item_type = 'line_item'
                            AND order_item_meta.meta_key = '_product_id'
                            AND order_item_meta.meta_value = '$first_product->ID'";
                    $order_list = $wpdb->get_results( $sql );

                    if( is_countable( $order_list ) && count( $order_list ) == 0 ){
                        $response = array( 'success' => false, 'message' => 'Pedido não é desse talento' );        
                    }else{
                        $order = wc_get_order( $order_id );
                        if($order){
                            if( $type == 'accept' ){
                                $order->update_status( 'processing', '', true );
                                $response = array( 'success' => true, 'message' => 'Vídeo aceito com sucesso' ); 
                            }                            
                            if( $type == 'reject' ){
                                $order->update_status( 'cancelled', '', true );
                                $response = array( 'success' => true, 'message' => 'Vídeo rejeitado' ); 
                            }  
                           
                        }
                    }
                }else{
                    $response = array( 'success' => false, 'message' => 'Talento sem produto' );     
                }
                
            }

            echo wp_json_encode( $response );
            wp_die();
        }

    }


    public function talent_order_data(){
        var_dump('chegou na action');die;
        if( $this->check_permission() ){
             if( !isset( $_POST['security'] ) || !wp_verify_nonce( $_POST['security'], 'polen-order-data-nonce' ) ) {
                 wp_send_json( array( 'nonce_fail' => 1 ) );
                 exit;
             }
         
             if( !isset( $_POST['order'] ) ) {
                 wp_send_json( array( 'order_fail' => 1 ) );
                 exit;
             }
 
             if( !isset( $_POST['type'] ) || ( trim( $_POST['type']) != 'accept' && trim( $_POST['type']) != 'reject' ) ){
                 wp_send_json( array( 'type_fail' => 1 ) );
                 exit;
             }
             
             $response = array();
             global $wpdb;

             require_once ABSPATH . '/wp-includes/pluggable.php';
             $talent_id = get_current_user_id();
             $order_id = trim($_POST['order']); 
             $type = strip_tags( $_POST['type'] );
 
            if( is_object( $first_product ) && isset( $first_product->ID ) ){
                $sql_product = " SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'product' and post_author = " . $talent_id;
                $talent_products = $wpdb->get_results($sql_product);
    
                if (is_countable($talent_products) && count($talent_products) > 0) {
                    $first_product = reset($talent_products);
    
                    if (is_object($first_product) && isset($first_product->ID)) {
                        $sql = " SELECT order_items.order_id
                        FROM {$wpdb->prefix}woocommerce_order_items as order_items
                        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
                        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
                        WHERE posts.post_type = 'shop_order'
                            AND posts.post_status IN ( 'wc-payment-approved' )
                            AND order_items.order_item_type = 'line_item'
                            AND order_item_meta.meta_key = '_product_id'
                            AND order_item_meta.meta_value = '$first_product->ID'
                            AND order_items.order_id = ".$order_id;
                        $order_list = $wpdb->get_results($sql);
    
                        if (is_countable($order_list) && count($order_list) == 0) {
                            return false;
                        } else {
                            $obj = array();
                            $robj = array();
                            foreach ($order_list as $obj_order):
                                $obj['order_id'] = $obj_order->order_id;
                                $order = wc_get_order($obj_order->order_id);
    
                                $obj['total'] = $order->get_formatted_order_total();
                                foreach ($order->get_items() as $item_id => $item) {
                                    $obj['email'] = $item->get_meta('email_to_video', true);
                                    $obj['instructions'] = $item->get_meta('instructions_to_video', true);
                                    $obj['name'] = $item->get_meta('name_to_video', true);
                                    $obj['from'] = $item->get_meta('offered_by', true);
                                    $obj['category'] = $item->get_meta('video_category', true);
                                }
    
                                $robj[] = $obj;
                            endforeach;

                            $response = array( 'success' => true, 'message' => 'Dados obtidos com sucesso', 'data' => $robj );     
                        }
                    }
                }                     
            }else{
                $response = array( 'success' => false, 'message' => 'Talento sem produto', 'data' => 0 );     
            }
            echo wp_json_encode( $response );
            wp_die();
        }
    }
}