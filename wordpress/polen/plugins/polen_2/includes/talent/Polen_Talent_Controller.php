<?php

namespace Polen\Includes\Talent;

use Polen\Includes\{Polen_Talent, Polen_Order, Debug};

use Vimeo\Vimeo;
use Vimeo\Exceptions\{VimeoRequestException, ExceptionInterface};

use Polen\Includes\Polen_Video_Info;
use Polen\Includes\Vimeo\{Polen_Vimeo_Response, Polen_Vimeo_Vimeo_Options};
use Polen\Includes\Cart\{Polen_Cart_Item_Factory, Polen_Cart_Item};

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
    public function talent_accept_or_reject(){
       $response = array();
       if( !isset( $_POST['security'] ) || !wp_verify_nonce( $_POST['security'], 'polen-order-accept-nonce' ) ) {
           $response = array( 'success' => false, 'message' => 'nonce_fail' );     
       }
   
       if( !isset( $_POST['order'] ) ) {
           $response = array( 'success' => false, 'message' => 'order_fail' );     

       }

       if( !isset( $_POST['type'] ) || ( trim( $_POST['type']) != 'accept' && trim( $_POST['type']) != 'reject' ) ){
           $response = array( 'success' => false, 'message' => 'type_fail' );     
       }

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
                           $order->update_status( 'talent-accepted', '', true );
                           $response = array( 'success' => true, 'message' => 'Vídeo aceito com sucesso', 'code' => 1 ); 
                       }                            
                       if( $type == 'reject' ){
                           $order->update_status( 'talent-rejected', '', true );
                           $response = array( 'success' => true, 'message' => 'Vídeo rejeitado', 'code' => 2 ); 
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

   public function get_data_description(){
       $response = array();

       if( !isset( $_POST['security'] ) || !wp_verify_nonce( $_POST['security'], 'polen-order-data-nonce' ) ) {
           $response = array( 'success' => false, 'message' => 'nonce_fail' );     
       }
   
       if( !isset( $_POST['order'] ) ) {
           $response = array( 'success' => false, 'message' => 'order_fail' );     
       }

       global $wpdb;
       $polen_talent = new Polen_Talent();

       require_once ABSPATH . '/wp-includes/pluggable.php';
       $talent_id = get_current_user_id();
       $order_id = trim($_POST['order']); 
       $logged_user = new \WP_User( $talent_id );

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
                   $response = array( 'success' => false, 'message' => 'Itens nao encontrados', 'data' => 0 );     
               } else {
                   $obj = array();
                   $robj = array();
                   foreach ($order_list as $obj_order):
                       $obj['order_id'] = $obj_order->order_id;
                       $obj['expiration'] = $polen_talent->video_expiration_time($logged_user, $obj['order_id']); 
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

       echo wp_json_encode( $response );
       wp_die();
   }
    
    /**
     * Handler para o AJAX onde é executado quando o Talento, seleciona um video e
     * envia, antes do envio é criado no Vimeo um Slot para receber o Video com o 
     * mesmo tamanho em bytes
     * 
     * @global type $Polen_Plugin_Settings
     * @throws VimeoRequestException
     */
    public function make_video_slot_vimeo()
    {
        global $Polen_Plugin_Settings;

        $client_id = $Polen_Plugin_Settings['polen_vimeo_client_id'];
        $client_secret = $Polen_Plugin_Settings['polen_vimeo_client_secret'];
        $token = $Polen_Plugin_Settings['polen_vimeo_access_token'];
        
        $lib = new Vimeo( $client_id, $client_secret, $token );
        
        $order_id = filter_input( INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT );
        $file_size = filter_input( INPUT_POST, 'file_size', FILTER_SANITIZE_NUMBER_INT );
        $name_to_video = filter_input( INPUT_POST, 'name_to_video' );
        
        try {
            $args = Polen_Vimeo_Vimeo_Options::get_option_insert_video( $file_size, $name_to_video );
            $vimeo_response = $lib->request( '/me/videos', $args, 'POST' );
            
            $response = new Polen_Vimeo_Response( $vimeo_response );
            
            if( $response->is_error() ) {
                throw new VimeoRequestException( $response->get_developer_message(), 500 );
            }
            
            $order = wc_get_order( $order_id );
            $cart_item = Polen_Cart_Item_Factory::polen_cart_item_from_order( $order );
            
            $video_info = $this->mount_video_info( $order, $cart_item, $response);
            
//            
//            $video_info = new Polen_Video_Info();
//            $video_info->is_public = $cart_item->get_public_in_detail_page();
//            $video_info->order_id = $order_id;
//            $video_info->talent_id = get_current_user_id();
//            $video_info->vimeo_id = $response->get_vimeo_id();
//            $video_info->vimeo_process_complete = 0;
//            $video_info->vimeo_link = $response->get_vimeo_link();
//            $video_info->vimeo_iframe = $response->get_iframe();
            
            $video_info->insert();
            
            wp_send_json_success( $response->response, 200 );
        } catch ( ExceptionInterface $e ) {
            wp_send_json_error( $e->getMessage(), $e->getCode() );
        } catch ( \Exception $e ) {
            wp_send_json_error( $e->getMessage(), $e->getCode() );
        }
        wp_die();
    }
    
    
    /**
     * 
     * @param type $order
     * @param type $cart_item
     * @param type $response
     * @return Polen_Video_Info
     */
    public function mount_video_info( \WC_Order $order, Polen_Cart_Item $cart_item, Polen_Vimeo_Response $response )
    {
            $video_info = new Polen_Video_Info();
            $video_info->is_public = $cart_item->get_public_in_detail_page();
            $video_info->order_id = $order->get_id();
            $video_info->talent_id = get_current_user_id();
            $video_info->vimeo_id = $response->get_vimeo_id();
            $video_info->vimeo_process_complete = 0;
            $video_info->vimeo_link = $response->get_vimeo_link();
            $video_info->vimeo_iframe = $response->get_iframe();
            return $video_info;
    }

    /**
     * O pedido estará como completo
     */
    public function talent_order_completed(){
        $response = array();

        if( !isset( $_POST['order'] ) ) {
            $response = array( 'success' => false, 'message' => 'order_fail' );     
 
        }
 
        global $wpdb;
 
        require_once ABSPATH . '/wp-includes/pluggable.php';
        $talent_id = get_current_user_id();
        $order_id = trim($_POST['order']); 
  
        $checked = $this->check_product_and_order( $talent_id, $order_id );

        if( $checked ){
            $first_product = reset($talent_products);
            $order = wc_get_order( $order_id );
            if( $order ){
                $order->update_status( 'completed', '', true );
                $response = array( 'success' => true, 'message' => 'Pedido completo!' );                        
            }
        }else{
            $response = array( 'success' => false, 'message' => 'Falha na relação talento/produto' );     
        }
             
        echo wp_json_encode( $response );
        wp_die();
    }

    /**
     * Verifica produto, pedido e talento
     */
    public function check_product_and_order( $talent_id, $order_id ){
        if( !$talent_id || !$order_id ){
            return false;
        }

        global $wpdb;
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
                    return false;
                }else{
                    return true;
                }
            }else{
                return false;
            }
        }

        return false;
    }
 
}